<?php

namespace App\Enum;

enum StockTypeEnum: string
{
    use CodeNameTrait;

    case COMING = 'Coming Stock';
    case IMMEDIATELY = 'Immediately Stock';
    case CUSTOMER = 'Customer Stock';
    case HOT_SALE = 'Hot Sale';
}
