<?php

declare(strict_types=1);

namespace App\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Art implements Renderable
{
    public function render()
    {
        return view('admin.widgets.art');
    }
}
