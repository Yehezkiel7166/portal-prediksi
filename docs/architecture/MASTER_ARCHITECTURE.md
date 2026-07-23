# MASTER ARCHITECTURE

Version: 1.0

---

# Purpose

This document defines the canonical architecture of Portal Prediksi CMS.

All implementation decisions must conform to this architecture unless superseded by a future ADR.

# Architecture Authority

This document is the highest-level technical architecture reference for Portal Prediksi CMS.

It defines:

- System-wide architectural boundaries.
- Dependency direction.
- Domain ownership principles.
- Integration rules.
- Architectural governance.
- Constraints that apply across all modules.

Detailed architecture documents may expand these rules but must not contradict them.

# Decision Precedence

When architectural guidance conflicts, the following precedence applies:

1. Approved Architecture Decision Records.
2. Feature Freeze v1.0.
3. Project Constitution.
4. Master Architecture.
5. Specialized architecture documents.
6. Module documentation.
7. Implementation code.

Implementation code must not be treated as architectural authority when it conflicts with approved documentation.

Any conflict must be resolved in documentation before implementation continues.

# Architecture Scope

This architecture governs:

- The Laravel application.
- Administrative interfaces.
- Public-facing delivery.
- Domain modules.
- Shared platform services.
- Persistence and caching.
- Background processing.
- Internal and external integrations.
- Testing boundaries.
- Deployment-related application structure.

Infrastructure provisioning details may be documented separately but must respect the boundaries defined here.
---

# Architectural Goals

- Modular domain-driven structure.
- High maintainability.
- Testability.
- Extensibility.
- Low coupling.
- High cohesion.
- Repository-first development.
- Documentation-driven engineering.

---

# High-Level Layers

Presentation

↓

Application

↓

Domain

↓

Infrastructure

↓

Persistence
## Layer Responsibilities

### Presentation Layer

Responsible for all user-facing delivery mechanisms.

Includes:

- Web routes
- Controllers
- Blade views
- API resources
- Request validation entry points
- Authentication entry points

Business rules must never originate here.

---

### Application Layer

Coordinates use cases.

Responsibilities include:

- Executing application actions.
- Transaction orchestration.
- Invoking domain services.
- Dispatching domain events.
- Coordinating cross-domain workflows.

Application services should remain thin and contain minimal business logic.

---

### Domain Layer

The Domain Layer represents the business core of the system.

Each domain owns:

- Entities
- Value Objects
- Domain Services
- Policies
- Business Rules
- Specifications
- Contracts

All business decisions must be implemented here.

---

### Infrastructure Layer

Provides technical implementations required by the application.

Examples include:

- Database repositories
- Cache adapters
- Queue implementations
- Notification providers
- Storage providers
- Search integrations
- External APIs

Infrastructure depends on Domain contracts, never the reverse.

---

### Persistence Layer

Responsible for durable storage.

Includes:

- Database schema
- Migrations
- Eloquent persistence
- Read models
- Indexes
- Cache persistence strategy

Persistence must not contain business logic.
---

# Domain Modules

Core

Market

Prediction

Result

Shio

Promotion

Blog

Live Draw

Future modules must integrate without breaking existing domains.
## Module Responsibilities

### Core

The Core module provides platform-wide capabilities shared by every domain.

Responsibilities:

- Authentication
- Authorization
- User Management
- Roles & Permissions
- Configuration
- Feature Flags
- Audit Logging
- Shared Contracts
- Shared Services
- Shared Utilities

The Core domain must never contain business rules that belong to other domains.

---

### Market

The Market module owns every prediction market available in the platform.

Responsibilities:

- Market registration
- Market metadata
- Country / Region configuration
- Operating schedule
- Draw schedule
- Opening & Closing status
- Market lifecycle

Market does not own prediction content or result publication.

---

### Prediction

The Prediction module owns prediction management.

Responsibilities:

- Prediction creation
- Prediction validation
- Prediction publishing
- Revision history
- Approval workflow
- Visibility rules
- Prediction archive

Prediction may consume Market information but never modifies Market configuration.

---

### Result

The Result module owns official draw results.

Responsibilities:

- Result publication
- Result verification
- Result synchronization
- Result correction
- Historical archive
- Result visibility

Result is the single authoritative source of draw outcomes.

---

### Shio

The Shio module owns zodiac-related business knowledge.

Responsibilities:

- Zodiac mapping
- Calendar mapping
- Conversion rules
- Lookup services
- Reference datasets

Shio remains completely isolated from infrastructure concerns.

---

### Promotion

