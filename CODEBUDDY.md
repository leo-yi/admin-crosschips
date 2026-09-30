# CODEBUDDY.md This file provides guidance to CodeBuddy when working with code in this repository.

## 常用命令

```bash
# 启动开发服务器
php artisan serve

# 启动 Redis 队列消费者（芯片产品库存导入专用队列）
php artisan queue:work --queue=chip_product_stock_import

# 运行测试
php artisan test

# 运行单个测试文件
php artisan test --filter=ExampleTest

# 代码格式化（Laravel Pint）
./vendor/bin/pint

# 清除所有缓存（路由、配置、视图等）
php artisan optimize:clear

# 生成 IDE 辅助文件（用于 Dcat Admin 自动补全）
php artisan admin:ide-helper
```

## 项目架构

### 概述

这是一个基于 **Laravel 13** + **dcat-plus/laravel-admin 3.x**（Dcat Admin 的社区 fork）的芯片/电子元器件电商后台管理系统（HKSaturday / CrossChips 双品牌共用后台）。管理员通过 Dcat Admin 面板管理产品、分类、制造商、库存、Banner、RFQ 询价、推荐品牌、推广站点及系统消息。异步任务通过 **Redis** 队列处理 Excel 库存导入等耗时操作；内容变更后会调用前端站点的 ISR On-Demand Revalidation 接口刷新静态页面。

- **PHP 版本**: ^8.3
- **管理面板框架**: dcat-plus/laravel-admin ^3.0（Dcat Admin fork）
- **队列驱动**: Redis
- **队列监控**: Supervisor（管理队列消费者进程）
- **文件存储**: S3 兼容存储（Cloudflare R2，通过 flysystem）

### 目录结构与职责

```
app/
├── Admin/                    # Dcat Admin 管理面板（路由、控制器、页面）
│   ├── routes.php            # /admin 前缀下的所有路由定义
│   ├── bootstrap.php         # Dcat Grid/Form 全局行为定制（CSS、默认工具栏）
│   ├── Controllers/          # 资源控制器 + 面板内部 API 控制器 + 认证控制器
│   ├── Widgets/              # 工作台组件（InfoBox、图表、RFQ 列表等）
│   ├── Repositories/         # Dcat 仓储（Grid 数据源封装）
│   └── Actions/              # 自定义 Grid 行操作和 Form 工具（Excel 导入表单等）
├── Models/                   # Eloquent 模型
├── Services/                 # 业务服务层
│   ├── TaskService.php       # 异步任务状态管理（创建/执行/完成）
│   ├── FileService.php       # S3 文件下载到本地 & 清理
│   └── ExcelImportTasks/     # Excel 导入策略（ImportInterface + 具体实现）
├── Jobs/                     # 队列 Job（ChipProductStockImportJob）
├── Enum/                     # PHP 原生枚举（库存类型、货币、队列名、Redis key 等）
├── Actions/                  # 应用级动作（TriggerIsrRevalidation：触发前端 ISR）
├── Console/Commands/         # Artisan 命令（RBAC 同步、工作台统计、队列测试）
├── Providers/                # 服务提供者
└── Exceptions/               # 异常处理
```

### 数据模型关系

```
ChipCategory (chip_category)          # 芯片分类（自关联 parent_id，支持两级分类）
  ├── 自引用: children / parent
  ├── ChipProduct::categoryOne()
  └── ChipProduct::categoryTwo()

ChipManufacturer (chip_manufacturer)  # 芯片制造商
  ├── ChipProduct::manufacturer()
  ├── ChipProductStock::manufacturer()   (hasOneThrough ChipProduct)
  └── ChipManufacturerRelation::manufacturer()

ChipProduct (chip_product)            # 核心产品表（6 级阶梯价格，softDeletes）
  ├── manufacturer() -> ChipManufacturer
  ├── categoryOne() / categoryTwo() -> ChipCategory
  ├── ChipProductStock::product()       (hasMany)
  └── ChipRfqProduct                     (关联 RFQ 明细)

ChipProductStock (chip_product_stock)  # 产品库存（Coming/Immediately/Customer/Hot Sale 四种类型）
  ├── product() -> ChipProduct
  └── manufacturer() -> ChipManufacturer (hasOneThrough)

ChipBanner (chip_banner)               # 首页 Banner 图
ChipManufacturerRelation (chip_manufacturer_relation) # 制造商首页展示关系
ChipMnfCategoryRelation (chip_mnf_category_relation) # 制造商-分类关联
ChipRfq / ChipRfqProduct               # RFQ 询价单及其产品明细
ChipStation (chip_station)             # 推广站点（自动生成带参链接）
SystemMessage (system_message)         # 系统消息（关联 AdminUser）
  └── user() -> AdminUser
MessageLog (message_log)               # 消息已读记录
Task (task)                            # 异步任务追踪表
  └── adminUser() -> AdminUser
AdminUser / User                       # 管理员用户 / 前端用户
```

### Dcat Admin 架构说明

所有后台管理页面位于 `/admin` 路径下，由 Dcat Admin 框架驱动。

