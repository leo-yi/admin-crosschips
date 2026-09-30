<?php

declare(strict_types=1);

namespace App\Console\Commands\System;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * 扫描 admin 路由，同步写入 admin_menu 表、admin_permissions 表，
 * 并自动建立 permission_menu 关联。
 *
 * 权限树形结构与菜单保持一致：
 *   分组父权限 → 资源权限（单条，http_method 留空即 any）
 *
 * 用法:
 *   php admin:sync-menu-permission             # 预览模式
 *   php admin:sync-menu-permission --write     # 写入模式
 */
class SyncMenuPermissionCommand extends Command
{
    protected $signature = 'admin:sync-menu-permission
        {--write : 实际写入数据库（默认仅预览）}
        {--lang=zh_CN : 语言包目录名}';

    protected $description = '扫描 admin 路由，同步写入 menu 表和 permissions 表（树形结构与菜单一致）';

    /** 路由文件中的注释分组 => 对应菜单父节点 & 权限父节点配置 */
    protected array $groupConfig = [
        '基础数据' => [
            'title' => 'Basic Data',
            'icon' => 'fa-align-justify',
            'uri' => '/basic-data',
            'order' => 6,
            'existing_id' => 15,
            'perm_slug' => 'group-basic-data',
        ],
        '运营管理' => [
            'title' => 'Web Admin',
            'icon' => 'fa-align-justify',
            'uri' => '/web',
            'order' => 2,
            'existing_id' => 11,
            'perm_slug' => 'group-web-admin',
        ],
    ];

    /** 资源路由 => 所属分组 */
    protected array $resourceGroupMap = [
        'chip-category' => '基础数据',
        'chip-manufacturer' => '基础数据',
        'chip-product' => '基础数据',
        'index-banner' => '运营管理',
        'chip-product-stock' => '运营管理',
        'chip-manufacturer-relation' => '运营管理',
        'chip-manufacturer-related' => '运营管理',
        'task' => '运营管理',
        'chip-rfq' => '运营管理',
        'chip-station' => '运营管理',
    ];

    protected string $defaultIcon = 'fa-circle-o';

    public function handle(): int
    {
        $write = (bool) $this->option('write');
        $langDir = (string) $this->option('lang');

        $this->info('');
        $this->info('══════════════════════════════════════════════════');
        $this->info('  Admin 菜单 & 权限同步工具');
        $this->info('  权限树形结构与菜单保持一致 · http_method = any');
        $this->info('══════════════════════════════════════════════════');
        $this->info('');

        // ── 1. 解析路由 ──────────────────────────────────────────────
        $routesFile = app_path('Admin/routes.php');
        if (! file_exists($routesFile)) {
            $this->error("路由文件不存在: {$routesFile}");

            return self::FAILURE;
        }
        $routeContent = file_get_contents($routesFile);
        $resourceRoutes = $this->parseResourceRoutes($routeContent);
        $singleRoutes = $this->parseSingleRoutes($routeContent);

        // ── 2. 加载语言包 ────────────────────────────────────────────
        $labels = $this->loadLabels($langDir);

        // ── 3. 同步分组菜单 + 分组权限 ──────────────────────────────
        $groupResult = $this->syncGroups($write);
        $groupMenuIds = $groupResult['menu_ids'];
        $groupPermIds = $groupResult['perm_ids'];

        $menuCreated = $groupResult['menu_created'];
        $permCreated = $groupResult['perm_created'];
        $pivotLinked = 0;

        // ── 4. 处理资源路由 ──────────────────────────────────────────
        foreach ($resourceRoutes as $resource) {
            $name = $resource['name'];
            $group = $this->resourceGroupMap[$name] ?? null;
            $label = $labels[$name] ?? $name;
            $parentMenuId = ($group && isset($groupMenuIds[$group])) ? $groupMenuIds[$group] : 0;
            $parentPermId = ($group && isset($groupPermIds[$group])) ? $groupPermIds[$group] : 0;

            $this->info("  [$name] → $label (group: ".($group ?: '无').')');

            // ─ 4a. 菜单 ──────────────────────────────────────────────
            $menuResult = $this->syncMenu($name, $label, $parentMenuId, $write);
            $menuId = $menuResult['id'];
            if ($menuResult['created']) {
                $menuCreated++;
            }

            // ─ 4b. 权限（单条，挂载在分组父权限下，http_method = any）──
            $permResult = $this->syncResourcePermission($name, $label, $parentPermId, $write);
            $permId = $permResult['id'];
            if ($permResult['created']) {
                $permCreated++;
            }

            // ─ 4c. 关联 permission_menu ──────────────────────────────
            if ($menuId && $permId) {
                $linked = $this->syncPivot($menuId, [$permId], $write);
                $pivotLinked += $linked;
            }

            $this->line('');
        }

        // ── 5. 处理单路由 ────────────────────────────────────────────
        if (! empty($singleRoutes)) {
            $this->info('── 单路由权限 ──');
            foreach ($singleRoutes as $route) {
                $this->line("  • {$route['uri']} → {$route['label']}");
                $permResult = $this->syncSingleRoutePermission($route, $write);
                if ($permResult['created']) {
                    $permCreated++;
                }
            }
            $this->line('');
        }

        // ── 6. 汇总 ─────────────────────────────────────────────────
        $this->info('──────────────────────────────────────────────────');
        $this->info(sprintf(
            '  菜单: %d | 权限: %d | 关联: %d',
            $menuCreated, $permCreated, $pivotLinked
        ));

        if (! $write) {
            $this->warn('');
            $this->warn('  当前为预览模式，添加 --write 参数写入数据库：');
            $this->warn('  php artisan admin:sync-menu-permission --write');
        } else {
            $this->info('');
            $this->info('  写入完成，建议执行：php artisan optimize:clear');
        }
        $this->info('');

        return self::SUCCESS;
    }

