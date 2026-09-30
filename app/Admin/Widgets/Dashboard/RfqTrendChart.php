<?php

declare(strict_types=1);

namespace App\Admin\Widgets\Dashboard;

use App\Models\DashboardStat;
use Dcat\Admin\Widgets\ApexCharts\Chart;
use Illuminate\Contracts\Support\Renderable;

/**
 * 工作台 - 近 30 天 RFQ 趋势面积图。
 *
 * 使用 Dcat Admin 内置 Chart 类（自动加载 ApexCharts JS + 处理 PJAX）。
 * 数据由 dashboard:sync-stats 写入 dashboard_stats.stat_meta。
 */
class RfqTrendChart implements Renderable
{
    protected string $containerId = 'dashboard-rfq-trend';

    public function render(): string
    {
        $meta = DashboardStat::metaOf(DashboardStat::KEY_RFQ_TREND_30D);
        $labels = $meta['labels'] ?? array_fill(0, 30, '');
        $values = $meta['values'] ?? array_fill(0, 30, 0);

        $categories = array_map(
            fn ($d) => substr((string) $d, 5), // MM-DD
            $labels
        );

        $chart = new Chart('#'.$this->containerId, [
            'chart' => [
                'type' => 'area',
                'height' => 320,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                ['name' => 'RFQ', 'data' => $values],
            ],
            'xaxis' => [
                'categories' => $categories,
                'labels' => ['rotate' => -45, 'hideOverlapping' => true],
            ],
            'dataLabels' => ['enabled' => false],
            'stroke' => ['curve' => 'smooth', 'width' => 2],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shadeIntensity' => 1,
                    'opacityFrom' => 0.4,
                    'opacityTo' => 0.1,
                    'stops' => [0, 90, 100],
                ],
            ],
            'colors' => ['#28c76f'],
            'tooltip' => ['x' => ['format' => 'yyyy-MM-dd']],
        ]);

        // 触发脚本注册（selector 已设置，html() 返回 null）
        $chart->html();

        return <<<HTML
<div class="card">
    <div class="card-header">
        <h4 class="card-title">近 30 天 RFQ 询价趋势</h4>
    </div>
    <div class="card-body">
        <div id="{$this->containerId}"></div>
    </div>
</div>
HTML;
    }
}
