<?php

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Models\ChipStation;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Str;

class ChipStationController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new ChipStation, function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name')->badge();
            $grid->column('param')->copyable();
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->equal('name');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new ChipStation, function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->hidden('param')->value(Str::random(6));
        });
    }
}
