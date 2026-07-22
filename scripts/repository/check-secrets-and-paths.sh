#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(git rev-parse --show-toplevel 2>/dev/null || true)"

if [ -z "$ROOT_DIR" ]; then
    echo "[FAIL] Secret/path check harus dijalankan di dalam Git repository." >&2
    exit 1
fi

cd "$ROOT_DIR"

failures=0
tracked_files="$(mktemp)"
private_path_results="$(mktemp)"

cleanup() {
    rm -f "$tracked_files" "$private_path_results"
}

trap cleanup EXIT INT TERM

echo "[CHECK] Secret files and private paths"

git ls-files > "$tracked_files"

while IFS= read -r file; do
    case "$file" in
        .env|.env.*|*/.env|*/.env.*)
            case "$file" in
                .env.example|*/.env.example)
                    ;;
                *)
                    echo "[FAIL] Environment file terlacak Git: $file" >&2
                    failures=$((failures + 1))
                    ;;
            esac
            ;;
        auth.json|*/auth.json)
            echo "[FAIL] Composer authentication file terlacak Git: $file" >&2
            failures=$((failures + 1))
            ;;
        *.pem|*.key|*.p12|*.pfx|*.jks)
            echo "[FAIL] Key atau certificate sensitif terlacak Git: $file" >&2
            failures=$((failures + 1))
            ;;
        database.sqlite|*/database.sqlite|*.sqlite3)
            echo "[FAIL] Database lokal terlacak Git: $file" >&2
            failures=$((failures + 1))
            ;;
    esac
done < "$tracked_files"

git grep -nF "/home/u339134899/" -- . \
    ":(exclude)AI_HANDOVER.md" \
    ":(exclude)DEPLOYMENT.md" \
    ":(exclude)docs/sprints/SPRINT-00-FOUNDATION.md" \
    ":(exclude)run-scheduler.sh" \
    ":(exclude)scripts/repository/check-secrets-and-paths.sh" \
    > "$private_path_results" || true

if [ -s "$private_path_results" ]; then
    echo "[FAIL] Path absolut akun hosting ditemukan di luar allowlist deployment:" >&2
    cat "$private_path_results" >&2
    failures=$((failures + 1))
fi

if [ "$failures" -gt 0 ]; then
    echo "[FAIL] Secret/path validation menemukan $failures masalah." >&2
    exit 1
fi

echo "[PASS] Tidak ada tracked secret file atau private path di luar allowlist."