The Promotion module owns promotional assets.

Responsibilities:

- Campaign management
- Landing pages
- Promotional banners
- Featured content
- Announcement management

Promotion must not contain editorial articles.

---

### Blog

The Blog module owns editorial publishing.

Responsibilities:

- Articles
- Categories
- Tags
- Authors
- Editorial workflow
- SEO metadata

Blog content remains independent from Promotion.

---

### Live Draw

The Live Draw module owns real-time draw presentation.

Responsibilities:

- Live sessions
- Streaming metadata
- Draw timeline
- Live status
- Replay references
- Broadcast scheduling

Live Draw consumes Result data but never owns Result data.

---

### Future Domains

Every new domain introduced after Feature Freeze v1.0 must:

- Define explicit ownership.
- Declare dependencies.
- Avoid duplicated business rules.
- Preserve backward compatibility.
- Be fully documented before implementation begins.
---

# Core Responsibilities

Core contains shared abstractions and infrastructure.

Examples:

- Clock
- Scheduler
- Events
- Shared Services
- Common Contracts
- Utilities
## Core Architecture

The Core domain provides platform-wide capabilities shared across every module.

Core serves as the foundation of the application and must remain independent from business-specific domains.

### Responsibilities

- Authentication
- Authorization
- User Management
- Role Management
- Permission Management
- Configuration Management
- Feature Flag Management
- Audit Logging
- Event Dispatching
- Scheduling
- Shared Services
- Shared Contracts
- Shared Utilities

### Ownership

Core owns:

- Platform configuration
- Security policies
- Shared abstractions
- Shared contracts
- Cross-cutting services
- Global configuration

### Dependencies

Core should have minimal dependencies.

Whenever possible Core should depend only on framework abstractions and internal contracts.

Business domains may depend on Core.

Core must never depend on business domains.

### Constraints

Core must never contain:

- Prediction logic
- Market logic
- Result logic
- Promotion logic
- Blog logic
- Live Draw logic

### Design Principles

Core should remain:

- Stable
- Lightweight
- Highly reusable
- Framework-friendly
- Easily testable

Changes to Core should be considered high impact because every module depends on it.
---

# Domain Responsibilities

Each domain owns:

- Models
- Actions
- Services
- Policies
- Validation
- Tests
- Documentation

Business rules must stay inside their respective domains.
## Domain Ownership Model

Every domain is responsible for its own business capabilities.

A domain owns:

- Business rules
- Domain services
- Entities
- Value Objects
- Validation rules
- Policies
- Specifications
- Events
- Documentation
- Automated tests

### Domain Boundaries

Every domain must expose a clearly defined public interface.

Internal implementation details must remain private to the domain.

Domains communicate only through:

- Public contracts
- Domain events
- Application services

Direct access to another domain's internal implementation is prohibited.

### Business Rule Isolation

Business rules must never be duplicated across multiple domains.

If multiple domains require the same capability:

- Move it into Core if it is platform-wide.
- Create an explicit shared contract.
- Expose a dedicated service.

Copying business logic between domains is prohibited.

### Dependency Direction

Dependencies must always point inward.

Presentation

↓

Application

↓

Domain

↓

Infrastructure

↓

Persistence

No lower layer may introduce business decisions.

### Domain Lifecycle

Every domain should evolve independently whenever possible.

Adding a new capability should not require modifications across unrelated domains.

### Documentation Requirements

Each domain must maintain:

- Architecture documentation
- Module documentation
- Public contracts
- Change history
- Test coverage

Documentation must be updated before implementation is considered complete.
---

# Cross-Domain Communication

Cross-domain communication should occur through:

- Domain events
- Explicit services
- Contracts

Avoid hidden dependencies.
## Communication Principles

Domain communication must remain explicit, traceable, and loosely coupled.

Hidden dependencies between domains are strictly prohibited.

### Allowed Communication Mechanisms

Domains may communicate through:

- Application Services
- Domain Events
- Published Contracts
- Repository Interfaces
- Query Services
- Integration Adapters

Communication through undocumented shortcuts is prohibited.

### Domain Events

Domain Events should be used when:

- A business action has completed.
- Another domain may react independently.
- Multiple downstream consumers are expected.

Events must describe completed business facts.

Examples:

- PredictionPublished
- ResultPublished
- MarketOpened
- MarketClosed
- PromotionActivated

Events should never expose infrastructure details.

### Synchronous Communication

Direct service calls should only be used when:

- Immediate responses are required.
- Transaction consistency is necessary.
- Business workflows require deterministic execution.

