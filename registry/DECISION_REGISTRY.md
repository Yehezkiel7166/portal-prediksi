# Decision Registry

Version: 1.0

Status: Canonical

---

# Purpose

This registry serves as the permanent index of all significant project
decisions made throughout the lifecycle of Portal Prediksi CMS.

A decision records an agreed direction that affects architecture,
product, engineering workflow, security, operations, SEO, or future
development.

The purpose of this registry is to ensure every important decision can
be discovered, traced, reviewed, and understood without relying on
conversation history.

This document is the canonical decision index for the project.

---

# Scope

The registry includes decisions related to:

- Product
- Architecture
- Engineering
- Security
- Infrastructure
- SEO
- Operations
- Repository Governance
- Development Workflow

Routine implementation choices are not recorded here unless they have
long-term architectural impact.

---

# Decision Identifier

Every decision receives a permanent identifier.

Format

DEC-001

DEC-002

DEC-003

...

Identifiers are never reused.

Deleted decisions remain archived.

---

# Decision Lifecycle

Draft

↓

Proposed

↓

Accepted

↓

Implemented

↓

Superseded

↓

Archived

---

# Decision Record Structure

Each decision should eventually contain:

- Decision ID
- Title
- Summary
- Status
- Date
- Owner
- Related ADR
- Related Capability
- Related Features
- Related Sprint
- Superseded By (optional)

---

# Decision Categories

## Product

Business direction.

Examples:

- Product scope
- Feature freeze
- Roadmap priorities

---

## Architecture

Platform structure.

Examples:

- Modular architecture
- Domain boundaries
- Layer separation

---

## Engineering

Development workflow.

Examples:

- Repository-first development
- Documentation-first approach
- Coding standards

---

## Security

Security policies.

Examples:

- MFA
- IP whitelist
- Session policy
- Access control

---

## SEO

Search strategy.

Examples:

- Brand protection
- Entity management
- SERP defense
- Canonical strategy

---

## Operations

Operational decisions.

Examples:

- Backup policy
- Monitoring
- Deployment workflow

---

# Decision Registry

| ID | Decision | Category | Status |
|----|----------|----------|--------|
| DEC-001 | Repository First Development | Engineering | Accepted |
| DEC-002 | Documentation Before Implementation | Engineering | Accepted |
| DEC-003 | Feature Freeze v1.0 | Product | Accepted |
| DEC-004 | Repository as Single Source of Truth | Engineering | Accepted |
| DEC-005 | Modular Architecture | Architecture | Accepted |
| DEC-006 | Domain-driven Platform Structure | Architecture | Accepted |
| DEC-007 | Layer Separation | Architecture | Accepted |
| DEC-008 | Documentation Governance | Engineering | Accepted |
| DEC-009 | ADR-based Architectural Decisions | Architecture | Accepted |
| DEC-010 | Permanent Knowledge Base Strategy | Product | Accepted |
| DEC-011 | Security-first Administration | Security | Accepted |
| DEC-012 | SEO as Core Platform Capability | SEO | Accepted |
| DEC-013 | Registry-based Knowledge Organization | Engineering | Accepted |
| DEC-014 | Sprint-driven Development Workflow | Engineering | Accepted |
| DEC-015 | Capability-oriented Planning | Architecture | Accepted |

---

# Relationship

Decision

↓

ADR

↓

Capability

↓

Feature

↓

Sprint

↓

Implementation

---

# Governance Rules

Every architectural decision should have a corresponding ADR.

Every strategic decision should appear in this registry.

A decision may reference multiple capabilities.

A decision may affect multiple features.

Decisions should remain immutable after acceptance.

If direction changes, a new decision should supersede the previous one
instead of modifying historical records.

---

# Superseded Decisions

Superseded decisions remain in this registry.

Status changes to:

Superseded

The replacement decision should be referenced.

Historical records must never be deleted.

---

# Change Policy

New decisions must:

- have a permanent identifier;
- define clear ownership;
- include implementation impact;
- reference related ADR where applicable;
- preserve historical traceability.

---

# Canonical Reference

Referenced by:

- ARCHITECTURE_DECISION_RECORDS.md
- IMPLEMENTATION_STRATEGY.md
- MASTER_ARCHITECTURE.md
- FEATURE_REGISTRY.md
- CAPABILITY_REGISTRY.md
- SPRINT_GUIDE.md
- ADR_REGISTRY.md

This document is part of the permanent Project Knowledge Base.
<!-- PROJECT-BRAIN-V1-START -->
## Project Brain Decisions — 2026-07-24

Canonical project-level decisions are recorded in `docs/project-brain/PROJECT_DECISIONS.md`.

Registered decisions:

- PD-001: Brand 1 → hardening → Owner Panel → Brand 2–5 → enterprise expansion.
- PD-002: Build multi-brand compatible, release Brand 1 first.
- PD-003: Repository is the permanent Project Brain.
- PD-004: Append-first and preserve history.
- PD-005: Security is a release gate.
- PD-006: Replace transitional broad admin access with brand-scoped policies/permissions.
- PD-007: Automation must be observable, retry-safe, and brand-aware.
- PD-008: AI remains assistive and cannot bypass sensitive approvals.
- PD-009: Future extensions require trusted packages, compatibility, permissions, audit, and rollback.
- PD-010: Brand 1 maximum delivery deadline is 2026-08-23 without waiving critical gates.
- PD-011: Commands are delivered in copy-paste-ready shell-specific blocks.
- PD-012: Repository completion claims require baseline and diff verification.
<!-- PROJECT-BRAIN-V1-END -->

<!-- CURRENT-DIRECTION-START -->
## Canonical Direction — 2026-07-25

- Project started on 2026-07-16.
- Brand 1 usable deadline is 2026-07-30.
- Overall project deadline is 2026-10-14.
- Brand 1 contains exactly 10 main modules and 6 lottery tools.
- Brand 1 is completed before Owner Panel and Brand 2–5.
- Domain Management is implemented through Commit 14B.
- The former active 30-day Brand 1 plan is superseded.
- Every sprint requires repository synchronization and CTO crosscheck.

Canonical reference:

- `docs/governance/CURRENT_DIRECTION.md`
- `docs/delivery/BRAND-1-14-DAY-USABLE-PLAN.md`
<!-- CURRENT-DIRECTION-END -->
