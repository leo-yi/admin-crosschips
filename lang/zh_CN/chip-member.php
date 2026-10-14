<?php

return [
    'labels' => [
        'ChipMember' => '会员管理',
        'chip-member' => '会员管理',
    ],
    'fields' => [
        'email' => '邮箱',
        'nickname' => '昵称',
        'realname' => '真实姓名',
        'company_name' => '公司名称',
        'phone' => '手机号',
        'avatar' => '头像',
        'status' => '状态',
        'email_verified_at' => '邮箱验证时间',
        'last_login_at' => '最近登录时间',
        'last_login_ip' => '最近登录 IP',
        'register_ip' => '注册 IP',
        'register_source' => '注册来源',
        'created_at' => '注册时间',
        'updated_at' => '更新时间',
        'detail' => '详情',
        'view_detail' => '查看详情',
        'oauth_accounts' => '第三方登录绑定',
        'recent_login_logs' => '最近 10 条登录日志',
    ],
    'options' => [
        'normal' => '正常',
        'disabled' => '禁用',
        'success' => '成功',
        'failed' => '失败',
    ],
    'permissions' => [
        'resource' => ['title' => '会员管理', 'group' => '会员管理'],
        'description' => '前台会员查询与禁用/启用',
        'actions' => [
            'index' => '列表',
            'show' => '查看',
            'edit' => '禁用/启用',
            'update' => '保存状态',
        ],
        'routes' => [
        ],
    ],
];
