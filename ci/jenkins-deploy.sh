#!/usr/bin/env bash
# ============================================================
# admin.crosschips.com 部署脚本（在 Jenkins 容器内执行）
#
# 用法：bash ci/jenkins-deploy.sh
# 由 Jenkins Job `admin-crosschips-cicd`（Pipeline script from SCM）调用。
#
# 服务器侧依赖：
#   - Jenkins 容器挂载 /var/run/docker.sock 与 /usr/bin/docker → 借宿主 Docker 干活
#   - php85 容器（1panel-php-fpm:8.5）把宿主 /opt/1panel/www 挂成 /www
#   - Jenkins 工作区在宿主上的路径 = $HOST_JH + ($WORKSPACE 去掉 /var/jenkins_home 前缀)
#
# 流程：
#   0. 前置检查
#   1. 首次部署：站点 index/ 整体改名为 index-bak（幂等，只做一次）
#   2. rsync 源码 → index/（排除 .git、运行时目录、.env）
#   3. 补齐 storage / bootstrap/cache 并回正属主（1000:1000 = linuxuser/www-data）
#   4. php85 容器内 composer install
#   5. index/.env 存在时：发布 dcat 资源 → 清缓存 → 迁移 → 重建缓存
#   6. 记录本次部署的 commit
#
# 可覆盖变量：HOST_JH / SITE_ROOT / PHP_CONTAINER / ALPINE_IMAGE / OWNER_UID
# ============================================================
set -euo pipefail

HOST_JH="${HOST_JH:-/opt/1panel/apps/jenkins/jenkins/data}"
SITE_ROOT="${SITE_ROOT:-/opt/1panel/www/sites/admin.crosschips.com}"
PHP_CONTAINER="${PHP_CONTAINER:-php85}"
ALPINE_IMAGE="${ALPINE_IMAGE:-alpine:3.20}"
OWNER_UID="${OWNER_UID:-1000}"
APP_NAME="${APP_NAME:-admin-crosschips}"
JENKINS_HOME_DIR="${JENKINS_HOME:-/var/jenkins_home}"

DEPLOY_DIR="${SITE_ROOT}/index"
BACKUP_DIR="${SITE_ROOT}/index-bak"
SITE_CONTAINER_PATH="/www/sites/admin.crosschips.com/index"
COMMIT_FILE="${JENKINS_HOME_DIR}/deploy/${APP_NAME}.commit"

log() { printf '\n[%s] %s\n' "$(date '+%F %T')" "$*"; }
die() { log "❌ $*"; exit 1; }

# ---------- 0. 前置检查 ----------
command -v docker >/dev/null 2>&1 || die "容器内没有 docker CLI，请检查 Jenkins 容器挂载"
docker info >/dev/null 2>&1 || die "无法连接宿主 Docker（/var/run/docker.sock 是否可用？）"
[ -n "${WORKSPACE:-}" ] || die "WORKSPACE 未设置（本脚本必须由 Jenkins 调用）"
[ -f "${WORKSPACE}/artisan" ] || die "工作区不是 Laravel 项目（缺 artisan）：${WORKSPACE}"

# 工作区在宿主上的真实路径：Jenkins 容器内 /var/jenkins_home 由宿主 HOST_JH 挂载而来。
# 注意：容器内看不到 /opt/... 这类宿主路径，所以借用宿主机根目录（只读挂载）做检查。
HOST_WS="${HOST_JH}${WORKSPACE#/var/jenkins_home}"
if ! docker run --rm -v /:/host:ro "${ALPINE_IMAGE}" sh -c "
      test -f /host${HOST_WS}/artisan && test -d /host${SITE_ROOT}
    " 2>/dev/null; then
  die "宿主路径检查失败 —— 工作区应为 ${HOST_WS}（需含 artisan），站点目录应为 ${SITE_ROOT}
      请核对宿主机 ${HOST_JH} 是否就是 Jenkins 容器内 /var/jenkins_home 的挂载源"
fi

docker ps --format '{{.Names}}' | grep -qx "$PHP_CONTAINER" || die "PHP 容器未运行：${PHP_CONTAINER}"

log "源码工作区（宿主）: ${HOST_WS}"
log "部署目标: ${DEPLOY_DIR}"

# ---------- 1. 首次部署：备份原有 index ----------
log "📦 检查站点目录，必要时把原有 index 备份为 index-bak ..."
docker run --rm -v "${SITE_ROOT}":/site "${ALPINE_IMAGE}" sh -c '
  set -e
  if [ -d /site/index ] && [ ! -e /site/index-bak ]; then
    mv /site/index /site/index-bak
    echo "   ✅ 原有 index 已改名为 index-bak"
  elif [ -e /site/index-bak ]; then
    echo "   ℹ️  index-bak 已存在，跳过备份（保留首次那份）"
  fi
  mkdir -p /site/index
