# Sprint 20B — Brand 1 Production Readiness Audit

## Baseline

- Branch: `main`
- Baseline commit: `04c6d18c4a56550cd76918df11929f4c2b4e39bd`
- Initial regression: `486 passed (1386 assertions)`
- Initial RED gates: 32
- Initial PASS: 28
- Initial FAIL: 4
- Initial BLOCKED: 0

## Initial RED Findings

1. `APP_ENV=Production` did not satisfy the canonical lowercase production
   environment check.
2. `public/storage` symbolic link was missing.
3. Queue runtime operation was not documented.
4. Scheduler runtime operation was not documented.

## GREEN Remediation

- normalized the server environment value to `APP_ENV=production`;
- created the Laravel public storage symbolic link;
- added an idempotent scheduler cron runner;
- added an idempotent queue cron runner;
- documented exact CloudLinux/cPanel runtime commands;
- preserved application behavior;
- introduced no database migration.

## Production Readiness Boundary

Sprint 20B distinguishes repository readiness from runtime activation.

Repository-level runtime procedures are implemented. Actual cPanel cron
activation and timestamped execution evidence remain required before Brand 1
can receive a final production-ready decision.

## Required Validation

- PHP syntax and shell syntax;
- Composer validation;
- Laravel cache operations;
- full automated regression;
- governance audit;
- repository scope audit;
- runtime runner smoke checks;
- clean migration scope.

## Next Evidence Gate

Verify active scheduler and queue cron execution on the production account,
including timestamped log evidence and failure handling.
