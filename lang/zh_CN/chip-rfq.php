<?php

return [
    'labels' => [
        'ChipRfq' => 'RFQ',
        'chip-rfq' => 'RFQ',
    ],
    'fields' => [
        'email' => '邮件地址',
        'phone' => '联系电话',
        'company' => '公司名称',
        'contact_name' => '联系人',
        'message' => '留言',
        'source' => '投放站点来源',
        'upload_file_id' => '上传文件ID',
        'ip_address' => 'ip 地址',
        'user_agent' => '访问标识',
        'accept_language' => '访问语言',
        'detail' => '详情',
        'view_rfq' => '查看RFQ',
    ],
    'options' => [
    ],
    'permissions' => [
        'resource' => ['title' => 'RFQ', 'group' => '运营管理'],
        'description' => '',
        'actions' => [
            'index' => '列表',
            'show' => '查看',
            'create' => '新建',
            'store' => '保存',
            'edit' => '编辑',
            'update' => '更新',
            'destroy' => '删除',
            'import' => '导入',
            'export' => '导出',
        ],
        'routes' => [
        ],
    ],
];
