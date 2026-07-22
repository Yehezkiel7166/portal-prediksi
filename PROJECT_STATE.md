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
