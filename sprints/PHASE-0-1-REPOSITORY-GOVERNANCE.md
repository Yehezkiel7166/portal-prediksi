# Phase 0.1 — Repository Governance Refactoring

## Status

- Phase: 0.1
- Type: repository governance and documentation refactoring
- Product behavior changes: none
- Current status: validation pending
- Feature Freeze: active

## Objective

Establish a consistent repository-governance foundation so that the repository remains the single source of truth for project state, sprint state, architecture decisions, implementation workflow, and future automation.

## Scope

This phase includes:

- Establishing `PROJECT_STATE.md` as the canonical project-state document.
- Establishing `SPRINT_STATE.md` as the canonical current-sprint summary.
- Adding `REPOSITORY_RULES.md`.
- Adding `WORKFLOW.md`.
- Adding an architecture decision record index.
- Normalizing ADR numbering so that every ADR identifier is unique.
- Auditing first-party Markdown links.
- Checking for merge markers and formatting errors.
- Running Laravel, Composer, and frontend validation.
- Committing and safely synchronizing the completed governance changes.

## Completed Work

- Added canonical project-state documentation.
- Added canonical sprint-state documentation.
- Added repository rules.
- Added repository workflow documentation.
- Added `docs/architecture/README.md` as the ADR index.
- Renumbered ADR files into a unique sequential series:

  - `ADR-0001-repository-single-source-of-truth.md`
  - `ADR-0002-update-safe-domain-evolution.md`
  - `ADR-0003-core-test-isolation.md`
  - `ADR-0004-clock-abstraction.md`
  - `ADR-0005-prediction-clock-adoption.md`
  - `ADR-0006-live-draw-architecture.md`

- Verified that no duplicate ADR identifiers remain.
- Verified that no repository references use the old ADR filenames.
- Verified that no unresolved merge markers remain.
- Verified that `git diff --check` reports no formatting errors.

## Pending Validation

The following validation must pass before this phase is complete:

- First-party Markdown link validation.
- Composer dependency and script validation.
- Laravel automated test suite.
- Frontend dependency and production build validation.
- Sensitive/generated path review.
- Final Git diff and scope review.
- Commit creation.
- Safe push to the configured remote.
- Final clean-working-tree and synchronization audit.

## Constraints

- No application behavior may be changed during this phase.
- No database schema or production configuration changes are allowed.
- Historical sprint records must remain preserved.
- Existing architecture decisions may be renumbered for consistency, but their substantive meaning must not be changed.
- Generated files, secrets, credentials, environment files, vendor dependencies, and runtime artifacts must not be committed.

## Known Limitations

- The hosting environment does not currently provide `python3`; repository validation must use available PHP, shell, Composer, Node, or project-native tooling.
- Codex sandbox execution was unavailable because the hosting environment could not initialize Bubblewrap correctly.
- Deployment and backup verification are outside this phase.
- Continuous-integration enforcement is deferred to Phase 0.2.

## Phase 0.2 Candidate Scope

Phase 0.2 should convert the governance checks from this phase into deterministic local and CI automation, including:

- Markdown link validation.
- ADR uniqueness validation.
- Canonical manifest coverage validation.
- Merge-marker detection.
- Secret and forbidden-path checks.
- Canonical project-state and sprint-state consistency checks.
- Composer and frontend validation entry points.
- CI enforcement without changing application behavior.

## Completion Criteria

Phase 0.1 is complete only when:

1. All governance documents exist and are internally consistent.
2. ADR numbering is unique and indexed.
3. All first-party Markdown links resolve.
4. Required backend and frontend validation passes.
5. No secrets, generated artifacts, or unrelated changes are included.
6. The changes are committed.
7. The commit is pushed safely.
8. The local working tree is clean and synchronized with the remote.
