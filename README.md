# Crosschips 运营后台

> 芯片 / 电子元器件电商后台管理系统。基于 **Laravel 13** + **dcat-plus/laravel-admin**（Dcat Admin 社区 fork），单品牌 **Crosschips**。

仓库地址：`git@github.com:leo-yi/admin-crosschips.git`

## 项目定位

本仓库是**纯运营后台**，只做数据的增删改查与运营配置，不对外提供 API：

- **数据写入方**：运营人员在 Dcat Admin 面板维护产品、分类、制造商、库存、Banner、RFQ、推荐品牌、推广站点等数据，写入 MySQL `chip` 库与 Cloudflare R2 对象存储。
- **与后端的关系**：前端站点调用的 API 由独立的 Java 服务（`crosschips-backend-java`）提供，两者**读写同一个 `chip` 库**——本项目与后端之间没有 API 调用，**数据库表结构即契约**，改表必须两端同步。
- **与前端的联动**：Banner、分类等内容变更后，本项目会主动调用前端站点的 ISR On-Demand Revalidation 接口，让静态页面即时重建。

> 历史上本项目还曾承载一套从前端 API 项目迁入的接口（Sanctum + 信封响应），已于 2026-09 全部移除，相关代码不在本仓库中。

## 技术栈

| 组件 | 版本 / 说明 |
|---|---|
| PHP | ^8.3 |
| Laravel | ^13.0 |
| 管理面板 | `dcat-plus/laravel-admin` ^3.0 |
| 数据库 | MySQL（`chip` 库，与 Java 后端共用） |
| 缓存 / Session / 队列 | Redis |
| 文件存储 | S3 兼容对象存储（Cloudflare R2），通过 flysystem |

## 快速开始

```bash
# 1. 安装依赖
composer install

# 2. 准备配置（复制后按需修改 DB / REDIS / AWS）
cp .env.example .env
php artisan key:generate

# 3. 初始化数据库
php artisan migrate

# 4. 启动开发服务器
php artisan serve

# 5. 另开终端启动队列消费者（Excel 库存导入依赖它）
php artisan queue:work --queue=chip_product_stock_import
```

后台入口为 `/admin`，站点根路径会 302 跳转过去。

本地如需工作台统计数字刷新，还需启动调度器：

```bash
php artisan schedule:work
```

## 目录结构

```
app/
├── Admin/                        # Dcat Admin 面板
│   ├── routes.php                # /admin 前缀下全部路由
│   ├── bootstrap.php             # Grid/Form 全局行为与样式定制
│   ├── Controllers/              # 资源控制器 + 面板内部接口 + 认证控制器
│   ├── Widgets/                  # 工作台组件（统计卡片、趋势图、最近询价）
│   ├── Repositories/             # Dcat 仓储（Grid 数据源）
│   ├── Actions/                  # Grid 行操作、Form 工具（Excel 导入表单）
│   ├── SelectTables/             # 关联选择表格
│   └── Constants/ Metrics/       # 常量与指标示例
├── Models/                       # Eloquent 模型
├── Services/                     # 业务服务层
│   ├── TaskService.php           # 异步任务状态机
│   ├── FileService.php           # S3 文件下载与清理
│   └── ExcelImportTasks/         # Excel 导入策略（ImportInterface + 实现）
├── Jobs/                         # 队列 Job（ChipProductStockImportJob）
├── Enum/                         # PHP 原生枚举（库存类型、队列名、Redis key…）
├── Actions/                      # 应用级动作（TriggerIsrRevalidation）
├── Console/Commands/             # Artisan 命令（RBAC、工作台统计、队列测试）
├── Support/Brand.php             # 多品牌配置解析
└── Providers/ Exceptions/

config/                           # 其中 brand.php 为多品牌对外出口
database/migrations/              # 表结构（与 Java 后端共用）
deploy/                           # 部署脚本与建表 SQL
lang/                             # zh_CN（主）/ zh_TW / en 文案
```

## 架构要点

### 品牌配置（单品牌 Crosschips）

品牌信息（名称、logo、favicon、站点 URL、CDN 域名、联系邮箱、邮件主题）固定在 `App\Support\Brand`（常量），`config/brand.php` 仅作对外暴露。**任何品牌信息都必须走 `config('brand.*')`，不要硬编码。** 历史上曾以 `BRAND_ID` + 多品牌预设支撑 hksaturday，2026-09 已下线并移除该机制。

用纯 PHP 类而非 config 文件间互引，是为了规避 Laravel 配置加载顺序问题（`config/admin.php` 按字母序先于 `config/brand.php` 加载）。

### 异步任务与 Excel 库存导入

