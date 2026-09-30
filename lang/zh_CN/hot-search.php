<?php

return [
    'labels' => [
        'HotSearch' => '热门搜索',
        'hot-search' => '热门搜索',
    ],
    'fields' => [
        'product_id' => '产品',
        'id' => '记录ID',
        'sorted' => '排序(数字越大越前)',
        'is_active' => '是否启用',
        'is_delete' => '软删',
        'last_update_time' => '更新时间',
        'created_at' => '创建时间',
        'product' => [
            'id' => '产品ID',
            'product_name' => '型号',
            'product_link' => '产品链接',
            'product_img_link' => '产品图片链接',
            'category_id' => '分类',
            'category' => [
                'second_category_name' => '产品名称',
                'id' => '产品名称',
                'first_category_name' => '品类',
                'first_category_id' => '品类',
            ],
            'manufacture_id' => 'MFR',
            'mnf' => [
                'manufacture_name' => 'MFR',
            ],
            'manufacturer_no' => '型号',
            'published' => '年份',
            'package' => '封装',
            'datasheet_link' => 'pdf link',
            'description' => '描述',
            'in_stock' => 'Fake Stock',
            'factory_lead_time' => '发货时间',
            'is_last_stock' => '是否首页last stock 展示',
            'stock' => [
                'stock' => 'Stock Available',
            ],
        ],
    ],
    'options' => [
    ],
];
