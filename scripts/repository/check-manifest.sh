#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(git rev-parse --show-toplevel 2>/dev/null || true)"

if [ -z "$ROOT_DIR" ]; then
    echo "[FAIL] Manifest check harus dijalankan di dalam Git repository." >&2
    exit 1
fi

cd "$ROOT_DIR"

MANIFEST="PROJECT_MANIFEST.md"

echo "[CHECK] Repository manifest"

if [ ! -f "$MANIFEST" ]; then
    echo "[FAIL] PROJECT_MANIFEST.md tidak ditemukan." >&2
    exit 1
fi

failures=0

required_files=(
    PROJECT_MANIFEST.md
    PROJECT_STATE.md
    PROJECT_STATE.json
    ROADMAP.md
    SPRINT_STATE.md
    AI_HANDOVER.md
    DEPLOYMENT.md
)

for file in "${required_files[@]}"; do
    if [ ! -f "$file" ]; then
        echo "[FAIL] File wajib tidak ditemukan: $file" >&2
        failures=$((failures + 1))
        continue
    fi

    if ! grep -Fq "$file" "$MANIFEST"; then
        echo "[FAIL] File belum terdaftar di manifest: $file" >&2
        failures=$((failures + 1))
    fi
done

if [ "$failures" -gt 0 ]; then
    echo "[FAIL] Manifest validation menemukan $failures masalah." >&2
    exit 1
fi

echo "[PASS] Manifest konsisten."
