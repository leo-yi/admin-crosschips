<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 将所有中文权限名称改为英文 slug。
     * 显示中文由 Permission 模型 getNameAttribute 翻译提供。
     */
    protected array $fixes = [
        '分类' => 'chip-category',
        '品牌厂商' => 'chip-manufacturer',
        '产品' => 'chip-product',
        '首页轮播' => 'index-banner',
        '产品库存' => 'chip-product-stock',
        '制造商关联管理' => 'chip-manufacturer-relation',
        '异步任务列表' => 'task',
        'RFQ' => 'chip-rfq',
        '投放站点' => 'chip-station',
        '后台首页' => 'home',
    ];

    public function up(): void
    {
        foreach ($this->fixes as $oldName => $newName) {
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
        foreach (array_flip($this->fixes) as $newName => $oldName) {
            DB::table('admin_permissions')
                ->where('name', $newName)
                ->update([
                    'name' => $oldName,
                    'updated_at' => now(),
                ]);
        }
    }
};
