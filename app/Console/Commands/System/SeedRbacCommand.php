<?php

declare(strict_types=1);

namespace App\Console\Commands\System;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * 生成 RBAC 基础数据：角色、权限树、权限-菜单关联，
 * 并将 user_id=1 绑定为超级管理员（administrator）。
 *
 * 用法:
 *   php artisan admin:seed-rbac             # 预览模式（不写数据库）
 *   php artisan admin:seed-rbac --write     # 写入模式（清空并重建）
 *
 * 说明:
 *   - 超级管理员角色在框架中被特殊处理（Permission 中间件 + Menu::visible 均跳过鉴权），
 *     因此不需要绑定 role_permissions / role_menu，仅绑定 role_users 即可。
 *   - 权限 name 字段存英文 slug，中文显示由 Permission 模型 getNameAttribute 通过
 *     admin.permission_names.* 翻译键提供。
 *   - 权限树形结构与菜单保持一致：分组父权限 → 资源权限。
 */
class SeedRbacCommand extends Command
{
    protected $signature = 'admin:seed-rbac
        {--write : 实际写入数据库（默认仅预览）}';

    protected $description = '生成 RBAC 基础数据（角色、权限树、关联）并绑定超级管理员';

    /** 分组权限树：slug => [children: slug => menu_uri] */
    protected array $groups = [
        'group-admin' => [
            'children' => [
                'auth-users' => 'auth/users',
                'auth-roles' => 'auth/roles',
                'auth-permissions' => 'auth/permissions',
                'auth-menu' => 'auth/menu',
                'auth-extensions' => 'auth/extensions',
                'auth-operation-logs' => 'auth/operation-logs',
            ],
        ],
        'group-help-center' => [
            'children' => [
                'help-categories' => 'help-categories',
                'helps' => 'helps',
            ],
        ],
        'group-web-admin' => [
            'children' => [
                'index-banner' => 'index-banner',
                'chip-rfq' => 'chip-rfq',
                'task' => 'task',
                'chip-manufacturer-relation' => 'chip-manufacturer-relation',
                'chip-product-stock' => 'chip-product-stock',
                'chip-station' => 'chip-station',
            ],
        ],
        'group-basic-data' => [
            'children' => [
                'chip-product' => 'chip-product',
                'chip-manufacturer' => 'chip-manufacturer',
                'chip-category' => 'chip-category',
            ],
        ],
    ];

    /** 单条权限（无分组）：slug => menu_uri */
    protected array $singles = [
        'home' => '/',
        'notifications' => 'notifications',
    ];

    /** 需要清空的表 */
    protected array $truncateTables = [
        'admin_role_users',
        'admin_role_permissions',
        'admin_role_menu',
        'admin_permission_menu',
        'admin_permissions',
        'admin_roles',
    ];

    public function handle(): int
    {
        $write = (bool) $this->option('write');

        $this->info('');
        $this->info('══════════════════════════════════════════════════');
        $this->info('  Admin RBAC 基础数据生成器');
        $this->info('══════════════════════════════════════════════════');
        $this->info('');

        // ── 1. 预检查：user_id=1 必须存在 ──────────────────────────
        $adminUser = DB::table('admin_users')->where('id', 1)->first();
        if (! $adminUser) {
            $this->error('  admin_users 表中不存在 id=1 的用户，无法绑定超级管理员。');

            return self::FAILURE;
        }
        $this->line("  超级管理员用户: id=1, username={$adminUser->username}, name={$adminUser->name}");

        // ── 2. 加载菜单 uri → id 映射 ─────────────────────────────
        $menuMap = $this->buildMenuMap();
        $this->line('  已加载菜单映射: '.count($menuMap).' 条');

        // ── 3. 预览模式 ──────────────────────────────────────────
        if (! $write) {
            $this->preview($menuMap);
            $this->warn('');
            $this->warn('  当前为预览模式，添加 --write 参数写入数据库：');
            $this->warn('  php artisan admin:seed-rbac --write');
            $this->info('');

            return self::SUCCESS;
        }

        // ── 4. 写入模式 ────────────────────────────────────────────
        $this->info('  开始写入...');

        // a. 清空表（TRUNCATE 是 DDL，会隐式提交，不能放在事务中）
        $this->truncateTables();

        // b. 在事务中执行所有 INSERT
        try {
            $result = DB::transaction(fn () => $this->seed($menuMap));
        } catch (\Throwable $e) {
            $this->error("  写入失败，已回滚 INSERT（表已清空无法回滚）: {$e->getMessage()}");

            return self::FAILURE;
        }

        // ── 5. 汇总 ──────────────────────────────────────────────
        $this->info('');
        $this->info('──────────────────────────────────────────────────');
        $this->info(sprintf(
            '  角色: %d | 权限: %d | 权限-菜单关联: %d | 角色-用户关联: %d',
            $result['roles'],
            $result['permissions'],
            $result['permission_menu'],
            $result['role_users'],
        ));
        $this->info('');
        $this->info('  写入完成，建议执行：php artisan optimize:clear');
        $this->info('');

        return self::SUCCESS;
    }

