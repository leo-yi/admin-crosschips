<?php

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Actions\TriggerIsrRevalidation;
use App\Models\ChipBanner;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class IndexBannerController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new ChipBanner, function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->column('id')->sortable();
            $grid->column('img_url')->image(width: 200, height: 200);
            $grid->column('url');
            $grid->column('sorted')->sortable()->editable();
            $grid->column('created_at')->sortable();
            $grid->disableFilter();
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
        return Show::make($id, new ChipBanner, function (Show $show) {
            $show->field('id');
            $show->field('img_url');
            $show->field('url');
            $show->field('sorted');
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
        return Form::make(new ChipBanner, function (Form $form) {
            //            $form->display('id');
            $form->image('img_url')->uniqueName()->autoUpload()->accept('jpg,png,gif,jpeg')->chunkSize(1024)->maxSize(1024 * 20)->help('请上传 640 x 420 的图片');
            $form->text('url')->help('填写域名后的地址，如 '.rtrim((string) config('brand.site_url'), '/').'/abc, 只需要填 abc 即可');
            $form->text('sorted')->default(10);
            $form->display('created_at');
            $form->display('updated_at');

            // 保存后触发前端 ISR 重新验证，实现后台修改即时生效
            $form->saved(function (Form $form) {
                app(TriggerIsrRevalidation::class)->execute('/');
            });
        });
    }
}
