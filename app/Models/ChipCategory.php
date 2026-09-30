<?php

namespace App\Models;

use Carbon\Carbon;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ChipCategory
 *
 * @property int $id
 * @property string $category_name
 * @property string|null $alias_name
 * @property string $category_desc
 * @property int $category_level
 * @property int $parent_id
 * @property int $sort
 * @property int $is_active
 * @property int $category_count
 * @property string|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ChipCategory extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $casts = [
        'category_level' => 'int',
        'parent_id' => 'int',
        'sort' => 'int',
        'is_active' => 'int',
        'category_count' => 'int',
    ];

    protected $fillable = [
        'category_name',
        'alias_name',
        'category_desc',
        'category_level',
        'parent_id',
        'sort',
        'is_active',
        'category_count',
    ];

    protected $table = 'chip_category';

    public function children()
    {
        return $this->hasMany(ChipCategory::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(ChipCategory::class, 'parent_id', 'id');
    }
}
