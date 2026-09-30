<?php

declare(strict_types=1);

namespace App\Admin\Constants;

class AdminConst
{
    const YES = 1;

    const NO = 0;

    const YES_OR_NO_MAP = [
        self::YES => '是',
        self::NO => '否',
    ];

    const SWITCH = [
        'on' => ['value' => self::YES, 'text' => '是', 'color' => 'success'],
        'off' => ['value' => self::NO, 'text' => '否', 'color' => 'danger'],
    ];

    const SORT_HELP = '数字越大，排序越靠前';
}
