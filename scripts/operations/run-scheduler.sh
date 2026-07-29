#!/usr/bin/env sh
set -eu

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd -P)"
REPOSITORY="$(cd "$SCRIPT_DIR/../.." && pwd -P)"
PHP_BIN="/opt/alt/php83/usr/bin/php"
LOCK_FILE="$REPOSITORY/storage/framework/scheduler-cron.lock"
LOG_FILE="$REPOSITORY/storage/logs/scheduler-cron.log"

cd "$REPOSITORY"

mkdir -p \
    "$REPOSITORY/storage/framework" \
    "$REPOSITORY/storage/logs"

if command -v flock >/dev/null 2>&1; then
    flock -n "$LOCK_FILE" \
        "$PHP_BIN" artisan schedule:run \
        --no-interaction \
        >>"$LOG_FILE" 2>&1
else
    "$PHP_BIN" artisan schedule:run \
        --no-interaction \
        >>"$LOG_FILE" 2>&1
fi
