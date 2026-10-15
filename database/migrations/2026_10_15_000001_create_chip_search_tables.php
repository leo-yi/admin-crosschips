<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Header 搜索功能两张新表：
     * - chip_member_search_history  登录会员搜索历史（Java 后端写，每会员保留最新 100 条；admin 只读聚合分析）
     * - chip_search_hot_keyword     热门推荐关键词（admin 维护 + Excel 导入；Java 读并缓存 Redis）
     */
    public function up(): void
    {
        if (!Schema::hasTable('chip_member_search_history')) {
            Schema::create('chip_member_search_history', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('member_id')->comment('关联 chip_member.id');
                $table->string('keyword', 100)->comment('搜索关键词（型号）');
                $table->timestamps();

                // 同一会员同一关键词只存一条，重复搜索刷新 updated_at
                $table->unique(['member_id', 'keyword'], 'uk_member_keyword');
                $table->index(['member_id', 'updated_at'], 'idx_member_updated');
            });
        }

        if (!Schema::hasTable('chip_search_hot_keyword')) {
            Schema::create('chip_search_hot_keyword', function (Blueprint $table) {
                $table->id();
                $table->string('keyword', 100)->comment('热门关键词（型号）');
                $table->integer('sorted')->default(10)->comment('排序，小的在前');
                $table->tinyInteger('status')->default(1)->comment('1=启用 0=禁用');
                $table->timestamps();

                $table->unique('keyword', 'uk_keyword');
                $table->index(['status', 'sorted'], 'idx_status_sorted');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chip_search_hot_keyword');
        Schema::dropIfExists('chip_member_search_history');
    }
};
