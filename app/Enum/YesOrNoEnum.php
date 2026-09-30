<?php

declare(strict_types=1);

namespace App\Enum;

enum YesOrNoEnum: int
{
    use EnumToArray;

    case YES = 1;
    case NO = 0;
}
