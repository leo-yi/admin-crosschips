<?php

namespace App\Admin\Controllers;

use App\Admin\SelectTables\Mnf;
use App\Models\ChipManufacturer;
use App\Models\ChipManufacturerRelated;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

/**
 * 厂商详情页推荐品牌（You May Also Be Interested In）配置。
 * 每条记录：厂商 mnf_id 的详情页底部推荐展示厂商 related_mnf_id。
 */
class ChipManufacturerRelatedController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(ChipManufacturerRelated::with(['manufacturer', 'relatedManufacturer']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('manufacturer.mnf_name');
            $grid->column('relatedManufacturer.mnf_name');
            $grid->column('relatedManufacturer.mnf_img')->image();
            $grid->column('sort')->sortable();
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->quickSearch(['id']);

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('mnf_id', '制造商')
                    ->selectTable(Mnf::make())
                    ->title('制造商')
                    ->model(ChipManufacturer::class, 'id', 'mnf_name')
                    ->width(3);
                $filter->equal('related_mnf_id', '推荐品牌')
                    ->selectTable(Mnf::make())
                    ->title('推荐品牌')
                    ->model(ChipManufacturer::class, 'id', 'mnf_name')
                    ->width(3);
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
        return Show::make($id, ChipManufacturerRelated::with(['manufacturer', 'relatedManufacturer']), function (Show $show) {
            $show->field('id');
            $show->field('manufacturer.mnf_name');
            $show->field('relatedManufacturer.mnf_name');
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
        return Form::make(new ChipManufacturerRelated, function (Form $form) {
            $form->display('id');
            $form->selectTable('mnf_id')
                ->title('制造商')
                ->from(Mnf::make(['id' => $form->getKey()]))
                ->model(ChipManufacturer::class, 'id', 'mnf_name')
                ->width(3)->required()->placeholder('点击右边箭头选择');
            $form->selectTable('related_mnf_id')
                ->title('推荐品牌')
                ->from(Mnf::make(['id' => $form->getKey()]))
                ->model(ChipManufacturer::class, 'id', 'mnf_name')
                ->width(3)->required()->placeholder('点击右边箭头选择')
                ->rules('different:mnf_id', [
                    'different' => '推荐品牌不能与制造商相同',
                ]);
            $form->number('sort')->default(0)->min(0)->max(999999)
                ->help('排序号，值越小越靠前');

            // 同一厂商下推荐品牌不可重复（编辑时排除自身）
            $form->saving(function (Form $form) {
                $exists = ChipManufacturerRelated::where('mnf_id', $form->mnf_id)
                    ->where('related_mnf_id', $form->related_mnf_id)
                    ->when($form->getKey(), fn ($query) => $query->where('id', '!=', $form->getKey()))
                    ->exists();
                if ($exists) {
                    return $form->response()->error('该制造商下已存在此推荐品牌');
                }
            });

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
