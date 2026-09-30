<?php

declare(strict_types=1);

namespace App\Admin\Actions\Grid\LatestStock;

use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\Tools\AbstractTool;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ChipProductStockTemplateAction extends AbstractTool
{
    /**
     * @return string
     */
    protected $title = '模板下载';

    /**
     * Handle the action request.
     */
    public function handle(Request $request): Response
    {
        return $this->response()->success('Processed successfully.');
    }

    /**
     * @param  Model|Authenticatable|HasPermissions|null  $user
     */
    protected function authorize($user): bool
    {
        return true;
    }

    /**
     * @author Leo Yi 2023/3/18
     */
    public function render(): string
    {
        return <<<'HTML'
<span class="grid-expand">
   <a href="/template/template-07163628.xlsx" target="_blank"><button class="btn btn-outline-info ">下载模板</button></a>
</span>
HTML;
    }
}
