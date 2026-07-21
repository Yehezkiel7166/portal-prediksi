# Repository Rules

## Authority

- The Git repository is the Single Source of Truth (SSOT) for implementation, architecture, decisions, project state, sprint state, tests, and operations.
- Chat is a communication channel and is never the SSOT.
- Every work session starts by inspecting the current branch, HEAD, working tree, implementation, tests, and relevant documentation.

## Development Governance

- Use documentation-driven development: synchronize governing documents in the same logical change as the work they describe.
- Use architecture-driven development: respect documented domain boundaries and record material architectural changes before implementation.
- Use decision-driven development: capture durable, cross-cutting decisions in a uniquely numbered ADR.
- Use sprint-driven development: scope work in a sprint record with explicit status, validation, and handover.
- Do not create duplicate documents for the same authority. Update the canonical document and preserve historical records.
- One logical change equals one logical commit.
- Documentation must match the implementation. When they conflict, stop and reconcile repository evidence before continuing.
- Tests accompany implementation and must cover new or changed behavior.
- Feature Freeze must not be bypassed. Only work allowed by the active phase or an explicitly approved exception may proceed.
- Conflicts in requirements, architecture, repository state, or unowned working-tree changes stop implementation until resolved.

## Safety and History

- Historical documentation, ADRs, sprint records, migrations, and changelog entries are preserved. Corrections should be additive or clearly recorded.
- Secrets must never be committed, including `.env`, credentials, private keys, tokens, database dumps, logs containing sensitive data, and production backups.
- Never force-push or rewrite shared history as part of the normal workflow.
- Do not modify generated or runtime directories such as `vendor/`, `node_modules/`, `storage/`, or `bootstrap/cache/` as source changes.

## Canonical References

- Documentation map: [PROJECT_MANIFEST.md](PROJECT_MANIFEST.md)
- Current project state: [PROJECT_STATE.md](PROJECT_STATE.md)
- Current sprint state: [SPRINT_STATE.md](SPRINT_STATE.md)
- Engineering workflow: [WORKFLOW.md](WORKFLOW.md)
- Architecture: [ARCHITECTURE.md](ARCHITECTURE.md)
- ADR index: [docs/architecture/README.md](docs/architecture/README.md)
