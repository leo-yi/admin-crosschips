<?php

namespace App\Admin\Controllers;

use App\Admin\SelectTables\Mnf;
use App\Enum\EnumUtil\CategoryEnum;
use App\Models\ChipManufacturer;
use App\Models\ChipProduct;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class ChipProductController extends AdminController
{
    private $hideColumns = [
        'img',
        'sku',
        'price_break_1',
        'price_1',
        'price_1_currency',
        'price_break_2',
        'price_2',
        'price_2_currency',
        'price_break_3',
        'price_3',
        'price_3_currency',
        'price_break_4',
        'price_4',
        'price_4_currency',
        'price_break_5',
        'price_5',
        'price_5_currency',
        'price_break_6',
        'price_6',
        'price_6_currency',
        'price_unit',
        'standard_packing_quantity',
        'quantity',
        'weight',
        'is_battery',
        'product_desc',
        'created_at',
    ];

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(ChipProduct::with(['categoryOne', 'categoryTwo', 'manufacturer']), function (Grid $grid) {
            $grid->hideColumns($this->hideColumns);
            $grid->column('id')->sortable();
            $grid->column('sku');
            $grid->column('img')->image();
            $grid->column('mpn')->copyable();
            $grid->column('manufacturer.mnf_name')->copyable();
            $grid->column('categoryOne.category_name')->copyable();
            $grid->column('categoryTwo.category_name')->copyable();
            $grid->column('product_package');
            $grid->column('price_unit');
            $grid->column('standard_packing_quantity');
            $grid->column('price_break_1');
            $grid->column('price_1');
            $grid->column('price_1_currency');
            $grid->column('price_break_2');
            $grid->column('price_2');
            $grid->column('price_2_currency');
            $grid->column('price_break_3');
            $grid->column('price_3');
            $grid->column('price_3_currency');
            $grid->column('price_break_4');
            $grid->column('price_4');
            $grid->column('price_4_currency');
            $grid->column('price_break_5');
            $grid->column('price_5');
            $grid->column('price_5_currency');
            $grid->column('price_break_6');
            $grid->column('price_6');
            $grid->column('price_6_currency');
            $grid->column('is_rohs')->switch();
            //            $grid->column('data_sheet_url');
            //            $grid->column('specifications');
            $grid->column('quantity');
            $grid->column('weight');
            $grid->column('is_battery');
            $grid->column('in_stock')->editable();
            $grid->column('product_desc')->editable();
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->startWith('mpn')->width(3);
                $filter->equal('manufacturer_id', '制造商')
                    ->selectTable(Mnf::make())
                    ->title('制造商')
                    ->model(ChipManufacturer::class, 'id', 'mnf_name')
                    ->width(3);
                $filter->equal('category_one_id')
                    ->select(CategoryEnum::first())
                    ->load('category_two_id', '/api/category')
                    ->width(3);
                $filter->equal('category_two_id')
                    ->select()
                    ->width(3);
            });

            $grid->simplePaginate();
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
        return Show::make($id, new ChipProduct, function (Show $show) {
            $show->field('img');
            $show->field('id');
            $show->field('mpn');
            $show->field('sku');
            $show->field('product_package');
            $show->field('product_desc');
            $show->field('price_unit');
            $show->field('standard_packing_quantity');
            $show->field('price_break_1');
            $show->field('price_1');
            $show->field('price_1_currency');
            $show->field('price_break_2');
            $show->field('price_2');
            $show->field('price_2_currency');
            $show->field('price_break_3');
            $show->field('price_3');
            $show->field('price_3_currency');
            $show->field('price_break_4');
            $show->field('price_4');
            $show->field('price_4_currency');
            $show->field('price_break_5');
            $show->field('price_5');
            $show->field('price_5_currency');
            $show->field('price_break_6');
            $show->field('price_6');
            $show->field('price_6_currency');
            $show->field('category_one_id');
            $show->field('category_two_id');
            $show->field('manufacturer_id');
            $show->field('is_rohs');
            $show->field('data_sheet_url');
            $show->field('specifications');
            $show->field('quantity');
            $show->field('weight');
            $show->field('is_battery');
            $show->field('in_stock');
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
        return Form::make(new ChipProduct, function (Form $form) {
            $form->display('id');
            $form->text('mpn');
            $form->text('sku');
            $form->text('img');
            $form->text('product_package');
            $form->text('product_desc');
            $form->text('price_unit');
            $form->text('standard_packing_quantity');
            $form->text('price_break_1');
            $form->text('price_1');
            $form->text('price_1_currency');
            $form->text('price_break_2');
            $form->text('price_2');
            $form->text('price_2_currency');
            $form->text('price_break_3');
            $form->text('price_3');
            $form->text('price_3_currency');
            $form->text('price_break_4');
            $form->text('price_4');
            $form->text('price_4_currency');
            $form->text('price_break_5');
            $form->text('price_5');
            $form->text('price_5_currency');
            $form->text('price_break_6');
            $form->text('price_6');
            $form->text('price_6_currency');
            $form->text('category_one_id');
            $form->text('category_two_id');
            $form->text('manufacturer_id');
            $form->text('is_rohs');
            $form->text('data_sheet_url');
            $form->text('specifications');
            $form->text('quantity');
            $form->text('weight');
            $form->text('is_battery');
            $form->text('in_stock');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
