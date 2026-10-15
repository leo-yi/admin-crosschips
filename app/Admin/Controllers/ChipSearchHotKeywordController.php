<?php

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\HotKeyword\ChipSearchHotKeywordImportAction;
use App\Admin\Actions\Grid\HotKeyword\ChipSearchHotKeywordTemplateAction;
use App\Models\ChipSearchHotKeyword;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

/**
 * 热门搜索关键词管理（PC 端 header 搜索弹窗「Hot Searches」数据源）。
 *
 * 缓存契约：任何变更由模型 saved/deleted 事件主动删除
 * Redis key web:search:hot_keywords（见 ChipSearchHotKeyword::booted），
 * Java 侧读缓存 miss 时回源本表并回填，无需 admin 调 Java API。
 */
class ChipSearchHotKeywordController extends AdminController
{
    protected function grid()
    {
        return Grid::make(new ChipSearchHotKeyword, function (Grid $grid) {
            $grid->model()->orderBy('sorted')->orderBy('id', 'desc');
            $grid->column('id')->sortable();
            $grid->column('keyword')->copyable();
            $grid->column('sorted')->sortable()->editable();
            $grid->column('status')->switch(ChipSearchHotKeyword::STATUS_MAP);
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->hide();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like('keyword')->width(3);
                $filter->equal('status')->select(ChipSearchHotKeyword::STATUS_MAP)->width(3);
            });

            $grid->tools(function (Grid\Tools $tools) {
                // Excel 导入（Task + 队列异步，与库存导入同构）
                $tools->append(new ChipSearchHotKeywordImportAction);
                $tools->append(new ChipSearchHotKeywordTemplateAction);
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new ChipSearchHotKeyword, function (Show $show) {
            $show->field('id');
            $show->field('keyword');
            $show->field('sorted');
            $show->field('status')->using(ChipSearchHotKeyword::STATUS_MAP);
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new ChipSearchHotKeyword, function (Form $form) {
            $form->text('keyword')
                ->required()
                ->max(100)
                ->help('热搜型号，如 XC7Z020-2CLG400I；保存时自动去空格，同一名称全站唯一');
            $form->number('sorted')->default(10)->help('越小越靠前');
            $form->switch('status')->customFormat(1, 0)->default(1);

            $form->display('created_at');
            $form->display('updated_at');

            // 保存前归一化：去首尾空格
            $form->saving(function (Form $form) {
                $form->keyword = trim((string) $form->keyword);
            });
        });
    }
}
