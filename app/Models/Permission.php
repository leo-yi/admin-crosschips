<?php

declare(strict_types=1);

namespace App\Models;

use Dcat\Admin\Models\Permission as BasePermission;

/**
 * 扩展 Dcat Admin Permission 模型，支持通过翻译文件显示中文名称。
 *
 * name 字段存储英文 slug，显示时自动查找 admin.permission_names.{name} 翻译 key，
 * 找不到翻译则回退为原始 name 值。
 */
class Permission extends BasePermission
{
    /**
     * 自动翻译权限名称。
     *
     * @param  string|null  $value
     */
    public function getNameAttribute($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $key = 'admin.permission_names.'.$value;

        if (app()->bound('translator') && app('translator')->has($key)) {
            return (string) app('translator')->get($key);
        }

        return $value;
    }
}
