<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ChipApplicationMenu
 *
 * 前台 Applications 导航菜单（数据来源：infineon.com 官网菜单结构）
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string $url
 * @property int $level
 * @property int $sort
 * @property string $source
 * @property int $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 */
class ChipApplicationMenu extends Model
{
    use SoftDeletes;

    protected $table = 'chip_application_menus';

    protected $casts = [
        'parent_id' => 'int',
        'level' => 'int',
        'sort' => 'int',
        'is_active' => 'int',
    ];

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'url',
        'level',
        'sort',
        'source',
        'is_active',
    ];

    public function children()
    {
        return $this->hasMany(ChipApplicationMenu::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(ChipApplicationMenu::class, 'parent_id', 'id');
    }
}
