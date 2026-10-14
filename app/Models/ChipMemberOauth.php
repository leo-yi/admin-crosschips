<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class ChipMemberOauth extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'chip_member_oauth';

    protected $fillable = [
        'member_id',
        'provider',
        'open_id',
        'email',
        'nickname',
        'avatar',
    ];

    public function member()
    {
        return $this->belongsTo(ChipMember::class, 'member_id', 'id');
    }
}
