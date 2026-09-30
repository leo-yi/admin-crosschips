<?php

declare(strict_types=1);

use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;

/*
 * Dcat-admin - admin builder based on Laravel.
 * @author jqh <https://github.com/jqhph>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 *
 * extend custom field:
 * Dcat\Admin\Form::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Column::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Filter::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
app('view')->prependNamespace('admin', resource_path('views/admin'));

Grid::resolving(function (Grid $grid) {
    $grid->toolsWithOutline(false);
    //    $grid->disableViewButton();
    //    $grid->disableBatchDelete();
    $grid->showColumnSelector();
    //    $grid->hideColumns(['id', 'updated_at']);
});

Form::resolving(function (Form $form) {
    $form->disableResetButton();

    $form->tools(function (Form\Tools $tools) {
        // 去掉跳转列表按钮
        // $tools->disableList();
        // 去掉跳转详情页按钮
        $tools->disableView();
        // 去掉删除按钮
        $tools->disableDelete();
    });
    $form->footer(function ($footer) {
        // 去掉`重置`按钮
        $footer->disableReset();
        // 去掉`查看`checkbox
        $footer->disableViewCheck();
        // 去掉`继续编辑`checkbox
        $footer->disableEditingCheck();
        // 去掉`继续创建`checkbox
        $footer->disableCreatingCheck();
    });
});

$primariyColor = Admin::color()->get('primary');
$primariyColorLight = Admin::color()->lighten('primary', 10);

Admin::style(<<<CSS
    /* 覆盖 dcatplus.css 的 #5c6bc0 硬编码颜色 */
    .main-sidebar .sidebar .nav-sidebar > .nav-item > .nav-link.active,
    .main-sidebar .sidebar .nav-sidebar > .nav-item > .nav-link.active:hover,
    .main-sidebar .sidebar .nav-treeview > .nav-item > .nav-link.active,
    .main-sidebar .sidebar .nav-treeview > .nav-item > .nav-link.active:hover,
    .main-sidebar .sidebar .nav-treeview > li > .nav-link.active,
    .main-sidebar .sidebar .nav-treeview > li > .nav-link.active:hover {
        background: linear-gradient(45deg, $primariyColor, $primariyColorLight) !important;
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: none !important;
        border-left: 3px solid $primariyColor;
        padding-left: calc(1rem - 3px);
        transition: all 0.3s ease;
    }
    
    .main-sidebar .sidebar .nav-link.active > i:first-child {
        color: #ffffff !important;
    }
    
    .main-sidebar .sidebar .nav-sidebar > .nav-item > .nav-link:hover {
        background: linear-gradient(45deg, $primariyColor, $primariyColorLight) !important;
        transform: translateX(5px);
        color: #ffffff !important;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(24, 144, 255, 0.3);
    }
CSS
);
