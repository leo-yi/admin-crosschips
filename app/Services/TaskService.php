<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;

final class TaskService
{
    private array $data = [];

    private string $request = '';

    private string $uid = '';

    private string $message = '';

    private string $taskType = '';

    public static function with(array $data, string $uid)
    {
        return tap(new self, function (self $service) use ($data, $uid) {
            $service->data = $data;
            $service->request = json_encode(request()->toArray());
            $service->uid = $uid;
        });
    }

    /**
     * 添加异步任务
     *
     *
     * @author Leo Yi 2023/2/25
     */
    public function add(): Task
    {
        return Task::create([
            'task_type' => $this->taskType,
            'payload' => json_encode($this->data),
            'request_type' => Task::SHOW,
            'request' => $this->request,
            'task_status' => Task::WAIT,
            'start_time' => time(),
            'end_time' => 0,
            'created_name' => $this->uid,
            'response_type' => 0,
            'response' => '',
            'message' => '',
        ]);
    }

    /**
     * 执行中.
     *
     *
     *
     * @author Leo Yi 2023/2/25
     */
    public static function execute(Task $task): bool
    {
        $task->task_status = Task::EXECUTING;

        return $task->save();
    }

    /**
     * 异步任务完成.
     *
     *
     *
     * @author Leo Yi 2023/2/25
     */
    public function finish(int $taskId, string $response, bool $result = true, int $responseType = Task::SHOW): void
    {
        Task::where('id', $taskId)->update([
            'task_status' => $result ? Task::SUCCESS : Task::FAILED,
            'response' => $response,
            'response_type' => $responseType,
            'end_time' => time(),
            'message' => $this->message,
        ]);
    }

    /**
     * 初始化.
     *
     *
     * @author Leo Yi 2023/2/25
     */
    public static function init(): self
    {
        return new self;
    }

    /**
     * 设置信息.
     *
     *
     * @return $this
     *
     * @author Leo Yi 2023/2/25
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * 设置任务类型.
     *
     *
     * @return $this
     *
     * @author Leo Yi 2023/2/25
     */
    public function setTaskType(string $taskType): self
    {
        $this->taskType = $taskType;

        return $this;
    }
}