Synchronous communication should remain minimal.

### Asynchronous Communication

Long-running operations should be handled asynchronously.

Examples include:

- Notifications
- Cache rebuilding
- Search indexing
- Analytics processing
- Reporting
- Background synchronization

### Dependency Rules

Domains must depend on contracts instead of concrete implementations.

Infrastructure provides implementations.

Business domains consume abstractions.

### Anti-Patterns

The following practices are prohibited:

- Circular dependencies
- Direct database access across domains
- Shared mutable state
- Hidden service calls
- Cross-domain model mutation
- Business logic inside infrastructure

### Documentation

Every cross-domain dependency must be documented.

Every published event must define:

- Publisher
- Consumers
- Payload
- Trigger
- Expected behavior
---

# Architecture Constraints

- No circular dependencies.
- No duplicated business logic.
- No direct infrastructure coupling inside domains.
- No undocumented architectural changes.
## Architectural Governance

All architectural decisions must preserve the integrity of the repository.

Architecture must evolve intentionally rather than incrementally through undocumented implementation changes.

### Dependency Constraints

The following dependency direction is mandatory:

Presentation

↓

Application

↓

Domain

↓

Infrastructure

↓

Persistence

Reverse dependencies are prohibited.

Business domains must never depend on infrastructure implementations.

### Source of Truth

Repository documentation is the authoritative source for architectural decisions.

Implementation must follow documentation.

Implementation must not redefine architecture.

When implementation and documentation differ, documentation must be updated first through the approved governance process.

### Architectural Integrity

Every architectural change must:

- Preserve module boundaries.
- Preserve dependency direction.
- Preserve backward compatibility whenever practical.
- Minimize coupling.
- Maximize cohesion.

### Prohibited Practices

The following are prohibited:

- Circular module references.
- Shared business logic across domains.
- Hidden framework dependencies.
- Business rules inside controllers.
- Business rules inside repositories.
- Business rules inside infrastructure services.
- Cross-domain database manipulation.
- Direct access to another domain's internal implementation.

### Exception Process

Architectural exceptions require:

- Documented justification.
- Impact assessment.
- Repository review.
- Future refactoring plan.
- Architecture Decision Record (ADR).

Temporary shortcuts must never become permanent architecture.

### Review Requirements

Every architectural pull request should verify:

- Dependency direction.
- Module ownership.
- Documentation consistency.
- Naming consistency.
- Testability.
- Backward compatibility.
- Repository structure compliance.
---

# Evolution Strategy

Architecture evolves through ADRs.

Repository documentation must remain synchronized with implementation.

Changes must preserve backward compatibility whenever practical.
## Repository Evolution

The repository is the Single Source of Truth for Portal Prediksi CMS.

Architecture evolves through documentation before implementation.

Every implementation must be traceable to documented architectural decisions.

Repository evolution follows this order:

1. Vision
2. Constitution
3. Master Architecture
4. Platform Architecture
5. Domain Architecture
6. Module Documentation
7. Implementation Strategy
8. Registry
9. Product Documentation
10. Source Code

Implementation must never skip undocumented architectural decisions.

---

## Documentation Lifecycle

Every document progresses through the following lifecycle:

Draft

↓

Review

↓

Approved

↓

Implemented

↓

Maintained

↓

Deprecated

Only approved documentation may be implemented.

Deprecated documentation must remain available for historical reference until officially removed.

---

## Change Management

Every architectural change should answer:

- Why is the change needed?
- Which modules are affected?
- Which dependencies change?
- What migration strategy is required?
- What backward compatibility concerns exist?
- Which documents require updates?

Architectural changes should minimize disruption to existing modules.

---

## Versioning Strategy

Architecture documentation uses semantic versioning principles.

Major versions indicate architectural redesign.

Minor versions introduce backward-compatible architectural improvements.

Patch versions clarify documentation without changing architectural intent.

Every version change should include:

- Version number
- Date
- Summary
- Author
- Related ADRs

---

## Success Criteria

The architecture is considered successful when:

- Every business capability belongs to exactly one domain.
- Dependencies remain unidirectional.
- Module ownership is unambiguous.
- Business rules remain isolated.
- Repository documentation remains synchronized.
- New developers can understand the architecture from documentation alone.
- New modules can be introduced with minimal impact.
- Long-term maintenance remains predictable.

---

## Architectural Principles Summary

The Portal Prediksi CMS architecture is founded upon the following principles:

