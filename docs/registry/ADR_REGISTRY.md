# ADR Registry

Version: 1.0

Status: Canonical

---

# Purpose

This registry serves as the master index for every Architecture Decision
Record (ADR) within the Portal Prediksi CMS repository.

Architecture Decision Records document important technical and
architectural choices that have long-term impact on the platform.

This registry enables every ADR to be located, reviewed, and traced
throughout the project's lifecycle.

The registry does not replace ADR documents.

Each ADR remains an independent document containing complete context,
alternatives, consequences, and rationale.

---

# Objectives

The ADR Registry exists to:

- provide a centralized ADR index;
- improve architectural traceability;
- connect architecture with implementation;
- preserve historical decisions;
- simplify future maintenance;
- support onboarding;
- support governance.

---

# ADR Identifier

Every Architecture Decision Record receives a permanent identifier.

Format

ADR-001

ADR-002

ADR-003

...

Identifiers are never reused.

Deprecated ADRs remain archived.

---

# ADR Lifecycle

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

# ADR Categories

## Platform

Core platform architecture.

Examples:

- Project structure
- Application layers
- Shared services

---

## Domain

Business domain architecture.

Examples:

- Domain boundaries
- Bounded contexts
- Domain ownership

---

## Security

Security architecture.

Examples:

- Authentication
- Authorization
- MFA
- Session model

---

## SEO

SEO architecture.

Examples:

- Metadata
- Canonical
- Entity strategy
- SERP protection

---

## Infrastructure

Infrastructure decisions.

Examples:

- Queue
- Cache
- Deployment
- Monitoring

---

## Engineering

Development workflow.

Examples:

- Repository-first
- Documentation-first
- Versioning
- Release process

---

# ADR Record Structure

Each ADR should contain:

- ADR ID
- Title
- Status
- Context
- Problem Statement
- Decision
- Alternatives Considered
- Consequences
- Related Decisions
- Related Capabilities
- Related Features
- Related Sprint
- Date

---

# ADR Registry

| ADR ID | Title | Category | Status |
|---------|-------|----------|--------|
| ADR-001 | Repository First Development | Engineering | Accepted |
| ADR-002 | Documentation Before Implementation | Engineering | Accepted |
| ADR-003 | Repository as Single Source of Truth | Engineering | Accepted |
| ADR-004 | Modular Platform Architecture | Platform | Accepted |
| ADR-005 | Layered Architecture Strategy | Platform | Accepted |
| ADR-006 | Domain-driven Structure | Domain | Accepted |
| ADR-007 | Feature Freeze v1.0 | Product | Accepted |
| ADR-008 | Registry-based Knowledge Organization | Engineering | Accepted |
| ADR-009 | Capability-oriented Planning | Platform | Accepted |
| ADR-010 | Security-first Administration | Security | Accepted |
| ADR-011 | SEO as Core Platform Capability | SEO | Accepted |
| ADR-012 | Permanent Knowledge Base Repository | Engineering | Accepted |

---

# Relationship

ADR

↓

Decision Registry

↓

Capability Registry

↓

Feature Registry

↓

Sprint Registry

↓

Implementation

---

# Governance Rules

Every significant architectural decision should have an ADR.

Every ADR should appear in this registry.

ADRs are immutable after acceptance.

If architecture changes, create a new ADR.

Do not overwrite historical ADRs.

Historical context must always be preserved.

---

# Superseded ADRs

Superseded ADRs remain in the registry.

Their status changes to:

Superseded

The replacement ADR should be referenced.

Historical ADRs must never be deleted.

---

# Change Policy

New ADRs must:

- receive a permanent identifier;
- describe the architectural context;
- explain the decision;
- document alternatives;
- describe consequences;
- reference related capabilities and decisions.

---

# Canonical Reference

Referenced by:

- ARCHITECTURE_DECISION_RECORDS.md
- DECISION_REGISTRY.md
- CAPABILITY_REGISTRY.md
- FEATURE_REGISTRY.md
- MASTER_ARCHITECTURE.md
- IMPLEMENTATION_STRATEGY.md
- ENGINEERING_WORKFLOW.md

This document is part of the permanent Project Knowledge Base.
