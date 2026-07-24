# Project Roadmap

Roadmap ini menggambarkan urutan pengembangan Portal Prediksi CMS berdasarkan kondisi repository aktual.

Repository dan implementasi tetap menjadi sumber kebenaran utama apabila roadmap tidak lagi sesuai.

## Status Legend

- Completed: implementasi, test, dokumentasi, commit, dan push selesai.
- In Progress: sedang dikerjakan dan belum boleh dianggap selesai.
- Planned: belum dimulai.
- Conditional: hanya dikerjakan setelah inspeksi membuktikan kebutuhan.

## Completed Foundation

- Laravel 13 application foundation.
- Filament admin panel and admin access control.
- Scheduler heartbeat and cron runner support.
- Application clock and shared Core infrastructure.
- Domain event foundation.

### Repository Foundation

Completed capabilities:

- canonical repository entry documentation;
- canonical human-readable project state in `PROJECT_STATE.md`;
- machine-readable compatibility state in `PROJECT_STATE.json`;
- project manifest and AI handover rules;
- architecture and operational documentation;
- repository Single Source of Truth ADR;
- sprint documentation, validation evidence, commit, push, remote synchronization, deterministic governance checks, and verified GitHub Actions repository audit.

## Completed Domains

- Market management.
- Prediction management.
- Result management.
- Shio periods and number management.
- Shio banner templates and generation.
- Promotion management.
- Blog management.
- Live Draw management.

## Completed Public Frontend

- Public frontend layout.
- Home page.
- Prediction listing and detail.
- Result listing and detail.
- Promotion listing and detail.
- Blog listing and detail.
- Live Draw listing.
- HLS stream playback.

## Completed Repository Governance Phases

### Phase 0.3A — Canonical Repository Synchronization

Completed scope:

- synchronized canonical repository documentation with the verified completion state of Phase 0.2;
- recorded commit `5185ad7` as the Phase 0.2 completion commit;
- recorded successful GitHub Actions repository audit verification;
- kept Feature Freeze active;
- made no application behavior changes;
- completed canonical synchronization at commit `45c4e5d`, synchronized with `origin/main`.

## Next Recommended Phase

### Phase 0.3B — Canonical Repository Validation

Objective: extend deterministic repository validation only where repository evidence identifies a remaining governance gap.

Constraints:

- no application behavior changes;
- no duplicate validation without repository evidence;
- preserve Feature Freeze;
- complete with tests, documentation, commit, push, remote verification, and a clean working tree.

### Site Configuration Foundation

Planned capabilities:

- centralized site identity;
- site name and public branding;
- default SEO metadata;
- contact and social links;
- frontend navigation configuration;
- footer configuration;
- operational settings that should not be hardcoded.

Requirements:

- settings must be editable from admin;
- defaults must remain safe when settings are absent;
- secrets must remain in environment configuration;
- settings must be cached safely;
- tests must cover fallback behavior.

## Planned Media Foundation

Objective: provide centralized, responsive, and secure media handling.

Planned capabilities:

- upload source;
- direct image URL source;
- approved embed provider source where required;
- focal point and alignment selection;
- automatic responsive sizes;
- automatic ratio and crop presets;
- thumbnail generation;
- provider whitelist;
- embed sanitization;
- media validation and fallback behavior.

Admin must not manually manage pixel dimensions, breakpoints, or technical transformation settings.

## Planned Frontend Completion

Conditional tasks after Site Configuration and Media Foundation:

- improve home page composition;
- implement remaining navigation destinations;
- add reusable frontend sections;
- improve responsive presentation;
- improve empty states and fallback states;
- audit public metadata and canonical behavior;
- add structured data where justified.

Do not create placeholder routes or unsupported content types without implementation and tests.

## Planned Operational Improvements

- application test/build CI expansion beyond the verified repository governance audit workflow;
- automated application test execution for pull requests or pushes;
- production deployment checklist validation;
- backup automation documentation and verification;
- queue worker monitoring;
- scheduler monitoring;
- log rotation and storage review;
- application health checks.

## Planned Security Improvements

- dependency vulnerability review;
- upload and external media threat review;
- content sanitization review;
- authorization audit across Filament resources;
- rate limiting review;
- production header review;
- secret rotation procedures;
- backup access controls.

## Planned Quality Improvements

- test coverage audit by domain;
- duplicate query and business logic review;
- database index review based on actual query patterns;
- N+1 query audit;
- accessibility audit;
- responsive layout audit;
- performance profiling after realistic data volume is available.

## Conditional Future Features

These features require explicit business requirements before implementation:

- Slot Gacor content management.
- Alat Togel tools.
- configurable public menu builder.
- additional content modules.
- public API.
- multi-language content.
- notification delivery.
- external data integrations.

Do not infer detailed behavior for these features from menu labels alone.

## Sprint Selection Rules

The next sprint must:

- start from repository inspection;
- solve one clear objective;
- avoid repeating completed work;
- identify dependencies and migration impact;
- include tests and documentation;
- end with one commit and clean working tree.

Priority order:

1. finish work already marked In Progress;
2. resolve verified defects or production risks;
3. complete foundations needed by multiple modules;
4. implement approved business features;
5. perform conditional optimization only after measurement.

## Roadmap Maintenance

Update this file when:

- a planned phase starts;
- a sprint is completed;
- priorities change;
- a new dependency or risk is discovered;
- implementation proves an assumption incorrect.

Keep `PROJECT_STATE.md`, `PROJECT_STATE.json`, `PROJECT_MANIFEST.md`, sprint documentation, and this roadmap consistent.

<!-- BRAND-1-BASELINE-START -->

## Mandatory Brand 1 Completion Gate

Before multi-brand production activation, complete and validate the Brand 1 baseline defined in:

- `docs/product/BRAND_1_FRONTEND_BASELINE.md`

Required capability groups:

1. Complete public navigation and responsive frontend.
2. Live Draw, Result, and Prediction regression stability.
3. Slot Gacor / RTP.
4. Bukti Jackpot.
5. Keluhan ticket workflow.
6. Panduan content workflow.
7. Jadwal Togel.
8. BBFS Generator.
9. Buku Mimpi.
10. Paito Togel Warna.
11. Konversi Angka SGP.
12. Tabel Shio.
13. Complete Filament administration.
14. Scheduler, queue, cache, event, audit, SEO, backup, and health automation.
15. Brand 1 production-readiness test suite.

Multi-brand is an extension milestone after this gate, not a replacement for it.

<!-- BRAND-1-BASELINE-END -->
<!-- PROJECT-BRAIN-V1-START -->
## Active Delivery Overlay — Brand 1 Maximum 30-Day Window

The detailed active plan is maintained in `docs/delivery/BRAND-1-30-DAY-PLAN.md`.

- Days 1–3: baseline, dependency/security review, blocker register.
- Days 4–8: Brand Context, isolation, configuration, theme, menu.
- Days 9–12: SEO and responsive media foundation.
- Days 13–17: authorization and security hardening.
- Days 18–21: queue, scheduler, monitoring, backup, restore, rollback.
- Days 22–25: regression, performance, responsive/accessibility, dependency triage.
- Days 26–28: staging release candidate and production-gate rehearsal.
- Days 29–30: controlled production release and stabilization.

Deadline: 2026-08-23. P0 security, data-integrity, backup, migration, or release failures block deployment.

After Brand 1:

1. optimization and hardening;
2. Owner Panel;
3. Brand 2–5 activation;
4. controlled enterprise capabilities such as Brand Wizard, extension API, trusted plugins, installer/updater, marketplace, and advanced AI assistance.
<!-- PROJECT-BRAIN-V1-END -->
