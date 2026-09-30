<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| 后台页面由 Dcat Admin 通过 app/Admin/routes.php 注册（/admin 前缀）。
| 站点根路径直接跳转至管理面板。
*/

Route::redirect('/', '/admin');
