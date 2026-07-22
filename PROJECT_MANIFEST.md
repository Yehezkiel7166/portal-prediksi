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

- Repository Foundation documentation has been completed and is maintained as the canonical project documentation.
- Continuous integration has not yet been verified.
- Deployment is not yet automated through a repository pipeline.
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
- local branch matches `origin/main`;
- final working tree is clean.
