# DOMAIN REGISTRY

Version: 1.0

---

# Purpose

The Domain Registry is the authoritative inventory of every business domain within Portal Prediksi CMS.

Each domain represents a bounded context with clearly defined ownership, responsibilities, dependencies, and lifecycle.

Every domain must be registered before implementation begins.

---

# Registry Rules

Every domain must define:

- Identifier
- Domain Name
- Category
- Business Owner
- Technical Owner
- Status
- Responsibilities
- Dependencies
- Public Interfaces
- Events
- Documentation
- Implementation Phase

---

# Domain Status

Allowed values:

- Planned
- Approved
- In Progress
- Implemented
- Deprecated
- Removed

---

# Domain Registry

| Domain | Category | Business Owner | Status | Depends On | Phase |
|---------|----------|----------------|---------|------------|-------|
| Core | Platform | Platform | Planned | Framework | Phase 2 |
| Market | Business | Market | Planned | Core | Phase 3 |
| Prediction | Business | Prediction | Planned | Market, Core | Phase 3 |
| Result | Business | Result | Planned | Market, Core | Phase 3 |
| Shio | Business | Shio | Planned | Core | Phase 3 |
| Promotion | Content | Promotion | Planned | Core | Phase 3 |
| Blog | Content | Editorial | Planned | Core | Phase 3 |
| Live Draw | Operations | Live Draw | Planned | Result, Market, Core | Phase 3 |

---

# Domain Responsibilities

Every registered domain must own:

- Business Rules
- Business Validation
- Domain Services
- Domain Events
- Public Contracts
- Documentation
- Automated Tests

No business capability may have multiple domain owners.

---

# Dependency Policy

Domains may depend only on documented upstream domains.

Allowed dependency direction:

Core

↓

Market

↓

Prediction

↓

Result

↓

Live Draw

Supporting domains:

- Blog
- Promotion
- Shio

depend only on Core unless otherwise approved through an ADR.

Reverse dependencies are prohibited.

---

# Public Interfaces

Each domain must document:

- Public Services
- Public Contracts
- Published Events
- Consumed Events
- Repository Interfaces
- External Integrations

Internal implementation details must never be considered public interfaces.

---

# Event Ownership

Every event must define:

- Publisher
- Consumers
- Payload
- Trigger
- Version

Events remain owned by the publishing domain.

---

# Documentation Requirements

Every domain must maintain:

- Architecture
- Responsibilities
- Dependency Diagram
- Public Interfaces
- Event Catalog
- Testing Strategy
- Change History

Documentation must be completed before implementation.

---

# Validation Checklist

Every domain should satisfy:

- Single ownership
- Explicit responsibilities
- Explicit dependencies
- Published documentation
- Approved implementation phase
- No duplicated business logic
- No circular dependency

---

# Governance

The Domain Registry must remain synchronized with:

- Master Architecture
- Platform Layers
- Module Catalog
- Domain Map
- Module Registry
- Implementation Strategy

A domain may not enter implementation until its registry entry has been reviewed and approved.
