<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 补充 dcat-plus 框架内置但数据库中缺失的菜单记录:
     *   - 操作日志 (auth/operation-logs)
     *   - 帮助中心分组 + 帮助分类 + 帮助文章
     *   - 系统通知
     */
    public function up(): void
    {
        $now = now();

        $menus = [
            // 操作日志 → 挂载在 admin 分组(id=2)
            [
                'id' => 33,
                'parent_id' => 2,
                'order' => 6,
                'title' => 'operation_log',
                'icon' => 'feather icon-file-text',
                'uri' => 'auth/operation-logs',
                'extension' => '',
                'show' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // 帮助中心分组
            [
                'id' => 34,
                'parent_id' => 0,
                'order' => 7,
                'title' => 'help_center',
                'icon' => 'feather icon-help-circle',
                'uri' => '',
                'extension' => '',
                'show' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // 帮助分类 → 帮助中心
            [
                'id' => 35,
                'parent_id' => 34,
                'order' => 1,
                'title' => 'help_categories',
                'icon' => 'feather icon-layers',
                'uri' => 'help-categories',
                'extension' => '',
                'show' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // 帮助文章 → 帮助中心
            [
                'id' => 36,
                'parent_id' => 34,
                'order' => 2,
                'title' => 'helps',
                'icon' => 'feather icon-book-open',
                'uri' => 'helps',
                'extension' => '',
                'show' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // 系统通知
            [
                'id' => 37,
                'parent_id' => 0,
                'order' => 8,
                'title' => 'notifications',
                'icon' => 'feather icon-bell',
                'uri' => 'notifications',
                'extension' => '',
                'show' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($menus as $menu) {
            $exists = DB::table('admin_menu')
                ->where('id', $menu['id'])
                ->orWhere(function ($q) use ($menu) {
                    $q->where('uri', $menu['uri'])->where('uri', '!=', '');
                })
                ->orWhere(function ($q) use ($menu) {
                    $q->where('title', $menu['title'])->where('parent_id', $menu['parent_id']);
                })
                ->exists();

            if (! $exists) {
                DB::table('admin_menu')->insert($menu);
            }
        }
    }

    public function down(): void
    {
        DB::table('admin_menu')->whereIn('id', [33, 34, 35, 36, 37])->delete();
    }
};
