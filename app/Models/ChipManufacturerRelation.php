<?php

namespace App\Models;

use Carbon\Carbon;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ChipManufacturerRelation
 *
 * @property int $id
 * @property int $manufacturer_id
 * @property string $relation_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ChipManufacturerRelation extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'chip_manufacturer_relation';

    protected $casts = [
        'manufacturer_id' => 'int',
    ];

    protected $fillable = [
        'manufacturer_id',
        'relation_type',
    ];

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(ChipManufacturer::class, 'manufacturer_id', 'id');
    }
}
