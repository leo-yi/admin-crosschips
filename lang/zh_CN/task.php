<?php

return [
    'labels' => [
        'Task' => '异步任务列表',
        'task' => '异步任务列表',
    ],
    'fields' => [
        'task_type' => '任务的类型',
        'payload' => '消费的数据',
        'request' => '请求的参数信息',
        'task_status' => '执行状态',
        'start_time' => '开始时间',
        'end_time' => '结束时间',
        'created_name' => '任务的添加人',
        'response' => '结果',
        'message' => '信息',
        'adminUser' => [
            'id' => '创建人',
            'username' => '创建人',
        ],
    ],
    'options' => [
    ],
    'permissions' => [
        'resource' => ['title' => '异步任务列表', 'group' => '运营管理'],
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
