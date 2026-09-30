<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class IndexBanner.
 *
 * @property int $id
 * @property string $img_url
 * @property string $url
 * @property int $sorted
 * @property string|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ChipBanner extends Model
{
    use SoftDeletes;

    protected $table = 'chip_banner';

    protected $casts = [
        'sorted' => 'int',
    ];

    protected $fillable = [
        'img_url',
        'url',
        'sorted',
    ];
}
