<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Task.
 *
 * @property int $id 主键id
 * @property string $task_type 任务的类型
 * @property string $payload 消费的数据
 * @property int $request_type 1 string 直接展示 2 json (弹框), 3, 文件（点击下载）
 * @property string $request 请求的参数信息
 * @property int $task_status 任务的执行状态: 任务状态，1 待执行 2 已分发 3执行中 4 已完成 5执行失败
 * @property int $start_time 任务的开始时间
 * @property int $end_time 任务的结束时间
 * @property string $created_name 任务的添加人
 * @property int $response_type 结果类型,1string 直接展示 2 json (弹框), 3, 文件（点击下载）
 * @property string $response 任务执行的结果
 * @property string $message 任务执行的信息
 * @property string $deleted_at 软删
 * @property Carbon $created_at 任务的添加时间
 */
class Task extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'task';

    public const SHOW = 1;

    public const MODEL = 2;

    public const DOWNLOAD = 3;

    public const TYPE_MAP = [
        self::SHOW => '直接展示',
        self::MODEL => '弹窗',
        self::DOWNLOAD => '文件下载',
    ];

    public const WAIT = 1;

    public const EXECUTING = 2;

    public const SUCCESS = 3;

    public const FAILED = 4;

    public const STATUS_MAP = [
        self::WAIT => '待执行',
        self::EXECUTING => '执行中',
        self::SUCCESS => '成功完成',
        self::FAILED => '执行失败',
    ];

    protected $casts = [
        'request_type' => 'int',
        'task_status' => 'int',
        'start_time' => 'string',
        'end_time' => 'string',
        'response_type' => 'int',
    ];

    protected $fillable = [
        'task_type',
        'payload',
        'request_type',
        'request',
        'task_status',
        'start_time',
        'end_time',
        'created_name',
        'response_type',
        'response',
        'message',
    ];

    /**
     * 格式化开始时间.
     *
     * @author Leo Yi 2023/2/25
     */
    public function startTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value > 0 ? date('Y-m-d H:i:s', $value) : '',
        );
    }

    /**
     * 格式化结束时间.
     *
     * @author Leo Yi 2023/2/25
     */
    public function endTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value > 0 ? date('Y-m-d H:i:s', $value) : '',
        );
    }

    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'created_name', 'id');
    }
}
