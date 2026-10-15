<?php

declare(strict_types=1);

namespace App\Admin\Actions\Grid\HotKeyword;

use App\Admin\Actions\Form\ChipSearchHotKeywordImportForm;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Tools\AbstractTool;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ChipSearchHotKeywordImportAction extends AbstractTool
{
    protected $title = '热门关键词导入';

    public function handle(Request $request): Response
    {
        return $this->response()
            ->success('Processed successfully.')
            ->redirect('/');
    }

    /**
     * @param  Model|Authenticatable|HasPermissions|null  $user
     */
    protected function authorize($user): bool
    {
        return true;
    }

    /**
     * 自定义按钮渲染（模态窗方式与库存导入一致）.
     */
    public function render(): string
    {
        $id = "hot-keyword-import-{$this->getKey()}";

        $this->modal($id);

        return <<<HTML
<span class="grid-expand" data-toggle="modal" data-target="#{$id}">
   <a href="javascript:void(0)"><button class="btn btn-outline-info ">上传Excel并导入数据</button></a>
</span>
HTML;
    }

    protected function modal($id)
    {
        $form = new ChipSearchHotKeywordImportForm;

        Admin::script('Dcat.onPjaxComplete(function () {
            $(".modal-backdrop").remove();
            $("body").removeClass("modal-open");
        }, true)');

        Admin::html(
            <<<HTML
<div class="modal fade" id="{$id}">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">导入热门关键词</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        {$form->render()}
      </div>
    </div>
  </div>
</div>
HTML
        );
    }
}
