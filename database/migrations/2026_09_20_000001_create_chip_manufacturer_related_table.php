<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 厂商详情页 "You May Also Be Interested In" 推荐品牌配置表。
     * 每条记录表示：厂商 mnf_id 的详情页推荐展示厂商 related_mnf_id。
     * 前台 /api/manufacturer/related 优先读此表（按 sort 升序），不足 12 个时
     * 用一级分类重合度自动推荐补足。
     */
    public function up(): void
    {
        if (Schema::hasTable('chip_manufacturer_related')) {
            return;
        }

        Schema::create('chip_manufacturer_related', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mnf_id')->comment('当前厂商ID（详情页所属厂商）');
            $table->unsignedBigInteger('related_mnf_id')->comment('推荐展示的厂商ID');
            $table->unsignedInteger('sort')->default(0)->comment('排序号，值越小越靠前');
            $table->timestamps();

            $table->unique(['mnf_id', 'related_mnf_id'], 'uk_mnf_related');
            $table->index('related_mnf_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chip_manufacturer_related');
    }
};
