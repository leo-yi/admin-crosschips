<?php

declare(strict_types=1);

namespace App\Admin\Widgets\Dashboard;

use App\Models\ChipRfq;
use Illuminate\Contracts\Support\Renderable;

/**
 * 工作台 - 最近 RFQ 列表。
 *
 * 实时查询 chip_rfq 表最近 8 条（小结果集，不影响性能）。
 */
class LatestRfqList implements Renderable
{
    /**
     * 是否有询价数据（供 HomeController 决定布局）。
     */
    public static function hasData(): bool
    {
        return ChipRfq::query()->exists();
    }

    public function render(): string
    {
        $list = ChipRfq::query()
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        if ($list->isEmpty()) {
            return '';
        }

        $rows = '';
        foreach ($list as $item) {
            $email = e($item->email ?: '-');
            $createdAt = optional($item->created_at)->format('m-d H:i');
            $url = admin_url('chip-rfq/'.$item->id);

            $rows .= <<<HTML
<tr>
    <td class="text-truncate" style="max-width:200px" title="{$email}">{$email}</td>
    <td>{$createdAt}</td>
    <td class="text-right">
        <a href="{$url}" class="btn btn-xs btn-outline-primary">查看</a>
    </td>
</tr>
HTML;
        }

        $moreUrl = admin_url('chip-rfq');

        return <<<HTML
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">最近询价（RFQ）</h4>
        <a href="{$moreUrl}" class="btn btn-sm btn-flat-primary">查看全部</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead>
                <tr>
                    <th>邮箱</th>
                    <th>提交时间</th>
                    <th class="text-right">操作</th>
                </tr>
            </thead>
            <tbody>
                {$rows}
            </tbody>
        </table>
    </div>
</div>
HTML;
    }
}
