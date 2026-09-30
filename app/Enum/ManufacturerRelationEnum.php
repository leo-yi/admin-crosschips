<?php

namespace App\Enum;

enum ManufacturerRelationEnum: string
{
    use CodeNameTrait;
    use EnumToArray;

    case INDEX_SHOW = 'index_show';
}
