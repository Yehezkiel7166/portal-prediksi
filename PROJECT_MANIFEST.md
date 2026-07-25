# Project Manifest

## Project Identity

- Name: Portal Prediksi CMS
- Repository: `https://github.com/Yehezkiel7166/portal-prediksi`
- Primary branch: `main`
- Repository role: Single Source of Truth
- Baseline commit before Repository Foundation: `dbc3b17`
- Baseline subject: `feat(live-draw): add hls stream player`

## Runtime

- PHP 8.3
- Laravel 13.20
- Filament 5.7
- MySQL
- Database queue
- Vite 8
- Tailwind CSS 4
- HLS.js

## Application Domains

### Core

Shared application infrastructure, application clock, scheduler heartbeat, events, listeners, and reusable support services.

### Market

Management of togel markets, slugs, codes, timezones, schedules, active state, ordering, and public availability.

### Prediction

Management and publication of market predictions by date, including public listing and detail pages.

### Result

Management and publication of market results by date, including latest-result resolution, public listing, and detail pages.

### Shio

Management of shio periods, shio numbers, period changes, events, banner templates, and generated banner output.

### Promotion

Management of published promotions with public listing and detail pages.

### Blog

Management of published blog posts with public listing and detail pages.

### Live Draw

Management of live draw schedules, status automation, stream sources, public listing, and HLS playback.

## Public Frontend

Implemented public routes include:

- `/`
- `/live-draw`
- `/prediksi-togel`
- `/prediksi-togel/{marketSlug}/{predictionDate}`
- `/data-result`
- `/data-result/{marketSlug}/{resultDate}`
- `/promosi`
- `/promosi/{slug}`
- `/blog`
- `/blog/{slug}`

## Administration

Filament administration is available at `/admin`.

Implemented resources include:

- Markets
- Predictions
- Results
- Shio Periods and Shio Numbers
- Promotions
- Blog Posts
- Live Draws

## Scheduled Operations

- `system:scheduler-heartbeat` runs every five minutes without overlapping.
- `live-draw:update-status` runs every minute without overlapping.

Production cron must execute Laravel scheduler every minute.

## Repository Documentation

The Repository Foundation must contain:

- `README.md`
- `START_HERE.md`
- `PROJECT_MANIFEST.md`
- `PROJECT_STATE.md`
- `PROJECT_STATE.json` (machine-readable compatibility artifact)
- `SPRINT_STATE.md`
- `AI_HANDOVER.md`
- `ROADMAP.md`
- `ARCHITECTURE.md`
- `SECURITY.md`
- `DEPLOYMENT.md`
- `MIGRATION.md`
- `BACKUP_RECOVERY.md`
- `TESTING.md`
- `CHANGELOG.md`
- sprint records under `docs/sprints/`
- architecture decisions under `docs/architecture/`

## Mandatory Engineering Workflow

`Inspect → Design → Patch → Syntax Check → Module Test → Full Test → Documentation → Git Clean → Commit → Push → Audit`

## Change Rules

- Inspect repository implementation before modifying it.
- Do not depend on chat history for technical state.
- Do not repeat completed sprints.
- Use one goal, one patch, and one commit.
- Do not modify historical migrations.
- Use new migrations for database changes.
- Preserve backward compatibility and existing data.
- Add or update tests for behavioral changes.
- Update documentation in the same sprint.
- Do not force push or rewrite shared history.
- Do not commit secrets, environment files, logs, dumps, or backups.

## Media Architecture Requirements

- Media must be responsive across mobile, tablet, and desktop.
- Administrators must not manually configure pixel dimensions.
- Administrators may select focal point or alignment.
- The application must control ratio, crop, object fit, breakpoints, thumbnails, and output resolution.
- Media sources may include upload, direct image URL, or approved embed providers where supported.
- Arbitrary JavaScript and unsanitized embed code are prohibited.
- External embeds must use provider whitelisting and sanitization.

## Current Known Gaps

- Repository Foundation documentation and Phase 0.3A canonical synchronization have been completed and are maintained as the canonical project documentation.
- Repository governance CI is implemented and verified through GitHub Actions; Phase 0.3A synchronization completed at commit `45c4e5d`.
- Application test/build CI expansion and deployment automation are not yet implemented.
- Backup automation is not implemented in the repository.
- Centralized site settings are not yet implemented.
- Centralized responsive media management is not yet implemented.

## Completion Definition

The repository can replace historical chat context only when:

- all Repository Foundation documents exist and agree with implementation;
- canonical project status documentation is established (`PROJECT_STATE.md`);
- machine-readable compatibility state (`PROJECT_STATE.json`) is synchronized;
- architecture and operational procedures are documented;
- syntax validation passes;
- relevant module tests pass;
- full test suite passes;
- documentation changes are committed and pushed;
- local branch matches `origin/main` and required repository governance CI passes;
- final working tree is clean.

<!-- MASTER-PROMPT-V2-ALIGNMENT -->

## Repository Direction

This repository follows the Enterprise Digital Platform Framework (EDPF).

Priority order:

1. Repository alignment with Master Prompt v2.0.
2. Five-brand-compatible platform foundation.
3. Brand #1 production.
4. Brand #2–#5 activation.
5. Enterprise expansion.

Official implementation principle:

> Build for many, release for one.

<!-- /MASTER-PROMPT-V2-ALIGNMENT -->

---

## Canonical Governance

