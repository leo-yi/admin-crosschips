<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MessageLog.
 *
 * @property int $id
 * @property int $uid
 * @property int $message_id
 * @property string|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class MessageLog extends Model
{
    protected $table = 'message_log';

    protected $casts = [
        'uid' => 'int',
        'message_id' => 'int',
    ];

    protected $fillable = [
        'uid',
        'message_id',
    ];
}
