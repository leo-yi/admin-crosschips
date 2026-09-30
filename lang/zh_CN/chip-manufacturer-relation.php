<?php

return [
    'labels' => [
        'ChipManufacturerRelation' => '制造商关联管理',
        'chip-manufacturer-relation' => '制造商关联管理',
    ],
    'fields' => [
        'manufacturer_id' => '制造商id',
        'relation_type' => '关联类型',
        'manufacturer' => [
            'mnf_name' => '制造商名称',
        ],
    ],
    'options' => [
    ],
    'permissions' => [
        'resource' => ['title' => '制造商关联管理', 'group' => '运营管理'],
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
