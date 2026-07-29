#!/usr/bin/env sh
set -u

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd -P)
REPOSITORY=$(CDPATH= cd -- "$SCRIPT_DIR/../.." && pwd -P)
PHP_BIN="/opt/alt/php83/usr/bin/php"

LOCK_FILE="$REPOSITORY/storage/framework/queue-cron.lock"
LOG_FILE="$REPOSITORY/storage/logs/queue-cron.log"

cd "$REPOSITORY" || exit 1

mkdir -p \
    "$REPOSITORY/storage/framework" \
    "$REPOSITORY/storage/logs"

timestamp() {
    date -u '+%Y-%m-%d %H:%M:%S UTC'
}

printf '[%s] QUEUE_CRON_START pid=%s\n' \
    "$(timestamp)" "$$" >> "$LOG_FILE"

if command -v flock >/dev/null 2>&1; then
    exec 9>"$LOCK_FILE"

    if ! flock -n 9; then
        printf '[%s] QUEUE_CRON_SKIP reason=lock_busy pid=%s\n' \
            "$(timestamp)" "$$" >> "$LOG_FILE"
        exit 0
    fi

    printf '[%s] QUEUE_CRON_LOCK_ACQUIRED pid=%s\n' \
        "$(timestamp)" "$$" >> "$LOG_FILE"
else
    printf '[%s] QUEUE_CRON_WARNING reason=flock_unavailable pid=%s\n' \
        "$(timestamp)" "$$" >> "$LOG_FILE"
fi

"$PHP_BIN" artisan queue:work \
    --stop-when-empty \
    --tries=3 \
    --timeout=90 \
    --no-interaction \
    >> "$LOG_FILE" 2>&1

STATUS=$?

printf '[%s] QUEUE_CRON_FINISH pid=%s exit=%s\n' \
    "$(timestamp)" "$$" "$STATUS" >> "$LOG_FILE"

exit "$STATUS"
