<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\EventServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        AppServiceProvider::class,
        AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        EventServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        health: '/up',
    )
    ->withCommands()
    ->withSchedule(function (Schedule $schedule) {
        // 每小时刷新后台工作台统计数据（大表聚合写至 dashboard_stats）
        $schedule->command('dashboard:sync-stats')->hourly()->withoutOverlapping();
    })
    ->withMiddleware(function (Middleware $middleware) {
        // CSRF 中间件在原项目中已被禁用（Kernel.php 中被注释）
        $middleware->removeFromGroup('web', PreventRequestForgery::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
