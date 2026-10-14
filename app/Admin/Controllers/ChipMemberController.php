<?php

namespace App\Admin\Controllers;

use App\Models\ChipMember;
use App\Models\ChipMemberLoginLog;
use App\Models\ChipMemberOauth;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Table;

/**
 * 前台会员管理（数据源由 Java 后端写入，admin 只读 + 禁用/启用）。
 *
 * 约定：
 * - 不提供新建/删除/资料编辑，password_hash 任何场景不出现在页面；
 * - 禁用/启用通过 status 开关完成，Java 端 MemberStatusFilter 每请求校验
 *   （Redis 缓存 60s），禁用最迟 60 秒内生效并踢掉会话。
 */
class ChipMemberController extends AdminController
{
    protected function grid()
    {
        $query = ChipMember::with([
            'oauthAccounts',
            'loginLogs' => function ($q) {
                $q->orderBy('id', 'desc')->limit(10);
            },
        ]);

        return Grid::make($query, function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('email');
            $grid->column('nickname');
            $grid->column('realname');
            $grid->column('company_name');
            $grid->column('phone')->hide();
            $grid->column('status')->switch();
            $grid->column('email_verified_at')->hide();
            $grid->column('last_login_at');
            $grid->column('last_login_ip')->hide();
            $grid->column('register_source')->hide();
            $grid->column('created_at')->sortable();

            $grid->column('detail')->display(trans('chip-member.fields.view_detail'))->expand(function () {
                $oauthRows = collect($this->oauthAccounts)->map(function (ChipMemberOauth $oauth) {
                    return [
                        'provider' => $oauth->provider,
                        'open_id' => $oauth->open_id,
                        'email' => $oauth->email,
                        'nickname' => $oauth->nickname,
                        'created_at' => $oauth->created_at,
                    ];
                });
                $oauthCard = new Card(
                    trans('chip-member.fields.oauth_accounts'),
                    new Table(['Provider', 'Open ID', 'Email', 'Nickname', 'Created At'], $oauthRows->toArray())
                );

                $logRows = collect($this->loginLogs)->map(function (ChipMemberLoginLog $log) {
                    return [
                        'created_at' => $log->created_at,
                        'ip' => $log->ip,
                        'status' => $log->status === 1 ? trans('chip-member.options.success') : trans('chip-member.options.failed'),
                        'fail_reason' => $log->fail_reason,
                        'user_agent' => $log->user_agent,
                    ];
                });
                $logCard = new Card(
                    trans('chip-member.fields.recent_login_logs'),
                    new Table(['Time', 'IP', 'Status', 'Fail Reason', 'User Agent'], $logRows->toArray())
                );

                return "<div style='padding:10px 10px 0'>$oauthCard $logCard</div>";
            });

            // 数据归 Java 后端管：admin 不新建、不删除、不批量删
            $grid->disableCreateButton();
            $grid->disableDeleteButton();
            $grid->disableBatchDelete();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('email');
                $filter->like('company_name');
                $filter->equal('status')->select([
                    1 => trans('chip-member.options.normal'),
                    0 => trans('chip-member.options.disabled'),
                ]);
                $filter->between('created_at')->datetime();
            });

            $grid->export([
                'id' => 'ID',
                'email' => trans('chip-member.fields.email'),
                'nickname' => trans('chip-member.fields.nickname'),
                'realname' => trans('chip-member.fields.realname'),
                'company_name' => trans('chip-member.fields.company_name'),
                'phone' => trans('chip-member.fields.phone'),
                'status' => trans('chip-member.fields.status'),
                'last_login_at' => trans('chip-member.fields.last_login_at'),
                'created_at' => trans('chip-member.fields.created_at'),
            ])->filename('chip-member');
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new ChipMember, function (Show $show) {
            $show->field('id');
            $show->field('email');
            $show->field('nickname');
            $show->field('realname');
            $show->field('company_name');
            $show->field('phone');
            $show->field('status')->using([
                1 => trans('chip-member.options.normal'),
                0 => trans('chip-member.options.disabled'),
            ]);
            $show->field('email_verified_at');
            $show->field('last_login_at');
            $show->field('last_login_ip');
            $show->field('register_ip');
            $show->field('register_source');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new ChipMember, function (Form $form) {
            $form->display('id');
            $form->display('email');
            $form->radio('status')
                ->options([
                    1 => trans('chip-member.options.normal'),
                    0 => trans('chip-member.options.disabled'),
                ])
                ->required();

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
