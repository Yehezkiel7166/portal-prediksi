## Sprint 17B — Complaint Workflow Completion

- Added controlled Open → In Progress → Resolved / Rejected transitions.
- Added administrator responses, internal notes, handler attribution, and response timestamps.
- Added immutable brand-scoped complaint status history.
- Added administrator email notification for new complaint submissions.
- Added compatibility migration from legacy `reviewed` records.
- Added targeted complaint workflow regression coverage.
- Added synchronized Sprint 17B governance and handover documentation.


## Sprint 17A — Complaint Foundation

- Added brand-scoped complaint persistence and reference codes.
- Added privacy-safe public complaint intake at `/keluhan`.
- Added Filament complaint review and resolution workflow.
- Added validation, honeypot, rate limiting, and feature coverage.
- Production migration remains a separate guarded operation.

## Unreleased — Sprint 18A Guide Foundation

- Added brand-scoped Guide domain, migration, factory, and publication workflow.
- Added public Guide listing/detail pages with canonical and Open Graph metadata.
- Added Filament Guide administration and public navigation integration.
- Added targeted Guide module, frontend, and access tests.

## Unreleased — Sprint 18B

- Added Market-backed lottery schedule configuration and public status display.
- Added public current-period Shio table.
- Added Alat Togel navigation destinations and regression tests.

## Unreleased — Sprint 18C

- Added deterministic BBFS generation for normalized 2–7 unique digits with 2D, 3D, and 4D output.
- Added deterministic SGP four-digit decomposition into AS, KOP, KEPALA, EKOR, 3D, and 2D.
- Added public routes, canonical metadata, navigation links, throttling, and validation.
- Added targeted regression tests and synchronized Sprint 18C documentation.
- No database migration or visitor-input persistence was introduced.



## Unreleased — Sprint 18E

- Synchronized canonical repository truth after verified Sprint 18D production deployment.
- Recorded Sprint 18D completion commit `20838e11d52af369fcb5b6274d089cecfa57429e` and tree `b05daff4acad282035e8b171ee53611b22d9eceb`.
- Recorded 470 passing tests, 1336 assertions, governance audit 7/7, completion gate PASS, and remote synchronization.
- Closed the six-tool Brand 1 lottery suite implementation milestone.
- Selected Site Configuration Foundation as the next approved implementation candidate.
- No application behavior or database schema changes were introduced.

## Unreleased — Sprint 18D

- Added searchable, paginated Buku Mimpi reference content with slug detail pages and related entries.
- Added canonical metadata and sitemap integration for Buku Mimpi routes.
- Added Result-derived Paito Togel Warna with market and date-range filters.
- Added deterministic digit color mapping and Result-versioned cache keys.
- Added public navigation, automated tests, and synchronized Sprint 18D documentation.
- No database migration or duplicate Result persistence was introduced.

## [0.19.0-alpha.1] - 2026-07-27

### Sprint 19A-1 — Site Configuration Data Foundation

- Added one brand-scoped `site_configurations` record per brand.
- Added centralized site identity, SEO-default, contact, social-link, and footer persistence fields.
- Added safe resolver fallbacks when no active database configuration exists.
- Added short-lived cache and explicit cache invalidation after configuration updates.
- Added a guarded upsert action that cannot move configuration ownership between brands.
- Added migration, relationship, and feature regression coverage.
- Kept Filament administration and frontend integration out of this slice; those remain for subsequent Sprint 19A increments.

## [0.19.0-alpha.2] - 2026-07-27

### Sprint 19A-2 — Site Configuration Filament Administration

- Added current-brand-only Filament administration for site configuration.
- Added validated identity, SEO-default, contact, social-link, and footer forms.
- Prevented a second configuration record from being created for the active brand.
- Routed create and edit persistence through the existing guarded upsert action.
- Added brand-isolation, access-control, page-registration, and singleton regression coverage.

## Unreleased — Sprint 19A-3 Site Configuration Frontend Integration

- Added a shared frontend view composer for resolved brand Site Configuration.
- Updated the public header to consume database-backed site identity, logo, and tagline.
- Updated the public footer to consume database-backed text, contacts, WhatsApp, and social links.
- Added default SEO title/description and favicon fallbacks.
- Restricted rendered external asset and social URLs to HTTP/HTTPS.
- Added frontend integration regression coverage.
- Added synchronized sprint and CTO crosscheck documentation; completion remains pending mandatory runtime regression and remote verification.

<!-- SPRINT-20A-CHANGELOG -->
## 2026-07-28 — Sprint 20A Repository Truth Reconciliation

- Reconciled canonical repository state with verified implementation.
- Synchronized PROJECT_STATE.json with branch `main` and Sprint 20A.
- Recorded ten Brand 1 module groups and six implemented lottery tools.
- Selected Sprint 20B Brand 1 Production Readiness Audit.
- No application behavior or migration changes.

<!-- SPRINT-20C-CHANGELOG -->
## 2026-07-29 — Sprint 20C Repository Truth Synchronization

- Recorded Sprint 20B implementation commit
  `19d863ace576bac9941cf7baa3f70b2b5af406ab`.
- Recorded scheduled backup, verification, and restore-rehearsal implementation.
- Removed stale canonical state claiming backup automation was unavailable.
- Synchronized active sprint state with verified repository implementation.
- Selected Sprint 20D Production Runtime Activation Evidence.
- Introduced no application behavior or database migration change.

<!-- SPRINT-20G-CHANGELOG-START -->
## 2026-08-01 - Sprint 20G Completion Truth Synchronization

- Recorded Sprint 20F completion commit `c53c742a6e526a8772e87023893311edc3786c81`.
- Recorded `493` passing tests and `1428` assertions.
- Recorded successful Sprint 20F audit, CTO crosscheck, push, and remote verification.
- Synchronized canonical project, sprint, roadmap, manifest, direction, and handover state.
- Selected Sprint 20H Brand 1 Production Acceptance Re-verification.
- Introduced no application behavior, migration, or production database change.
<!-- SPRINT-20G-CHANGELOG-END -->

## 2026-08-01 - Sprint 20G Completed

- Completed Sprint 20G repository truth synchronization.
- Recorded `493` passing tests and `1428` assertions.
- Recorded governance checks `7/7` PASS.
- Recorded Sprint 20G CTO decision as PASS.
- Selected Sprint 20H Brand 1 Production Acceptance Re-verification.
- Introduced no application, migration, or production database change.
