<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final readonly class TriggerIsrRevalidation
{
    /**
     * 调用前端 ISR On-Demand Revalidation API，即时刷新静态页面缓存。
     *
     * @param  string  $path  需要重新验证的页面路径，默认 '/'
     */
    public function execute(string $path = '/'): void
    {
        $secret = config('services.nextjs.revalidate_secret');
        $siteUrl = config('services.nextjs.site_url');
        $url = rtrim((string) $siteUrl, '/').'/api/revalidate?secret='.urlencode((string) $secret);

        try {
            $response = Http::timeout(10)->get($url);

            if ($response->successful()) {
                Log::info('ISR revalidation succeeded', ['path' => $path]);

                return;
            }

            Log::warning('ISR revalidation failed', [
                'path' => $path,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('ISR revalidation error', [
                'path' => $path,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
