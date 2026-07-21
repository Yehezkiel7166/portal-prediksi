# Project State

This is the canonical human-readable project state. [PROJECT_STATE.json](PROJECT_STATE.json) is retained as a machine-readable compatibility artifact.

## Project Status

- Status: active; Feature Freeze applies outside the approved repository-governance phase.
- Current phase: Phase 0 — Repository Foundation and Governance.
- Current milestone: Phase 0.1 — Repository Governance Refactoring.
- Repository authority: Git repository on `main`.
- Last synchronized commit at phase start: `e3dbc3d` (`docs(repository): synchronize project state`), matching `origin/main` on 2026-07-21.

## Completed Modules

- Core infrastructure and scheduler heartbeat
- Market administration
- Prediction administration and public listing/detail
- Result administration, latest-result resolver, and public listing/detail
- Shio periods, numbers, templates, and banner generation
- Promotion administration and public listing/detail
- Blog administration and public listing/detail
- Live Draw administration, public page, lifecycle automation, latest-result integration, and HLS playback
- Repository Foundation and repository consistency audit

## Work State

### In Progress

- Phase 0.1 governance synchronization, ADR normalization, canonical state documents, link audit, and release validation.

### Pending

- Phase 0.2 repository automation and enforcement identified by the Phase 0.1 sprint record.
- Site Configuration Foundation.
- Centralized responsive media management.
- Repository CI verification and deployment automation.
- Repository-managed backup automation.

### Blockers

- No product blocker is recorded.
- CI, automated deployment, and automated backup are not yet established repository capabilities.

## Readiness Status

- Architecture status: modular Laravel domain structure is implemented and documented; ADR numbering/index governance is normalized in Phase 0.1.
- Documentation status: canonical manifest, project state, sprint state, repository rules, workflow, ADR index, and Phase 0.1 record are established; historical records remain preserved.
- Testing status: the last recorded Repository Foundation full suite passed with 174 tests and 480 assertions; Phase 0.1 validation results are recorded in its sprint document.
- Deployment status: documented manual deployment process; repository pipeline automation is pending.

## Resume Instructions

1. Read [START_HERE.md](START_HERE.md), then [PROJECT_MANIFEST.md](PROJECT_MANIFEST.md).
2. Confirm branch, HEAD, tracking branch, synchronization, and a clean working tree.
3. Read [SPRINT_STATE.md](SPRINT_STATE.md), [REPOSITORY_RULES.md](REPOSITORY_RULES.md), and [WORKFLOW.md](WORKFLOW.md).
4. Review [ARCHITECTURE.md](ARCHITECTURE.md), the [ADR index](docs/architecture/README.md), and the relevant sprint records.
5. Do not begin feature work while Feature Freeze applies or while repository evidence conflicts.
6. Start with the Phase 0.2 candidate recorded in [SPRINT_STATE.md](SPRINT_STATE.md), using one scoped sprint and one logical commit.