    /**
     * 构建 menu_uri(规范化) => menu_id 映射
     * - '/' → key='/'
     * - 'auth/users' → key='auth/users'
     * - '/index-banner' → key='index-banner'
     * - '' → 跳过（分组父菜单无 uri）
     */
    protected function buildMenuMap(): array
    {
        $map = [];
        $menus = DB::table('admin_menu')->select('id', 'uri')->get();

        foreach ($menus as $menu) {
            $uri = trim((string) $menu->uri);
            if ($uri === '') {
                continue;
            }
            if ($uri === '/') {
                $map['/'] = $menu->id;
            } else {
                $map[trim($uri, '/')] = $menu->id;
            }
        }

        return $map;
    }

    /**
     * 规范化 URI：去掉前导/尾部斜杠，用于匹配
     */
    protected function normalizeUri(?string $uri): string
    {
        return trim(trim((string) $uri), '/');
    }

    /**
     * 预览将要创建的数据
     */
    protected function preview(array $menuMap): void
    {
        $this->info('');
        $this->info('── 将创建的角色 ──');
        $this->line('  • [1] Administrator (slug=administrator)');

        $permCount = 0;
        $pivotCount = 0;

        $this->info('');
        $this->info('── 将创建的权限树 ──');

        // 分组权限
        $order = 0;
        foreach ($this->groups as $groupSlug => $config) {
            $order++;
            $this->line("  • [parent] {$groupSlug} (parent_id=0, order={$order})");
            $permCount++;

            foreach ($config['children'] as $slug => $menuUri) {
                $menuId = $this->findMenuId($menuMap, $menuUri);
                $httpPath = $this->buildHttpPath($menuUri);
                $this->line("    └─ {$slug} (http_path={$httpPath}".($menuId ? ", menu_id={$menuId}" : ', ⚠ 无匹配菜单').')');
                $permCount++;
                if ($menuId) {
                    $pivotCount++;
                }
            }
        }

        // 单条权限
        $this->line('  • [singles]');
        foreach ($this->singles as $slug => $menuUri) {
            $menuId = $this->findMenuId($menuMap, $menuUri);
            $httpPath = $this->buildHttpPath($menuUri);
            $this->line("    └─ {$slug} (parent_id=0, http_path={$httpPath}".($menuId ? ", menu_id={$menuId}" : ', ⚠ 无匹配菜单').')');
            $permCount++;
            if ($menuId) {
                $pivotCount++;
            }
        }

        $this->info('');
        $this->info('── 将创建的关联 ──');
        $this->line('  • admin_role_users: role_id=1 (administrator) ↔ user_id=1');
        $this->line("  • admin_permission_menu: {$pivotCount} 条");

        $this->info('');
        $this->info('── 将清空的表 ──');
        foreach ($this->truncateTables as $table) {
            $this->line("  • {$table}");
        }
    }

