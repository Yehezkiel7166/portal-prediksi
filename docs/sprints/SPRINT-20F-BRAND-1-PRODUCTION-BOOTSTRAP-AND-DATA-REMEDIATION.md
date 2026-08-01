# Sprint 20F - Brand 1 Production Bootstrap and Data Remediation

Status: **COMPLETE**

## Baseline

- Branch: `main`
- Baseline commit:
  `a0951d9248faab3ff3089f6001363e9b8ac6da6c`
- Initial working tree: clean
- Initial local/remote synchronization: `0 0`

## Objective

Provide a repository-owned, rollback-aware, repeatable, and idempotent
production bootstrap for Brand 1.

## Delivered Scope

- production-safe Brand 1 bootstrap command;
- canonical Brand 1 creation or update;
- active primary production domain registration;
- Brand 1 Site Configuration creation or update;
- administrator creation or promotion;
- nullable tenant ownership remediation for Result, Prediction, and Live Draw;
- production security response headers;
- dry-run and idempotency regression coverage.

## Safety Controls

- dry-run by default;
- explicit `--apply` required;
- production-only execution unless `--force` is supplied;
- administrator password obtained from an environment variable;
- no password stored in repository or command history;
- database mutation wrapped in a transaction;
- schema requirements validated before mutation;
- repeated execution is idempotent;
- production backup required before applying mutations.

## Production Verification

Verified production state:

- canonical Brand ID: `7`;
- canonical Brand name: `SANTOTO4D`;
- production domain: `santoto4d-prediksi.site`;
- Site Configuration: present for Brand ID `7`;
- administrator ID: `1`;
- administrator name: `Yehezkiel`;
- administrator email: `k11037166@gmail.com`;
- nullable `results.brand_id`: `0`;
- nullable `predictions.brand_id`: `0`;
- nullable `live_draws.brand_id`: `0`;
- BrandResolver production-domain resolution: PASS;
- production database mutation during final repository regression: none.

## Validation Evidence

- PHP syntax: PASS;
- command registration: PASS;
- default dry-run: PASS;
- targeted command tests: `2` passed / `10` assertions;
- full regression: `493` tests / `1428` assertions / PASS;
- Composer validation: PASS;
- repository audit: PASS;
- security and secret audit: PASS;
- Sprint Completion Gate: PASS;
- CTO crosscheck: PASS.

## Completion Commit

- Commit: `c53c742a6e526a8772e87023893311edc3786c81`;
- Subject: `feat: add Brand 1 production bootstrap and remediation`;
- Branch: `main`;
- Remote branch: `origin/main`;
- Local and remote HEAD equality: PASS;
- Ahead/behind after push: `0 0`;
- Final working tree: clean.

## Known Limitation

Sprint 20F remediates production identity, ownership, administration,
configuration, and security-header foundations. It does not independently close
the Brand 1 production acceptance gate.

Minimum content and complete public acceptance must be evaluated in the next
bounded sprint.

## Next Sprint

**Sprint 20H - Brand 1 Production Acceptance Re-verification**
