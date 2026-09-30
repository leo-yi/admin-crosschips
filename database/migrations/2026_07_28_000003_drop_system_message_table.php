<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 删除自建 SystemMessage 模块（与 dcat-plus 自带 Notification 重复，已弃用）。
 *
 * 清理范围：
 *   1. system_message 数据表（含数据）
 *   2. admin_users.msg_index 字段（SystemMessage 已读游标）
 *   3. admin_menu 中 uri='system-message' 的菜单记录 + permission_menu 关联
 *   4. admin_permissions 中 slug='system-message' 的权限记录 + permission_menu 关联
 *
 * 不可逆：原表无 create 迁移，down() 无法重建。
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. 删除 system_message 数据表（含数据）
        Schema::dropIfExists('system_message');

        // 2. 删除 admin_users.msg_index 字段
        $usersTable = config('admin.database.users_table') ?: 'admin_users';
        $connection = config('admin.database.connection') ?: config('database.default');
        $schema = Schema::connection($connection);

        if ($schema->hasColumn($usersTable, 'msg_index')) {
            $schema->table($usersTable, function (Blueprint $table) {
                $table->dropColumn('msg_index');
            });
        }

        // 3. 清理 admin_menu 中 uri='system-message' 的菜单 + 关联
        $menuIds = DB::table('admin_menu')->where('uri', 'system-message')->pluck('id')->all();
        if (! empty($menuIds)) {
            DB::table('admin_permission_menu')->whereIn('menu_id', $menuIds)->delete();
            DB::table('admin_menu')->whereIn('id', $menuIds)->delete();
        }

        // 4. 清理 admin_permissions 中 slug='system-message' 的权限 + 关联
        $permIds = DB::table('admin_permissions')->where('slug', 'system-message')->pluck('id')->all();
        if (! empty($permIds)) {
            DB::table('admin_permission_menu')->whereIn('permission_id', $permIds)->delete();
            DB::table('admin_permissions')->whereIn('id', $permIds)->delete();
        }
    }

    public function down(): void
    {
        // 不可逆：原表无 create 迁移，无法重建 schema
    }
};
