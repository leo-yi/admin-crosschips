<?php

declare(strict_types=1);

namespace App\Enum\EnumUtil;

use App\Enum\RedisEnum;
use App\Models\ChipCategory;
use Illuminate\Support\Facades\Cache;

class CategoryEnum
{
    /**
     * 品类.
     */
    public static function first(): array
    {
        return Cache::remember(RedisEnum::FIRST_CATEGORY->value, 60 * 60 * 24, function () {
            $firstCategory = ChipCategory::where('category_level', 1)->orderBy('id', 'desc')->get();
            $result = [];
            /** @var ChipCategory $item */
            foreach ($firstCategory as $item) {
                $result[$item->id] = $item->category_name;
            }

            return $result;
        });
    }

    /**
     * 产品名称.
     */
    public static function second(): array
    {
        return Cache::remember(RedisEnum::CATEGORY->value, 60 * 60 * 24, function () {
            $category = ChipCategory::where('category_level', 2)->orderBy('id', 'desc')->get();
            $result = [];
            /** @var ChipCategory $item */
            foreach ($category as $item) {
                $result[$item->id] = $item->category_name;
            }

            return $result;
        });
    }

    public static function level(): array
    {
        return [
            1 => '一级',
            2 => '二级',
            3 => '三级',
        ];
    }
}
