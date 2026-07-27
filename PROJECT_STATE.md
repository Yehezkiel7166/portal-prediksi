# Project State

This is the canonical human-readable project state. [PROJECT_STATE.json](PROJECT_STATE.json) is retained as a machine-readable compatibility artifact.

## Project Status

- Status: active; Feature Freeze applies outside the approved repository-governance phase.
- Current phase: Phase 1 — EDPF Repository Alignment.
- Completed milestone: Phase 0.3B — Canonical Repository Validation.
- Next planned milestone: Phase 1.1 — EDPF Constitution and Feature Freeze Alignment.
- Repository authority: Git repository on `main`.
- Last verified repository commit: `a8a77a4` (`docs(repository): canonical repository validation baseline`), synchronized with `origin/main`.

## Completed Modules

- Core infrastructure and scheduler heartbeat
- Market administration
- Prediction administration and public listing/detail
- Result administration, latest-result resolver, and public listing/detail
- Shio periods, numbers, templates, and banner generation
- Promotion administration and public listing/detail
- Blog administration and public listing/detail
- Live Draw administration, public page, lifecycle automation, latest-result integration, and HLS playback
- Repository Foundation, repository consistency audit, and repository governance automation

## Work State

### Completed

- Phase 0.3A canonical repository synchronization completed.
- Phase 0.3B canonical repository validation completed at repository baseline `a8a77a4`.

### Pending

- Phase 1.1 EDPF repository alignment.
- Site Configuration Foundation.
- Centralized responsive media management.
- Application test/build CI expansion and deployment automation.
- Repository-managed backup automation.

### Blockers

- No product blocker is recorded.
- Application test/build CI, automated deployment, and automated backup are not yet established repository capabilities.

## Readiness Status

- Architecture status: modular Laravel domain structure is implemented and documented; ADR numbering/index governance is normalized in Phase 0.1.
- Documentation status: canonical manifest, project state, sprint state, repository rules, workflow, ADR index, and Phase 0.1–0.2 records are established; historical records remain preserved.
- Testing status: Laravel full suite passed with 174 tests and 480 assertions; repository governance audit passed with 5 checks; GitHub Actions repository audit verified successfully.
- Deployment status: documented manual deployment process; repository pipeline automation is pending.

## Resume Instructions

1. Read [START_HERE.md](START_HERE.md), then [PROJECT_MANIFEST.md](PROJECT_MANIFEST.md).
2. Confirm branch, HEAD, tracking branch, synchronization, and a clean working tree.
3. Read [SPRINT_STATE.md](SPRINT_STATE.md), [REPOSITORY_RULES.md](REPOSITORY_RULES.md), and [WORKFLOW.md](WORKFLOW.md).
4. Review [ARCHITECTURE.md](ARCHITECTURE.md), the [ADR index](docs/architecture/README.md), and the relevant sprint records.
5. Do not begin feature work while Feature Freeze applies or while repository evidence conflicts.
6. Begin Phase 1.1 EDPF Repository Alignment.
7. Read docs\/product\/FEATURE_FREEZE_V1.md.
8. Read docs\/sprints\/PHASE-1-1-EDPF-REPOSITORY-ALIGNMENT.md.
9. Repository evolution remains the highest priority until Master Prompt v2.0 alignment is complete.

---

## Result Validation Baseline

The repository follows RESULT_VALIDATION_POLICY.md.

Duplicate result values are permitted.

Only duplicate draw identities require administrator validation.

Correction history is mandatory.
<!-- PROJECT-BRAIN-V1-START -->
## Project Brain Integration — 2026-07-24

Status: Documentation foundation integrated into the current repository snapshot.

Current product priority:

1. Brand 1 production readiness no later than 2026-08-23, subject to mandatory release gates.
2. Brand 1 optimization and hardening.
3. Owner Panel.
4. Brand 2–5 activation.

Canonical planning and governance additions:

- `docs/project-brain/README.md`
- `docs/project-brain/MASTER_VISION.md`
- `docs/project-brain/WORKING_MODEL.md`
- `docs/project-brain/PROJECT_DECISIONS.md`
- `docs/project-brain/SYSTEM_BLUEPRINT.md`
- `docs/project-brain/FEATURE_CATALOG.md`
- `docs/project-brain/IDEA_BACKLOG.md`
- `docs/project-brain/KNOWLEDGE_MAINTENANCE.md`
- `docs/delivery/BRAND-1-30-DAY-PLAN.md`
- `docs/delivery/BRAND-1-PRODUCTION-GATE.md`
- `docs/security/THREAT_MODEL.md`
- `docs/security/SECURITY_CONTROL_MATRIX.md`

Immediate next execution stage: Days 1–3 baseline verification and blocker register, followed by Brand Context, brand isolation, site configuration, SEO/media, security, operations, quality, staging, and production gates.

This entry records documentation and planning state only. Individual capabilities retain their implementation status until code and tests provide verification evidence.
<!-- PROJECT-BRAIN-V1-END -->

