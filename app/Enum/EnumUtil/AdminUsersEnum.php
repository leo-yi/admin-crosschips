<?php

declare(strict_types=1);

namespace App\Enum\EnumUtil;

use App\Models\AdminUser;

class AdminUsersEnum
{
    public static function list()
    {
        $firstCategory = AdminUser::orderBy('id', 'desc')->get();
        $result = [];
        /** @var AdminUser $item */
        foreach ($firstCategory as $item) {
            $result[$item->id] = $item->username;
        }

        return $result;
    }
}