    // ═══════════════════════════════════════════════════════════════════
    //  分组同步（菜单 + 权限）
    // ═══════════════════════════════════════════════════════════════════

    /**
     * 同步所有分组：创建菜单 + 创建对应的父权限
     * 返回 ['menu_ids' => [], 'perm_ids' => [], 'menu_created' => int, 'perm_created' => int]
     */
    protected function syncGroups(bool $write): array
    {
        $menuIds = [];
        $permIds = [];
        $menuCreated = 0;
        $permCreated = 0;
        $order = 1;

        foreach ($this->groupConfig as $groupName => $config) {
            $permSlug = $config['perm_slug'];

            // ── 菜单 ─────────────────────────────────────────────────
            if (! empty($config['existing_id'])) {
                $menuIds[$groupName] = $config['existing_id'];
                $this->line("  ✓ 分组菜单已存在: {$groupName} → id={$config['existing_id']}");
            } else {
                $existing = DB::table('admin_menu')
                    ->where('title', $config['title'])
                    ->where('parent_id', 0)
                    ->first();

                if ($existing) {
                    $menuIds[$groupName] = $existing->id;
                    $this->line("  ✓ 分组菜单已存在: {$groupName} → id={$existing->id}");
                } elseif ($write) {
                    $id = DB::table('admin_menu')->insertGetId([
                        'parent_id' => 0,
                        'order' => $config['order'] ?? $order,
                        'title' => $config['title'],
                        'icon' => $config['icon'] ?? 'fa-folder',
                        'uri' => $config['uri'] ?? '',
                        'extension' => '',
                        'show' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $menuIds[$groupName] = $id;
                    $menuCreated++;
                    $this->info("  ✔ 创建分组菜单: {$groupName} → id={$id}");
                } else {
                    $this->warn("  + 将创建分组菜单: {$groupName} ({$config['title']})");
                    $menuIds[$groupName] = 0;
                }
            }

            // ── 分组父权限 ───────────────────────────────────────────
            $existingPerm = DB::table('admin_permissions')->where('slug', $permSlug)->first();
            if ($existingPerm) {
                $permIds[$groupName] = $existingPerm->id;
                $this->line("  ✓ 分组权限已存在: {$groupName} → id={$existingPerm->id} slug={$permSlug}");
            } elseif ($write) {
                $pid = DB::table('admin_permissions')->insertGetId([
                    'parent_id' => 0,
                    'name' => Str::limit($config['title'], 50, ''),
                    'slug' => $permSlug,
                    'http_method' => '',
                    'http_path' => '',
                    'order' => $order,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $permIds[$groupName] = $pid;
                $permCreated++;
                $this->info("  ✔ 创建分组权限: {$groupName} → id={$pid} slug={$permSlug}");
            } else {
                $this->warn("  + 将创建分组权限: {$groupName} (slug={$permSlug})");
                $permIds[$groupName] = 0;
            }

            $order++;
        }

        return [
            'menu_ids' => $menuIds,
            'perm_ids' => $permIds,
            'menu_created' => $menuCreated,
            'perm_created' => $permCreated,
        ];
    }

    // ═══════════════════════════════════════════════════════════════════
    //  菜单同步
    // ═══════════════════════════════════════════════════════════════════

    protected function syncMenu(string $resourceName, string $label, int $parentMenuId, bool $write): array
    {
        $existing = DB::table('admin_menu')
            ->whereIn('uri', [$resourceName, '/'.$resourceName, '/'.ltrim($resourceName, '/')])
            ->first();

        if ($existing) {
            $this->line("    菜单已存在: id={$existing->id} title={$existing->title}");
            if ($parentMenuId > 0 && (int) $existing->parent_id !== $parentMenuId) {
                $this->warn("    ⚠ parent_id 不一致: 当前={$existing->parent_id}, 期望={$parentMenuId}");
            }

            return ['id' => $existing->id, 'created' => false];
        }

        $existing = DB::table('admin_menu')->where('title', $label)->first();
        if ($existing) {
            $this->line("    菜单已存在(按title): id={$existing->id}");

            return ['id' => $existing->id, 'created' => false];
        }

        if ($write) {
            $maxOrder = DB::table('admin_menu')->where('parent_id', $parentMenuId)->max('order') ?? 0;
            $id = DB::table('admin_menu')->insertGetId([
                'parent_id' => $parentMenuId,
                'order' => $maxOrder + 1,
                'title' => $resourceName,
                'icon' => $this->defaultIcon,
                'uri' => $resourceName,
                'extension' => '',
                'show' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->info("    ✔ 创建菜单: {$label} → id={$id} title={$resourceName} (parent={$parentMenuId})");

            return ['id' => $id, 'created' => true];
        }

        $this->warn("    + 将创建菜单: {$label} (title={$resourceName}, uri={$resourceName}, parent={$parentMenuId})");

        return ['id' => null, 'created' => false];
    }

    // ═══════════════════════════════════════════════════════════════════
    //  权限同步
    // ═══════════════════════════════════════════════════════════════════

    /**
     * 同步资源路由的权限记录（单条，挂载在分组父权限下）
     * http_method 留空 = any（匹配所有方法）
     */
    protected function syncResourcePermission(string $resourceName, string $label, int $parentPermId, bool $write): array
    {
        $slug = $resourceName;

        $existing = DB::table('admin_permissions')->where('slug', $slug)->first();
        if ($existing) {
            $this->line("    权限已存在: id={$existing->id} slug={$slug} parent_id={$existing->parent_id}");

            return ['id' => $existing->id, 'created' => false];
        }

        if ($write) {
            $id = DB::table('admin_permissions')->insertGetId([
                'parent_id' => $parentPermId,
                'name' => $resourceName,
                'slug' => $slug,
                'http_method' => '',
                'http_path' => "/{$resourceName}*",
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->info("    ✔ 创建权限: {$label} → id={$id} slug={$slug} parent={$parentPermId} any /{$resourceName}*");

            return ['id' => $id, 'created' => true];
        }

        $this->warn("    + 将创建权限: {$label} (slug={$slug}, parent={$parentPermId})");

        return ['id' => 0, 'created' => false];
    }

    /**
     * 同步单路由权限（http_method 留空 = any）
     */
    protected function syncSingleRoutePermission(array $route, bool $write): array
    {
        $uri = trim($route['uri'], '/');
        $slug = str_replace(['/', '{', '}'], ['.', '', ''], $uri) ?: 'home';
        $label = $route['label'] ?: $slug;

        $existing = DB::table('admin_permissions')->where('slug', $slug)->first();
        if ($existing) {
            $this->line("    权限已存在: id={$existing->id} slug={$slug}");

            return ['id' => $existing->id, 'created' => false];
        }

        if ($write) {
            $slug = Str::limit($slug, 50, '');
            $id = DB::table('admin_permissions')->insertGetId([
                'parent_id' => 0,
                'name' => $slug,
                'slug' => $slug,
                'http_method' => '',
                'http_path' => '/'.ltrim($uri ?: '', '/'),
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->info("    ✔ 创建权限: {$label} → id={$id} slug={$slug} any /{$uri}");

            return ['id' => $id, 'created' => true];
        }

        $this->warn("    + 将创建权限: {$label} (slug={$slug})");

        return ['id' => 0, 'created' => false];
    }

    // ═══════════════════════════════════════════════════════════════════
    //  关联同步
    // ═══════════════════════════════════════════════════════════════════

    protected function syncPivot(int $menuId, array $permissionIds, bool $write): int
    {
        $linked = 0;

        foreach ($permissionIds as $permId) {
            if ($permId <= 0) {
                continue;
            }

            $exists = DB::table('admin_permission_menu')
                ->where('permission_id', $permId)
                ->where('menu_id', $menuId)
                ->exists();

            if ($exists) {
                continue;
            }

            if ($write) {
                DB::table('admin_permission_menu')->insert([
                    'permission_id' => $permId,
                    'menu_id' => $menuId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $linked++;
            } else {
                $this->line("      + 关联: menu({$menuId}) ↔ perm({$permId})");
            }
        }

        return $linked;
    }

    // ═══════════════════════════════════════════════════════════════════
    //  路由解析 & 语言包加载
    // ═══════════════════════════════════════════════════════════════════

    protected function parseResourceRoutes(string $content): array
    {
        $routes = [];
        $pattern = '/\$router\s*->\s*resource\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*(\w+Controller)::class\s*\)/';
        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $routes[] = ['name' => $match[1], 'controller' => $match[2]];
            }
        }

        return $routes;
    }

    protected function parseSingleRoutes(string $content): array
    {
        $routes = [];
        $lines = explode("\n", $content);

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];
            if (preg_match('/->resource\s*\(|Route::group|Admin::routes/', $line)) {
                continue;
            }
            if (preg_match('/\$router\s*->\s*(get|post|put|patch|delete)\s*\(\s*[\'"]([^\'"]+)[\'"]/', $line, $m)) {
                $uri = $m[2];
                if (str_starts_with($uri, 'api/') || $uri === 'api') {
                    continue;
                }

                $hasLabel = str_contains($line, 'permissionLabel');
                if (! $hasLabel && $i + 1 < count($lines)) {
                    $hasLabel = str_contains($lines[$i + 1], 'permissionLabel');
                }

                $label = '';
                if ($hasLabel) {
                    $combinedLine = $line.($i + 1 < count($lines) ? ' '.$lines[$i + 1] : '');
                    if (preg_match("/permissionLabel\s*\(\s*['\"]([^'\"]+)['\"]/", $combinedLine, $pm)) {
                        $label = $pm[1];
                    }
                } elseif ($i > 0 && preg_match('/\/\/\s*(.+)/', $lines[$i - 1], $cm)) {
                    $label = trim($cm[1]);
                }

                $routes[] = [
                    'method' => strtoupper($m[1]),
                    'uri' => $uri,
                    'label' => $label ?: $uri,
                ];
            }
        }

        return $routes;
    }

    protected function loadLabels(string $langDir): array
    {
        $labels = [];
        $langPath = lang_path($langDir);
        if (! is_dir($langPath)) {
            return $labels;
        }
        foreach (glob("{$langPath}/*.php") as $file) {
            $name = basename($file, '.php');
            $data = require $file;
            if (! is_array($data)) {
                continue;
            }
            $langLabels = $data['labels'] ?? [];
            if (is_array($langLabels)) {
                $labels[$name] = $langLabels[$name] ?? (reset($langLabels) ?: $name);
            }
        }

        return $labels;
    }
}
