#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(git rev-parse --show-toplevel 2>/dev/null || true)"

if [[ -z "$ROOT_DIR" ]]; then
    echo "[FAIL] Sprint Completion Gate check harus dijalankan di dalam Git repository." >&2
    exit 1
fi

cd "$ROOT_DIR"

echo "[CHECK] Permanent Sprint Completion Gate"

required_files=(
    "docs/governance/SPRINT_COMPLETION_GATE.md"
    "docs/sprints/crosschecks/README.md"
    "docs/sprints/crosschecks/TEMPLATE.md"
    "WORKFLOW.md"
    "docs/governance/CURRENT_DIRECTION.md"
    "PROJECT_MANIFEST.md"
    "PROJECT_STATE.md"
    "PROJECT_STATE.json"
    "SPRINT_STATE.md"
    "AI_HANDOVER.md"
    "CHANGELOG.md"
)

failures=0

for file in "${required_files[@]}"; do
    if [[ ! -f "$file" ]]; then
        echo "[FAIL] File wajib Sprint Completion Gate tidak ditemukan: $file" >&2
        failures=$((failures + 1))
    fi
done

required_gate_patterns=(
    "Single Source of Truth"
    "INSPECT → SYNC → RED → GREEN → REGRESSION → AUDIT → CTO_CROSSCHECK → COMMIT → PUSH → REMOTE_VERIFY"
    "Start-of-Sprint Gate"
    "End-of-Sprint Repository Re-read"
    "Mandatory CTO Crosscheck"
    "Hard Completion Stops"
    "docs/sprints/crosschecks/"
)

for pattern in "${required_gate_patterns[@]}"; do
    if ! grep -Fq "$pattern" docs/governance/SPRINT_COMPLETION_GATE.md; then
        echo "[FAIL] Aturan wajib tidak ditemukan: $pattern" >&2
        failures=$((failures + 1))
    fi
done

for file in \
    WORKFLOW.md \
    docs/governance/CURRENT_DIRECTION.md \
    PROJECT_MANIFEST.md \
    PROJECT_STATE.md \
    SPRINT_STATE.md \
    AI_HANDOVER.md
do
    if ! grep -Fq "SPRINT-COMPLETION-GATE-START" "$file"; then
        echo "[FAIL] Marker Sprint Completion Gate belum ada: $file" >&2
        failures=$((failures + 1))
    fi
done

"$PHP_BINARY" -r '
$data = json_decode(
    file_get_contents("PROJECT_STATE.json"),
    true,
    512,
    JSON_THROW_ON_ERROR
);

$gate = $data["sprint_completion_gate"] ?? null;

if (!is_array($gate)) {
    fwrite(STDERR, "[FAIL] sprint_completion_gate tidak ditemukan di PROJECT_STATE.json\n");
    exit(1);
}

$required = [
    "repository_reread_at_sprint_start",
    "repository_reread_before_commit",
    "cto_crosscheck_required",
    "crosscheck_report_required",
    "commit_blocked_until_crosscheck_passes",
    "remote_verification_required",
];

foreach ($required as $key) {
    if (($gate[$key] ?? null) !== true) {
        fwrite(STDERR, "[FAIL] PROJECT_STATE.json gate tidak aktif: {$key}\n");
        exit(1);
    }
}
' || failures=$((failures + 1))

if [[ "$failures" -gt 0 ]]; then
    echo "[FAIL] Sprint Completion Gate audit menemukan $failures masalah." >&2
    exit 1
fi

echo "[PASS] Permanent Sprint Completion Gate aktif dan sinkron."
