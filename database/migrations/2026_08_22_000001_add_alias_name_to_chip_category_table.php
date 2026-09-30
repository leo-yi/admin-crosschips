<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 给 chip_category 表新增 alias_name 别名字段，用于首页品类展示名称。
     * 注意：chip_category 表并非由迁移创建（历史库直接建表），故此处做幂等处理，
     * 仅在列不存在时新增，避免重复执行报错。
     */
    public function up(): void
    {
        Schema::table('chip_category', function (Blueprint $table) {
            if (! Schema::hasColumn('chip_category', 'alias_name')) {
                $table->string('alias_name', 100)->nullable()->after('category_name')->comment('分类别名（首页展示名称，为空时回退到 category_name）');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chip_category', function (Blueprint $table) {
            if (Schema::hasColumn('chip_category', 'alias_name')) {
                $table->dropColumn('alias_name');
            }
        });
    }
};
