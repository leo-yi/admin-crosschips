<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\LatestStock\ChipProductStockImportAction;
use App\Admin\Actions\Grid\LatestStock\ChipProductStockTemplateAction;
use App\Admin\SelectTables\ChipProductTable;
use App\Enum\CurrencyEnum;
use App\Models\ChipProduct;
use App\Models\ChipProductStock;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class ChipProductStockController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(ChipProductStock::with(['product', 'manufacturer']), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->column('id')->sortable();
            $grid->column('product.mpn')->copyable();
            $grid->column('manufacturer.mnf_name')->copyable();
            $grid->column('stock_type')->using(ChipProductStock::STOCK_TYPE_MAP);
            $grid->column('stock')->editable();
            $grid->column('price')->display(function () {
                /** @var ChipProductStock $this */
                return $this->price.' '.$this->currency_code;
            });
            $grid->column('created_at');
            $grid->column('updated_at')->sortable()->hide();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('id')->width(3);
                $filter->equal('stock_type')->select(ChipProductStock::STOCK_TYPE_MAP)->width(3);
            });

            $grid->tools(function (Grid\Tools $tools) {
                // Excel导入
                $tools->append(new ChipProductStockImportAction);
                $tools->append(new ChipProductStockTemplateAction);
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
        return Show::make($id, new ChipProductStock, function (Show $show) {
            $show->field('id');
            $show->field('product_id');
            $show->field('stock_type');
            $show->field('stock');
            $show->field('price');
            $show->field('currency_code');
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
        return Form::make(new ChipProductStock, function (Form $form) {
            $form->display('id');
            $form->selectTable('product_id')
                ->title('产品列表')
                ->from(ChipProductTable::make(['id' => $form->getKey()]))
                ->model(ChipProduct::class, 'id', 'mpn');
            $form->select('stock_type')->options(ChipProductStock::STOCK_TYPE_MAP);
            $form->number('stock')->default(0);
            $form->text('price');
            $form->select('currency_code')->options(CurrencyEnum::array());

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
