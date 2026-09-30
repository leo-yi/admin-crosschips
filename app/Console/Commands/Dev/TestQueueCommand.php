<?php

namespace App\Console\Commands\Dev;

use App\Enum\QueueEnum;
use App\Jobs\ChipProductStockImportJob;
use App\Models\Task;
use Illuminate\Console\Command;

class TestQueueCommand extends Command
{
    protected $signature = 'test:queue';

    protected $description = 'Command description';

    public function handle(): void
    {
        $task = Task::where('id', 1)->first();
        ChipProductStockImportJob::dispatch(['task_id' => $task->id])->onQueue(QueueEnum::CHIP_PRODUCT_STOCK_IMPORT->value);
    }
}
