<?php

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Models\ChipMemberSearchHistory;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

/**
 * 会员搜索历史（只读聚合分析页）。
 *
 * 数据由 Java 后端写入（会员在 PC 端搜索弹窗的搜索记录），
 * admin 只做按关键词聚合的热搜分析，辅助运营维护 chip_search_hot_keyword，
 * 不提供新建/编辑/删除。
 */
class ChipMemberSearchHistoryController extends AdminController
{
    protected function grid()
    {
        // 按关键词聚合：搜索次数 / 搜索人数 / 最近搜索时间
        $query = ChipMemberSearchHistory::query()
            ->selectRaw('keyword, COUNT(*) as search_count, COUNT(DISTINCT member_id) as member_count, MAX(updated_at) as last_searched_at')
            ->groupBy('keyword');

        return Grid::make($query, function (Grid $grid) {
            $grid->model()->orderBy('search_count', 'desc');

            $grid->column('keyword');
            $grid->column('search_count', '搜索次数')->sortable();
            $grid->column('member_count', '搜索人数')->sortable();
            $grid->column('last_searched_at', '最近搜索时间')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like('keyword')->width(4);
            });

            // 只读分析页：禁用全部写操作入口
            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableBatchDelete();

            $grid->export([
                'keyword' => 'Keyword',
                'search_count' => 'Search Count',
                'member_count' => 'Member Count',
                'last_searched_at' => 'Last Searched At',
            ])->filename('member-search-history');
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new ChipMemberSearchHistory, function (Show $show) {
            $show->field('id');
            $show->field('member_id');
            $show->field('keyword');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }
}