耗时操作（如批量导入库存）不在请求周期内执行，而是走「任务表 + Redis 队列」：

1. 控制器经 `TaskService` 创建 `task` 记录（状态 `WAIT`）并投递 Job 到队列；
2. 消费者取到 Job 后把状态置为 `EXECUTING`；
3. 处理完毕后写回 `SUCCESS` / `FAILED` 与响应内容；
4. 后台「异步任务」页面展示全部任务的状态与结果。

当前唯一的队列是 `chip_product_stock_import`（由 `ChipProductStockImportJob` 消费）。导入流程：从任务负载取出 S3 路径 → `FileService` 下载到本地临时目录 → `ChipProductStockImportService` 逐行解析 → 按 MPN 查找产品（不存在则据名称模糊匹配制造商与分类并自动创建）→ 写入 `chip_product_stock` → 清理临时文件。

Excel 列顺序固定：MPN → Quantity → Manufacturer → Package → Publish → Date Code → Lead Time → Price → Currency → First Category → Second Category → Description。

> 生产环境需要常驻队列消费者（Supervisor 管理），部署脚本不含该步骤。

### 文件存储

默认磁盘为 S3 兼容存储（Cloudflare R2）。Dcat Admin 的上传走独立的 `admin` disk（root 为 `admin_upload`，公开可见）。组装图片 URL 统一使用 `App\Support\Brand::storageUrl()`，它会把相对路径拼成完整 CDN 地址，已是完整 URL 的则原样返回，避免双重前缀。

### 与前端站点的联动（ISR）

内容变更后经 `App\Actions\TriggerIsrRevalidation` 调用前端 `GET /api/revalidate?secret=…` 重建静态页面，配置项为 `NEXTJS_SITE_URL` 与 `NEXTJS_REVALIDATE_SECRET`（后者须与前端 `.env` 的 `REVALIDATE_SECRET` 一致）。调用失败只记录日志，不影响后台操作本身。

### 工作台统计

工作台不直接对产品、RFQ 等大表做实时聚合，而是读取 `dashboard_stats` 快照表，由 `dashboard:sync-stats` 命令每小时刷新一次。**调度器没在跑，工作台数字就不会更新。**

## 常用命令

```bash
php artisan serve                                  # 开发服务器
php artisan queue:work --queue=chip_product_stock_import
php artisan schedule:work                          # 本地跑调度（工作台统计刷新）
php artisan test                                   # PHPUnit（tests/Unit）
php artisan test --filter=SomeTest                 # 单个测试（支持 类#方法）
./vendor/bin/pint                                  # 代码格式化
php artisan optimize:clear                         # 清理全部缓存
php artisan admin:ide-helper                       # 重新生成 IDE 补全文件

php artisan admin:seed-rbac                        # 初始化 RBAC 角色权限
php artisan admin:generate-permissions             # 生成权限项
php artisan admin:sync-menu-permission             # 同步菜单与权限映射
php artisan dashboard:sync-stats                   # 手动刷新工作台统计
php artisan test:queue                             # 手工投递一条导入任务（调试用）
```

## 部署

```bash
bash deploy/deploy.sh     # 部署 crosschips 站点（单品牌，无参数）
```

脚本会锁定并发、拉取最新代码、rsync 同步到 1Panel 服务器站点目录，再在 `php85` 容器内执行 `composer install` → 发布 Dcat 资源 → `optimize:clear` → `migrate --force` → `optimize`，失败时通过飞书 Webhook 告警。同步时排除 `.env`、`vendor`、`storage` 等目录，因此**服务器上的 `.env` 不会被覆盖**。

`deploy/data/admin_tables.sql` 为后台相关表的建表参考。

## 环境变量

| 变量 | 说明 |
|---|---|
| `DB_*` | MySQL 连接，指向与 Java 后端共用的 `chip` 库 |
| `REDIS_*` | 缓存、Session、队列 |
| `AWS_*` | S3 兼容存储（Cloudflare R2）凭证与 bucket |
| `QUEUE_CONNECTION` | 生产应为 `redis` |
| `FILESYSTEM_DISK` | 默认 `s3` |
| `NEXTJS_SITE_URL` / `NEXTJS_REVALIDATE_SECRET` | 触发前端 ISR 重建，密钥须与前端一致 |

## 相关仓库

| 仓库 | 说明 |
|---|---|
| `leo-yi/admin-crosschips` | 本仓库：运营后台 |
| `leo-yi/crosschips-backend-java` | API v1 服务（Spring Boot + MyBatis-Plus），与本站共用 `chip` 库 |
| `leo-yi/crosschips-frontend` | 前台站点（Next.js 16），消费 API 并接收本站的 ISR 重建请求 |
