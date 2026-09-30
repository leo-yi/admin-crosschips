<?php

declare(strict_types=1);

namespace App\Console\Commands\Business;

use App\Models\ChipCategory;
use App\Models\ChipManufacturer;
use App\Models\ChipProduct;
use App\Models\ChipProductStock;
use App\Models\ChipRfq;
use App\Models\DashboardStat;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * 刷新后台工作台统计数据（dashboard_stats 表）。
 *
 * 由 Laravel 调度器每小时调用：
 *   php artisan dashboard:sync-stats
 *
 * 也可手动执行（首次部署后建议先跑一次）。
 */
class SyncDashboardStatsCommand extends Command
{
    protected $signature = 'dashboard:sync-stats';

    protected $description = '刷新后台工作台统计数据快照（产品数、RFQ 趋势、分类分布等）';

    public function handle(): int
    {
        $this->info('正在刷新工作台统计数据...');

        $this->syncProductTotal();
        $this->syncCategoryTotal();
        $this->syncManufacturerTotal();
        $this->syncRfqStats();
        $this->syncUserStats();
        $this->syncStockStats();
        $this->syncRfqTrend30d();
        $this->syncCategoryDistribution();

        $this->info('工作台统计刷新完成，updated_at = '.now()->toDateTimeString());

        return self::SUCCESS;
    }

    // ── 基础数据 ──────────────────────────────────────────────────────────

    protected function syncProductTotal(): void
    {
        DashboardStat::put(
            DashboardStat::KEY_PRODUCT_TOTAL,
            ChipProduct::query()->count()
        );
    }

    protected function syncCategoryTotal(): void
    {
        // 一级分类数
        DashboardStat::put(
            DashboardStat::KEY_CATEGORY_TOTAL,
            ChipCategory::query()->where('category_level', 1)->count()
        );
    }

    protected function syncManufacturerTotal(): void
    {
        DashboardStat::put(
            DashboardStat::KEY_MANUFACTURER_TOTAL,
            ChipManufacturer::query()->count()
        );
    }

    // ── RFQ ────────────────────────────────────────────────────────────────

    protected function syncRfqStats(): void
    {
        $today = today()->toDateString();

        DashboardStat::put(
            DashboardStat::KEY_RFQ_TOTAL,
            ChipRfq::query()->count()
        );

        DashboardStat::put(
            DashboardStat::KEY_RFQ_TODAY,
            ChipRfq::query()->whereDate('created_at', $today)->count()
        );
    }

    protected function syncRfqTrend30d(): void
    {
        $rows = DB::table('chip_rfq')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as day, COUNT(*) as cnt")
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->whereNull('deleted_at')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $labels = [];
        $values = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $labels[] = $day;
            $values[] = (int) ($rows[$day]->cnt ?? 0);
        }

        DashboardStat::put(
            DashboardStat::KEY_RFQ_TREND_30D,
            array_sum($values),
            ['labels' => $labels, 'values' => $values]
        );
    }

    // ── 用户 ────────────────────────────────────────────────────────────────

    protected function syncUserStats(): void
    {
        $today = today()->toDateString();

        DashboardStat::put(
            DashboardStat::KEY_USER_TOTAL,
            User::query()->count()
        );

        DashboardStat::put(
            DashboardStat::KEY_USER_TODAY,
            User::query()->whereDate('created_at', $today)->count()
        );
    }

    // ── 库存 ────────────────────────────────────────────────────────────────

    protected function syncStockStats(): void
    {
        $map = [
            DashboardStat::KEY_STOCK_COMING => ChipProductStock::COMING,
            DashboardStat::KEY_STOCK_IMMEDIATELY => ChipProductStock::IMMEDIATELY,
            DashboardStat::KEY_STOCK_CUSTOMER => ChipProductStock::CUSTOMER,
            DashboardStat::KEY_STOCK_HOT_SALE => ChipProductStock::HOT_SALE,
        ];

        foreach ($map as $key => $type) {
            DashboardStat::put(
                $key,
                ChipProductStock::query()->where('stock_type', $type)->count()
            );
        }
    }

    // ── 分类分布（一级分类下的产品数） ───────────────────────────────────────

    protected function syncCategoryDistribution(): void
    {
        $rows = DB::table('chip_product as p')
            ->join('chip_category as c', 'c.id', '=', 'p.category_one_id')
            ->select('c.id', 'c.category_name', DB::raw('COUNT(*) as cnt'))
            ->whereNull('p.deleted_at')
            ->whereNull('c.deleted_at')
            ->where('c.category_level', 1)
            ->groupBy('c.id', 'c.category_name')
            ->orderByDesc('cnt')
            ->limit(10)
            ->get();

        $labels = $rows->pluck('category_name')->all();
        $values = $rows->pluck('cnt')->all();

        DashboardStat::put(
            DashboardStat::KEY_CATEGORY_DISTRIBUTION,
            (int) $rows->sum('cnt'),
            ['labels' => $labels, 'values' => $values]
        );
    }
}
