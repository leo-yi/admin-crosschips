<?php

return [
    'labels' => [
        'ChipManufacturer' => '品牌厂商',
        'chip-manufacturer' => '品牌厂商',
    ],
    'fields' => [
        'mnf_img' => 'LOGO图片',
        'mnf_name' => '制造商名称',
        'mnf_desc' => '制造商描述',
        'mnf_count' => '此制造商下的产品数量',
        'is_active' => '是否启用',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],
    'options' => [
    ],
    'permissions' => [
        'resource' => ['title' => '品牌厂商', 'group' => '基础数据'],
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
