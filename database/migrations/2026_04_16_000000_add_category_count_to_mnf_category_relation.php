<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 幂等：生产库该列可能已由 api.hksaturday.com 旧仓库迁移/手工 SQL 添加
        if (Schema::hasColumn('chip_mnf_category_relation', 'category_count')) {
            return;
        }

        Schema::table('chip_mnf_category_relation', function (Blueprint $table) {
            $table->bigInteger('category_count')->default(0)->after('category_id')->comment('该制造商在该分类下的产品数量');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('chip_mnf_category_relation', 'category_count')) {
            return;
        }

        Schema::table('chip_mnf_category_relation', function (Blueprint $table) {
            $table->dropColumn('category_count');
        });
    }
};
