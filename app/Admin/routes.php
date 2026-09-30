<?php

declare(strict_types=1);

use App\Admin\Controllers\ApiController;
use App\Admin\Controllers\ChipCategoryController;
use App\Admin\Controllers\ChipManufacturerController;
use App\Admin\Controllers\ChipManufacturerRelatedController;
use App\Admin\Controllers\ChipManufacturerRelationController;
use App\Admin\Controllers\ChipProductController;
use App\Admin\Controllers\ChipProductStockController;
use App\Admin\Controllers\ChipRfqController;
use App\Admin\Controllers\ChipStationController;
use App\Admin\Controllers\IndexBannerController;
use App\Admin\Controllers\MenuController;
use App\Admin\Controllers\TaskController;
use Dcat\Admin\Admin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    /* ==============================后台系统============================== */
    // 后台首页
    $router->get('/', 'HomeController@index')
        ->permissionLabel('后台首页', '查看后台首页和数据概览', '后台基础');
    // 覆盖框架菜单控制器，为菜单管理页面添加标题翻译
    $router->resource('auth/menu', MenuController::class);

    /* ==============================基础数据============================== */
    // 分类
    $router->resource('chip-category', ChipCategoryController::class);
    // 制造商
    $router->resource('chip-manufacturer', ChipManufacturerController::class);
    // 产品
    $router->resource('chip-product', ChipProductController::class);
    /* ==============================运营管理============================== */
    // 首页 Banner
    $router->resource('index-banner', IndexBannerController::class);
    // 首页-Latest Stock
    $router->resource('chip-product-stock', ChipProductStockController::class);
    $router->resource('chip-manufacturer-relation', ChipManufacturerRelationController::class);
    // 厂商详情页推荐品牌
    $router->resource('chip-manufacturer-related', ChipManufacturerRelatedController::class);
    // 异步任务
    $router->resource('task', TaskController::class);
    // rfq 列表
    $router->resource('chip-rfq', ChipRfqController::class);
    // 推广站点
    $router->resource('chip-station', ChipStationController::class);

    /* ==============================后台api接口============================== */
    $router->group(['prefix' => 'api'], function (Router $route) {
        // 获取 manufacture 的 select 列表
        $route->get('mnf', [ApiController::class, 'mnf'])
            ->permissionLabel('制造商下拉列表', '获取制造商选择列表数据', '接口');
        // 获取 一级分类 的 select 列表
        $route->get('category', [ApiController::class, 'category'])
            ->permissionLabel('分类下拉列表', '获取一级分类选择列表数据', '接口');
    });
});
