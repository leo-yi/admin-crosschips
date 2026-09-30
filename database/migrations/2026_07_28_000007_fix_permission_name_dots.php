<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 将 name 中的点号替换为连字符（避免 Laravel trans() 点号嵌套解析问题）。
     * 只处理 admin_permissions.name 列，不影响 slug。
     */
    protected array $dotToHyphen = [
        'operation-log.index' => 'operation-log-index',
        'operation-log.destroy' => 'operation-log-destroy',
        'notifications.index' => 'notifications-index',
        'notifications.show' => 'notifications-show',
        'notifications.create' => 'notifications-create',
        'notifications.store' => 'notifications-store',
        'notifications.edit' => 'notifications-edit',
        'notifications.update' => 'notifications-update',
        'notifications.destroy' => 'notifications-destroy',
        'permissions.index' => 'permissions-index',
        'permissions.show' => 'permissions-show',
        'permissions.create' => 'permissions-create',
        'permissions.store' => 'permissions-store',
        'permissions.edit' => 'permissions-edit',
        'permissions.update' => 'permissions-update',
        'permissions.destroy' => 'permissions-destroy',
        'menu.index' => 'menu-index',
        'menu.store' => 'menu-store',
        'menu.edit' => 'menu-edit',
        'menu.update' => 'menu-update',
        'menu.destroy' => 'menu-destroy',
        'roles.index' => 'roles-index',
        'roles.show' => 'roles-show',
        'roles.create' => 'roles-create',
        'roles.store' => 'roles-store',
        'roles.edit' => 'roles-edit',
        'roles.update' => 'roles-update',
        'roles.destroy' => 'roles-destroy',
        'log-viewer.download' => 'log-viewer-download',
        'log-viewer.delete' => 'log-viewer-delete',
        'log-viewer.clear' => 'log-viewer-clear',
    ];

    public function up(): void
    {
        foreach ($this->dotToHyphen as $oldName => $newName) {
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
        foreach (array_flip($this->dotToHyphen) as $newName => $oldName) {
            DB::table('admin_permissions')
                ->where('name', $newName)
                ->update([
                    'name' => $oldName,
                    'updated_at' => now(),
                ]);
        }
    }
};
