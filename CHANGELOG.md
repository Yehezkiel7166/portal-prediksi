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
