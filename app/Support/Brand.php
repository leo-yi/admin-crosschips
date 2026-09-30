<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * 品牌配置（单品牌：Crosschips）。
 *
 * 历史上曾以 presets() + BRAND_ID / BRAND_* 环境变量支撑 hksaturday 与
 * crosschips 多品牌；2026-09 hksaturday 下线后仅保留 Crosschips，多品牌
 * 机制一并移除，此处直接返回固定值。保留本类与 config('brand.*') 访问面，
 * 调用方（config/admin.php、Mail、模型等）无需改动。
 */
class Brand
{
    public const NAME = 'Crosschips';

    // 4:1 透明 wordmark；只设 height，宽度按比例自适应，勿写死 width 以免变形。
    // Dcat 有 `.navbar-header .navbar-brand img{max-width:45px}`（为方形 logo 设计），
    // 会把宽 wordmark 横向压扁，故内联 max-width:none 覆盖（该规则无 !important）。
    public const LOGO = '<img src="/logo_crosschips.png" height="35" style="height:35px;width:auto;max-width:none">';

    // 侧边栏折叠时用 1:1 方形图标，避免 4:1 wordmark 在窄栏溢出。
    public const LOGO_MINI = '<img src="/logo_crosschips_icon.svg" height="35">';

    public const FAVICON = '/favicon_crosschips.ico';

    public const SITE_URL = 'https://www.crosschips.com';

    // R2 对象存储公开访问域名（CDN 图片前缀）。
    public const UPLOAD_BASE_URL = 'https://images.crosschips.com';

    public const EMAIL = 'sales@crosschips.com';

    public const DOMAIN = 'crosschips.com';

    public static function name(): string
    {
        return self::NAME;
    }

    public static function logo(): string
    {
        return self::LOGO;
    }

    public static function logoMini(): string
    {
        return self::LOGO_MINI;
    }

    public static function favicon(): string
    {
        return self::FAVICON;
    }

    public static function siteUrl(): string
    {
        return self::SITE_URL;
    }

    public static function uploadBaseUrl(): string
    {
        return self::UPLOAD_BASE_URL;
    }

    /**
     * CDN 图片 URL 前缀（R2 对象存储公开访问域名）。
     *
     * 与 {@see uploadBaseUrl()} 等价，供从 api 项目迁移的代码使用。
     */
    public static function cdnUrl(): string
    {
        return self::UPLOAD_BASE_URL;
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
        return self::EMAIL;
    }

    /**
     * 品牌主域名。
     */
    public static function domain(): string
    {
        return self::DOMAIN;
    }

    public static function mailFromName(): string
    {
        return self::NAME;
    }

    public static function mailSubjectTag(): string
    {
        return self::NAME;
    }
}
