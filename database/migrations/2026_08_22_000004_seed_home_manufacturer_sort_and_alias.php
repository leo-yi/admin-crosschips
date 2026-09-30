<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 导航栏厂商下拉初始化。
     *
     * sort 语义：导航排序号，sort > 0 的厂商进入导航下拉，值越小越靠前；sort = 0 不进导航。
     *
     * 11 个厂商（顺序固定）：
     *   XILINX   -> 108 AMD/XILINX
     *   MPS      -> 99  Monolithic Power Systems
     *   ALTERA   -> 75  Intel/Altera
     *   MICRON   -> 193 Micron Tech
     *   SAMSUNG  -> 1079 Samsung
     *   BROADCOM -> 192 Broadcom Limited
     *   ADI      -> 49  Analog Devices
     *   TI       -> 19  Texas Instruments
     *   NXP      -> 14  NXP Semicon
     *   ISSI     -> 76  ISSI(Integrated Silicon Solution)
     *   ST       -> 8   STMicroelectronics
     */
    public function up(): void
    {
        $homeManufacturers = [
            ['id' => 108, 'sort' => 1, 'alias_name' => 'XILINX'],
            ['id' => 99, 'sort' => 2, 'alias_name' => 'MPS'],
            ['id' => 75, 'sort' => 3, 'alias_name' => 'ALTERA'],
            ['id' => 193, 'sort' => 4, 'alias_name' => 'MICRON'],
            ['id' => 1079, 'sort' => 5, 'alias_name' => 'SAMSUNG'],
            ['id' => 192, 'sort' => 6, 'alias_name' => 'BROADCOM'],
            ['id' => 49, 'sort' => 7, 'alias_name' => 'ADI'],
            ['id' => 19, 'sort' => 8, 'alias_name' => 'TI'],
            ['id' => 14, 'sort' => 9, 'alias_name' => 'NXP'],
            ['id' => 76, 'sort' => 10, 'alias_name' => 'ISSI'],
            ['id' => 8, 'sort' => 11, 'alias_name' => 'ST'],
        ];

        foreach ($homeManufacturers as $item) {
            DB::table('chip_manufacturer')->where('id', $item['id'])->update([
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
        DB::table('chip_manufacturer')->whereIn('id', [108, 99, 75, 193, 1079, 192, 49, 19, 14, 76, 8])->update([
            'sort' => 0,
            'alias_name' => null,
        ]);
    }
};
