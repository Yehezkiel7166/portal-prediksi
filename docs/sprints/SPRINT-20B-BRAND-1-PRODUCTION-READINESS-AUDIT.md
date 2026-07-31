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

<!-- BEGIN SPRINT-20B-TESTING-BOOTSTRAP-REMEDIATION -->

## Testing bootstrap remediation

RED audit membuktikan bahwa production config cache mengoverride testing
configuration saat PHPUnit dijalankan secara langsung.

Remediasi menyediakan `bin/test-safe` sebagai canonical repository test runner.
Runner memastikan test menggunakan:

- `APP_ENV=testing`;
- SQLite `:memory:`;
- array cache;
- synchronous queue;
- array session;
- array mail;
- isolated temporary Laravel bootstrap cache.

Production `bootstrap/cache/config.php` tidak dihapus atau diubah.

Validation gate:

```bash
bash -n bin/test-safe

bin/test-safe \
    tests/Unit/Operations/SafeTestRunnerTest.php

bin/test-safe \
    --filter=test_action_creates_normalized_blog_post \
    tests/Feature/Blog/BlogModuleTest.php

bin/test-safe
```

Testing bootstrap blocker selesai setelah targeted regression, repeatability,
full regression, dan repository governance audit seluruhnya lulus.

Sprint 20B belum selesai sebelum backup automation, scheduled backup, dan
restore rehearsal juga lulus.

<!-- END SPRINT-20B-TESTING-BOOTSTRAP-REMEDIATION -->

<!-- SPRINT-20B-BACKUP-COMPLETION-START -->
## Backup Automation Completion

Implementation commit:
`19d863ace576bac9941cf7baa3f70b2b5af406ab`.

Verified repository capabilities:

- `backup:create`;
- `backup:verify`;
- `backup:restore-rehearsal`;
- daily schedule at `15 3 * * *`;
- database backup;
- public-storage archive;
- SHA-256 checksum support;
- backup manifest;
- isolated rehearsal database restore;
- production database preservation.

Sprint 20B repository implementation is complete. Actual production cron
activation remains a separate runtime evidence gate.
<!-- SPRINT-20B-BACKUP-COMPLETION-END -->
