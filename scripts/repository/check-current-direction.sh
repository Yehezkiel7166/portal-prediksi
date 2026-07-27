#!/usr/bin/env bash
set -euo pipefail

grep -q '2026-07-16' docs/governance/CURRENT_DIRECTION.md
grep -q '2026-07-30' docs/governance/CURRENT_DIRECTION.md
grep -q '2026-10-14' docs/governance/CURRENT_DIRECTION.md
grep -q '"main_modules": 10' PROJECT_STATE.json
grep -q '"lottery_tools": 6' PROJECT_STATE.json
grep -q '"repository_crosscheck_every_sprint": true' PROJECT_STATE.json
grep -q 'Superseded' docs/delivery/BRAND-1-30-DAY-PLAN.md

php -r '
$d=json_decode(file_get_contents("PROJECT_STATE.json"),true,512,JSON_THROW_ON_ERROR);
if (
    $d["canonical_direction"]["brand_1_usable_days"] !== 14 ||
    $d["canonical_direction"]["overall_project_days"] !== 90 ||
    $d["canonical_direction"]["main_modules"] !== 10 ||
    $d["canonical_direction"]["lottery_tools"] !== 6
) {
    exit(1);
}
'

echo '[PASS] Current project direction synchronized.'