#!/bin/bash
set -euo pipefail

# ==============================
# 多品牌参数化部署脚本
# 用法: bash deploy.sh [crosschips|hksaturday]
# 默认: hksaturday
# ==============================

BRAND=${1:-hksaturday}

case "$BRAND" in
    crosschips)
        PROJECT_NAME="admin.crosschips.com"
        WORKSPACE="/root/workspace/admin.hksaturday.com"
        DEPLOY_PATH="/opt/1panel/www/sites/admin.crosschips.com/index"
        CONTAINER_NAME="php85"
        CONTAINER_WORKDIR="/www/sites/admin.crosschips.com/index"
        ;;
    hksaturday)
        PROJECT_NAME="admin.hksaturday.com"
        WORKSPACE="/root/workspace/admin.hksaturday.com"
        DEPLOY_PATH="/opt/1panel/www/sites/admin.hksaturday.com/index"
        CONTAINER_NAME="php85"
        CONTAINER_WORKDIR="/www/sites/admin.hksaturday.com/index"
        ;;
    *)
        echo "用法: bash deploy.sh [crosschips|hksaturday]"
        echo "  crosschips  部署 crosschips.com (容器 php85)"
        echo "  hksaturday  部署 hksaturday.com (容器 php85，默认)"
        exit 1
        ;;
esac

# ==============================
# 加载配置
# ==============================
LOG_DIR="/root/repo/log"
LOG_FILE="$LOG_DIR/${PROJECT_NAME}_deploy.log"
LOCK_FILE="/tmp/${PROJECT_NAME}_deploy.lock"
MAX_LOG_SIZE=10485760  # 10MB in bytes
LOG_BACKUP_COUNT=5
FEISHU_WEBHOOK=""

# 如果存在独立的配置文件，则从中加载（避免 Webhook 暴露在代码仓库中）
CONF_FILE="/root/workspace/.deploy_conf"
[ -f "$CONF_FILE" ] && source "$CONF_FILE"

mkdir -p "$LOG_DIR"

# ==============================
# 工具函数
# ==============================
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $*" | tee -a "$LOG_FILE"
}

send_feishu() {
    [ -z "$FEISHU_WEBHOOK" ] && return 0
    curl -s -X POST "$FEISHU_WEBHOOK" \
        -H 'Content-Type: application/json' \
        -d "{\"msg_type\":\"text\",\"content\":{\"text\":\"$1\"}}" \
        > /dev/null 2>&1 || true
}

error_exit() {
    msg="❌ 部署失败: $*"
    log "$msg"
    send_feishu "🔴 **$PROJECT_NAME 部署失败**\n> $msg\n> 时间: $(date +'%Y-%m-%d %H:%M:%S')"
    release_lock
    exit 1
}

acquire_lock() {
    if [ -f "$LOCK_FILE" ]; then
        pid=$(cat "$LOCK_FILE" 2>/dev/null || echo "")
        if kill -0 "$pid" 2>/dev/null; then
            error_exit "另一个部署正在进行 (PID: $pid)"
        fi
        rm -f "$LOCK_FILE"
    fi
    echo $$ > "$LOCK_FILE"
    log "🔒 获取部署锁"
}

release_lock() {
    [ -f "$LOCK_FILE" ] && [ "$(cat "$LOCK_FILE")" = "$$" ] && rm -f "$LOCK_FILE"
}

rotate_log() {
    if [ -f "$LOG_FILE" ] && [ $(stat -c%s "$LOG_FILE" 2>/dev/null || echo 0) -gt $MAX_LOG_SIZE ]; then
        for i in $(seq $((LOG_BACKUP_COUNT - 1)) -1 1); do
            [ -f "$LOG_FILE.$i" ] && mv "$LOG_FILE.$i" "$LOG_FILE.$((i+1))"
        done
        mv "$LOG_FILE" "$LOG_FILE.1"
        touch "$LOG_FILE"
    fi
}

# 清除 git hook 设置的环境变量，避免 git 命令指向裸仓库
unset $(git rev-parse --local-env-vars 2>/dev/null) || true
unset GIT_DIR GIT_WORK_TREE

# ==============================
# 主流程
# ==============================
main() {
    rotate_log
    acquire_lock
    trap release_lock EXIT

    start_time=$(date +%s)
    log "🚀 开始部署 $PROJECT_NAME (品牌: $BRAND)..."

    # 检查工作目录
    [ ! -d "$WORKSPACE" ] && error_exit "工作目录不存在: $WORKSPACE"
    [ ! -d "$WORKSPACE/.git" ] && error_exit "不是有效的 Git 仓库: $WORKSPACE"

    cd "$WORKSPACE"

    # 1. 拉取最新代码
    log "📥 拉取最新代码..."
    git pull || error_exit "Git 拉取失败"

    # 2. 同步文件到部署目录（排除 .git、隐藏文件、运行时目录；.env 不会被覆盖）
    log "📦 同步文件到部署目录..."
    rsync -a --delete --stats \
        --exclude='.git' \
        --exclude='.*' \
        --exclude='/vendor' \
        --exclude='/storage' \
        --exclude='/bootstrap/cache' \
        --exclude='/node_modules' \
        --exclude='/public/build' \
        --exclude='/public/hot' \
        --exclude='/public/storage' \
        --exclude='/public/uploads' \
        --exclude='/public/vendor' \
        "$WORKSPACE/" "$DEPLOY_PATH" || error_exit "文件同步失败"

    # 3. 创建必要的存储目录
    log "📁 创建存储目录..."
    mkdir -p \
        "${DEPLOY_PATH}/bootstrap/cache" \
        "${DEPLOY_PATH}/storage" \
        "${DEPLOY_PATH}/storage/app" \
        "${DEPLOY_PATH}/storage/framework" \
        "${DEPLOY_PATH}/storage/framework/cache" \
        "${DEPLOY_PATH}/storage/framework/sessions" \
        "${DEPLOY_PATH}/storage/framework/testing" \
        "${DEPLOY_PATH}/storage/framework/views" \
        "${DEPLOY_PATH}/storage/logs"

    # 4. 设置权限（仅处理需要写入的目录）
    log "🔐 设置目录权限..."
    chown -R linuxuser:linuxuser \
        "${DEPLOY_PATH}/bootstrap/cache" \
        "${DEPLOY_PATH}/storage" || error_exit "权限设置失败"

    # 5. 在容器中执行部署命令
    log "🐳 执行容器内部署命令 ($CONTAINER_NAME)..."
    if ! docker exec -i "$CONTAINER_NAME" /bin/sh <<EOF
set -e
cd $CONTAINER_WORKDIR

# 安装/更新 Composer 依赖
composer install --no-dev --optimize-autoloader --no-interaction

# 发布 dcat-admin 前端资源
php artisan vendor:publish --tag=dcat-admin-assets --force

# 清除旧缓存并重建
php artisan optimize:clear

# 执行数据库迁移（生产环境强制执行）
php artisan migrate --force

php artisan optimize
EOF
    then
        error_exit "容器内命令执行失败"
    fi

    duration=$(( $(date +%s) - start_time ))
    success_msg="✅ 部署成功！耗时: ${duration} 秒"
    log "$success_msg"
    send_feishu "🟢 $PROJECT_NAME 部署成功\n> 耗时: ${duration} 秒\n> 时间: $(date +'%Y-%m-%d %H:%M:%S')"
}
main "$@"