- Repository First
- Documentation Driven
- Domain Driven
- Modular by Design
- Explicit Dependencies
- High Cohesion
- Low Coupling
- Contract-Based Integration
- Independent Domain Evolution
- Testability
- Maintainability
- Extensibility
- Backward Compatibility
- Predictable Change Management

These principles apply to every module, package, service, and future enhancement within the repository.

---

<!-- MASTER-PROMPT-V2-ARCHITECTURE-ALIGNMENT-START -->
# Master Prompt v2.0 Architecture Alignment

Status: Active

This amendment extends the existing architecture rules. It does not replace
valid architecture definitions already present in this document.

## Multi-Brand Runtime Context

Every brand-sensitive execution must operate with an explicit Brand Context.

Every market-sensitive execution must operate with an explicit Market Context.

Context may be established through:

- approved hostname resolution;
- authenticated administration scope;
- an explicit application-service argument;
- a queue job payload;
- a scheduled execution definition;
- an approved command option.

Context must remain stable during one request, command, job, or scheduled
execution.

Unknown or inactive hostnames must fail closed. They must never silently resolve
to Brand #1.

## Ownership Model

Platform-owned data includes shared platform security, configuration, audit,
queue, scheduler, and automation infrastructure.

Brand-owned data includes brand content, brand configuration, brand media,
navigation, SEO configuration, complaints, guides, promotions, blog content,
and jackpot proof records.

Market-owned data includes markets, draw schedules, results, predictions, live
draw operational state, and approved source configuration.

Shared reference data may include deterministic Shio, Buku Mimpi, and conversion
reference definitions.

Shared engines may provide BBFS, Paito, conversion, scheduling, and automation,
but shared engines must not create shared ownership of brand data.

## Brand-to-Market Authorization

A brand may access market-owned data only through an explicit authorization
relationship.

Authorization must be enforced for:

- Result;
- Prediction;
- Live Draw;
- Paito;
- market schedules;
- market-specific automation.

Possession of a market identifier is not sufficient authorization.

## Persistence and Query Isolation

Brand-owned records must carry explicit brand ownership.

Market-owned records must carry explicit market ownership.

Brand and market isolation must not depend only on controller filters.

Isolation must be enforced through appropriate combinations of:

- policies;
- application services;
- repositories;
- query scopes;
- database constraints;
- foreign keys;
- automated tests.

## Cache Context

Brand-sensitive cache keys must include Brand Context.

Market-sensitive cache keys must include Market Context.

Cache entries affected by both contexts must include both identifiers.

Data cached for one brand or market must never be returned under another
context.

## Queue Context

Queue jobs requiring brand-sensitive behavior must carry a stable brand
identifier.

Queue jobs requiring market-sensitive behavior must carry a stable market
identifier.

A job must reconstruct and validate its required context before invoking the
owning module.

Queue jobs must not depend on request-session state.

## Scheduler Context

The scheduler discovers due work but must not duplicate business rules owned by
modules.

Scheduled definitions must identify, where applicable:

- owning module;
- brand;
- market;
- timezone;
- cadence;
- overlap policy;
- retry behavior;
- idempotency expectations;
- failure behavior.

## Automation Boundary

Automation is a shared orchestration capability.

Automation may coordinate Result, Prediction, Live Draw, and RTP through their
public application contracts.

Automation must not directly modify another module's persistence model or
duplicate another module's business rules.

Automation execution must preserve brand and market context and provide:

- retries;
- terminal failure handling;
- execution history;
- health state;
- structured logging;
- idempotency support.

## Module Boundary Additions

The mandatory architecture also recognizes:

- RTP as an operational module;
- Jackpot Proof as brand-owned content;
- Complaint as a brand-owned workflow;
- Guide as brand-owned content;
- BBFS as a deterministic lottery tool;
- Buku Mimpi as an approved reference module;
- Paito as Result-derived presentation;
- Converter as a deterministic conversion module;
- Automation as shared orchestration;
- Media as storage infrastructure;
- SEO as reusable presentation infrastructure.

Media does not own the lifecycle of business records referencing media.

SEO does not own the originating business or content records.

## Mandatory Isolation Tests

Architecture-sensitive test coverage must include:

- hostname resolution;
- unknown-host rejection;
- Brand Context lifecycle;
- Market Context lifecycle;
- cross-brand read isolation;
- cross-brand write isolation;
- brand-to-market authorization;
- cache isolation;
- queue context reconstruction;
- scheduler context reconstruction;
- automation idempotency;
- automation retry and terminal failure behavior.
<!-- MASTER-PROMPT-V2-ARCHITECTURE-ALIGNMENT-END -->
