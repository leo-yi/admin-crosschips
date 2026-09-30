<?php

return [
    'labels' => [
        'IndexBanner' => '首页轮播',
        'index-banner' => '首页轮播',
    ],
    'fields' => [
        'img_url' => '图片地址',
        'url' => '跳转地址',
        'sorted' => '排序(数字大排前面)',
    ],
    'options' => [
    ],
    'permissions' => [
        'resource' => ['title' => '首页轮播', 'group' => '运营管理'],
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
