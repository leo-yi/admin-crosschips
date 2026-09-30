<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Task;
use App\Services\ExcelImportTasks\ChipProductStockImportService;
use App\Services\FileService;
use App\Services\TaskService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChipProductStockImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data = [];

    public int $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(1800);
        $begin = microtime(true);
        if (! isset($this->data['task_id'])) {
            throw new \Exception('没找到task id');
        }

        try {
            $task = Task::find($this->data['task_id']);
            if (empty($task)) {
                throw new \Exception('没找到task');
            }
            TaskService::execute($task);
            $payload = json_decode($task->payload, true);
            if (empty($payload['file'])) {
                throw new \Exception('没找到文件');
            }
            $fileService = new FileService;
            $tempFilePath = $fileService->downloadFile($payload['file']);

            // 执行导入
            ChipProductStockImportService::init($tempFilePath)->handle();
            // 更新状态
            TaskService::init()->finish($this->data['task_id'], '导入成功');
            // 删除文件
            $fileService->cleanupFile($tempFilePath);
        } catch (\Throwable $exception) {
            $end = microtime(true);
            $className = Str::snake(class_basename($this));
            Log::error('/mq/consumer/'.$className, [
                'request' => [
                    'queue' => $this->queue,
                    'payload' => $this->data,
                ],
                'response' => $exception->getMessage(),
                'time_consume' => number_format($end - $begin, 4),
            ]);
            TaskService::init()->finish($this->data['task_id'], $exception->getMessage(), false);
        }
    }

    public function retryUntil(): Carbon
    {
        return now()->addMinutes(60);
    }
}
