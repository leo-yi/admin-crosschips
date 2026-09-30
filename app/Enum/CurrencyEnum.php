<?php

namespace App\Enum;

enum CurrencyEnum: string
{
    use EnumToArray;

    case USD = 'USD';
    case CNY = 'CNY';
    case HKD = 'HKD';
    case EUR = 'EUR';
}
