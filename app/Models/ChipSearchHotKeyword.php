<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\RedisEnum;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

/**
 * 热门搜索关键词（admin 维护 + Excel 导入；Java 读取并缓存 Redis）。
 *
 * @property int $id
 * @property string $keyword
 * @property int $sorted
 * @property int $status 1=启用 0=禁用
 */
class ChipSearchHotKeyword extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'chip_search_hot_keyword';

    public const STATUS_MAP = [
        1 => '启用',
        0 => '禁用',
    ];

    protected $fillable = [
        'keyword',
        'sorted',
        'status',
    ];

    protected $casts = [
        'sorted' => 'int',
        'status' => 'int',
    ];

    /**
     * 任何变更（表单保存、Grid 快捷编辑、Excel 导入、删除）都主动失效 Java 侧热词缓存，
     * Java 下次读取时回源本表并回填。admin 与 Java 零 API 调用，靠 Redis key 契约联动。
     */
    protected static function booted(): void
    {
        $flush = function () {
            Redis::del(RedisEnum::SEARCH_HOT_KEYWORDS->value);
        };
        static::saved($flush);
        static::deleted($flush);
    }
}
