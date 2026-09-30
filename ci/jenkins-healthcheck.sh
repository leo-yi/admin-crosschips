#!/usr/bin/env bash
# ============================================================
# admin.crosschips.com 站点探测（在 Jenkins 容器内执行）
#
# 用途：流水线的「站点探测」stage。从宿主网络栈（--network host）
#       用 Host 头访问 127.0.0.1:80，判断站点是否就绪。
#
# 退出码：0 = 站点正常；非 0 = 未就绪（流水线标记为 UNSTABLE，不阻断部署）
# ============================================================
set -euo pipefail

SITE_HOST="${SITE_HOST:-admin.crosschips.com}"
ALPINE_IMAGE="${ALPINE_IMAGE:-alpine:3.20}"
PROBE_TIMEOUT="${PROBE_TIMEOUT:-10}"

code="$(
  docker run --rm --network host "${ALPINE_IMAGE}" \
    wget -S -O /dev/null --header="Host: ${SITE_HOST}" -T "${PROBE_TIMEOUT}" \
    "http://127.0.0.1/" 2>&1 \
    | awk '/HTTP\//{print $2; exit}' || true
)"

echo "源站探测: Host=${SITE_HOST} → HTTP ${code:-无响应}"

case "${code}" in
  200|301|302|303)
    echo "✅ 站点响应正常"
    exit 0
    ;;
  403)
    echo "⚠️  403 —— nginx root 未指向 index/public（配置文件 /opt/1panel/www/conf.d/${SITE_HOST}.conf）"
    exit 1
    ;;
  404)
    echo "⚠️  404 —— 站点 root 下找不到入口文件"
    exit 1
    ;;
  5*)
    echo "⚠️  ${code} —— 应用报错，优先检查 index/.env（APP_KEY、DB、Redis 连接）"
    exit 1
    ;;
  000|"")
    echo "⚠️  无响应 —— openresty 或 PHP-FPM 未就绪"
    exit 1
    ;;
  *)
    echo "⚠️  未预期状态码 ${code}，请人工确认"
    exit 1
    ;;
esac
