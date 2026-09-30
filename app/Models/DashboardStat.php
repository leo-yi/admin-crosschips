<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $stat_key
 * @property int $stat_value
 * @property array|null $stat_meta
 * @property Carbon $updated_at
 */
class DashboardStat extends Model
{
    protected $table = 'dashboard_stats';

    public $timestamps = false;

    protected $casts = [
        'stat_value' => 'int',
        'stat_meta' => 'array',
    ];

    protected $fillable = [
        'stat_key',
        'stat_value',
        'stat_meta',
    ];

    // 标准统计键
    public const KEY_PRODUCT_TOTAL = 'product_total';

    public const KEY_CATEGORY_TOTAL = 'category_total';

    public const KEY_MANUFACTURER_TOTAL = 'manufacturer_total';

    public const KEY_RFQ_TOTAL = 'rfq_total';

    public const KEY_RFQ_TODAY = 'rfq_today';

    public const KEY_USER_TOTAL = 'user_total';

    public const KEY_USER_TODAY = 'user_today';

    public const KEY_STOCK_COMING = 'stock_coming';

    public const KEY_STOCK_IMMEDIATELY = 'stock_immediately';

    public const KEY_STOCK_CUSTOMER = 'stock_customer';

    public const KEY_STOCK_HOT_SALE = 'stock_hot_sale';

    public const KEY_RFQ_TREND_30D = 'rfq_trend_30d';

    public const KEY_CATEGORY_DISTRIBUTION = 'category_distribution';

    /**
     * 读取一个统计项的数值（不存在返回 0）。
     */
    public static function valueOf(string $key): int
    {
        return (int) (self::query()->where('stat_key', $key)->value('stat_value') ?? 0);
    }

    /**
     * 读取一个统计项的 meta（不存在返回空数组）。
     */
    public static function metaOf(string $key): array
    {
        return (array) (self::query()->where('stat_key', $key)->value('stat_meta') ?? []);
    }

    /**
     * 一次性读取多个统计项，返回 [key => row] 映射。
     */
    public static function loadMany(array $keys): array
    {
        return self::query()
            ->whereIn('stat_key', $keys)
            ->get()
            ->keyBy('stat_key')
            ->toArray();
    }

    /**
     * upsert 一条统计记录。
     */
    public static function put(string $key, int $value, ?array $meta = null): void
    {
        DB::table('dashboard_stats')->upsert(
            [
                'stat_key' => $key,
                'stat_value' => $value,
                'stat_meta' => $meta ? json_encode($meta, JSON_UNESCAPED_UNICODE) : null,
                'updated_at' => now(),
            ],
            ['stat_key'],
            ['stat_value', 'stat_meta', 'updated_at']
        );
    }
}
