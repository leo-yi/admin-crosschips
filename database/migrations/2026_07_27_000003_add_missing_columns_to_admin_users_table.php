<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('admin.database.users_table') ?: 'admin_users';
        $connection = config('admin.database.connection') ?: config('database.default');
        $schema = Schema::connection($connection);

        $schema->table($tableName, function (Blueprint $table) use ($schema, $tableName) {
            if (! $schema->hasColumn($tableName, 'parent_id')) {
                $table->bigInteger('parent_id')->default(0)->after('id');
            }
            if (! $schema->hasColumn($tableName, 'order')) {
                $table->integer('order')->default(0)->after('name');
            }
            if (! $schema->hasColumn($tableName, 'email')) {
                $table->string('email', 100)->nullable()->after('order');
            }
            if (! $schema->hasColumn($tableName, 'wx_openid')) {
                $table->string('wx_openid', 255)->nullable()->after('email');
            }
            if (! $schema->hasColumn($tableName, 'is_active')) {
                $table->integer('is_active')->default(1)->nullable()->after('wx_openid');
            }
        });
    }

    public function down(): void
    {
        $tableName = config('admin.database.users_table') ?: 'admin_users';
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->table($tableName, function (Blueprint $table) {
            $table->dropColumn(['parent_id', 'order', 'email', 'wx_openid', 'is_active']);
        });
    }
};
