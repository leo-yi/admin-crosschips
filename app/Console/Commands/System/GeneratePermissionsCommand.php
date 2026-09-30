<?php

declare(strict_types=1);

namespace App\Console\Commands\System;

use Illuminate\Console\Command;

/**
 * 扫描 admin 路由，为资源路由对应的 lang 文件补充 permissions 块，
 * 并列出需要手动添加 permissionLabel() 的单路由。
 *
 * 用法:
 *   php admin:generate-permissions           # 预览模式（不写文件）
 *   php admin:generate-permissions --write   # 写入模式
 */
class GeneratePermissionsCommand extends Command
{
    protected $signature = 'admin:generate-permissions
        {--write : 实际写入文件（默认仅预览）}
        {--lang=zh_CN : 语言包目录名}';

    protected $description = '为 admin 资源路由的 lang 文件自动补充 permissions 权限配置块';

    /** 默认 action 中文模板（{label} 会被替换为资源中文名） */
    protected array $actionTemplates = [
        'index' => '列表',
        'show' => '查看',
        'create' => '新建',
        'store' => '保存',
        'edit' => '编辑',
        'update' => '更新',
        'destroy' => '删除',
        'import' => '导入',
        'export' => '导出',
    ];

    /** 业务分组映射：resource-name => group */
    protected array $groupMap = [
        'chip-category' => '基础数据',
        'chip-manufacturer' => '基础数据',
        'chip-product' => '基础数据',
        'index-banner' => '运营管理',
        'chip-product-stock' => '运营管理',
        'chip-manufacturer-relation' => '运营管理',
        'task' => '运营管理',
        'chip-rfq' => '运营管理',
        'chip-station' => '运营管理',
    ];

    public function handle(): int
    {
        $write = (bool) $this->option('write');
        $langDir = (string) $this->option('lang');
        $langPath = lang_path($langDir);

        if (! is_dir($langPath)) {
            $this->error("语言包目录不存在: {$langPath}");

            return self::FAILURE;
        }

        $routesFile = app_path('Admin/routes.php');
        if (! file_exists($routesFile)) {
            $this->error("路由文件不存在: {$routesFile}");

            return self::FAILURE;
        }

        $routeContent = file_get_contents($routesFile);

        // ── 1. 解析资源路由 ────────────────────────────────────────────
        $resourceRoutes = $this->parseResourceRoutes($routeContent);
        // ── 2. 解析单路由 ─────────────────────────────────────────────
        $singleRoutes = $this->parseSingleRoutes($routeContent);

        $this->info('');
        $this->info('══════════════════════════════════════════════════');
        $this->info('  Admin 权限配置生成器');
        $this->info('══════════════════════════════════════════════════');
        $this->info('');

        // ── 3. 处理资源路由 ────────────────────────────────────────────
        $updated = 0;
        $skipped = 0;

        foreach ($resourceRoutes as $resource) {
            $name = $resource['name']; // e.g. chip-product
            $langFile = "{$langPath}/{$name}.php";

            if (! file_exists($langFile)) {
                $this->warn("  ⚠  lang 文件不存在，跳过: {$name}.php");
                $skipped++;

                continue;
            }

            $content = file_get_contents($langFile);

            // 检查是否已有 permissions 块
            if (preg_match("/['\"]permissions['\"]\s*=>/", $content)) {
                $this->line("  ✓  已有 permissions，跳过: {$name}.php");
                $skipped++;

                continue;
            }

            // 从 labels 中提取中文名
            $label = $this->extractLabel($content, $name);
            $group = $this->groupMap[$name] ?? '';

            // 生成 permissions 块
            $permissionsBlock = $this->buildPermissionsBlock($label, $group);

            if ($write) {
                $this->injectPermissions($langFile, $content, $permissionsBlock);
                $this->info("  ✔  已写入: {$name}.php  (label: {$label}, group: {$group})");
                $updated++;
            } else {
                $this->info("  +  将写入: {$name}.php  (label: {$label}, group: {$group})");
                $this->line($permissionsBlock);
                $this->line('');
                $updated++;
            }
        }

        // ── 4. 列出单路由 ─────────────────────────────────────────────
        $this->info('');
        $this->info('── 单路由（需手动在 routes.php 添加 permissionLabel）──');

        if (empty($singleRoutes)) {
            $this->line('  （无单路由）');
        } else {
            foreach ($singleRoutes as $route) {
                $comment = $route['comment'] ?: '请补充描述';
                $this->line("  • {$route['method']} {$route['uri']}  →  {$comment}");
                $this->line("    ->permissionLabel('{$comment}', '', '分组名');");
            }
        }

        // ── 5. 汇总 ──────────────────────────────────────────────────
        $this->info('');
        $this->info('──────────────────────────────────────────────────');
        $this->info(sprintf(
            '  资源路由: %d 个需更新, %d 个已跳过 | 单路由: %d 个',
            $updated, $skipped, count($singleRoutes)
        ));

        if (! $write && $updated > 0) {
            $this->warn('');
            $this->warn('  当前为预览模式，添加 --write 参数实际写入文件：');
            $this->warn('  php artisan admin:generate-permissions --write');
        }

        if ($write) {
            $this->info('');
            $this->info('  写入完成，建议执行：');
            $this->info('  php artisan optimize:clear');
        }

        $this->info('');

        return self::SUCCESS;
    }