**路由注册**: `app/Admin/routes.php` 通过 `Admin::routes()` 注册预设路由（登录、权限管理等），再通过 `Route::group` 注册自定义资源路由。路由前缀、命名空间、中间件由 `config/admin.php` 的 `route` 段控制。

**CRUD 控制器**（13 个）均继承 `Dcat\Admin\Http\Controllers\AdminController`。每个控制器内定义 `grid()`、`form()`、`detail()` 三个方法，分别对应列表、表单、详情页。Dcat 基于这些方法自动渲染完整的后台界面。

**级联选择器**: ChipProductController 使用 `distpicker` 或级联方式实现一级分类 → 二级分类的联动选择。

**API 控制器**: `ApiController` 提供制造商下拉列表 `mnf()` 和分类级联数据 `category()`，供前端异步请求。

**全局定制**: `app/Admin/bootstrap.php` 通过闭包监听 `Grid::resolving` 和 `Form::resolving` 事件：
- Grid 默认隐藏 tools outline，开启列选择器
- Form 禁用重置按钮、隐藏查看/删除按钮、Footer 仅保留提交按钮
- 注入自定义 CSS 实现侧边栏渐变色效果

**IDE 辅助**: `dcat_admin_ide_helper.php` 为 PhpStorm 等 IDE 提供 Dcat Admin Grid、Show、Form 字段的自动补全。

### 异步任务队列系统

系统通过 `Task` 模型 + `TaskService` 实现了任务跟踪机制，非简单的 fire-and-forget：

1. **任务创建**: 用户在管理界面操作（如上传 Excel），Controller 调用 `TaskService::with($data, $uid)->setTaskType(...)->add()` 创建 task 记录（状态: WAIT），并发送消息到 Redis 队列
2. **任务分发**: Job 被队列消费者捡起后，调用 `TaskService::execute($task)` 更新状态为 EXECUTING
3. **任务完成**: Job 处理完毕后调用 `TaskService::init()->finish($taskId, $response, $result)` 更新状态为 SUCCESS 或 FAILED，同时写入响应内容
4. **管理界面**: TaskController 在后台列表展示所有异步任务的状态和结果

**当前队列**: 仅 `chip_product_stock_import` 队列，由 `ChipProductStockImportJob` 消费。

### Excel 导入流程

`ChipProductStockImportJob` 的 handle 流程：
1. 从 task payload 中读取 S3 文件路径
2. 通过 `FileService` 将 S3 文件下载到 `storage/app/temp/{Ymd}/` 临时目录
3. 调用 `ChipProductStockImportService::init($path)->handle()` 逐行解析 Excel
4. 跳过表头行，对每行数据：
   - 用 Validator 校验 12 个字段
   - 按 MPN 查找已存在的 ChipProduct，若不存在则自动创建（模糊匹配制造商和分类名称）
   - 在 `chip_product_stock` 表写入库存记录
5. 完成后清理临时文件

Excel 列映射（固定顺序）: MPN → Quantity → Manufacturer → Package → Publish → Date Code → Lead Time → Price → Currency → First Category → Second Category → Description

### 枚举系统

项目使用 PHP 原生 `enum` 配合标准 trait：

- `EnumToArray`: 提供 `names()`、`values()`、`array()` 方法
- `CodeNameTrait`: 提供 `getValues()` 和 `getCodeByName()` 方法（根据字符串值反查序号，用于 Excel 导入时 Leads Time 到 StockType 的映射）

关键枚举：
- `StockTypeEnum`: COMING / IMMEDIATELY / CUSTOMER / HOT_SALE（`CodeNameTrait`）
- `QueueEnum`: CHIP_PRODUCT_STOCK_IMPORT
- `CurrencyEnum`: USD / CNY / HKD / EUR
- `YesOrNoEnum`: YES=1 / NO=0
- `ManufacturerRelationEnum`: INDEX_SHOW
- `RedisEnum`: 各业务模块的 Redis key 常量（限流器、分类缓存、制造商名称缓存等）

### 文件存储

默认文件系统驱动为 S3（`filesystems.default = s3`）。Dcat Admin 上传使用独立的 `admin` disk（同样基于 S3，但 root 为 `admin_upload` 目录，且 `visibility=public`）。

制造商图片通过 accessor `mnfImg` 拼接为 `{config('brand.cdn_url')}/uploads/mnf/{filename}` 格式。

### 队列基础设施

- 主队列连接: `redis`（通过 `QUEUE_CONNECTION` 环境变量配置）
- 备选: sync / database / sqs
- 失败队列: `database-uuids` 驱动，存储到 `failed_jobs` 表
- 监控: 使用 Supervisor 管理队列消费者进程

### 关键配置项（环境变量）

| 变量 | 说明 |
|------|------|
| `QUEUE_CONNECTION` | 队列驱动，生产环境应为 `redis` |
| `REDIS_*` | Redis 连接（缓存、Session、队列） |
| `FILESYSTEM_DISK` | 文件存储驱动，默认 `s3` |
| `AWS_*` 系列 | S3 兼容存储配置 |
| `ADMIN_ROUTE_PREFIX` | 后台路径前缀，默认 `admin` |
| `DB_*` | MySQL 数据库连接 |
| `REDIS_*` | Redis 连接（缓存 & 队列） |
