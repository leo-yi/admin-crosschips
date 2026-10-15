<?php

declare(strict_types=1);

namespace App\Enum;

enum RedisEnum: string
{
    case REPEAT_LIMITER = 'repeat_limiter';
    case RFQ_SUBMIT_LIMITER = 'rfq_submit_limit';
    case SUBSCRIPTION_LIMITER = 'subscription_limiter';
    case FIRST_CATEGORY = 'model:first_category';
    case CATEGORY = 'model:category';
    case CATEGORY_WEB_MENU = 'web:index:category';
    case APPLICATION_MENU = 'web:index:application_menu';
    case RFQ_RATE_LIMITER = 'web:rfq_submit_limit';

    case MMF_NAME_CACHE = 'mnf:name_cache';

    // 热门搜索关键词缓存（Java 侧 GET /v1/search/hot-keywords 读取；admin 变更后主动删除）
    case SEARCH_HOT_KEYWORDS = 'web:search:hot_keywords';
}
