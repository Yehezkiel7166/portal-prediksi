#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(git rev-parse --show-toplevel 2>/dev/null || true)"

if [ -z "$ROOT_DIR" ]; then
    echo "[FAIL] Markdown link check harus dijalankan di dalam Git repository." >&2
    exit 1
fi

cd "$ROOT_DIR"

failures=0
markdown_files="$(mktemp)"
links_file="$(mktemp)"

cleanup() {
    rm -f "$markdown_files" "$links_file"
}

trap cleanup EXIT INT TERM

echo "[CHECK] Markdown local links"

git ls-files "*.md" > "$markdown_files"

if [ ! -s "$markdown_files" ]; then
    echo "[PASS] Tidak ada Markdown file untuk diperiksa."
    exit 0
fi

while IFS= read -r file; do
    : > "$links_file"

    grep -oE "\[[^][]+\]\([^)]+\)" "$file" 2>/dev/null \
        | sed -E "s/^[^(]*\((.*)\)$/\1/" \
        > "$links_file" || true

    while IFS= read -r raw_target; do
        target="$(printf "%s" "$raw_target" | sed -E "s/^[[:space:]]+//; s/[[:space:]]+$//")"

        case "$target" in
            ""|\#*|http://*|https://*|mailto:*|tel:*|data:*|javascript:*|\<*|*\{\{*|*\}\}*)
                continue
                ;;
        esac

        target="${target%%#*}"
        target="${target%%\?*}"

        if [ -z "$target" ]; then
            continue
        fi

        target="$(printf "%s" "$target" | sed "s/%20/ /g")"

        case "$target" in
            /*)
                continue
                ;;
        esac

        base_dir="$(dirname "$file")"
        resolved="$base_dir/$target"

        if [ ! -e "$resolved" ]; then
            echo "[FAIL] Broken Markdown link: $file -> $raw_target" >&2
            failures=$((failures + 1))
        fi
    done < "$links_file"
done < "$markdown_files"

if [ "$failures" -gt 0 ]; then
    echo "[FAIL] Markdown link audit menemukan $failures masalah." >&2
    exit 1
fi

echo "[PASS] Semua Markdown local links memiliki target."
