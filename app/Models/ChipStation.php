<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ChipStation.
 *
 * @property int $id
 * @property string $name
 * @property string $param
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ChipStation extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    const KEY = 'chip-station';

    const PARAM_NAME = 'xply';

    protected $table = 'chip_station';

    protected $fillable = [
        'name',
        'param',
    ];

    public function getParamAttribute($key)
    {
        if ($key) {
            return rtrim((string) config('brand.site_url'), '/').'?'.self::PARAM_NAME.'='.$key;
        }

        return '';
    }

    public static function getName(string $param): string
    {
        return self::where('param', $param)->pluck('name')->first() ?? '';
    }
}
