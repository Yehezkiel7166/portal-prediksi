# Sprint 15A — Repository Brain Canonical Synchronization

Status: In Progress
Baseline branch: `feat/domain-management-foundation`
Baseline commit: `b702ef326147456d0e98ebb1ca8fbd4881f31d72`

## Objective

Synchronize project requirements, Master Prompt inheritance, registry
lifecycles, delivery direction, and machine-readable project state without
changing stable application behavior.

## Inspection Evidence

The repository inspection package identified:

- Master Prompt v2.0 and v2.1 inheritance was documented but distributed;
- current direction was corrected in Sprint 14C;
- `PROJECT_STATE.json` still contained historical branch, deadline, and phase
  values;
- PD-010 still described the superseded 30-day Brand 1 delivery window;
- requirements and ideas were spread across several project-brain and registry
  documents;
- implementation status must remain evidence-based.

## Scope

- Add canonical Master Prompt inheritance document.
- Add consolidated project requirements.
- Add registry lifecycle model.
- Mark obsolete 30-day decision as superseded.
- Synchronize machine-readable state.
- Add repository audit coverage for canonical Sprint 15 documents.
- Update manifest, changelog, sprint state, and AI handover.

## Non-Scope

- No application feature implementation.
- No database migration.
- No production deployment.
- No destructive deletion of historical documents.
- No Owner Panel implementation.

## Required Validation

- JSON syntax and canonical values.
- Shell syntax.
- Markdown local-link audit.
- Repository manifest audit.
- Current-direction audit.
- Full application regression before commit.
