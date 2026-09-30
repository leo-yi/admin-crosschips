<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 后台工作台统计快照表。
 *
 * 大表聚合统计（产品数、RFQ 趋势、分类分布等）由
 * `php artisan dashboard:sync-stats` 定时（每小时）刷新，
 * 工作台页面直接读取本表，避免实时扫描大表。
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_stats', function (Blueprint $table) {
            $table->id();
            $table->string('stat_key', 100)->unique();
            $table->unsignedBigInteger('stat_value')->default(0)->comment('数值统计');
            $table->json('stat_meta')->nullable()->comment('附加 JSON 数据（趋势、分布等）');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_stats');
    }
};
