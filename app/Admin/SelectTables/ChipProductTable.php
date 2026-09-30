<?php

declare(strict_types=1);

namespace App\Admin\SelectTables;

use App\Models\ChipProduct;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class ChipProductTable extends LazyRenderable
{
    public function grid(): Grid
    {
        return Grid::make(ChipProduct::with(['manufacturer']), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->column('mpn', 'MPN');
            $grid->column('manufacturer.mnf_name', '制造商名称');
            $grid->column('product_package', '产品封装');
            $grid->column('created_at', '创建时间');
            $grid->quickSearch(['id', 'mpn']);
            $grid->filter(function (Grid\Filter $filter) {
                $filter->startWith('mpn')->width(4);
                $filter->equal('id', '产品ID')->width(4);
            });
            $grid->simplePaginate();
        });
    }
}
