<?php

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Enum\EnumUtil\AdminUsersEnum;
use App\Models\Task;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;
use Illuminate\Support\Facades\Storage;

class TaskController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(Task::with('adminUser'), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->column('id')->sortable();
            $grid->column('task_type');
            $grid->column('payload')->display('查看消息') // 设置按钮名称
                ->modal(function ($modal) {
                    // 设置弹窗标题
                    $modal->title('消费体');
                    // 自定义图标
                    $modal->icon('feather icon-mail');
                    if (! isset($this->payload)) {
                        $card = new Card(null, '无消息');

                        return "<div style='padding:10px 10px 0'>$card</div>";
                    }
                    $info = json_decode($this->payload);
                    $fileInfo = pathinfo($info->file);
                    $downloadUrl = Storage::drive('admin')->temporaryUrl($info->file, now()->addMinutes(5));
                    $cardContent = "点击下载上传的原始文件：<a href='".$downloadUrl."' target='_blank'>".$fileInfo['basename'].'</a>';
                    $card = new Card(null, $cardContent);

                    return "<div style='padding:10px 10px 0'>$card</div>";
                });
            $grid->column('task_status')->using(Task::STATUS_MAP)->label([
                'default' => 'primary',
                1 => 'default',
                2 => 'primary',
                4 => 'danger',
                3 => 'success',
            ]);
            $grid->column('start_time');
            $grid->column('end_time');
            $grid->column('adminUser.username');
            $grid->column('response');
            $grid->column('message');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable()->hide();
            if (Admin::user()->id != 1) {
                $grid->disableActions();
            }
            $grid->disableCreateButton();
            $grid->disableBatchActions();
            $grid->disableColumnSelector();
            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('id')->width(3);
                $filter->equal('adminUser.id')->select(AdminUsersEnum::list())->width(3);
                $filter->between('created_at')->datetime()->width(3);
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param  mixed  $id
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Task, function (Show $show) {
            $show->field('id');
            $show->field('task_type');
            $show->field('payload');
            $show->field('request');
            $show->field('task_status');
            $show->field('start_time');
            $show->field('end_time');
            $show->field('created_name');
            $show->field('response');
            $show->field('message');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Task, function (Form $form) {
            $form->display('id');
            $form->text('task_type');
            $form->text('payload');
            $form->text('request');
            $form->text('task_status');
            $form->text('start_time');
            $form->text('end_time');
            $form->text('created_name');
            $form->text('response');
            $form->text('message');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
