<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\ChipManufacturer;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class ChipManufacturerController extends AdminController
{
    private $hideColumns = ['mnf_desc', 'created_at'];

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new ChipManufacturer, function (Grid $grid) {
            $grid->hideColumns($this->hideColumns);
            $grid->quickSearch(['id', 'mnf_name']);
            $grid->model()->orderBy('id', 'desc');
            $grid->column('id')->sortable();
            $grid->column('mnf_img')->image();
            $grid->column('mnf_name');
            $grid->column('alias_name');
            $grid->column('mnf_desc');
            $grid->column('mnf_count')->sortable();
            $grid->column('sort')->sortable();
            $grid->column('is_active')->switch();
            $grid->column('created_at');
            $grid->column('updated_at')->sortable()->hide();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('id')->width(3);
                $filter->startWith('mnf_name')->width(3);
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
        return Show::make($id, new ChipManufacturer, function (Show $show) {
            $show->field('id');
            $show->field('mnf_img');
            $show->field('mnf_name');
            $show->field('alias_name');
            $show->field('mnf_desc');
            $show->field('is_active');
            $show->field('mnf_count');
            $show->field('sort');
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
        return Form::make(new ChipManufacturer, function (Form $form) {
            $form->display('id');
            $form->image('mnf_img')->autoUpload()->uniqueName();
            $form->text('mnf_name')->required()->rules('required|string|min:1|max:50');
            $form->text('alias_name')->help('导航下拉展示别名，留空则显示 mnf_name');
            $form->text('mnf_desc');
            $form->number('mnf_count')->default(0);
            $form->number('sort')->default(0)->help('导航排序号，值越小越靠前，0 表示不进导航下拉');
            $form->switch('is_active');
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
