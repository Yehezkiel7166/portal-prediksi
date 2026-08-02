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

<!-- SPRINT-COMPLETION-GATE-START -->
## Mandatory Sprint Completion Governance

Canonical artifacts:

- `docs/governance/SPRINT_COMPLETION_GATE.md`
- `docs/sprints/crosschecks/README.md`
- `docs/sprints/crosschecks/TEMPLATE.md`
- `scripts/repository/check-sprint-completion-gate.sh`

These artifacts enforce repository re-read at sprint start and before commit,
mandatory CTO crosscheck evidence, commit blocking until a `PASS` decision, and
remote verification before sprint completion.
<!-- SPRINT-COMPLETION-GATE-END -->

<!-- SPRINT-15C-MANIFEST-START -->
## Sprint 15C Artifact

- `docs/sprints/SPRINT-15C-REPOSITORY-TRUTH-SYNCHRONIZATION.md`
<!-- SPRINT-15C-MANIFEST-END -->

<!-- SPRINT-16A-HOMEPAGE-MANIFEST-START -->

## Brand 1 Production Homepage

Capability `MP21-F017` is implemented.

Implementation components:

- `app/Http/Controllers/Frontend/HomeController.php`
- `resources/views/frontend/home.blade.php`
- `resources/views/frontend/layouts/app.blade.php`
- `tests/Feature/Frontend/PublicProductionHomepageTest.php`

The homepage aggregates current-Brand Live Draw, Result, Prediction, Promotion,
and Blog content. It provides mandatory module access, canonical and Open Graph
metadata, and safe empty-state behavior.

Verified baseline:

- 421 tests;
- 1,166 assertions;
- PASS.

<!-- SPRINT-16A-HOMEPAGE-MANIFEST-END -->

<!-- SPRINT-16D-PROJECT-MANIFEST-START -->
## Sprint 16D — Post-Implementation Truth Synchronization

Canonical implementation truth at baseline `af99d9a6ab748188698b0cb09c6093d3f81ca891`:

- Sprint 16A Homepage Engine: IMPLEMENTED.
- Sprint 16B Slot Gacor / RTP: IMPLEMENTED.
- Sprint 16C Jackpot Proof: IMPLEMENTED and production migration completed.
- Latest full regression: 433 tests / 1,204 assertions / PASS.
- Governance audit: 7/7 PASS.
- Owner Panel and Brand 2–5 remain after Brand 1 completion and stabilization.
- Next implementation candidate: Visitor Complaint Engine.

This synchronization introduces no application behavior change.
<!-- SPRINT-16D-PROJECT-MANIFEST-END -->

<!-- SPRINT-17B-MANIFEST-START -->
## Sprint 17B Manifest Addition

Complaint workflow implementation now includes:
- `app/Domains/Complaint/Models/ComplaintStatusHistory.php`;
- `app/Domains/Complaint/Notifications/NewComplaintSubmitted.php`;
- `database/migrations/2026_07_27_120000_complete_complaint_workflow.php`;
- `tests/Feature/Complaint/ComplaintWorkflowTest.php`;
- Sprint and CTO cross-check records for Sprint 17B.
<!-- SPRINT-17B-MANIFEST-END -->

<!-- SPRINT-18B-START -->
## Sprint 18B Manifest Increment

Prepared public routes:

- `/alat-togel/jadwal-togel`
- `/alat-togel/tabel-shio`

Jadwal Togel uses Market configuration as source of truth. Tabel Shio uses published ShioPeriod and ShioNumber records.
<!-- SPRINT-18B-END -->

<!-- SPRINT-18C-START -->
## Sprint 18C Manifest Increment

Prepared public routes:

- `/alat-togel/bbfs-generator`
- `/alat-togel/konversi-angka-sgp`

Implementation components:

- `app/Domains/Bbfs/Support/BbfsGenerator.php`
- `app/Domains/Converter/Support/SgpNumberConverter.php`
- `app/Http/Controllers/Frontend/BbfsGeneratorController.php`
- `app/Http/Controllers/Frontend/SgpNumberConverterController.php`
- public views and targeted feature tests under `tests/Feature/LotteryTools`.

Both tools are deterministic, rate-limited, and do not persist visitor input.
<!-- SPRINT-18C-END -->


<!-- SPRINT-18D-START -->
## Sprint 18D Manifest Increment

- Buku Mimpi: repository-owned indexed reference content, search, pagination, slugs, detail pages, related entries, metadata, and sitemap exposure.
- Paito: canonical Result-derived historical presentation, market/date filters, deterministic colors, and Result-versioned caching.
- Database migration: none.
<!-- SPRINT-18D-END -->


<!-- SPRINT-18E-START -->
## Sprint 18E Manifest Increment

- Type: canonical truth synchronization.
- Baseline commit: `20838e11d52af369fcb5b6274d089cecfa57429e`.
- Baseline tree: `b05daff4acad282035e8b171ee53611b22d9eceb`.
- Behavior change: none.
- Database migration: none.
- Canonical artifacts: `PROJECT_STATE.md`, `PROJECT_STATE.json`, `SPRINT_STATE.md`, `ROADMAP.md`, `CHANGELOG.md`, `AI_HANDOVER.md`, sprint record, and CTO crosscheck.
- Next candidate: Site Configuration Foundation.
<!-- SPRINT-18E-END -->

### Site Configuration Foundation

