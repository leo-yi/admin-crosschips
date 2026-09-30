<?php

namespace Database\Seeders;

use App\Enum\RedisEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Applications 导航菜单数据（来源：infineon.com 官网菜单结构）.
 *
 * 幂等：重复执行会清空后重灌，显式 ID 保证 parent_id 引用一致。
 * 执行：php artisan db:seed --class=ApplicationMenuSeeder
 */
class ApplicationMenuSeeder extends Seeder
{
    public function run(): void
    {
        $path = __DIR__.'/data/application_menus.json';
        $rows = json_decode(file_get_contents($path), true);

        $now = now();
        $rows = array_map(function ($row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;

            return $row;
        }, $rows);

        DB::transaction(function () use ($rows) {
            // 用 delete() 而非 truncate()：TRUNCATE 会隐式提交，导致事务失效
            DB::table('chip_application_menus')->delete();
            foreach (array_chunk($rows, 100) as $chunk) {
                DB::table('chip_application_menus')->insert($chunk);
            }
        });

        Cache::forget(RedisEnum::APPLICATION_MENU->value);
    }
}
