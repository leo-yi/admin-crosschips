<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 对齐 admin_settings 表结构到 Dcat Admin 2.x。
 *
 * 升级时该表仍停留在旧版 laravel-admin 结构（slug 为主键，无 id / group_name），
 * 导致运行时 admin_setting_group('layout_config') 查询 group_name 列报
 * SQLSTATE[42S22] 并引发后台页面 500。
 *
 * 迁移 2020_09_07_090635_create_admin_settings_table 要求：
 *   id (PK, 自增) / group_name (nullable) / slug (unique) / value / timestamps
 * 这里以不丢数据的方式补齐缺失的列。
 */
return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('admin_settings', 'group_name')) {
            return;
        }

        // 1. 移除旧版以 slug 为主键的约束
        DB::statement('ALTER TABLE admin_settings DROP PRIMARY KEY');

        // 2. 补齐 Dcat Admin 2.x 所需的 id 自增主键与 group_name 列
        DB::statement('ALTER TABLE admin_settings ADD COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
        DB::statement('ALTER TABLE admin_settings ADD COLUMN group_name VARCHAR(200) NULL AFTER id');

        // slug 原有的 UNIQUE 约束保留，与迁移定义一致
    }

    public function down()
    {
        if (! Schema::hasColumn('admin_settings', 'group_name')) {
            return;
        }

        DB::statement('ALTER TABLE admin_settings DROP COLUMN id');
        DB::statement('ALTER TABLE admin_settings DROP COLUMN group_name');
        DB::statement('ALTER TABLE admin_settings ADD PRIMARY KEY (slug)');
    }
};
