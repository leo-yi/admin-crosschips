<?php

declare(strict_types=1);

namespace App\Admin\Widgets;

use Dcat\Admin\Widgets\Widget;

/**
 * AdminLTE 风格 small-box 卡片（全色背景 + 大数字 + 大图标 + 详情链接）。
 */
class InfoBox extends Widget
{
    protected $view = 'admin.widgets.info-box';

    private array $data;

    public function __construct(
        string $name,
        string $info,
        string $link = '',
        string $icon = 'info',
        string $color = 'primary',
        string $sub = ''
    ) {
        $this->data = [
            'name' => $name,
            'icon' => $icon,
            'link' => $link,
            'info' => $info,
            'sub' => $sub,
        ];

        $this->class("small-box bg-{$color}");
    }

    public function render(): string
    {
        $variables = array_merge($this->data, ['attributes' => $this->formatHtmlAttributes()]);

        return view('admin.widgets.info-box', $variables)->render();
    }
}
