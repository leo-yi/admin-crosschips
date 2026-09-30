<?php

return [
    'labels' => [
        'HotSale' => 'Latest Stock',
        'product-stock' => 'Latest Stock',
    ],
    'fields' => [
        'id' => '库存ID',
        // form 翻译
        'product_id' => '产品名称',
        // select Table 翻译
        'manufacturer_no' => '型号',
        'published' => '发布的年份',
        'package' => '封装',
        // 字段翻译
        'stock_type' => '库存类型',
        'stock' => '库存数量',
        // 关联表翻译
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
            'in_stock' => 'Stock Available',
            'factory_lead_time' => '发货时间',
            'is_last_stock' => '是否首页last stock 展示',
        ],
    ],
    'options' => [
    ],
];
