<?php

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Admin\Widgets\Dashboard\LatestRfqList;
use App\Admin\Widgets\Dashboard\QuickActions;
use App\Admin\Widgets\Dashboard\RfqTrendChart;
use App\Admin\Widgets\InfoBox;
use App\Models\DashboardStat;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;

class HomeController
{
    public function index(Content $content)
    {
        // 从 dashboard_stats 定时快照读取
        $productTotal = DashboardStat::valueOf(DashboardStat::KEY_PRODUCT_TOTAL);
        $categoryTotal = DashboardStat::valueOf(DashboardStat::KEY_CATEGORY_TOTAL);
        $mnfTotal = DashboardStat::valueOf(DashboardStat::KEY_MANUFACTURER_TOTAL);
        $rfqTotal = DashboardStat::valueOf(DashboardStat::KEY_RFQ_TOTAL);
        $rfqToday = DashboardStat::valueOf(DashboardStat::KEY_RFQ_TODAY);
        $userTotal = DashboardStat::valueOf(DashboardStat::KEY_USER_TOTAL);
        $userToday = DashboardStat::valueOf(DashboardStat::KEY_USER_TODAY);
        $stockComing = DashboardStat::valueOf(DashboardStat::KEY_STOCK_COMING);
        $stockImm = DashboardStat::valueOf(DashboardStat::KEY_STOCK_IMMEDIATELY);
        $stockCust = DashboardStat::valueOf(DashboardStat::KEY_STOCK_CUSTOMER);
        $stockHot = DashboardStat::valueOf(DashboardStat::KEY_STOCK_HOT_SALE);
        $stockTotal = $stockComing + $stockImm + $stockCust + $stockHot;

        $fmt = fn (int $n) => number_format($n);

        return $content
            ->header('工作台')
            ->description('数据概览')
            // 第一行：4 个 small-box 统计卡片（全色背景 + 大图标）
            ->body(function (Row $row) use ($fmt, $productTotal, $categoryTotal, $mnfTotal, $rfqTotal, $rfqToday, $userTotal, $userToday, $stockTotal, $stockComing, $stockImm, $stockCust, $stockHot) {
                $row->column(3, new InfoBox(
                    '产品总数',
                    $fmt($productTotal),
                    admin_url('chip-product'),
                    'cube',
                    'primary',
                    "分类 {$fmt($categoryTotal)} · 品牌 {$fmt($mnfTotal)}"
                ));
                $row->column(3, new InfoBox(
                    'RFQ 询价总数',
                    $fmt($rfqTotal),
                    admin_url('chip-rfq'),
                    'file-text-o',
                    'success',
                    "今日新增 {$fmt($rfqToday)} 条"
                ));
                $row->column(3, new InfoBox(
                    '前端用户总数',
                    $fmt($userTotal),
                    admin_url('auth/users'),
                    'users',
                    'info',
                    "今日新增 {$fmt($userToday)} 人"
                ));
                $row->column(3, new InfoBox(
                    '库存总条目',
                    $fmt($stockTotal),
                    admin_url('chip-product-stock'),
                    'cubes',
                    'warning',
                    "现货 {$fmt($stockImm)} · Coming {$fmt($stockComing)} · 客户 {$fmt($stockCust)} · 热卖 {$fmt($stockHot)}"
                ));
            })
            // 第二行：快捷入口
            ->body(function (Row $row) {
                $row->column(12, new QuickActions);
            })
            // 第三行：最近询价列表 + RFQ 趋势图并排（6/6）
            ->body(function (Row $row) {
                $row->column(6, new LatestRfqList);
                $row->column(6, new RfqTrendChart);
            });
    }
}
