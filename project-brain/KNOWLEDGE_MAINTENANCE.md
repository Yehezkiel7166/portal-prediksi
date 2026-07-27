# Knowledge Maintenance

Status: Canonical

## Purpose

Keep code, decisions, status, roadmap, and operational knowledge synchronized so the repository can replace historical chat context.

## Classification

Every material item belongs to one or more categories:

- Vision
- Product capability
- Architecture decision
- Security control
- Delivery item
- Operational procedure
- Technical debt
- Idea backlog
- Release record

## Required metadata

New or materially changed knowledge should identify:

- status;
- owner or accountable role;
- effective date;
- dependencies;
- security impact where relevant;
- implementation or verification evidence;
- superseded document or decision where relevant.

## Synchronization rules

When a feature changes, update as applicable:

1. specialized specification;
2. feature or idea registry;
3. roadmap or delivery plan;
4. project state;
5. test documentation;
6. changelog;
7. ADR or decision record;
8. operational runbook.

## Status integrity

- Implemented means code exists.
- Verified means automated or documented verification has passed.
- Production means it is deployed and operationally accepted.
- Documentation must not mark a feature verified merely because a design exists.

## Review cadence

- Every sprint: synchronize changed capabilities and decisions.
- Weekly during Brand 1 delivery: review production blockers and status.
- Before release: full documentation, security, operations, and rollback review.
- After incident: update controls, runbooks, and decision history.

## Anti-duplication rule

Extend the existing canonical document when the subject already has an owner. New documents are created only when the subject has distinct scope and an index entry.
