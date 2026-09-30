<?php

namespace App\Models;

use Carbon\Carbon;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ChipManufacturer
 *
 * @property int $id
 * @property string $mnf_img
 * @property string $mnf_name
 * @property string|null $alias_name
 * @property string $mnf_desc
 * @property int $mnf_count
 * @property int $sort
 * @property string|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ChipManufacturer extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'chip_manufacturer';

    protected $casts = [
        'mnf_count' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'mnf_img',
        'mnf_name',
        'alias_name',
        'mnf_desc',
        'is_active',
        'mnf_count',
        'sort',
    ];

    /**
     * 给图片加路径前缀：CDN/R2 URL
     */
    protected function mnfImg(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value
                ? config('brand.cdn_url').'/uploads/mnf/'.$value
                : null,
        );
    }
}
