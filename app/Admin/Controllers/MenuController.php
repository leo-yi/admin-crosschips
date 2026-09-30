<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Http\Controllers\MenuController as BaseMenuController;
use Illuminate\Support\Facades\Lang;

/**
 * 覆盖框架自带的 MenuController，为菜单管理页面的树节点添加标题翻译。
 *
 * 框架 Layout\Menu::translate()（侧边栏）已有翻译逻辑，
 * 但 MenuController::treeView() 的 branch 回调直接使用原始 title，
 * 此类仅覆盖 treeView() 重新设置 branch 回调。
 */
class MenuController extends BaseMenuController
{
    /**
     * {@inheritDoc}
     */
    protected function treeView()
    {
        $tree = parent::treeView();

        $tree->branch(function ($branch) {
            $titleTranslation = 'menu.titles.'.trim(str_replace(' ', '_', strtolower($branch['title'])));
            $title = Lang::has($titleTranslation)
                ? __($titleTranslation)
                : $branch['title'];
            $payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$title}</strong>";

            if (! isset($branch['children'])) {
                if (url()->isValidUrl($branch['uri'])) {
                    $uri = $branch['uri'];
                } else {
                    $uri = admin_base_path($branch['uri']);
                }

                $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
            }

            return $payload;
        });

        return $tree;
    }
}
