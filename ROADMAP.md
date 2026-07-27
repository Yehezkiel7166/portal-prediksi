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

<!-- SPRINT-15C-ROADMAP-START -->

## Post-Sprint 15B Execution Order

Current repository truth:

1. Domain Management is implemented through Sprint 14B.
2. Sprint 15B Permanent Sprint Completion Gate is completed.
3. Sprint 15C synchronizes repository truth without application behavior changes.
4. After Sprint 15C, resume Brand 1 usable implementation.
5. Owner Panel remains after Brand 1 completion and stabilization.

The next product sprint must select the highest-priority incomplete Brand 1
capability from implementation evidence. It must not reopen completed Domain
Management work without a verified regression gap.
<!-- SPRINT-15C-ROADMAP-END -->

<!-- SPRINT-16A-ROADMAP-START -->

## Sprint 16A Roadmap Update

`MP21-F017 — Brand 1 Production Homepage` is implemented.

The Homepage Engine now provides the verified Brand 1 public aggregation layer
and gateway to the mandatory public module groups.

This completion does not change the implementation state of Slot Gacor / RTP,
Jackpot Proof, Complaint, Guide, Theme Engine, Widget Engine, or incomplete
Lottery Tool modules.

Execution order after Sprint 16A:

1. preserve the implemented Homepage Engine;
2. select the next highest-priority incomplete Brand 1 capability;
3. finish Brand 1 before Owner Panel and Brand 2–5;
4. apply the permanent completion workflow to every sprint.

<!-- SPRINT-16A-ROADMAP-END -->

<!-- SPRINT-16D-ROADMAP-START -->
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
<!-- SPRINT-16D-ROADMAP-END -->

<!-- SPRINT-17B-ROADMAP-START -->
## Sprint 17B — Complaint Workflow Completion

Status: implementation package completed; server regression, commit, push, and remote verification are executed by the guarded Hostinger delivery script.

Delivered scope:
- Open → In Progress → Resolved / Rejected workflow;
- administrator response and internal notes;
- immutable brand-scoped status history;
- handler, first-response, review, and resolution timestamps;
- administrator email notification on submission;
- compatibility migration from legacy `reviewed` to `in_progress`.

Next product selection must be made only after Sprint 17B remote verification and a fresh implementation-truth cross-check.
<!-- SPRINT-17B-ROADMAP-END -->

<!-- SPRINT-18A-ROADMAP-START -->
## Current Brand 1 Sequence After Sprint 17B

1. Sprint 18A — Public Guide Foundation (implementation package prepared).
2. Lottery Tool delivery in evidence-based grouped increments.
3. Brand configuration, SEO, and operational minimum.
4. Release candidate and Brand 1 usable gate.

Owner Panel and Brand 2–5 remain blocked until Brand 1 passes the usable gate.
<!-- SPRINT-18A-ROADMAP-END -->

<!-- SPRINT-18B-START -->
## Sprint 18B — Jadwal Togel and Tabel Shio

Status: implementation package prepared pending server validation.

This increment adds the first two public lottery tools using existing canonical Market, Result, and Shio data. After completion, the remaining lottery tools are BBFS Generator, Buku Mimpi, Paito Togel Warna, and Konversi Angka SGP.
<!-- SPRINT-18B-END -->

<!-- SPRINT-18C-START -->
## Sprint 18C — BBFS Generator and Konversi Angka SGP

Status: implementation package prepared pending guarded server validation.

This increment adds two stateless deterministic lottery tools. BBFS normalizes 2–7 unique digits and produces ordered 2D, 3D, or 4D permutations without repeated digits. Konversi Angka SGP decomposes an exact four-digit input into AS, KOP, KEPALA, EKOR, 3D, and 2D according to rules shown on the public page.

After completion, the remaining mandatory lottery tools are Buku Mimpi and Paito Togel Warna.
<!-- SPRINT-18C-END -->


<!-- SPRINT-18D-START -->
## Sprint 18D — Buku Mimpi and Paito Togel Warna

Status: completed and remotely verified at commit `20838e11d52af369fcb5b6274d089cecfa57429e` with tree `b05daff4acad282035e8b171ee53611b22d9eceb`.

This increment completed the mandatory six-tool Brand 1 lottery suite. Buku Mimpi provides searchable repository-owned reference content, while Paito renders stable historical color output directly from canonical Result records with market/date filters and automatic cache versioning. Verification completed with 470 tests, 1336 assertions, governance audit 7/7, and completion gate PASS.
<!-- SPRINT-18D-END -->


<!-- SPRINT-18E-START -->
## Sprint 18E — Post-Implementation Truth Synchronization

Status: documentation-only synchronization prepared from the verified Sprint 18D baseline.

Objective: align canonical repository state with production truth and close the Brand 1 lottery-suite milestone without changing application behavior. After synchronization, the next approved implementation candidate is Site Configuration Foundation, subject to repository inspection and RED evidence.
<!-- SPRINT-18E-END -->

## Sprint 19A Increment Plan

Site Configuration Foundation is intentionally divided into deterministic increments:

1. **Sprint 19A-1 — Data Foundation:** brand-scoped persistence, safe resolver fallback, cache invalidation, and tests.
2. **Sprint 19A-2 — Filament Administration:** current-brand configuration editing with validation and authorization.
3. **Sprint 19A-3 — Frontend Integration:** shared identity, SEO defaults, contact/social links, and footer consumption.
4. **Sprint 19A-4 — Analytics and Structured Metadata:** non-secret identifiers, Open Graph defaults, and justified JSON-LD.
5. **Sprint 19A-5 — Completion Audit:** full cross-module regression, documentation truth sync, and production-readiness closure.

Each increment must pass the permanent completion gate before the next increment starts.

## Sprint 19A-2 — Filament Administration

Status: implementation package prepared from verified Sprint 19A-1 baseline.

Scope: current-brand-only configuration listing, create/edit workflow, validated site identity/SEO/contact/social/footer fields, singleton enforcement, and administrator access tests. Frontend consumption remains Sprint 19A-3.