    /**
     * 清空 RBAC 相关表（TRUNCATE 是 DDL，会隐式提交，不能在事务中调用）
     */
    protected function truncateTables(): void
    {
        foreach ($this->truncateTables as $table) {
            DB::table($table)->truncate();
            $this->line("  ✓ 清空 {$table}");
        }
    }

    /**
     * 执行写入（仅 INSERT，由调用方包裹事务）
     */
    protected function seed(array $menuMap): array
    {
        // ── a. 创建超级管理员角色 ─────────────────────────────────
        DB::table('admin_roles')->insert([
            'id' => 1,
            'name' => 'Administrator',
            'slug' => 'administrator',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->info('  ✔ 创建角色: id=1, slug=administrator');
        $rolesCreated = 1;

        // ── b. 创建权限树 ────────────────────────────────────────
        $permissionsCreated = 0;
        $pivotCreated = 0;
        $order = 0;

        // 分组父权限 + 子权限
        foreach ($this->groups as $groupSlug => $config) {
            $order++;
            $parentId = DB::table('admin_permissions')->insertGetId([
                'parent_id' => 0,
                'name' => $groupSlug,
                'slug' => $groupSlug,
                'http_method' => '',
                'http_path' => '',
                'order' => $order,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->line("  ✓ 创建分组权限: {$groupSlug} → id={$parentId}");
            $permissionsCreated++;

            foreach ($config['children'] as $slug => $menuUri) {
                $permId = DB::table('admin_permissions')->insertGetId([
                    'parent_id' => $parentId,
                    'name' => $slug,
                    'slug' => $slug,
                    'http_method' => '',
                    'http_path' => $this->buildHttpPath($menuUri),
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $permissionsCreated++;
                $this->line("    ✓ 创建权限: {$slug} → id={$permId}");

                // 关联 permission_menu
                $menuId = $this->findMenuId($menuMap, $menuUri);
                if ($menuId) {
                    DB::table('admin_permission_menu')->insert([
                        'permission_id' => $permId,
                        'menu_id' => $menuId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $pivotCreated++;
                } else {
                    $this->warn("    ⚠ 权限 {$slug} 未找到匹配菜单 (uri={$menuUri})");
                }
            }
        }

        // 单条权限（无分组）
        foreach ($this->singles as $slug => $menuUri) {
            $permId = DB::table('admin_permissions')->insertGetId([
                'parent_id' => 0,
                'name' => $slug,
                'slug' => $slug,
                'http_method' => '',
                'http_path' => $this->buildHttpPath($menuUri),
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $permissionsCreated++;
            $this->line("  ✓ 创建单条权限: {$slug} → id={$permId}");

            $menuId = $this->findMenuId($menuMap, $menuUri);
            if ($menuId) {
                DB::table('admin_permission_menu')->insert([
                    'permission_id' => $permId,
                    'menu_id' => $menuId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $pivotCreated++;
            } else {
                $this->warn("  ⚠ 权限 {$slug} 未找到匹配菜单 (uri={$menuUri})");
            }
        }

        // ── c. 绑定超级管理员 ────────────────────────────────────
        DB::table('admin_role_users')->insert([
            'role_id' => 1,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->info('  ✔ 绑定超级管理员: role_id=1 ↔ user_id=1');

        return [
            'roles' => $rolesCreated,
            'permissions' => $permissionsCreated,
            'permission_menu' => $pivotCreated,
            'role_users' => 1,
        ];
    }

    /**
     * 根据 menu_uri 查找菜单 id
     */
    protected function findMenuId(array $menuMap, string $menuUri): ?int
    {
        $uri = trim($menuUri);
        if ($uri === '/') {
            return $menuMap['/'] ?? null;
        }

        $normalized = trim($uri, '/');

        return $menuMap[$normalized] ?? null;
    }

    /**
     * 构建 http_path：
     * - '/' → '/'
     * - 'auth/users' → '/auth/users*'
     * - 'chip-product' → '/chip-product*'
     */
    protected function buildHttpPath(string $menuUri): string
    {
        $normalized = $this->normalizeUri($menuUri);
        if ($normalized === '') {
            return '/';
        }

        return '/'.$normalized.'*';
    }
}
