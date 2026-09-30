<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 前台 Applications 导航菜单（数据来源：infineon.com 官网菜单结构）。
     * 注意：本地/线上库中该表可能已由人工建好并导入数据，故做幂等处理。
     */
    public function up(): void
    {
        if (Schema::hasTable('chip_application_menus')) {
            return;
        }

        Schema::create('chip_application_menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->comment('父级菜单ID，顶级为NULL');
            $table->string('name')->comment('菜单名称');
            $table->string('slug')->default('')->comment('URL别名');
            $table->string('url', 500)->default('')->comment('来源链接');
            $table->unsignedTinyInteger('level')->default(1)->comment('层级1-4');
            $table->unsignedInteger('sort')->default(0)->comment('同级排序号');
            $table->string('source', 50)->default('infineon')->comment('数据来源');
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id');
            $table->index('level');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chip_application_menus');
    }
};
