# PRODUCT ROADMAP

Version: 1.0

---

# Purpose

This document defines the long-term implementation roadmap for Portal Prediksi CMS.

The roadmap translates the approved architecture and product vision into sequential implementation milestones.

All roadmap items must remain consistent with Feature Freeze v1.0.

---

# Roadmap Principles

The roadmap follows these principles:

- Documentation First
- Repository First
- Architecture Before Code
- Incremental Delivery
- Backward Compatibility
- Small Reviewable Milestones
- Stable Foundation Before Expansion

---

# Phase 0

Repository Foundation

Objectives

- Repository organization
- Documentation foundation
- Architectural governance
- Registry completion
- Product documentation
- Development standards

Deliverables

- Constitution
- Architecture
- Registry
- Product documents
- Governance documents
- SEO documentation

Status

In Progress

---

# Phase 1

Platform Foundation

Objectives

Build the shared platform required by every business module.

Includes

- Authentication
- Authorization
- User Management
- Roles
- Permissions
- Configuration
- Audit Logging
- Feature Flags

Dependencies

Repository Foundation

Status

Planned

---

# Phase 2

Business Foundation

Objectives

Implement the primary business domains.

Implementation Order

1. Market
2. Prediction
3. Result
4. Shio

Dependencies

Platform Foundation

Status

Planned

---

# Phase 3

Content Foundation

Objectives

Implement content management modules.

Includes

- Promotion
- Blog

Dependencies

Business Foundation

Status

Planned

---

# Phase 4

Operations Foundation

Objectives

Implement operational capabilities.

Includes

- Live Draw
- Background Jobs
- Notifications
- Scheduled Tasks

Dependencies

Business Foundation

Status

Planned

---

# Phase 5

Integration

Objectives

Implement platform integrations.

Includes

- Search
- Cache
- Queue
- Storage
- External Services

Dependencies

Operations Foundation

Status

Planned

---

# Phase 6

Quality Assurance

Objectives

Prepare the system for production readiness.

Includes

- Unit Testing
- Feature Testing
- Integration Testing
- Architecture Review
- Documentation Review
- Performance Review

Dependencies

All previous phases

Status

Planned

---

# Phase 7

Production Readiness

Objectives

Prepare the first production release.

Includes

- Deployment Validation
- Release Notes
- Version Tagging
- Migration Review
- Security Review
- Backup Verification

Dependencies

Quality Assurance

Status

Planned

---

# Roadmap Governance

Roadmap changes require:

- Architecture review
- Documentation update
- Registry synchronization
- Product review

Roadmap changes must never bypass Feature Freeze governance.

---

# Success Criteria

The roadmap is considered complete when:

- Every planned phase is completed.
- Documentation remains synchronized.
- Architecture remains stable.
- Registry remains accurate.
- Repository standards are maintained.
- Every implementation milestone is traceable.

---

# Continuous Planning

After Version 1.0 is completed:

- Review completed milestones.
- Archive completed roadmap items.
- Create Version 1.1 roadmap.
- Re-evaluate priorities.
- Update architectural documentation before expanding functionality.

---

<!-- MASTER-PROMPT-V2-MILESTONE-ROADMAP-START -->
# Master Prompt v2.0 — Milestone-Based Product Roadmap

Status: Active

This roadmap supplements the existing phase roadmap by adding explicit product
milestones and exit criteria.

The existing implementation phases remain useful as execution groupings, but
product release decisions are governed by the milestones below.

## Roadmap Principles

- Repository First
- Documentation First
- Architecture Before Implementation
- Build for Many
- Release for One
- Stabilize Before Expansion
- Configuration Before Duplication
- Tests Before Production Acceptance
- No Repository Fork per Brand

## Milestone A — Repository Foundation

### Objective

Establish the repository as the Single Source of Truth.

### Required Outcomes

- Project Constitution established
- Project Manifest established
- Project State established
- Documentation Governance established
- Canonical Map established
- Registries established
- Architecture baseline established
- ADR process established
- Sprint governance established
- Test governance established

### Exit Criteria

- canonical document hierarchy is valid;
- no competing source of truth exists;
- repository rules govern implementation;
- documentation conflicts have an escalation path.

## Milestone A1 — Multi-Brand Foundation

### Objective

Create the shared technical foundation for multiple brands before expanding
business modules.

### Required Outcomes

- Brand UUID
- Brand model
- Brand Context
- Domain resolution
- Unknown-host safe failure
- Brand-aware authentication
- Owner access
- Brand Super Admin access
- Brand-to-market authorization
- Brand-aware persistence
- Brand-aware factories
- Brand-aware service boundaries
- Brand-aware cache strategy
- Brand-aware queues
- Brand-aware scheduler
- Brand-aware media ownership
- Audit context
- Five-brand-compatible data foundation

