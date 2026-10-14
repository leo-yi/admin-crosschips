<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 前台会员体系三张新表（全新设计，不复用旧 member_users / member_oauth）：
     * - chip_member            会员主表（Java 后端写，admin 读 + 禁用/启用）
     * - chip_member_oauth      第三方登录绑定表（本期只建表占位，OAuth 后续接入）
     * - chip_member_login_log  登录审计表（只写不改）
     */
    public function up(): void
    {
        if (!Schema::hasTable('chip_member')) {
            Schema::create('chip_member', function (Blueprint $table) {
                $table->id();
                $table->string('email', 100)->comment('登录账号（小写归一化，全站唯一）');
                $table->string('password_hash', 100)->comment('BCrypt(strength 12)，绝不出网');
                $table->string('nickname', 50)->default('')->comment('展示名');
                $table->string('realname', 50)->default('')->comment('真实姓名（B2B 报价/合同场景）');
                $table->string('company_name', 100)->default('')->comment('公司名（B2B 关键画像字段）');
                $table->string('phone', 30)->default('');
                $table->string('avatar', 255)->default('');
                $table->tinyInteger('status')->default(1)->comment('1=正常 0=禁用（admin 可改；Java 每请求校验）');
                $table->dateTime('email_verified_at')->nullable()->comment('邮箱验证通过时间（时间戳兼作审计）');
                $table->dateTime('last_login_at')->nullable();
                $table->string('last_login_ip', 45)->nullable()->comment('兼容 IPv6');
                $table->string('register_ip', 45)->nullable();
                $table->string('register_source', 20)->default('web')->comment('web / google / 导入');
                $table->timestamps();
                $table->timestamp('deleted_at')->nullable()->comment('软删除（与 Java MyBatis-Plus logic-delete 对齐）');

                $table->unique('email', 'uk_email');
                $table->index(['status', 'created_at'], 'idx_status_created');
            });
        }

        if (!Schema::hasTable('chip_member_oauth')) {
            Schema::create('chip_member_oauth', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('member_id')->comment('关联 chip_member.id');
                $table->string('provider', 20)->comment('google / linkedin / github…');
                $table->string('open_id', 128)->comment('第三方用户标识');
                $table->string('email', 100)->default('')->comment('第三方返回的邮箱快照');
                $table->string('nickname', 255)->default('')->comment('快照');
                $table->string('avatar', 255)->default('')->comment('快照');
                $table->timestamps();

                $table->unique(['provider', 'open_id'], 'uk_provider_openid');
                $table->index('member_id', 'idx_member_id');
            });
        }

        if (!Schema::hasTable('chip_member_login_log')) {
            Schema::create('chip_member_login_log', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('member_id')->nullable()->comment('失败时可能无对应账号');
                $table->string('email', 100)->comment('登录尝试的邮箱原文');
                $table->string('login_type', 20)->comment('password（预留 oauth 类型）');
                $table->string('ip', 45);
                $table->string('user_agent', 255)->default('')->comment('截断存储');
                $table->tinyInteger('status')->comment('1=成功 0=失败');
                $table->string('fail_reason', 50)->default('')->comment('bad_credentials / account_disabled / locked');
                $table->timestamp('created_at')->nullable();

                $table->index(['member_id', 'created_at'], 'idx_member_created');
                $table->index(['ip', 'created_at'], 'idx_ip_created');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chip_member_login_log');
        Schema::dropIfExists('chip_member_oauth');
        Schema::dropIfExists('chip_member');
    }
};
