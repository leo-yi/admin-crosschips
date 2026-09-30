<?php

namespace Tests\Services\ExcelImportTasks;

use App\Services\ExcelImportTasks\ChipProductStockImportService;
use Tests\TestCase;

class ChipProductStockImportServiceTest extends TestCase
{
    public function test_handle()
    {
        $file = '/Users/leoyi/code/crosschips/storage/app/temp/20240919/0c678f63a066e3161e208028e6a15d39.xlsx';
        $service = new ChipProductStockImportService($file);
        $service->handle();
    }
}
