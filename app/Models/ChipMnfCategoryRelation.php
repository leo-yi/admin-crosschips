<?php

namespace App\Models;

use Carbon\Carbon;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ChipMnfCategoryRelation
 *
 * @property int $id
 * @property int $mnf_id
 * @property int $category_id
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ChipMnfCategoryRelation extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'chip_mnf_category_relation';

    protected $casts = [
        'mnf_id' => 'int',
        'category_id' => 'int',
    ];

    protected $fillable = [
        'mnf_id',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ChipCategory::class, 'category_id');
    }
}
