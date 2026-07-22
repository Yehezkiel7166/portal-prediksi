#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(git rev-parse --show-toplevel 2>/dev/null || true)"

if [ -z "$ROOT_DIR" ]; then
    echo "[FAIL] ADR check harus dijalankan di dalam Git repository." >&2
    exit 1
fi

cd "$ROOT_DIR"

ADR_DIR="docs/architecture"
failures=0
seen_ids=""

echo "[CHECK] ADR filename and heading consistency"

if [ ! -d "$ADR_DIR" ]; then
    echo "[FAIL] Direktori ADR tidak ditemukan: $ADR_DIR" >&2
    exit 1
fi

for file in "$ADR_DIR"/ADR-*.md; do
    if [ ! -f "$file" ]; then
        echo "[FAIL] Tidak ada file ADR di $ADR_DIR." >&2
        exit 1
    fi

    filename="$(basename "$file")"
    filename_id="$(printf "%s" "$filename" | grep -oE "^ADR-[0-9]{4}" || true)"
    heading="$(grep -m1 -E "^# +ADR-[0-9]{4}" "$file" || true)"
    heading_id="$(printf "%s" "$heading" | grep -oE "ADR-[0-9]{4}" || true)"

    if [ -z "$filename_id" ]; then
        echo "[FAIL] Nama file ADR tidak valid: $file" >&2
        failures=$((failures + 1))
        continue
    fi

    if [ -z "$heading_id" ]; then
        echo "[FAIL] Heading ADR tidak ditemukan: $file" >&2
        failures=$((failures + 1))
    elif [ "$filename_id" != "$heading_id" ]; then
        echo "[FAIL] ADR ID tidak konsisten: $file" >&2
        echo "       filename=$filename_id heading=$heading_id" >&2
        failures=$((failures + 1))
    fi

    if printf "%s\n" "$seen_ids" | grep -Fxq "$filename_id"; then
        echo "[FAIL] Duplicate ADR ID: $filename_id" >&2
        failures=$((failures + 1))
    else
        seen_ids="${seen_ids}${filename_id}"$'\n'
    fi
done

if [ "$failures" -gt 0 ]; then
    echo "[FAIL] ADR validation menemukan $failures masalah." >&2
    exit 1
fi

echo "[PASS] ADR filename, heading, dan ID unik konsisten."
