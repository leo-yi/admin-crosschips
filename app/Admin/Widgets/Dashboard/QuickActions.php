<?php

declare(strict_types=1);

namespace App\Admin\Widgets\Dashboard;

use Illuminate\Contracts\Support\Renderable;

/**
 * 工作台 - 快捷操作入口。
 */
class QuickActions implements Renderable
{
    protected array $actions = [
        [
            'icon' => 'feather icon-plus',
            'color' => 'primary',
            'label' => '新增产品',
            'url' => 'chip-product/create',
        ],
        [
            'icon' => 'feather icon-package',
            'color' => 'info',
            'label' => '产品库存',
            'url' => 'chip-product-stock',
        ],
        [
            'icon' => 'feather icon-file-text',
            'color' => 'success',
            'label' => 'RFQ 管理',
            'url' => 'chip-rfq',
        ],
        [
            'icon' => 'feather icon-image',
            'color' => 'warning',
            'label' => '首页 Banner',
            'url' => 'index-banner',
        ],
        [
            'icon' => 'feather icon-grid',
            'color' => 'primary',
            'label' => '分类管理',
            'url' => 'chip-category',
        ],
        [
            'icon' => 'feather icon-layers',
            'color' => 'info',
            'label' => '品牌厂商',
            'url' => 'chip-manufacturer',
        ],
        [
            'icon' => 'feather icon-navigation',
            'color' => 'success',
            'label' => '投放站点',
            'url' => 'chip-station',
        ],
        [
            'icon' => 'feather icon-list',
            'color' => 'warning',
            'label' => '异步任务',
            'url' => 'task',
        ],
    ];

    public function render(): string
    {
        $html = '<div class="card"><div class="card-header"><h4 class="card-title">快捷入口</h4></div><div class="card-body"><div class="row">';

        foreach ($this->actions as $action) {
            $url = admin_url($action['url']);
            $html .= <<<HTML
<div class="col-6 col-md-3 mb-1">
    <a href="{$url}" class="btn btn-outline-{$action['color']} btn-block text-left">
        <i class="{$action['icon']}"></i> {$action['label']}
    </a>
</div>
HTML;
        }

        $html .= '</div></div></div>';

        return $html;
    }
}
