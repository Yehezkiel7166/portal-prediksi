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
