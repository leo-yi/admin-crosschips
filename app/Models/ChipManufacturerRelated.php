<?php

namespace App\Models;

use Carbon\Carbon;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ChipManufacturerRelated
 *
 * 厂商详情页推荐品牌配置（mnf_id 的详情页推荐展示 related_mnf_id）
 *
 * @property int $id
 * @property int $mnf_id
 * @property int $related_mnf_id
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ChipManufacturerRelated extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'chip_manufacturer_related';

    protected $casts = [
        'mnf_id' => 'int',
        'related_mnf_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'mnf_id',
        'related_mnf_id',
        'sort',
    ];

    /**
     * 当前厂商（详情页所属厂商）
     */
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(ChipManufacturer::class, 'mnf_id', 'id');
    }

    /**
     * 推荐展示的厂商
     */
    public function relatedManufacturer(): BelongsTo
    {
        return $this->belongsTo(ChipManufacturer::class, 'related_mnf_id', 'id');
    }
}
