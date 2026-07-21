# Engineering Workflow

## Required Sequence

`Inspect → Design → Patch → Syntax Check → Module Test → Full Test → Documentation → Git Clean Review → Commit → Push → Repository Audit`

1. **Inspect** the branch, HEAD, tracking state, working tree, relevant code, tests, documentation, and historical decisions.
2. **Design** the smallest change that satisfies the active sprint while preserving architecture and compatibility.
3. **Patch** only in-scope first-party files. Preserve unrelated work and historical records.
4. **Syntax Check** every changed executable or configuration file with the appropriate parser or linter.
5. **Module Test** the smallest relevant test set for fast, targeted feedback.
6. **Full Test** the complete automated suite before release.
7. **Documentation** synchronizes state, sprint, architecture, decisions, operations, and changelog as applicable.
8. **Git Clean Review** checks the diff, whitespace, scope, secrets, generated files, and unexpected behavior changes.
9. **Commit** one logical change with one accurate commit message.
10. **Push** non-destructively only when the branch safely tracks its intended remote and all required validation passes.
11. **Repository Audit** verifies remote synchronization, clean status, canonical state, and a complete handover.

## Definition of Done

Work is done only when the scoped objective is met; architecture and product rules are respected; syntax, module, and full tests pass; documentation matches implementation; the reviewed diff contains only intended changes; no secrets or generated dependencies are included; commit and safe push succeed when required; and the final repository audit is recorded.

## Commit Rules

- One logical change equals one logical commit.
- Do not mix unrelated cleanup, formatting, refactoring, or feature work.
- Commit messages describe the repository outcome.
- Never amend, force-push, or rewrite shared history without explicit release-owner authorization.
- Do not commit `.env`, secrets, dumps, backups, runtime output, `vendor/`, or `node_modules/`.

## Documentation Synchronization

Update canonical documents rather than adding competing summaries. At minimum, review `PROJECT_STATE.md`, `SPRINT_STATE.md`, the relevant sprint record, `PROJECT_MANIFEST.md`, `CHANGELOG.md`, and any affected architecture or operational document. `PROJECT_STATE.json` remains a machine-readable compatibility artifact and must not contradict `PROJECT_STATE.md`.

## Conflict Handling

Stop implementation when repository evidence conflicts with requirements, an architectural decision conflicts with the proposed design, an unknown working-tree change overlaps the task, a migration or release operation risks data loss, or branch tracking makes a push unsafe. Record the evidence and obtain the necessary product or owner decision; do not conceal or guess through the conflict.

## Rollback Expectations

- Prefer reversible, backward-compatible patches and roll-forward database recovery.
- Define rollback or recovery steps before deployment when a change can affect data or availability.
- Never edit an applied historical migration to simulate rollback.
- Restore from a verified backup when recovery requires destructive data replacement.
- A failed deployment must leave an auditable record and a synchronized project state.

## AI-Agent Rules

- Treat repository evidence as authoritative and chat context as non-authoritative.
- Inspect before editing and do not recreate completed work.
- Keep changes inside the active sprint and do not alter application behavior unless explicitly scoped.
- Preserve user changes, history, and secrets; never invent successful validation.
- Cite concrete files, commits, test output, and blockers in handover records.
- Do not bypass Feature Freeze, conflict stops, product decisions, or destructive-action approvals.

## Product-Decision Boundaries

Implementation must stop for decisions that change business rules, public behavior, data ownership or retention, security posture, supported integrations, destructive migration strategy, feature priority, or release acceptance criteria. Technical details within an approved design may be resolved by the technical lead when they preserve documented behavior and architecture.
