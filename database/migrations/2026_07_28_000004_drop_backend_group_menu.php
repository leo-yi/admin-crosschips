<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * 删除"后台系统"分组菜单及其父权限。
 *
 * 自 SystemMessage 模块被废弃后，该分组（admin_menu.id=30）下已无任何子菜单，
 * 而 dcat-plus 自带的 "admin" 分组（id=2）已覆盖系统管理入口（用户/角色/权限/菜单/扩展/操作日志），
 * 故删除冗余分组避免侧边栏出现两个相同标题的顶级菜单。
 *
 * 清理对象（按 id 精确锁定，避免误删 id=2 的 dcat 自带 admin 分组）：
 *   1. admin_menu id=30
 *   2. admin_role_menu menu_id=30 关联
 *   3. admin_permission_menu menu_id=30 关联
 *   4. admin_permissions id=108 (slug=group-backend) 父权限
 *   5. admin_permission_menu permission_id=108 关联
 *
 * 不可逆：分组菜单删除后无意义重建，down() 留空。
 */
return new class extends Migration
{
    /** "后台系统" 分组菜单 id（与 dcat 自带 admin 分组 id=2 区分） */
    private const GROUP_MENU_ID = 30;

    /** "后台系统" 分组父权限 id */
    private const GROUP_PERMISSION_ID = 108;

    public function up(): void
    {
        // 1. 删除菜单的角色绑定
        DB::table('admin_role_menu')->where('menu_id', self::GROUP_MENU_ID)->delete();

        // 2. 删除菜单的权限绑定
        DB::table('admin_permission_menu')->where('menu_id', self::GROUP_MENU_ID)->delete();

        // 3. 删除分组父权限的菜单关联（permission_id=108）
        DB::table('admin_permission_menu')->where('permission_id', self::GROUP_PERMISSION_ID)->delete();

        // 4. 删除分组父权限本身
        DB::table('admin_permissions')->where('id', self::GROUP_PERMISSION_ID)->delete();

        // 5. 删除分组菜单本身（最后删，确保关联已清）
        DB::table('admin_menu')->where('id', self::GROUP_MENU_ID)->delete();
    }

    public function down(): void
    {
        // 不可逆：分组菜单语义已废弃，重建无意义
    }
};
