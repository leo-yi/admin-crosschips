<?php

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Admin\Repositories\ChipManufacturer;
use App\Models\ChipCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * api 控制器，用于组件的异步查询
 */
class ApiController
{
    public function mnf(Request $request)
    {
        $q = $request->get('q');

        return ChipManufacturer::where('mnf_name', 'like', "%$q%")->paginate(null, ['id', 'mnf_name as text']);
    }

    public function category(Request $request)
    {
        $q = $request->get('q');

        return ChipCategory::where('parent_id', $q)->get(['id', DB::raw('category_name as text')]);
    }
}
