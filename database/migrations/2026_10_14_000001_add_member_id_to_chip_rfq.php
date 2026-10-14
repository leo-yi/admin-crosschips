<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * RFQ 会员化（Account 页 Phase 2A）：
     * - member_id：已登录提交时回填（匿名询价保持 NULL，是现有获客入口，不强制登录）
     * - status：处理状态，admin 侧流转（pending 默认）
     */
    public function up(): void
    {
        if (Schema::hasTable('chip_rfq')) {
            if (! Schema::hasColumn('chip_rfq', 'member_id')) {
                Schema::table('chip_rfq', function (Blueprint $table) {
                    $table->unsignedBigInteger('member_id')->nullable()->comment('会员ID（已登录提交时回填）')->after('id');
                    $table->index('member_id', 'idx_member_id');
                });
            }

            if (! Schema::hasColumn('chip_rfq', 'status')) {
                Schema::table('chip_rfq', function (Blueprint $table) {
                    $table->string('status', 20)->default('pending')->comment('pending/quoted/won/lost')->after('message');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('chip_rfq')) {
            if (Schema::hasColumn('chip_rfq', 'member_id')) {
                Schema::table('chip_rfq', function (Blueprint $table) {
                    $table->dropIndex('idx_member_id');
                    $table->dropColumn('member_id');
                });
            }

            if (Schema::hasColumn('chip_rfq', 'status')) {
                Schema::table('chip_rfq', function (Blueprint $table) {
                    $table->dropColumn('status');
                });
            }
        }
    }
};
