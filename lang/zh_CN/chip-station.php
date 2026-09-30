<?php

return [
    'labels' => [
        'ChipStation' => '投放站点',
        'chip-station' => '投放站点',
    ],
    'fields' => [
        'name' => '站点名称',
        'param' => '参数',
    ],
    'options' => [
    ],
    'permissions' => [
        'resource' => ['title' => '投放站点', 'group' => '运营管理'],
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
