<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 给 chip_manufacturer 表新增排序与别名字段，用于导航栏厂商下拉。
     * 注意：chip_manufacturer 表并非由迁移创建，故做幂等处理。
     */
    public function up(): void
    {
        Schema::table('chip_manufacturer', function (Blueprint $table) {
            if (! Schema::hasColumn('chip_manufacturer', 'sort')) {
                $table->unsignedInteger('sort')->default(0)->after('mnf_count')->comment('导航排序号，sort>0 进入导航下拉，值越小越靠前');
            }
            if (! Schema::hasColumn('chip_manufacturer', 'alias_name')) {
                $table->string('alias_name', 100)->nullable()->after('mnf_name')->comment('厂商别名（导航展示名称，为空时回退 mnf_name）');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chip_manufacturer', function (Blueprint $table) {
            if (Schema::hasColumn('chip_manufacturer', 'sort')) {
                $table->dropColumn('sort');
            }
            if (Schema::hasColumn('chip_manufacturer', 'alias_name')) {
                $table->dropColumn('alias_name');
            }
        });
    }
};