Master Prompt Traceability

- docs/governance/MASTER_PROMPT_TRACEABILITY.md

Canonical Map

- docs/governance/MASTER_PROMPT_CANONICAL_MAP.md

Alignment Checklist

- docs/governance/MASTER_PROMPT_ALIGNMENT_CHECKLIST.md

<!-- BRAND-1-BASELINE-START -->

## Brand 1 Baseline Manifest

The repository recognizes `docs/product/BRAND_1_FRONTEND_BASELINE.md` as the canonical complete feature baseline for the default trial brand.

Mandatory Brand 1 capability groups:

- Home;
- Live Draw;
- Result;
- Prediction;
- Slot Gacor / RTP;
- Jackpot Proof;
- Promotion;
- Complaint;
- Guide;
- Lottery Tool Suite;
- administration;
- automation;
- SEO;
- cache;
- queue;
- scheduler;
- audit;
- backup;
- operational monitoring.

The Lottery Tool Suite consists of:

- Jadwal Togel;
- BBFS Generator;
- Buku Mimpi;
- Paito Togel Warna;
- Konversi Angka SGP;
- Tabel Shio.

<!-- BRAND-1-BASELINE-END -->

<!-- BEGIN SEO-ENGINE-MANIFEST -->
## SEO Engine Canonical Documents

- `docs/product/SEO_ENGINE_SPECIFICATION.md`
- `docs/product/BRAND_1_FRONTEND_BASELINE.md`
- `docs/sprints/BRAND-1-FRONTEND-COMPLETION.md`
- `MASTER_PROMPT_POINTER.md`

These documents define:

- Brand 1 SEO automation;
- SERP operations;
- evergreen title behavior;
- manual SEO locks;
- Card View and List View behavior;
- AI technical operating responsibility.
<!-- END SEO-ENGINE-MANIFEST -->

<!-- BEGIN BRAND-1-ARCHITECTURE-SPECIFICATIONS -->
## Brand 1 Architecture Specifications

Canonical specifications:

- `docs/product/SITE_CONFIGURATION_ENGINE_SPECIFICATION.md`
- `docs/product/MEDIA_ENGINE_SPECIFICATION.md`
- `docs/product/MENU_ENGINE_SPECIFICATION.md`
- `docs/product/BANNER_ENGINE_SPECIFICATION.md`
- `docs/product/WIDGET_ENGINE_SPECIFICATION.md`
- `docs/product/SEO_ENGINE_SPECIFICATION.md`
- `docs/product/AI_CONTENT_ENGINE_SPECIFICATION.md`
- `docs/product/AI_GOVERNANCE_SPECIFICATION.md`

These documents define Brand 1 architecture before full implementation.
<!-- END BRAND-1-ARCHITECTURE-SPECIFICATIONS -->
<!-- PROJECT-BRAIN-V1-START -->
## Project Brain v1

Effective 2026-07-24, the repository also serves as the permanent project knowledge system. Canonical entry point: `docs/project-brain/README.md`.

Project Brain includes:

- product mission and long-term vision;
- collaboration and command-delivery model;
- project-level decisions;
- multi-brand system blueprint;
- full feature and idea catalogs;
- Brand 1 maximum 30-day production plan;
- production gate;
- threat model and security control matrix;
- knowledge-maintenance rules.

### Current delivery order

1. Brand 1 production readiness.
2. Brand 1 optimization and hardening.
3. Owner Panel.
4. Brand 2–5 activation.
5. Enterprise expansion.

Target Brand 1 completion window: 2026-07-24 through 2026-08-23. Critical security, data-integrity, backup, migration, and release controls may not be waived to meet the date.

### Working environment

The owner primarily executes prepared copy-paste commands through Windows PowerShell and Linux SSH. Repository operations must identify the correct shell, use complete commands, validate state, and stop on failure.
<!-- PROJECT-BRAIN-V1-END -->

<!-- CURRENT-DIRECTION-START -->
## Canonical Direction — 2026-07-25

- Project started on 2026-07-16.
- Brand 1 usable deadline is 2026-07-30.
- Overall project deadline is 2026-10-14.
- Brand 1 contains exactly 10 main modules and 6 lottery tools.
- Brand 1 is completed before Owner Panel and Brand 2–5.
- Domain Management is implemented through Commit 14B.
- The former active 30-day Brand 1 plan is superseded.
- Every sprint requires repository synchronization and CTO crosscheck.

Canonical reference:

- `docs/governance/CURRENT_DIRECTION.md`
- `docs/delivery/BRAND-1-14-DAY-USABLE-PLAN.md`
<!-- CURRENT-DIRECTION-END -->

<!-- SPRINT-15A-REPOSITORY-BRAIN-START -->
## Repository Brain Canonical Documents

The following documents are canonical:

- `docs/governance/MASTER_PROMPT_V2_0_TO_V2_1_INHERITANCE.md`
- `docs/project-brain/CANONICAL_REQUIREMENTS.md`
- `docs/registry/LIFECYCLE_MODEL.md`
- `docs/governance/CURRENT_DIRECTION.md`
- `docs/registry/IMPLEMENTATION_STATUS.md`

Master Prompt v2.1 extends v2.0. Historical requirements remain active unless
explicitly superseded by a registered decision.

Feature status must be based on implementation evidence, not documentation
existence.
<!-- SPRINT-15A-REPOSITORY-BRAIN-END -->
