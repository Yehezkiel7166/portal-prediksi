#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(git rev-parse --show-toplevel 2>/dev/null || true)"

if [ -z "$ROOT_DIR" ]; then
    echo "[FAIL] Merge-marker check harus dijalankan di dalam Git repository." >&2
    exit 1
fi

cd "$ROOT_DIR"

echo "[CHECK] Merge conflict markers"

if git grep -nE "^(<<<<<<< .+|>>>>>>> .+)$" -- .; then
    echo "[FAIL] Merge conflict marker ditemukan pada tracked files." >&2
    exit 1
fi

echo "[PASS] Tidak ada merge conflict marker."