' || die "备份 / 准备站点目录失败"

# ---------- 2. rsync 同步源码 ----------
log "🔄 同步源码 → ${DEPLOY_DIR} ..."
docker run --rm \
  -v "${HOST_WS}":/src:ro \
  -v "${DEPLOY_DIR}":/dst \
  "${ALPINE_IMAGE}" sh -c '
    set -e
    apk add --no-cache rsync >/dev/null 2>&1 || { echo "   ❌ apk 安装 rsync 失败"; exit 1; }
    rsync -a --delete \
      --exclude=".git/" \
      --exclude=".idea/" --exclude=".qoder/" --exclude=".codebuddy/" --exclude=".wrangler/" \
      --exclude=".DS_Store" --exclude=".phpunit.result.cache" \
      --exclude=".env" --exclude=".env.*" \
      --exclude="admin.crosschips.com_副本/" \
      --exclude="vendor/" --exclude="node_modules/" \
      --exclude="storage/" --exclude="bootstrap/cache/" \
      --exclude="public/build/" --exclude="public/hot" --exclude="public/storage" \
      --exclude="public/uploads/" --exclude="public/vendor/" \
      /src/ /dst/
    echo "   ✅ 同步完成，文件数: $(find /dst -type f | wc -l)"
  ' || die "rsync 同步失败"

# ---------- 3. 运行时目录 + 属主 ----------
log "📁 补齐运行时目录并回正属主（${OWNER_UID}:${OWNER_UID}）..."
docker run --rm -v "${DEPLOY_DIR}":/dst "${ALPINE_IMAGE}" sh -c "
  set -e
  mkdir -p /dst/bootstrap/cache \
           /dst/storage/app/public \
           /dst/storage/framework/cache/data \
           /dst/storage/framework/sessions \
           /dst/storage/framework/testing \
           /dst/storage/framework/views \
           /dst/storage/logs
  chown -R ${OWNER_UID}:${OWNER_UID} /dst
" || die "运行时目录准备失败"

# ---------- 4. composer install ----------
log "🐘 composer install（在 ${PHP_CONTAINER} 容器内）..."
docker exec -i -e COMPOSER_HOME=/tmp/.composer "$PHP_CONTAINER" sh -ec "
  set -e
  cd ${SITE_CONTAINER_PATH}
  composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-progress
" || die "composer install 失败"

docker run --rm -v "${DEPLOY_DIR}":/dst "${ALPINE_IMAGE}" \
  sh -c "chown -R ${OWNER_UID}:${OWNER_UID} /dst" >/dev/null

# ---------- 5. artisan（依赖 index/.env） ----------
if docker exec "$PHP_CONTAINER" test -f "${SITE_CONTAINER_PATH}/.env"; then
  log "🧩 执行 artisan：发布 dcat 资源 → 清缓存 → 迁移 → 重建缓存 ..."
  docker exec -i "$PHP_CONTAINER" sh -ec "
    set -e
    cd ${SITE_CONTAINER_PATH}
    php artisan vendor:publish --tag=dcat-admin-assets --force
    php artisan optimize:clear
    php artisan migrate --force
    php artisan optimize
  " || die "artisan 步骤失败（修复后可重跑本流水线）"

  docker run --rm -v "${DEPLOY_DIR}":/dst "${ALPINE_IMAGE}" \
    sh -c "chown -R ${OWNER_UID}:${OWNER_UID} /dst" >/dev/null
else
  log "⚠️  未找到 ${SITE_CONTAINER_PATH}/.env —— 跳过 artisan 步骤"
  log "    （Laravel 必须靠 index/.env 启动；补齐 .env 后重跑一次流水线即可）"
fi

# ---------- 6. 记录 commit ----------
commit_sha="$(git -C "${WORKSPACE}" rev-parse HEAD 2>/dev/null || echo unknown)"
mkdir -p "$(dirname "${COMMIT_FILE}")"
printf '%s\n' "${commit_sha}" > "${COMMIT_FILE}"
log "📌 已部署 commit: ${commit_sha}"

log "📂 部署目录一览："
docker run --rm -v "${DEPLOY_DIR}":/dst "${ALPINE_IMAGE}" sh -c 'ls -la /dst | head -25'

log "🎉 代码部署完成：${DEPLOY_DIR}"
log "   站点是否可访问还取决于两件事（人工维护）："
log "     ① nginx root 指向 index/public ② index/.env 存在"
