<?php

use App\Support\Brand;

/*
|--------------------------------------------------------------------------
| 多品牌配置
|--------------------------------------------------------------------------
|
| 一套代码同时支撑多个品牌（hksaturday / crosschips），通过环境变量
| BRAND_ID 切换当前品牌。预设与解析逻辑封装在 App\Support\Brand 中，
| 这里仅作对外暴露（供运行期 config('brand.*') 读取）。
|
| 各部署站点在 .env 中设置 BRAND_ID=xxx 即可生效；如需覆盖某一项预设，
| 可设置对应的 BRAND_* 变量（优先级高于 preset）。
|
| 部署入口见 deploy/deploy.sh：
|   bash deploy/deploy.sh hksaturday   # 部署 hksaturday 品牌
|   bash deploy/deploy.sh crosschips   # 部署 crosschips 品牌
|
| 注意：config 文件会在 `php artisan optimize` 时编译进缓存，env() 仅
| 在编译期有意义；运行期读取的是已缓存的值。
|
*/

return [
    // 当前品牌标识。
    'id' => Brand::id(),

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

    // 全部品牌预设（只读参考，便于排查）。
    'presets' => Brand::presets(),
];
