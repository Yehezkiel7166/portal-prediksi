# Sprint Completion Gate

Status: **Mandatory and canonical**

## Repository Authority

The Git repository is the Single Source of Truth.

Chat history, remembered instructions, previous summaries, and assumptions are
non-authoritative until verified against the current repository state.

Every sprint must begin and end with a repository re-read.

## Mandatory Workflow

Every implementation and governance sprint must follow:

`INSPECT → SYNC → RED → GREEN → REGRESSION → AUDIT → CTO_CROSSCHECK → COMMIT → PUSH → REMOTE_VERIFY`

No stage may be silently skipped.

A skipped stage requires an explicit repository-recorded reason showing that the
stage is not applicable and that skipping it cannot reduce safety, correctness,
auditability, or delivery alignment.

## Start-of-Sprint Gate

Before design or modification begins, the AI must inspect the current repository
and verify at minimum:

1. active branch;
2. current HEAD;
3. remote tracking branch;
4. ahead/behind state;
5. clean working tree;
6. active sprint state;
7. relevant code and tests;
8. roadmap and delivery priority;
9. current project state;
10. applicable decisions, ideas, capabilities, domains, routes, permissions,
    events, modules, engines, and configuration registries;
11. current manifest and AI handover;
12. current Brand 1 usable milestone.

Repository evidence overrides remembered chat context.

## End-of-Sprint Repository Re-read

After implementation, regression, and audit, but before commit, the AI must
re-read the repository and crosscheck the resulting state.

The re-read must cover all existing and relevant artifacts, including:

- application code;
- automated tests;
- routes;
- migrations;
- console commands;
- scheduler definitions;
- queue behavior;
- configuration;
- security controls;
- architecture documentation;
- `PROJECT_STATE.md`;
- `PROJECT_STATE.json`;
- `SPRINT_STATE.md`;
- `ROADMAP.md`;
- `docs/product/PRODUCT_ROADMAP.md`;
- `PROJECT_MANIFEST.md`;
- `CHANGELOG.md`;
- `AI_HANDOVER.md`;
- the active sprint record;
- `DECISION_REGISTRY.md`;
- all registries under `docs/registry/`;
- `docs/governance/CURRENT_DIRECTION.md`;
- Brand 1 usable milestone requirements.

## Mandatory CTO Crosscheck

Every sprint requires a CTO-level crosscheck before commit.

The crosscheck must determine:

1. whether implementation matches documentation;
2. whether documentation matches implementation;
3. whether tests cover the implemented direction;
4. whether the roadmap still reflects the correct delivery order;
5. whether Project State and Sprint State are mutually consistent;
6. whether registries reflect actual implementation status;
7. whether the manifest lists all canonical artifacts;
8. whether the changelog records the sprint outcome;
9. whether AI Handover provides a correct continuation point;
10. whether security, migration, data integrity, queue, scheduler, backup,
    deployment, and rollback implications are addressed where applicable;
11. whether the sprint still supports the July 30, 2026 Brand 1 usable milestone;
12. whether the next proposed sprint remains the highest-value correct direction.

## Sprint Completion Evidence

Every completed sprint must include a CTO crosscheck report under:

`docs/sprints/crosschecks/`

The report must record:

- sprint identifier;
- baseline branch and commit;
- final branch and pre-commit HEAD;
- sprint objective;
- files and systems inspected;
- tests executed;
- regression result;
- repository governance audit result;
- implementation/documentation consistency result;
- registry consistency result;
- roadmap and milestone alignment result;
- known limitations;
- blockers;
- final CTO decision: `PASS` or `FAIL`.

A sprint with no crosscheck report may not be marked completed.

## Hard Completion Stops

A sprint must not be marked completed when any of the following is true:

- repository re-read was not completed;
- CTO crosscheck evidence is missing;
- code and documentation contradict each other;
- `PROJECT_STATE.md` and `PROJECT_STATE.json` contradict each other;
- Sprint State and the active sprint record contradict each other;
- roadmap priorities conflict with implementation direction;
- affected registries are not synchronized;
- implementation is documented as complete without supporting code or tests;
- implemented work remains documented as merely planned;
- mandatory regression failed;
- repository governance audit failed;
- security or data-integrity risks are unresolved;
- the working tree contains unexplained changes;
- branch or HEAD changed unexpectedly;
- local and intended remote branch are not synchronized after push;
- Brand 1 delivery priority was displaced without an explicit owner decision.

## Commit and Remote Verification

Commit is allowed only after the CTO crosscheck returns `PASS`.

After push, remote verification must confirm:

- local HEAD equals the intended remote branch HEAD;
- ahead/behind is `0 0`;
- the working tree is clean;
- the sprint completion report is present;
- all mandatory audits still pass.

Only then may the sprint be declared completed.
