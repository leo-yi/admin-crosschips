# Commands 部署命令清单

> 本文档列出 `app/Console/Commands` 下所有 Artisan 命令的调用方式、参数与说明。
> 按领域分类：`Business/`（业务）、`System/`（系统）、`Dev/`（开发调试）。
> 命令分类详情见同目录 [`README.md`](./README.md)。

---

## Business（业务类）

### `dashboard:sync-stats` — 刷新工作台统计数据

```bash
# 刷新后台工作台统计数据快照（产品数、RFQ 趋势、分类分布等），写入 dashboard_stats 表
php artisan dashboard:sync-stats
```

| 参数 / 选项 | 类型 | 必填 | 默认值 | 说明 |
|-------------|------|------|--------|------|
| — | — | — | — | 无参数无选项 |

- **执行频率**：定时，每小时自动执行（已在 `bootstrap/app.php` 的 `withSchedule()` 注册，带 `withoutOverlapping()`）
- **手动场景**：首次部署后、数据大批量变更后需立即刷新统计时手动执行
- **安全等级**：低风险，仅写入 `dashboard_stats` 统计快照表

---

## System（系统类）

### `admin:sync-menu-permission` — 同步菜单与权限

```bash
# 预览模式：扫描 admin 路由，列出将写入的菜单和权限（不写数据库）
php artisan admin:sync-menu-permission

# 写入模式：实际写入 admin_menu、admin_permissions、admin_permission_menu 表
php artisan admin:sync-menu-permission --write

# 指定语言包目录
php artisan admin:sync-menu-permission --write --lang=zh_CN
```

| 参数 / 选项 | 类型 | 必填 | 默认值 | 说明 |
|-------------|------|------|--------|------|
| `--write` | flag | 否 | 不传 | 实际写入数据库；不传则仅预览 |
| `--lang` | string | 否 | `zh_CN` | 语言包目录名，用于读取资源中文名 |

- **执行频率**：按需，新增资源路由后运行
- **执行顺序**：新增路由后**先**运行本命令同步菜单与权限，**再**运行 `admin:generate-permissions` 生成 lang 权限块
- **安全等级**：中风险，会向系统表 INSERT 数据（幂等，已存在则跳过）

### `admin:generate-permissions` — 生成权限配置块

```bash
# 预览模式：扫描资源路由，列出将写入 lang 文件的 permissions 块（不写文件）
php artisan admin:generate-permissions

# 写入模式：将 permissions 配置块注入到对应 lang 文件
php artisan admin:generate-permissions --write

# 指定语言包目录
php artisan admin:generate-permissions --write --lang=zh_CN
```

| 参数 / 选项 | 类型 | 必填 | 默认值 | 说明 |
|-------------|------|------|--------|------|
| `--write` | flag | 否 | 不传 | 实际写入 lang 文件；不传则仅预览 |
| `--lang` | string | 否 | `zh_CN` | 语言包目录名 |

- **执行频率**：按需，新增资源路由并同步菜单权限后运行
- **安全等级**：中风险，修改 `lang/*/xxx.php` 语言包文件（已存在 permissions 块则跳过）
- **写入后**：建议执行 `php artisan optimize:clear` 清除缓存

### `admin:seed-rbac` — 生成 RBAC 基础数据

```bash
# 预览模式：列出将创建的角色、权限树、关联（不写数据库）
php artisan admin:seed-rbac

# 写入模式：清空并重建 RBAC 数据，绑定 user_id=1 为超级管理员
php artisan admin:seed-rbac --write
```

| 参数 / 选项 | 类型 | 必填 | 默认值 | 说明 |
|-------------|------|------|--------|------|
| `--write` | flag | 否 | 不传 | 实际写入数据库；不传则仅预览 |

- **执行频率**：按需，**部署初始化**或 RBAC 结构重建时运行
- **⚠️ 高危操作**：`--write` 模式会 TRUNCATE 以下表，数据不可恢复：
  - `admin_roles`、`admin_permissions`
  - `admin_role_users`、`admin_role_permissions`、`admin_role_menu`
  - `admin_permission_menu`
- **前置条件**：`admin_users` 表必须存在 `id=1` 的用户（将绑定为超级管理员）
- **写入后**：建议执行 `php artisan optimize:clear` 清除缓存

---

## Dev（开发 / 调试类）

### `test:queue` — 测试队列派发

```bash
# 向 ChipProductStockImportJob 派发一个 task_id=1 的队列任务，验证队列配置
php artisan test:queue
```

| 参数 / 选项 | 类型 | 必填 | 默认值 | 说明 |
|-------------|------|------|--------|------|
| — | — | — | — | 无参数无选项 |

- **执行频率**：按需，临时调试
- **⚠️ 仅限开发环境**：硬编码 `Task::where('id', 1)`，不应在生产环境执行；验证完成后建议删除

---

## 快速参考

| 命令 | 领域 | 频率 | 写入开关 | 风险 |
|------|------|------|----------|------|
| `dashboard:sync-stats` | 业务 | 定时（每小时） | 无（直接写） | 低 |
| `admin:sync-menu-permission` | 系统 | 按需 | `--write` | 中 |
| `admin:generate-permissions` | 系统 | 按需 | `--write` | 中 |
| `admin:seed-rbac` | 系统 | 按需（初始化） | `--write` | **高（TRUNCATE）** |
| `test:queue` | 开发 | 按需（临时） | 无 | 仅开发环境 |
