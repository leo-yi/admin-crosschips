<?php

declare(strict_types=1);

namespace App\Admin\Actions\Form;

use App\Enum\QueueEnum;
use App\Jobs\ChipSearchHotKeywordImportJob;
use App\Services\TaskService;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Widgets\Form;

class ChipSearchHotKeywordImportForm extends Form
{
    /**
     * 导入操作处理：登记 Task 并分发队列，异步执行.
     */
    public function handle(array $input): JsonResponse
    {
        try {
            $task = TaskService::with($input, strval(Admin::user()->id))
                ->setMessage($input['file'])
                ->setTaskType('热门关键词导入')
                ->add();
            // 复用现有 chip_product_stock_import 队列消费进程，避免生产新增 supervisor 队列配置
            ChipSearchHotKeywordImportJob::dispatch(array_merge($input, ['task_id' => $task->id]))
                ->onQueue(QueueEnum::CHIP_PRODUCT_STOCK_IMPORT->value);

            return $this->response()->success('数据导入处理中，请在任务列表中查看')->redirect('task');
        } catch (\Exception $e) {
            return $this->response()->error($e->getMessage());
        }
    }

    public function form()
    {
        $this->file('file', '上传数据（Excel）')->rules([
            'required',
            'max:20480',
            'bail',
            'mimes:xls,xlsx',
        ], ['required' => '文件不能为空'])->autoUpload()->uniqueName()
            ->help('列顺序：Keyword → Sorted → Status（1=启用 0=禁用），首行为表头；按 Keyword 去重覆盖');
    }
}
