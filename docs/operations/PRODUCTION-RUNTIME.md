# Production Runtime Operations

## Scope

This document defines the production runtime commands for Brand 1 on the
current CloudLinux/cPanel environment.

Repository:

```text
<REPOSITORY_ROOT>
```

PHP runtime:

```text
/opt/alt/php83/usr/bin/php
```

Queue connection:

```text
database
```

## Scheduler Runtime

Laravel scheduler definitions are executed through:

```text
<REPOSITORY_ROOT>/scripts/operations/run-scheduler.sh
```

Required cPanel cron frequency:

```cron
* * * * * <REPOSITORY_ROOT>/scripts/operations/run-scheduler.sh
```

The runner:

- changes to the canonical repository directory;
- uses PHP 8.3 explicitly;
- executes `php artisan schedule:run`;
- uses `flock` when available to prevent overlapping executions;
- writes output to `storage/logs/scheduler-cron.log`.

## Queue Runtime

Queue jobs are executed through:

```text
<REPOSITORY_ROOT>/scripts/operations/run-queue-worker.sh
```

Recommended cPanel cron frequency:

```cron
* * * * * <REPOSITORY_ROOT>/scripts/operations/run-queue-worker.sh
```

The queue runner:

- uses the configured `QUEUE_CONNECTION=database`;
- executes `queue:work --stop-when-empty`;
- uses three attempts and a 90-second timeout;
- uses `flock` when available;
- writes output to `storage/logs/queue-cron.log`.

## Runtime Activation Status

The repository runners and exact cron commands are implemented by Sprint 20B.

The existence of these files does not itself prove that the corresponding
cPanel cron entries are active. Runtime activation and timestamped execution
evidence must be verified separately before declaring Brand 1 production-ready.

## Verification Commands

Scheduler:

```bash
<REPOSITORY_ROOT>/scripts/operations/run-scheduler.sh
tail -n 50 <REPOSITORY_ROOT>/storage/logs/scheduler-cron.log
```

Queue:

```bash
<REPOSITORY_ROOT>/scripts/operations/run-queue-worker.sh
tail -n 50 <REPOSITORY_ROOT>/storage/logs/queue-cron.log
```

Laravel definitions:

```bash
cd <REPOSITORY_ROOT>
/opt/alt/php83/usr/bin/php artisan schedule:list
/opt/alt/php83/usr/bin/php artisan queue:failed
```

## Safety Rules

- Never run multiple permanent queue supervisors together.
- Never modify historical migrations.
- Never expose secrets from `.env` in logs or repository files.
- Do not claim production runtime PASS until cron execution evidence exists.
