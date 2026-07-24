# Brand 1 — 30-Day Production Plan

Status: Active delivery plan
Start: 2026-07-24
Deadline: 2026-08-23
Primary objective: Brand 1 production-ready

## Delivery rule

The deadline is a maximum target. Critical security, data-integrity, backup, migration, or release failures block production. Non-essential future capabilities move to backlog rather than extending Brand 1 scope.

## Days 1–3 — Baseline and blocker register

- Confirm branch, commit, clean working tree, runtime, routes, scheduler, migrations, and dependencies.
- Run PHP syntax, focused tests, full tests, frontend build, repository audit, Composer audit, and npm audit review.
- Map current Brand Context and all brand-owned tables/resources.
- Create Critical/High/Medium blocker register.
- Verify production and staging access requirements without storing credentials.

Exit criteria: repeatable baseline, known blockers, no unexplained working-tree changes.

## Days 4–8 — Brand foundation

- Complete Brand Context resolution for HTTP, admin, jobs, commands, cache, media, and SEO.
- Add explicit brand ownership and required indexes/constraints.
- Implement brand-scoped policies and administrative queries.
- Implement typed site configuration, theme assets, and public navigation foundation.
- Add cross-brand isolation regression tests.

Exit criteria: no verified cross-brand read/write path; core brand settings render reliably.

## Days 9–12 — SEO and media foundation

- Canonical, metadata, Open Graph, schema where applicable, sitemap, robots, breadcrumbs, indexability, and redirect baseline.
- Responsive upload/media validation and storage policy.
- Brand-aware cache keys and invalidation.
- Validate critical public routes and error states.

Exit criteria: crawlable, canonical, brand-correct public output; unsafe uploads blocked.

## Days 13–17 — Security and authorization

- Replace transitional broad admin access with policy and permission enforcement.
- Remove privileged fields from unsafe mass assignment paths.
- Harden login, sessions, cookies, rate limits, sensitive actions, and audit events.
- Validate remote media/embed SSRF and sanitization controls.
- Run unauthorized, privilege-escalation, and isolation tests.

Exit criteria: all P0 controls in the Security Control Matrix are implemented or an explicit release blocker remains.

## Days 18–21 — Operations

- Queue configuration, retries, failure visibility, and brand-aware payloads.
- Scheduler heartbeat and overlap validation.
- Health endpoints or checks for application, database, queue, scheduler, storage, and cache.
- Backup automation and off-public storage.
- Perform a restore rehearsal on non-production data.
- Finalize deployment and rollback commands.

Exit criteria: backup restore evidence exists; failed automation is visible and recoverable.

## Days 22–25 — Quality and performance

- Full regression suite.
- Browser-critical flow checks.
- N+1 and slow-query review.
- Cache and response review.
- Responsive and accessibility baseline.
- Dependency vulnerability triage.
- Documentation and registry synchronization.

Exit criteria: no unresolved P0 blocker; accepted P1 risks have owner and mitigation.

## Days 26–28 — Staging release candidate

- Deploy immutable release candidate to staging.
- Run migrations, seed/config validation, cache warm-up, queues, scheduler, and smoke tests.
- Validate production-like environment settings.
- Complete security, SEO, backup, monitoring, and rollback rehearsal.
- Freeze non-critical changes.

Exit criteria: signed production gate and tested rollback.

## Days 29–30 — Production and stabilization

- Pre-deployment backup.
- Deploy approved release.
- Run controlled migrations and cache steps.
- Verify public routes, admin, queue, scheduler, logs, storage, SEO output, and health checks.
- Monitor and stabilize.
- Record release, known risks, and follow-up hardening backlog.

Exit criteria: production gate passed, operational owner accepts release, no active critical incident.

## Scope control

During this 30-day window, the following do not block Brand 1 unless required by current implementation: marketplace, plugin ecosystem, billing, mobile app, advanced AI automation, distributed infrastructure, or full Owner Panel.
