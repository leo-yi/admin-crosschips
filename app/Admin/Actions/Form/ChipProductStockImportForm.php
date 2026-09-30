<?php

declare(strict_types=1);

namespace App\Admin\Actions\Form;

use App\Enum\QueueEnum;
use App\Jobs\ChipProductStockImportJob;
use App\Services\TaskService;
use Dcat\Admin\Admin;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Widgets\Form;

class ChipProductStockImportForm extends Form
{
    /**
     * 导入操作处理.
     *
     *
     *
     * @author Leo Yi 2023/3/18
     */
    public function handle(array $input): JsonResponse
    {
        try {
            // 记录操作日志
            $task = TaskService::with($input, strval(Admin::user()->id))
                ->setMessage($input['file'])
                ->setTaskType('产品库存导入')
                ->add();
            // 分发队列
            ChipProductStockImportJob::dispatch(array_merge($input, ['task_id' => $task->id]))->onQueue(QueueEnum::CHIP_PRODUCT_STOCK_IMPORT->value);

            // 异步处理这个 file
            return $this->response()->success('数据导入处理中，请在任务列表中查看')->redirect('task');
        } catch (\Exception $e) {
            return $this->response()->error($e->getMessage());
        }
    }

    /**
     * 导入表单.
     *
     * @author Leo Yi 2023/3/18
     */
    public function form()
    {
        $this->file('file', '上传数据（Excel）')->rules([
            'required',
            'max:20480',
            'bail',
            'mimes:xls,xlsx',
        ], ['required' => '文件不能为空'])->autoUpload()->uniqueName();
    }
}
