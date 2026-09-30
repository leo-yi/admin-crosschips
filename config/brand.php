<?php

use App\Support\Brand;

/*
|--------------------------------------------------------------------------
| 品牌配置（单品牌：Crosschips）
|--------------------------------------------------------------------------
|
| 品牌信息统一收敛在 App\Support\Brand（固定值），这里仅作对外暴露
| （供运行期 config('brand.*') 读取）。任何品牌差异（名称、logo、favicon、
| 站点 URL、CDN 域名、联系邮箱、邮件主题）都必须走 config('brand.*')，
| 不要硬编码。
|
| 注意：config 文件会在 `php artisan optimize` 时编译进缓存；运行期读取
| 的是已缓存的值。
|
*/

return [
    // 品牌展示名称。
    'name' => Brand::name(),

    // 后台 logo HTML。
    'logo' => Brand::logo(),

    // 侧边栏折叠 mini logo HTML。
    'logo_mini' => Brand::logoMini(),

    // favicon 相对路径。
    'favicon' => Brand::favicon(),

    // 官网首页 URL。
    'site_url' => Brand::siteUrl(),

    // 上传文件公开访问基础 URL（不含尾部斜杠）。
    'upload_base_url' => Brand::uploadBaseUrl(),

    // CDN 图片 URL 前缀（与 upload_base_url 等价，供迁移代码使用）。
    'cdn_url' => Brand::cdnUrl(),

    // 品牌主域名。
    'domain' => Brand::domain(),

    // 品牌联系邮箱。
    'email' => Brand::email(),

    // 邮件相关配置。
    'mail' => [
        'from_name' => Brand::mailFromName(),
        'subject_tag' => Brand::mailSubjectTag(),
    ],

    // 主题配色（邮件模板等使用）。
    'theme' => [
        'primary' => env('BRAND_COLOR_PRIMARY', '#ef7e01'),
        'primary_light' => env('BRAND_COLOR_PRIMARY_LIGHT', '#ff9500'),
        'badge_bg' => env('BRAND_COLOR_BADGE_BG', '#e3f2fd'),
        'badge_text' => env('BRAND_COLOR_BADGE_TEXT', '#1976d2'),
    ],
];
