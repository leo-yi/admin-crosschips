<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChipRfq extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'chip_rfq';

    protected $casts = [
        'upload_file_id' => 'int',
    ];

    protected $fillable = [
        'email',
        'phone',
        'company',
        'contact_name',
        'message',
        'source',
        'upload_file_id',
        'ip_address',
        'user_agent',
        'accept_language',
    ];

    public function products()
    {
        return $this->hasMany(ChipRfqProduct::class, 'rfq_id', 'id');
    }
}
