<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChipMember extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'chip_member';

    protected $fillable = [
        'email',
        'nickname',
        'realname',
        'company_name',
        'phone',
        'avatar',
        'status',
        'register_source',
    ];

    protected $casts = [
        'status' => 'int',
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * 密码哈希是 Java 后端的写入域，admin 任何场景都不允许触碰。
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
    ];

    public function oauthAccounts()
    {
        return $this->hasMany(ChipMemberOauth::class, 'member_id', 'id');
    }

    public function loginLogs()
    {
        return $this->hasMany(ChipMemberLoginLog::class, 'member_id', 'id');
    }
}
