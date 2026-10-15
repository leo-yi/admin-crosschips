<?php

declare(strict_types=1);

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

/**
 * 会员搜索历史（Java 后端写入，admin 只读聚合分析，不做编辑/删除）。
 *
 * @property int $id
 * @property int $member_id
 * @property string $keyword
 */
class ChipMemberSearchHistory extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'chip_member_search_history';

    protected $fillable = [
        'member_id',
        'keyword',
    ];

    protected $casts = [
        'member_id' => 'int',
    ];

    public function member()
    {
        return $this->belongsTo(ChipMember::class, 'member_id', 'id');
    }
}