### Exit Criteria

- Brand #1 can resolve from its domain;
- unknown hosts do not resolve to Brand #1;
- cross-brand reads fail safely;
- cross-brand writes fail safely;
- brand-aware factories generate valid records;
- queue jobs preserve Brand Context;
- scheduled execution preserves Brand Context;
- Owner and Brand Admin scopes are tested.

## Milestone B — Brand #1 Ready

### Objective

Deliver a complete and operational Brand #1 experience on top of the shared
multi-brand foundation.

### Mandatory Modules

- Market
- Prediction
- Result
- Shio
- Promotion
- Blog
- Live Draw
- Slot Gacor / RTP
- Jackpot Proof
- Complaint
- Guide

### Mandatory Lottery Tools

- BBFS Generator
- Buku Mimpi
- Paito
- Jadwal
- Konversi Toto

### Mandatory Automation

- Live Draw automation
- Result automation
- Prediction automation
- RTP automation
- Scheduler
- Queue
- Retry handling
- Failure logging
- Execution history
- Health checks
- Brand and market context preservation

### Mandatory Product Surfaces

- Owner panel
- Brand administration panel
- Brand #1 public frontend
- Approved route ownership
- Brand navigation
- Brand metadata
- Brand sitemap behavior
- Brand canonical URL behavior
- Operational visibility
- Audit visibility
- Automation health visibility

### Exit Criteria

- all mandatory modules are registered;
- all mandatory capabilities are registered;
- all mandatory features are registered;
- all mandatory routes are registered;
- architecture and ADR requirements are complete;
- automated test suites pass;
- Brand #1 frontend acceptance passes;
- cross-brand isolation passes;
- brand-to-market authorization passes;
- scheduler and queue isolation pass;
- automation recovery behavior passes;
- SEO ownership passes;
- no mandatory capability depends on hard-coded Brand #1 logic.

## Milestone B1 — Brand #1 Stabilization

### Objective

Prove that Brand #1 is reliable in production-like operation before activating
additional brands.

### Stabilization Areas

- Domain resolution
- Authentication
- Authorization
- Database isolation
- Cache isolation
- Queue reliability
- Scheduler reliability
- Automation reliability
- Failure recovery
- Audit completeness
- Media ownership
- SEO behavior
- Frontend stability
- Performance
- Backup and restore
- Operational runbooks

### Exit Criteria

- no unresolved critical isolation defect;
- no unresolved critical authorization defect;
- no unresolved critical automation defect;
- production error behavior is understood;
- health checks are operational;
- recovery procedures are documented;
- backup and restore are verified;
- regression suite remains green;
- Brand #1 acceptance is formally recorded.

## Milestone C — Brand #2 Activation

### Objective

Validate that the system supports another brand without application or repository
forking.

### Activation Method

Brand #2 must be activated using configuration and approved data provisioning.

### Exit Criteria

- Brand #2 uses the same codebase;
- Brand #2 uses the same migrations;
- Brand #2 resolves through its own domain;
- Brand #2 has isolated content;
- Brand #2 has authorized market access;
- Brand #2 has isolated cache behavior;
- Brand #2 has isolated media;
- Brand #2 has isolated queue and scheduler context;
- Brand #1 behavior remains unchanged;
- no hard-coded Brand #2 condition is introduced.

## Milestone C1 — Brand #3 Through Brand #5 Activation

### Objective

Validate the five-brand-compatible foundation.

### Exit Criteria

- five brands can coexist;
- every brand has independent domain configuration;
- every brand has independent navigation and SEO configuration;
- every brand has explicit market authorization;
- cross-brand isolation remains valid;
- automation remains context-safe;
- operational monitoring can distinguish brands;
- new activation requires configuration rather than code duplication.

## Milestone D — Enterprise Operations

### Objective

Strengthen platform-wide administration and observability after multi-brand
validation.

### Candidate Scope

- Platform health dashboard
- Queue monitor
- Scheduler monitor
- Automation execution dashboard
- Failure and retry dashboard
- Media management
- Advanced audit review
- Cross-brand operational reporting
- Configuration validation
- Deployment readiness controls
- Backup and recovery operations

Candidate scope must be registered before implementation.

## Milestone E — Integration Expansion

### Objective

Add approved integrations without weakening module boundaries or tenancy.

### Candidate Scope

- External result sources
- External Live Draw sources
- Telegram integration
- Webhook integrations
- Extended analytics
- Public or partner APIs
- Mobile API

Every integration must define:

