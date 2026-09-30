<?php

namespace App\Enum;

trait CodeNameTrait
{
    // 获取所有枚举值
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    // 根据字符串值获取枚举序号
    public static function getCodeByName(string $value): int
    {
        $values = self::getValues();
        $flipped = array_flip($values);

        if (! isset($flipped[$value])) {
            return 0;
        }

        return array_search($value, $values) + 1;
    }
}
