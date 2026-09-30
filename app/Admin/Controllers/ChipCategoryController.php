<?php

namespace App\Admin\Controllers;

use App\Actions\TriggerIsrRevalidation;
use App\Enum\EnumUtil\CategoryEnum;
use App\Models\ChipCategory;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class ChipCategoryController extends AdminController
{
    private array $hideColumns = [
        'created_at',
    ];

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(ChipCategory::with(['parent']), function (Grid $grid) {
            $grid->hideColumns($this->hideColumns);
            $grid->quickSearch(['id', 'category_name', 'alias_name']);
            $grid->model()->orderBy('sort', 'asc')->orderBy('id', 'asc');
            $grid->column('id')->sortable();
            $grid->column('sort')->sortable();
            $grid->column('category_name');
            $grid->column('alias_name');

            $grid->column('category_level')->using(CategoryEnum::level());
            $grid->column('parent.category_name');
            $grid->column('is_active')->switch();
            $grid->column('category_count');
            $grid->column('category_desc');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable()->hide();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('id', '一级分类')->select(CategoryEnum::first())->width(6);
                $filter->equal('id', '二级分类')->select(CategoryEnum::second())->width(6);

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
        return Show::make($id, new ChipCategory, function (Show $show) {
            $show->field('id');
            $show->field('category_name');
            $show->field('alias_name');
            $show->field('category_desc');
            $show->field('category_level');
            $show->field('parent_id');
            $show->field('sort');
            $show->field('is_active');
            $show->field('category_count');
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
        return Form::make(new ChipCategory, function (Form $form) {
            $form->display('id');
            $form->text('category_name');
            $form->text('alias_name')->help('首页展示别名，留空则显示 category_name');
            $form->text('category_desc');
            $form->text('category_level');
            $form->text('parent_id');
            $form->number('sort')->help('数值越小越靠前，控制首页品类展示顺序');
            $form->text('is_active');
            $form->text('category_count');

            $form->display('created_at');
            $form->display('updated_at');

            // 保存后触发前端 ISR 重新验证，实现后台修改排序/别名即时生效
            $form->saved(function (Form $form) {
                app(TriggerIsrRevalidation::class)->execute('/');
            });
        });
    }
}