- ownership;
- authentication;
- authorization;
- rate limits;
- failure behavior;
- retries;
- idempotency;
- observability;
- brand and market context;
- data retention.

## Milestone F — Intelligence Expansion

### Objective

Introduce AI-assisted capabilities only after the deterministic platform and
automation foundation are stable.

### Candidate Scope

- AI prediction assistance
- AI RTP assistance
- AI article assistance
- AI content recommendations
- AI operational anomaly detection

AI capabilities must remain assistive unless a later approved requirement grants
autonomous authority.

AI output must not bypass:

- authorization;
- validation;
- publication workflow;
- audit logging;
- Brand Context;
- Market Context;
- human approval where required.

## Dependency Rules

Milestones must follow this dependency order:

Repository Foundation
↓
Multi-Brand Foundation
↓
Brand #1 Ready
↓
Brand #1 Stabilization
↓
Brand #2 Activation
↓
Brand #3 Through Brand #5 Activation
↓
Enterprise Operations
↓
Integration Expansion
↓
Intelligence Expansion

Later milestones may not be used to justify incomplete exit criteria in earlier
milestones.

## Sprint Mapping Rule

Each implementation sprint must declare:

- roadmap milestone;
- feature IDs;
- capability IDs;
- module IDs;
- affected architecture documents;
- relevant ADRs;
- acceptance criteria;
- required automated tests;
- brand-isolation impact;
- market-authorization impact;
- operational impact;
- documentation updates.

A sprint without canonical traceability is not implementation-ready.

## Roadmap Change Control

Roadmap changes require:

1. repository-recorded rationale;
2. registry impact review;
3. Feature Freeze impact review;
4. architecture impact review;
5. dependency review;
6. test impact review;
7. production impact review.

Conversation history alone does not modify the roadmap.
<!-- MASTER-PROMPT-V2-MILESTONE-ROADMAP-END -->

<!-- BEGIN BRAND-1-LISTING-PRESENTATION -->
## Brand 1 Frontend Listing Presentation

Brand 1 frontend completion includes reusable Card View and List View support
for applicable public listing modules.

Implementation MUST include:

- reusable display-mode toggle;
- module-specific card and list renderers;
- responsive desktop, tablet, and mobile behavior;
- safe visitor preference persistence;
- unchanged filtering, sorting, pagination, SEO, cache, authorization, and
  brand isolation;
- automated component and feature regression tests.

This capability MUST be stable before multi-brand production activation.
<!-- END BRAND-1-LISTING-PRESENTATION -->

<!-- BEGIN SEO-ENGINE-ROADMAP -->
## SEO Engine and SERP Operations

Brand 1 completion includes implementation of the canonical
[SEO Engine Specification](SEO_ENGINE_SPECIFICATION.md).

Delivery MUST cover:

1. SEO data model and manual locks;
2. brand and module templates;
3. evergreen title and description resolution;
4. slug and canonical management;
5. robots and sitemap automation;
6. redirect management;
7. structured data;
8. Open Graph and social metadata;
9. SERP preview;
10. SEO audit checks;
11. internal linking;
12. optional verified indexing integration;
13. optional verified SERP monitoring;
14. cache, queue, event, scheduler, and audit behavior;
15. complete regression and production verification.

Implementation MUST prioritize stable canonical pages over daily title or slug
mutation.

Automation MUST fill missing or invalid unlocked values, but MUST preserve
valid manually approved locked values.
<!-- END SEO-ENGINE-ROADMAP -->

<!-- BEGIN BRAND-1-ARCHITECTURE-FOUNDATIONS -->
## Brand 1 Architecture Foundations

Before full Brand 1 frontend implementation, the canonical architecture MUST
include:

1. Site Configuration Engine;
2. Media Engine;
3. Menu Engine;
4. Banner Engine;
5. Widget Engine;
6. AI Content Engine;
7. AI Governance.

Canonical specifications:

- `SITE_CONFIGURATION_ENGINE_SPECIFICATION.md`
- `MEDIA_ENGINE_SPECIFICATION.md`
- `MENU_ENGINE_SPECIFICATION.md`
- `BANNER_ENGINE_SPECIFICATION.md`
- `WIDGET_ENGINE_SPECIFICATION.md`
- `AI_CONTENT_ENGINE_SPECIFICATION.md`
- `AI_GOVERNANCE_SPECIFICATION.md`

Implementation MUST remain incremental.

These specifications MUST NOT be used to justify an unrestricted page builder,
unsafe arbitrary code, uncontrolled AI publishing, or premature multi-brand
production activation.
<!-- END BRAND-1-ARCHITECTURE-FOUNDATIONS -->
