<?php

return [
    'labels' => [
        'ChipCategory' => '分类',
        'chip-category' => '分类',
    ],
    'fields' => [
        'category_name' => '分类名称',
        'category_desc' => '分类描述',
        'category_level' => '分类级别',
        'parent_id' => '父级分类ID',
        'sort' => '排序',
        'is_active' => '是否显示',
        'category_count' => '分类产品数量',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
        'parent' => [
            'category_name' => '父级分类名称',
        ],
    ],
    'options' => [
    ],
    'permissions' => [
        'resource' => ['title' => '分类', 'group' => '基础数据'],
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
