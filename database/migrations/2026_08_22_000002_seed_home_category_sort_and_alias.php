<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 首页品类导航初始化。
     *
     * sort 语义：首页导航排序号，sort > 0 的分类进入首页导航，值越小越靠前；
     * sort = 0 表示不进入首页导航。
     *
     * 6 个首页品类（顺序固定）：
     *   FPGA        -> 二级 412 (Programmable Logic Device (CPLDs/FPGAs))
     *   Memory      -> 一级 17
     *   MCU         -> 二级 411 (Microcontrollers (MCU/MPU/SOC))
     *   IC          -> 一级 15 (Logic，别名显示为 IC)
     *   Sensors     -> 一级 18
     *   Connectors  -> 一级 7
     */
    public function up(): void
    {
        $homeCategories = [
            ['id' => 412, 'sort' => 1, 'alias_name' => 'FPGA'],
            ['id' => 17, 'sort' => 2, 'alias_name' => null],
            ['id' => 411, 'sort' => 3, 'alias_name' => 'MCU'],
            ['id' => 15, 'sort' => 4, 'alias_name' => 'IC'],
            ['id' => 18, 'sort' => 5, 'alias_name' => null],
            ['id' => 7, 'sort' => 6, 'alias_name' => null],
        ];

        foreach ($homeCategories as $item) {
            DB::table('chip_category')->where('id', $item['id'])->update([
                'sort' => $item['sort'],
                'alias_name' => $item['alias_name'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('chip_category')->whereIn('id', [412, 17, 411, 15, 18, 7])->update([
            'sort' => 0,
            'alias_name' => null,
        ]);
    }
};
