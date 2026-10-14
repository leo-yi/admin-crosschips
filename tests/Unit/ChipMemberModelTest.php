<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\ChipMember;
use App\Models\ChipMemberLoginLog;
use App\Models\ChipMemberOauth;
use PHPUnit\Framework\TestCase;

/**
 * 会员模型约束（纯内存，不触数据库）：
 * - password_hash 绝不出网（toArray/toJson 均不可见）；
 * - 登录审计表无 Eloquent 时间戳管理（行由 Java 后端写入）；
 * - 三表表名与 Java 端 MyBatis-Plus 对齐。
 */
class ChipMemberModelTest extends TestCase
{
    public function test_password_hash_never_leaks_via_serialization(): void
    {
        $member = new ChipMember([
            'email' => 'unit@example.com',
            'nickname' => 'unit',
        ]);
        $member->password_hash = '$2y$12$fakehashfakehashfakehashfakehashfakehashfakehash12';

        $this->assertArrayNotHasKey('password_hash', $member->toArray());
        $this->assertStringNotContainsString('password_hash', $member->toJson());
        $this->assertStringNotContainsString('$2y$12$', $member->toJson());
    }

    public function test_table_names_align_with_java_side(): void
    {
        $this->assertSame('chip_member', (new ChipMember)->getTable());
        $this->assertSame('chip_member_oauth', (new ChipMemberOauth)->getTable());
        $this->assertSame('chip_member_login_log', (new ChipMemberLoginLog)->getTable());
    }

    public function test_login_log_model_has_no_timestamp_management(): void
    {
        // 审计表只有 created_at 且由 Java 写入，Eloquent 不应自动维护时间戳
        $this->assertFalse((new ChipMemberLoginLog)->timestamps);
    }
}