<!-- MASTER-PROMPT-V2-1-SYNC-2026-07-24-STATE-START -->

## Master Prompt v2.1 Project State

- Canonical prompt: docs/governance/MASTER_PROMPT_V2_1.md
- Master Prompt v2.0 diwarisi sepenuhnya.
- Target delivery aktif: 90 hari.
- Owner dan Brand memiliki administration context terpisah.
- Owner mengelola Theme, Homepage Template, Widget, provider global, dan game global.
- Brand mengelola SEO, konten, Slot Gacor, RTP, Panduan, dan Keluhan.
- Panduan adalah konten publik Brand kepada pengunjung.
- Keluhan adalah laporan pengunjung kepada Brand.
- SEO mendukung MANUAL, AUTO, dan HYBRID.
- Seluruh otomatisasi aktif Master Prompt v2.0 tetap berlaku.
- Sinkronisasi dokumentasi tidak berarti seluruh engine sudah diimplementasikan.
- Tahap berikutnya adalah audit kode terhadap Master Prompt v2.1.
<!-- MASTER-PROMPT-V2-1-SYNC-2026-07-24-STATE-END -->

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

<!-- SPRINT-COMPLETION-GATE-START -->
## Permanent Sprint Completion Gate

Repository re-read is mandatory at the beginning of every sprint and again
after regression and audit but before commit.

Every sprint requires a CTO-level crosscheck covering code, tests, routes,
migrations, commands, scheduler, queue, configuration, security, architecture,
roadmap, Project State, Sprint State, manifest, changelog, AI handover, active
sprint evidence, all affected registries, and Brand 1 milestone alignment.

A sprint cannot be marked completed until its crosscheck report exists under
`docs/sprints/crosschecks/` and records a final decision of `PASS`.

Commit, push, and remote verification remain separate mandatory gates.
<!-- SPRINT-COMPLETION-GATE-END -->

<!-- SPRINT-15C-TRUTH-START -->
## Repository Truth — Sprint 15C

Current verified baseline:

- Branch: `feat/domain-management-foundation`
- Local and remote verified commit:
  `5ff3a08128e3f80f6dfef75fcaa26a5efa1cf3a7`
- Working tree at Sprint 15C start: clean
- Ahead/behind at Sprint 15C start: `0/0`
- Sprint 15B: completed
- Sprint Completion Gate: active
- Governance audit: 7/7 PASS
- Domain Management: implemented through Sprint 14B
- Domain test inventory: 15 files
- Latest targeted Domain regression: 169 tests, 512 assertions, PASS
- Project Brain state: canonical

Active work:

- Sprint 15C — Repository Truth Synchronization
- Application behavior changes: none
- Next product priority after Sprint 15C: Brand 1 usable completion
<!-- SPRINT-15C-TRUTH-END -->

<!-- SPRINT-15C-COMPLETED -->

## Sprint 15C Completion State

Sprint 15C — Repository Truth Synchronization was completed on 2026-07-26.

The canonical repository state, project brain, registries, roadmap, handover,
manifest, Sprint Completion Gate, and governance audit are synchronized.

No application behavior changed during Sprint 15C.

The next active product priority is Brand 1 usable completion under
`docs/delivery/BRAND-1-14-DAY-USABLE-PLAN.md`.

<!-- SPRINT-16A-PRODUCTION-HOMEPAGE-START -->

## Sprint 16A — Brand 1 Production Homepage

Status: Completed pending commit, push, and remote verification by the current
Sprint 16A finalization workflow.

Capability:

- `MP21-F017 — Brand 1 Production Homepage`
- Homepage Engine: `IMPLEMENTED`

Verified implementation:

- Brand-aware homepage content aggregation;
- explicit Brand isolation for Live Draw, Result, Prediction, Promotion,
  and Blog;
- safe empty homepage when Brand Context is unavailable;
- active Live Draw section;
- latest Result section;
- current Prediction section;
- active Promotion section;
- latest Article section;
- access to all ten mandatory Brand 1 public module groups;
- canonical metadata;
- Open Graph title, description, and URL;
- responsive public layout;
- automated regression coverage.

Validation evidence:

- Dedicated homepage suite: 6 tests / 50 assertions / PASS.
- Frontend suite: 72 tests / 312 assertions / PASS.
- Full project suite: 421 tests / 1,166 assertions / PASS.
- Permanent Sprint Completion Gate: PASS.
- Repository governance audit: 7/7 PASS.

Routes, migrations, commands, scheduler, queue, configuration, and dependencies
were not changed.

Homepage placeholders do not mark their backing engines as complete. Slot
Gacor / RTP, Jackpot Proof, Complaint, Guide, Theme Engine, Widget Engine,
and incomplete Lottery Tool modules retain their existing statuses.

<!-- SPRINT-16A-PRODUCTION-HOMEPAGE-END -->
