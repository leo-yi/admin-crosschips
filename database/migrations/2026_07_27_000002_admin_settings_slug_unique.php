<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 对齐 admin_settings.slug 为唯一索引（Dcat Admin 2.x 迁移定义 slug 为 unique）。
 * 上一步移除 slug 主键时一并丢失了唯一约束，这里补回。
 */
return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('admin_settings', 'slug')) {
            return;
        }

        Schema::table('admin_settings', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down()
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });
    }
};
