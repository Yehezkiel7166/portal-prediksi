# Registry Lifecycle Model

Status: Canonical
Effective date: 2026-07-25

## Idea Lifecycle

`Captured → Reviewed → Accepted → Planned → Implementing → Implemented → Extended`

Alternative terminal states:

- `Deferred`
- `Rejected`
- `Superseded`
- `Archived`

Ideas are never silently deleted.

## Requirement Lifecycle

- `Active`
- `Partially Implemented`
- `Implemented`
- `Superseded`
- `Retired`

## Feature and Capability Status

- `Implemented`: verified in application code and tests.
- `Partially Implemented`: foundation exists but requirement is incomplete.
- `Planned`: accepted and placed on roadmap.
- `Deferred`: accepted but intentionally postponed.
- `Future`: preserved without active scheduling.
- `Rejected`: reviewed and explicitly not adopted.
- `Superseded`: replaced by a newer registered decision.
- `Unknown`: evidence is insufficient.

Documentation alone does not qualify as implementation.

## Decision Lifecycle

`Draft → Proposed → Accepted → Implemented → Superseded → Archived`

Decision identifiers are permanent and never reused.

## Removal Rule

A document, idea, or decision may be physically removed only when:

1. it is a byte-for-byte or semantically exact duplicate;
2. its canonical replacement is recorded;
3. local links and manifest references are updated;
4. deletion history remains visible in Git;
5. governance audit passes.

Otherwise, mark it `SUPERSEDED` or `ARCHIVED`.