- `app/Domains/SiteConfiguration/Models/SiteConfiguration.php`
- `app/Domains/SiteConfiguration/Data/ResolvedSiteConfiguration.php`
- `app/Domains/SiteConfiguration/Support/SiteConfigurationResolver.php`
- `app/Domains/SiteConfiguration/Actions/UpsertSiteConfiguration.php`
- `database/migrations/2026_07_27_180000_create_site_configurations_table.php`
- `tests/Feature/SiteConfiguration/SiteConfigurationFoundationTest.php`

<!-- SPRINT-19A-2-MANIFEST-START -->
## Sprint 19A-2 Manifest

- `app/Filament/Resources/SiteConfigurations/SiteConfigurationResource.php`
- `app/Filament/Resources/SiteConfigurations/Schemas/SiteConfigurationForm.php`
- `app/Filament/Resources/SiteConfigurations/Tables/SiteConfigurationsTable.php`
- `app/Filament/Resources/SiteConfigurations/Pages/ListSiteConfigurations.php`
- `app/Filament/Resources/SiteConfigurations/Pages/CreateSiteConfiguration.php`
- `app/Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php`
- `tests/Feature/SiteConfiguration/SiteConfigurationFilamentAdministrationTest.php`
<!-- SPRINT-19A-2-MANIFEST-END -->

<!-- SPRINT-19A-3-MANIFEST-START -->
## Sprint 19A-3 Manifest

- `app/Providers/AppServiceProvider.php`
- `app/Domains/SiteConfiguration/Support/SiteConfigurationResolver.php`
- `app/Filament/Resources/SiteConfigurations/Schemas/SiteConfigurationForm.php`
- `resources/views/frontend/layouts/app.blade.php`
- `resources/views/frontend/partials/header.blade.php`
- `resources/views/frontend/partials/footer.blade.php`
- `tests/Feature/SiteConfiguration/SiteConfigurationFrontendIntegrationTest.php`
- `docs/sprints/SPRINT-19A-3-SITE-CONFIGURATION-FRONTEND-INTEGRATION.md`
- `docs/sprints/crosschecks/SPRINT-19A-3-SITE-CONFIGURATION-FRONTEND-INTEGRATION.md`
<!-- SPRINT-19A-3-MANIFEST-END -->

<!-- SPRINT-20A-MANIFEST-START -->
## Sprint 20A Canonical Manifest

- Branch: `main`.
- Baseline: `8c621626d6d353b0c7c2550a5889d4fb8038d43e`.
- Sprint: Sprint 20A — Repository Truth Reconciliation.
- Behavior changes: none.
- Migration changes: none.
- Current direction: `docs/governance/CURRENT_DIRECTION.md`.
- Current state: `PROJECT_STATE.md` and `PROJECT_STATE.json`.
- Sprint state: `SPRINT_STATE.md`.
- Completion gate: `docs/governance/SPRINT_COMPLETION_GATE.md`.
- Sprint record:
  `docs/sprints/SPRINT-20A-REPOSITORY-TRUTH-RECONCILIATION.md`.
- CTO report:
  `docs/sprints/crosschecks/SPRINT-20A-REPOSITORY-TRUTH-RECONCILIATION.md`.
- Next sprint: Sprint 20B — Brand 1 Production Readiness Audit.
<!-- SPRINT-20A-MANIFEST-END -->

<!-- SPRINT-20G-MANIFEST-START -->
## Sprint 20F Completion Manifest

- Completion commit: `c53c742a6e526a8772e87023893311edc3786c81`.
- Branch: `main`.
- Remote branch: `origin/main`.
- Full regression: `493` tests / `1428` assertions / PASS.
- CTO crosscheck: PASS.
- Remote verification: PASS.
- Final worktree after push: clean.

Implemented artifacts:

- `app/Console/Commands/BootstrapBrandOneProduction.php`
- `app/Http/Middleware/AddProductionSecurityHeaders.php`
- `bootstrap/app.php`
- `tests/Feature/Production/BootstrapBrandOneProductionCommandTest.php`
- `docs/sprints/SPRINT-20F-BRAND-1-PRODUCTION-BOOTSTRAP-AND-DATA-REMEDIATION.md`
- `docs/sprints/crosschecks/SPRINT-20F-BRAND-1-PRODUCTION-BOOTSTRAP-AND-DATA-REMEDIATION.md`

Sprint 20G canonical artifacts:

- `docs/sprints/SPRINT-20G-COMPLETION-TRUTH-SYNCHRONIZATION.md`
- `docs/sprints/crosschecks/SPRINT-20G-COMPLETION-TRUTH-SYNCHRONIZATION.md`

Next bounded sprint:

- Sprint 20H - Brand 1 Production Acceptance Re-verification.
<!-- SPRINT-20G-MANIFEST-END -->

<!-- BEGIN SPRINT-20H-CANONICAL-STATUS -->
## Sprint 20H canonical status

- Sprint: `Sprint 20H — Brand 1 Production Acceptance`
- Status: `COMPLETE`
- Completion commit: `89c49e129c69df3b23b82ed48cfce0588be68a54`
- Completion commit remote verification: PASS
- Full regression evidence: `501 tests / 1469 assertions`
- Frontend production build and manifest: PASS
- Production acceptance: PASS
- Brand 1: `SANTOTO4D`, Brand ID `7`
- Brand 1 status: COMPLETE
- Next bounded sprint: `Bulk Import Result (CSV/XLSX)`
- Evidence: `storage/logs/sprint-20h-finalization-20260802-091435.txt`
<!-- END SPRINT-20H-CANONICAL-STATUS -->
