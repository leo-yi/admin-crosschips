<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->configureQueryLogging();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // 登录接口限流：同一 IP+邮箱 每分钟最多 5 次
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip().'|'.$request->input('email'));
        });
    }

    /**
     * 将 SQL 执行日志写入独立的 sql.log，与应用日志分离。
     * 仅非生产环境启用，避免影响性能。
     */
    protected function configureQueryLogging(): void
    {
        if (app()->environment('production')) {
            return;
        }

        DB::listen(function ($query) {
            $sql = $query->sql;
            $bindings = $query->bindings;
            $time = $query->time;

            // 将绑定的参数替换到 SQL 中，方便直接排查
            foreach ($bindings as $i => $binding) {
                if ($binding instanceof \DateTimeInterface) {
                    $bindings[$i] = $binding->format('Y-m-d H:i:s');
                } elseif (is_string($binding)) {
                    $bindings[$i] = "'{$binding}'";
                } elseif (is_bool($binding)) {
                    $bindings[$i] = $binding ? '1' : '0';
                } elseif (is_null($binding)) {
                    $bindings[$i] = 'NULL';
                }
            }

            $sql = str_replace(['%', '?'], ['%%', '%s'], $sql);
            $sql = vsprintf($sql, $bindings);

            Log::channel('sql')->debug(sprintf('[%sms] %s', $time, $sql));
        });
    }
}
