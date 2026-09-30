<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 所有需要修正的菜单：uri => 正确的 title（资源 slug / 翻译 key）
     */
    protected array $fixes = [
        // ── 分组菜单 ──
        '' => 'admin',       // "后台系统" → admin
        // ── 资源菜单（按 uri 精确匹配）──
        'system-message' => 'system-message',
        'chip-category' => 'chip-category',
        'chip-manufacturer' => 'chip-manufacturer',
        'chip-product' => 'chip-product',
        'index-banner' => 'index-banner',
        'chip-product-stock' => 'chip-product-stock',
        'chip-manufacturer-relation' => 'chip-manufacturer-relation',
        'task' => 'task',
        'chip-rfq' => 'chip-rfq',
        'chip-station' => 'chip-station',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->fixes as $uri => $newTitle) {
            $query = DB::table('admin_menu');

            if ($uri === '') {
                // 分组菜单：uri 为空，限定 parent_id=0 且排除已有的 "admin"(id=2)
                $query->where('uri', '')
                    ->where('parent_id', 0)
                    ->where('title', '!=', 'Admin')
                    ->where('title', '!=', 'Index');
            } else {
                $query->where('uri', $uri);
            }

            $query->update([
                'title' => $newTitle,
                'updated_at' => now(),
            ]);
        }

        // 额外处理：uri 带前导斜杠的 index-banner
        DB::table('admin_menu')
            ->where('uri', '/index-banner')
            ->update(['title' => 'index-banner', 'updated_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 不做回滚——title 修正属于数据修复，回滚无意义
    }
};
