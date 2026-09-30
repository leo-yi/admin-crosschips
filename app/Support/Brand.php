<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * 多品牌配置解析。
 *
 * 一套代码部署多个品牌（hksaturday / crosschips）。当前品牌由环境变量
 * BRAND_ID 决定，每个品牌的预设值见 {@see presets()}。任一预设项均可通过
 * 对应的 BRAND_* 环境变量覆盖（优先级高于 preset）。
 *
 * 这里用纯 PHP 类而非 config 文件间互引，是为了规避 Laravel 配置加载
 * 顺序不确定的问题（`config/admin.php` 字母序先于 `config/brand.php`
 * 加载，此时 `config('brand.*')` 尚未填充）。config/brand.php 与
 * config/admin.php 都直接调用本类方法，autoload 保证可用。
 *
 * 注意：方法内调用 env() 仅在配置编译期（`php artisan config:cache`
 * 或首次 bootstrap）有意义；运行期读取的是已缓存/已加载的配置值。
 */
class Brand
{
    /**
     * 全部品牌预设（只读）。
     *
     * @return array<string,array<string,string>>
     */
    public static function presets(): array
    {
        return [
            'hksaturday' => [
                // 品牌展示名称：用于 logo 文字、登录页标题、浏览器 title 等。
                'name' => 'HKSaturday',
                // 后台 logo（支持 img 标签或纯文字）。logo 图片需放在 public/ 下。
                'logo' => '<img src="/logo_square.png" width="35"> &nbsp;HKSaturday',
                // 侧边栏折叠时的 mini logo。
                'logo_mini' => '<img src="/vendor/dcat-admin/images/logo.png">',
                // 浏览器标签 favicon（相对站点根路径）。
                'favicon' => '/favicon.png',
                // 官网首页 URL，用于后台「返回官网」入口。
                'site_url' => 'https://www.hksaturday.com',
                // 上传文件公开访问基础 URL，用于拼接 manufacturer 等图片地址。
                // 对应前端 API 域名（如 https://images.hksaturday.com）。
                'upload_base_url' => 'https://images.hksaturday.com',
            ],
            'crosschips' => [
                'name' => 'Crosschips',
                // logo_crosschips.png 取自 crosschips.com 前端 public/logo-crosschips.png
                // （1000×249 透明底，4:1），已含品牌 wordmark，故不再追加文字避免重复。
                // 只设 height、宽度按 4:1 自适应（height=35 → 约 140 宽）；勿写死 width 以免拉伸变形。
                'logo' => '<img src="/logo_crosschips.png" height="35">',
                // 侧边栏折叠后空间窄，改用方形图标（logo_crosschips_icon.svg，1:1）而非 4:1 wordmark，避免溢出/变形。
                'logo_mini' => '<img src="/logo_crosschips_icon.svg" height="35">',
                // crosschips 专属 favicon，取自 crosschips.com 前端 public/favicon.ico。
                'favicon' => '/favicon_crosschips.ico',
                'site_url' => 'https://www.crosschips.com',
                'upload_base_url' => 'https://images.crosschips.com',
            ],
        ];
    }

    /**
     * 取当前品牌预设；传 key 则取该 key 的值。
     *
     * @return array<string,string>|string|null
     */
    public static function preset(?string $key = null)
    {
        $brandId = self::id();
        $presets = self::presets();
        $preset = $presets[$brandId] ?? $presets['hksaturday'];

        return $key === null ? $preset : ($preset[$key] ?? null);
    }

    public static function id(): string
    {
        return env('BRAND_ID', 'hksaturday');
    }

    public static function name(): string
    {
        return (string) env('BRAND_NAME', self::preset('name'));
    }

    public static function logo(): string
    {
        return (string) env('BRAND_LOGO', self::preset('logo'));
    }

    public static function logoMini(): string
    {
        return (string) env('BRAND_LOGO_MINI', self::preset('logo_mini'));
    }

    public static function favicon(): string
    {
        return (string) env('BRAND_FAVICON', self::preset('favicon'));
    }

    public static function siteUrl(): string
    {
        return (string) env('BRAND_SITE_URL', self::preset('site_url'));
    }

    public static function uploadBaseUrl(): string
    {
        return rtrim((string) env('BRAND_UPLOAD_BASE_URL', self::preset('upload_base_url')), '/');
    }

    /**
     * CDN 图片 URL 前缀（R2 对象存储公开访问域名）。
     *
     * 与 {@see uploadBaseUrl()} 等价，供从 api 项目迁移的代码使用。
     */
    public static function cdnUrl(): string
    {
        return rtrim((string) env('BRAND_CDN_URL', self::uploadBaseUrl()), '/');
    }

    /**
     * 安全地解析存储路径为完整 URL。
     *
     * 统一入口，确保后台回显与前端 API 返回的图片 URL 一致。
     * - 空值返回空字符串
     * - 已是完整 URL（http/https 开头）直接返回，避免双重前缀
     * - 相对路径通过 admin 磁盘拼接完整 CDN URL
     *
     * @param  string|null  $path  数据库存储的路径（相对路径或完整 URL）
     * @return string 解析后的完整 URL
     */
    public static function storageUrl(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        // 已是完整 URL，直接返回（与 Dcat Admin Grid/Displayers/Image 逻辑一致）
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        return Storage::disk('admin')->url($path);
    }

    /**
     * 品牌联系邮箱（RFQ 通知默认收件人）。
     */
    public static function email(): string
    {
        return (string) env('BRAND_EMAIL', 'sales@hksaturday.com');
    }

    /**
     * 品牌主域名。
     */
    public static function domain(): string
    {
        return (string) env('BRAND_DOMAIN', 'hksaturday.com');
    }

    /**
     * 邮件发件人显示名称。
     */
    public static function mailFromName(): string
    {
        return (string) env('BRAND_MAIL_FROM_NAME', self::name());
    }

    /**
     * 邮件主题标签。
     */
    public static function mailSubjectTag(): string
    {
        return (string) env('BRAND_MAIL_SUBJECT_TAG', self::name());
    }
}
