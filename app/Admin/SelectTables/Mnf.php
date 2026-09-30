<?php

declare(strict_types=1);

namespace App\Admin\SelectTables;

use App\Models\ChipManufacturer;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class Mnf extends LazyRenderable
{
    public function grid(): Grid
    {
        $id = $this->id;

        return Grid::make(new ChipManufacturer, function (Grid $grid) {
            $grid->column('mnf_name', '制造商');
            $grid->column('created_at', '创建时间');
            $grid->quickSearch(['id', 'mnf_name']);
            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('mnf_name')->width(4);
                $filter->equal('id')->width(4);
            });
        });
    }
}
