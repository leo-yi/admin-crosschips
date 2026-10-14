<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

/**
 * 登录审计表：只读（行由 Java 后端写入，无 updated_at 列）。
 */
class ChipMemberLoginLog extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'chip_member_login_log';

    public $timestamps = false;

    protected $casts = [
        'member_id' => 'int',
        'status' => 'int',
        'created_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(ChipMember::class, 'member_id', 'id');
    }
}
