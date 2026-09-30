<?php

return [
    'labels' => [
        'ChipManufacturerRelated' => '推荐品牌管理',
        'chip-manufacturer-related' => '推荐品牌管理',
    ],
    'fields' => [
        'mnf_id' => '制造商id',
        'related_mnf_id' => '推荐品牌id',
        'sort' => '排序号',
        'manufacturer' => [
            'mnf_name' => '制造商名称',
        ],
        'relatedManufacturer' => [
            'mnf_name' => '推荐品牌名称',
            'mnf_img' => '推荐品牌logo',
        ],
    ],
    'options' => [
    ],
    'permissions' => [
        'resource' => ['title' => '推荐品牌管理', 'group' => '运营管理'],
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
