# Sprint 16B — Brand Slot Gacor / RTP Foundation

## Status

RED — implementation contract established, implementation not started.

## Baseline

- Branch: `feat/domain-management-foundation`
- Baseline commit:
  `d9400d4962fb110a7d83acdc97756f795785ccaa`
- Working tree at start: clean
- Local and remote synchronized: yes

## Selected Capabilities

- `MP21-F008 — Brand Slot Gacor`
- `MP21-F009 — RTP Snapshot and History`

## Selection Evidence

The Sprint 16B read-only selection audit ranked Slot Gacor / RTP as the
highest-priority incomplete Brand 1 capability.

Repository implementation truth at sprint start:

- Brand Slot Gacor: `PLANNED`
- RTP Snapshot and History: `PLANNED`
- Brand Slot Gacor Engine: `PLANNED`
- RTP Engine: `PLANNED`
- no verified application implementation;
- no public RTP route;
- no RTP migrations;
- no RTP administration resource;
- no RTP scheduler integration.

## RED Contract

The initial implementation contract requires:

1. a Brand-owned Slot Gacor record;
2. immutable RTP snapshot/history records;
3. Brand isolation;
4. validated RTP values;
5. publication and active-state controls;
6. deterministic ordering;
7. a public `/slot-gacor` route;
8. a public Brand-scoped listing;
9. safe empty-state behavior;
10. canonical and Open Graph metadata;
11. administration workflow;
12. automated regression coverage.

## Scope Boundary

Sprint 16B must not falsely complete:

- Global Provider Catalog;
- Global Game Catalog;
- full RTP rotation automation;
- Theme Engine;
- Widget Engine;
- Owner Panel;
- Brand 2–5.

Those capabilities retain their existing repository statuses unless verified
implementation evidence is added.

## Mandatory Workflow

INSPECT → SYNC → RED → GREEN → REGRESSION → AUDIT →
CTO CROSSCHECK → COMMIT → PUSH → REMOTE VERIFY

## RED Decision

The contract test must fail against the current baseline because the selected
Slot Gacor / RTP foundation does not yet exist.

No commit or push is permitted during this RED phase.