    /**
     * 从路由文件内容中解析资源路由列表
     * 匹配: $router->resource('chip-product', ChipProductController::class);
     *
     * @return array<int, array{name: string, controller: string}>
     */
    protected function parseResourceRoutes(string $content): array
    {
        $routes = [];
        // 匹配 $router->resource('xxx', XxxController::class)
        $pattern = '/\$router\s*->\s*resource\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*(\w+Controller)::class\s*\)/';

        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $routes[] = [
                    'name' => $match[1], // e.g. chip-product
                    'controller' => $match[2], // e.g. ChipProductController
                ];
            }
        }

        return $routes;
    }

    /**
     * 从路由文件内容中解析单路由列表（排除 resource 和 api 前缀的内部路由）
     *
     * @return array<int, array{method: string, uri: string, comment: string}>
     */
    protected function parseSingleRoutes(string $content): array
    {
        $routes = [];
        $lines = explode("\n", $content);

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];

            // 跳过 resource 路由和路由组定义
            if (preg_match('/->resource\s*\(|Route::group|Admin::routes/', $line)) {
                continue;
            }

            // 匹配 $router->get/post/put/delete('uri', ...)
            if (preg_match('/\$router\s*->\s*(get|post|put|patch|delete)\s*\(\s*[\'"]([^\'"]+)[\'"]/', $line, $m)) {
                $method = strtoupper($m[1]);
                $uri = $m[2];

                // 检查当前行或下一行是否已有 permissionLabel
                $hasLabel = str_contains($line, 'permissionLabel');
                if (! $hasLabel && $i + 1 < count($lines)) {
                    $hasLabel = str_contains($lines[$i + 1], 'permissionLabel');
                }
                if ($hasLabel) {
                    continue; // 已配置，跳过
                }

                // 尝试从上一行注释中提取中文说明
                $comment = '';
                if ($i > 0 && preg_match('/\/\/\s*(.+)/', $lines[$i - 1], $cm)) {
                    $comment = trim($cm[1]);
                }

                $routes[] = [
                    'method' => $method,
                    'uri' => $uri,
                    'comment' => $comment,
                ];
            }
        }

        return $routes;
    }

    /**
     * 从 lang 文件内容中提取 labels 的中文名称
     */
    protected function extractLabel(string $content, string $resourceName): string
    {
        // 优先匹配 resource-name 对应的值：'chip-product' => '产品'
        if (preg_match("/['\"]".preg_quote($resourceName, '/')."['\"]\s*=>\s*['\"]([^'\"]+)['\"]/", $content, $m)) {
            return $m[1];
        }

        // 其次匹配 labels 块中第一个值
        if (preg_match("/'labels'\s*=>\s*\[.*?['\"]([^'\"]+)['\"]\s*=>\s*['\"]([^'\"]+)['\"]/s", $content, $m)) {
            return $m[2];
        }

        return $resourceName;
    }

    /**
     * 生成 permissions PHP 数组代码块
     */
    protected function buildPermissionsBlock(string $label, string $group): string
    {
        $lines = [];
        $lines[] = "    'permissions' => [";

        // resource 级别元数据（group 必须放在 resource 内框架才会读取）
        if ($group !== '') {
            if ($label !== '') {
                $lines[] = "        'resource' => ['title' => '{$label}', 'group' => '{$group}'],";
            } else {
                $lines[] = "        'resource' => ['group' => '{$group}'],";
            }
        } elseif ($label !== '') {
            $lines[] = "        'resource' => ['title' => '{$label}'],";
        } else {
            $lines[] = "        'resource' => [],";
        }

        $lines[] = "        'description' => '',";
        $lines[] = "        'actions' => [";

        foreach ($this->actionTemplates as $action => $defaultLabel) {
            $lines[] = "            '{$action}' => '{$defaultLabel}',";
        }

        $lines[] = '        ],';
        $lines[] = "        'routes' => [";
        $lines[] = '        ],';
        $lines[] = '    ],';

        return implode("\n", $lines);
    }

    /**
     * 将 permissions 块注入到 lang 文件的 return 数组末尾（]; 之前）
     */
    protected function injectPermissions(string $filePath, string $content, string $block): void
    {
        // 在最后的 ]; 之前插入 permissions 块
        // 找到最后一个 ]; 的位置
        $lastBracket = strrpos($content, '];');

        if ($lastBracket === false) {
            $this->error("  无法定位 ]; 位置: {$filePath}");

            return;
        }

        $newContent = substr($content, 0, $lastBracket)
            .$block."\n"
            .substr($content, $lastBracket);

        file_put_contents($filePath, $newContent);
    }
}
