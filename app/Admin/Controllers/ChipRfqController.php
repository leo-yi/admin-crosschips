<?php

namespace App\Admin\Controllers;

use App\Models\ChipRfq;
use App\Models\ChipRfqProduct;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Table;

class ChipRfqController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(ChipRfq::with(['products']), function (Grid $grid) {
            $grid->hideColumns(['upload_file_id', 'ip', 'user_agent', 'accept_language']);
            $grid->model()->orderBy('id', 'desc');
            $grid->column('id')->sortable();
            $grid->column('email');
            $grid->column('phone');
            $grid->column('company');
            $grid->column('contact_name');
            $grid->column('message');
            $grid->column('detail')->display(trans('chip-rfq.fields.view_rfq'))->expand(function () {
                $header = ['MPN', 'Manufacturer', 'Quantity', 'Package', 'Target Price'];
                if (! $this->products) {
                    return new Table($header, []);
                }
                $orderGoods = collect($this->products)->map(function (ChipRfqProduct $detail) {
                    return [
                        'mpn' => $detail->mpn,
                        'manufacturer' => $detail->manufacturer,
                        'quantity' => $detail->quantity,
                        'product_package' => $detail->product_package,
                        'target_price' => $detail->target_price,
                    ];
                });

                $card = new Card(null, new Table($header, $orderGoods->toArray()));

                return "<div style='padding:10px 10px 0'>$card</div>";
            });
            $grid->column('source')->hide();
            $grid->column('upload_file_id');
            $grid->column('ip_address')->hide();
            $grid->column('user_agent');
            $grid->column('accept_language');
            $grid->column('created_at');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

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
        return Show::make($id, new ChipRfq, function (Show $show) {
            $show->field('id');
            $show->field('email');
            $show->field('phone');
            $show->field('company');
            $show->field('contact_name');
            $show->field('message');
            $show->field('source');
            $show->field('upload_file_id');
            $show->field('ip_address');
            $show->field('user_agent');
            $show->field('accept_language');
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
        return Form::make(new ChipRfq, function (Form $form) {
            $form->display('id');
            $form->text('email');
            $form->text('phone');
            $form->text('company');
            $form->text('contact_name');
            $form->text('message');
            $form->text('source');
            $form->text('upload_file_id');
            $form->text('ip_address');
            $form->text('user_agent');
            $form->text('accept_language');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
