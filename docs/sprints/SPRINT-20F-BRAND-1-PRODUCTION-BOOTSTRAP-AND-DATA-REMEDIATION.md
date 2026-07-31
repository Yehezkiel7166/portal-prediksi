# Sprint 20F - Brand 1 Production Bootstrap and Data Remediation

Status: **GREEN implementation prepared**

## Baseline

- Branch: `main`
- Baseline commit:
  `a0951d9248faab3ff3089f6001363e9b8ac6da6c`
- Initial working tree: clean
- Initial local/remote synchronization: `0 0`

## RED Evidence

Sprint 20F implementation was not previously represented in tracked repository
state. Production data mutations existed only as runtime state.

## Objective

Provide a repository-owned, rollback-aware, repeatable, and idempotent
production bootstrap for Brand 1.

## GREEN Scope

- production-only Brand 1 bootstrap command;
- canonical Brand 1 creation or update;
- active primary production domain registration;
- Brand 1 Site Configuration creation or update;
- administrator creation or promotion;
- nullable tenant ownership remediation for Result, Prediction, and Live Draw;
- production security response headers;
- command dry-run and idempotency coverage.

## Safety Controls

- dry-run by default;
- explicit `--apply` required;
- production-only execution unless `--force` is supplied;
- administrator password read from an environment variable;
- no password stored in repository or command history;
- database mutation wrapped in a transaction;
- schema requirements validated before mutation;
- repeated execution is idempotent;
- production backup required before `--apply`.

## Application Files

- `app/Console/Commands/BootstrapBrandOneProduction.php`
- `app/Http/Middleware/AddProductionSecurityHeaders.php`
- `bootstrap/app.php`
- `tests/Feature/Production/BootstrapBrandOneProductionCommandTest.php`

## Completion State

GREEN implementation is prepared.

Regression, repository audit, production execution, production acceptance
re-verification, CTO crosscheck PASS, commit, push, and remote verification
remain pending.
