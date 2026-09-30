<?php

namespace App\Admin\Controllers;

use App\Admin\Constants\MappingConst;
use App\Admin\SelectTables\Mnf;
use App\Enum\ManufacturerRelationEnum;
use App\Models\ChipManufacturer;
use App\Models\ChipManufacturerRelation;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class ChipManufacturerRelationController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(ChipManufacturerRelation::with(['manufacturer']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('manufacturer.mnf_name');
            $grid->column('manufacturer.mnf_img')->image();
            $grid->column('relation_type')->using(MappingConst::MANUFACTURER_RELATION_TYPE);
            $grid->column('created_at');
            $grid->column('updated_at')->sortable()->hide();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('relation_type')->select(MappingConst::MANUFACTURER_RELATION_TYPE)->width(3);
                $filter->equal('manufacturer_id', '制造商')
                    ->selectTable(Mnf::make())
                    ->title('制造商')
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
        return Show::make($id, new ChipManufacturerRelation, function (Show $show) {
            $show->field('id');
            $show->field('manufacturer_id');
            $show->field('relation_type');
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
        return Form::make(new ChipManufacturerRelation, function (Form $form) {
            $form->display('id');
            $form->selectTable('manufacturer_id')
                ->title('制造商')
                ->from(Mnf::make(['id' => $form->getKey()]))
                ->model(ChipManufacturer::class, 'id', 'mnf_name')
                ->width(3)->required()->placeholder('点击右边箭头选择');
            $form->select('relation_type')->options(MappingConst::MANUFACTURER_RELATION_TYPE)
                ->required()->default(ManufacturerRelationEnum::INDEX_SHOW->value);
            $form->creating(function (Form $form) {
                return $form->response()->error('已存在该关系');
            });
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
