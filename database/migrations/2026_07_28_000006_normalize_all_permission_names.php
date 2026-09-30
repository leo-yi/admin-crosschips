<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 将所有 admin_permissions.name 从中文改为英文 slug。
     */
    protected array $nameFixMap = [
        // 标准 CRUD action — 按 slug 中的 action 部分匹配
        '列表' => 'index',
        '查看' => 'show',
        '新建' => 'create',
        '保存' => 'store',
        '编辑' => 'edit',
        '更新' => 'update',
        '删除' => 'destroy',
        // 系统级 action — 逐个映射
        '查看操作日志' => 'operation-log.index',
        '删除操作日志' => 'operation-log.destroy',
        '查看通知列表' => 'notifications.index',
        '查看通知详情' => 'notifications.show',
        '新建通知' => 'notifications.create',
        '保存通知' => 'notifications.store',
        '编辑通知' => 'notifications.edit',
        '更新通知' => 'notifications.update',
        '删除通知' => 'notifications.destroy',
        '查看权限列表' => 'permissions.index',
        '查看权限详情' => 'permissions.show',
        '新建权限' => 'permissions.create',
        '保存权限' => 'permissions.store',
        '编辑权限' => 'permissions.edit',
        '更新权限' => 'permissions.update',
        '删除权限' => 'permissions.destroy',
        '查看菜单列表' => 'menu.index',
        '保存菜单' => 'menu.store',
        '编辑菜单' => 'menu.edit',
        '更新菜单' => 'menu.update',
        '删除菜单' => 'menu.destroy',
        '查看角色列表' => 'roles.index',
        '查看角色详情' => 'roles.show',
        '新建角色' => 'roles.create',
        '保存角色' => 'roles.store',
        '编辑角色' => 'roles.edit',
        '更新角色' => 'roles.update',
        '删除角色' => 'roles.destroy',
        '查看日志文件' => 'log-viewer-file',
        '查看系统日志' => 'log-viewer',
        '下载系统日志' => 'log-viewer.download',
        '删除系统日志' => 'log-viewer.delete',
        '清空系统日志' => 'log-viewer.clear',
    ];

    public function up(): void
    {
        foreach ($this->nameFixMap as $oldName => $newName) {
            DB::table('admin_permissions')
                ->where('name', $oldName)
                ->update([
                    'name' => $newName,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        foreach (array_flip($this->nameFixMap) as $newName => $oldName) {
            DB::table('admin_permissions')
                ->where('name', $newName)
                ->update([
                    'name' => $oldName,
                    'updated_at' => now(),
                ]);
        }
    }
};
