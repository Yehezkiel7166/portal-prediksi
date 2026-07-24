# IDEA REGISTRY

Version: 1.0

Status: Active

Owner: Project Owner

Last Review: 2026-07-21

---

# Purpose

This document is the canonical registry for every product, business, architecture, security, SEO, operational, administrative, and user-experience idea proposed for Portal Prediksi CMS.

The repository is the Single Source of Truth.

Ideas recorded in this registry must not depend on conversation history, assistant memory, or undocumented assumptions.

The Project Owner should never need to repeatedly explain an idea that has already been recorded here or in another canonical repository document.

---

# Registry Authority

This registry records ideas before they become approved features or implementation tasks.

An idea may later be promoted into:

- Product Vision
- Product Strategy
- Feature Registry
- Capability Registry
- Module Registry
- Domain Registry
- Architecture Decision Record
- Sprint Registry
- SEO documentation
- Governance documentation
- Implementation documentation

Promotion does not automatically remove the original idea entry.

The original entry remains available for historical traceability.

---

# Idea Capture Policy

Every meaningful project idea must be recorded when it is introduced.

This includes ideas related to:

- Product direction
- Business requirements
- Platform capabilities
- Administrative workflows
- Security
- Authentication
- Authorization
- User experience
- Branding
- Multi-brand behavior
- Content management
- SEO
- Domain architecture
- Data ownership
- External integrations
- Reporting
- Automation
- Infrastructure
- Deployment
- Testing
- Future modules

No idea may be implemented solely from conversation context.

Before implementation, the idea must exist in the repository.

---

# Idea Status

Every idea uses one of the following statuses.

## Captured

The idea has been recorded but has not been reviewed.

## Under Review

The idea is being evaluated for architecture, business value, dependencies, and risk.

## Approved

The idea is approved for planning but may not yet be scheduled.

## Planned

The idea has been assigned to a roadmap phase or sprint.

## Implementing

Implementation is currently active.

## Implemented

The idea has been implemented and validated.

## Deferred

The idea remains valid but is not included in the current roadmap.

## Rejected

The idea was reviewed and intentionally rejected.

## Superseded

The idea has been replaced by a newer decision or design.

---

# Priority Levels

## Critical

Required for platform integrity, security, or repository governance.

## High

Important for Version 1.0 or core business operations.

## Medium

Provides meaningful value but is not required for the first stable release.

## Low

Optional enhancement or future optimization.

---

# Canonical Idea Table

| ID | Idea | Category | Priority | Status | Target | Canonical Reference |
|---|---|---|---|---|---|---|
| IDEA-0001 | Treat the repository as the Single Source of Truth for all project decisions and implementation instructions. | Governance | Critical | Approved | Foundation | PROJECT_CONSTITUTION.md |
| IDEA-0002 | Record every meaningful project idea in the repository so the Project Owner does not need to repeat it in future conversations. | Governance | Critical | Approved | Foundation | docs/registry/IDEA_REGISTRY.md |
| IDEA-0003 | Use documentation-first engineering before implementation begins. | Governance | Critical | Approved | Foundation | docs/architecture/IMPLEMENTATION_STRATEGY.md |
| IDEA-0004 | Preserve Feature Freeze v1.0 until documentation, repository foundation, and mandatory modules are ready. | Product | Critical | Approved | Version 1.0 | docs/product/FEATURE_FREEZE_V1.md |
| IDEA-0005 | Build Portal Prediksi CMS using modular domain-driven architecture with explicit ownership and low coupling. | Architecture | Critical | Approved | Version 1.0 | docs/architecture/MASTER_ARCHITECTURE.md |
| IDEA-0006 | Support multiple brands through one centralized CMS platform. | Product | High | Approved | Version 1.0 | docs/product/PRODUCT_VISION.md |
| IDEA-0007 | Keep business logic isolated inside its owning domain and prohibit duplicated business rules. | Architecture | Critical | Approved | Version 1.0 | docs/architecture/DOMAIN_MAP.md |
| IDEA-0008 | Develop the repository through small, reviewable, testable milestones. | Engineering | High | Approved | All Phases | docs/product/PRODUCT_ROADMAP.md |
| IDEA-0009 | Use registries for modules, domains, routes, permissions, events, configuration, features, capabilities, decisions, ADRs, ideas, and sprints. | Governance | High | Approved | Foundation | docs/registry/ |
| IDEA-0010 | Maintain backward compatibility whenever practical during repository evolution. | Architecture | High | Approved | All Phases | docs/governance/VERSIONING_POLICY.md |

---

# Product and Platform Ideas

## IDEA-0011 — Centralized Multi-Brand Administration

Category: Product

Priority: High

Status: Approved

Target: Version 1.0

Description:

Portal Prediksi CMS should manage multiple brands from a centralized platform while maintaining clear ownership and access boundaries.

Expected capabilities:

- Central administration
- Owner-level administration
- Brand-level administration
- Brand-specific content
- Brand-specific configuration
- Brand-specific users
- Brand-specific permissions
- Shared platform services
- Controlled data isolation

Related documents:

- Product Vision
- Module Catalog
- Domain Map
- Permission Registry

---

## IDEA-0012 — Stable Shared Login Experience

Category: User Experience

Priority: High

Status: Captured

Target: Platform Foundation

Description:

The administrative login page should remain simple, consistent, predictable, and operationally stable across all brands.

The login structure must not be rebuilt independently for each brand.

Fixed elements include:

- Page structure
- Form layout
- Element positioning
- Typography hierarchy
- Input behavior
- Validation behavior
- Primary action button
- MFA flow
- Recovery flow
- Responsive behavior

Brand customization must not compromise consistency or security.

Potential brand-configurable elements include:

- Brand name
- Brand logo
- Limited accent color
- Background asset
- Support information
- Legal or operational notices

An automatic theme builder is not included in the approved direction.

Required follow-up:

- Create a UI architecture document.
- Define allowed brand customization boundaries.
- Define login component contracts.
- Define accessibility requirements.

---

## IDEA-0013 — Role-Based Authentication Security Levels

Category: Security

Priority: Critical

Status: Captured

Target: Platform Foundation

Description:

Login and account security must use different controls based on the risk level of each role.

The platform should not apply one identical authentication policy to every user type.

Required role groups:

- Central Admin
- Owner
- Brand Admin
- Operator
- Content Editor

Required follow-up:

- Create an authentication security architecture document.
- Create an ADR for role-based security policy.
- Register security configuration.
- Register security permissions.
- Define recovery and escalation workflows.

---

## IDEA-0014 — Brand Admin and Operator IP Security

Category: Security

Priority: Critical

Status: Captured

Target: Platform Foundation

Description:

Brand Admin and Operator access should primarily use IP-based security controls.

Expected capabilities:

- Single trusted IP
- Multiple trusted IP addresses
- CIDR range support
- Optional access schedules
- Trusted network management
- IP change notifications
- Approval workflow for whitelist changes
- Owner or Central approval
- Audit trail
- Temporary exception process
- Emergency access handling

IP restrictions must remain configurable without embedding business rules in controllers or middleware.

Required follow-up:

- Define trusted-access domain ownership.
- Define IP normalization and CIDR validation.
- Define approval lifecycle.
- Define denied-access logging.
- Define proxy and forwarded-header policy.

---

## IDEA-0015 — Owner and Central Admin Strong Authentication

Category: Security

Priority: Critical

Status: Captured

Target: Platform Foundation

Description:

Owner and Central Admin accounts require stronger security than operational roles.

Mandatory or expected capabilities:

- Mandatory MFA
- Passkey or WebAuthn support
- Recovery codes
- Trusted device management
- Session management
- Device visibility
- Session revocation
- Step-up authentication
- Sensitive-action confirmation
- Authentication event logging
- Security notifications

Password-only authentication is not considered sufficient for these roles.

Required follow-up:

- Create an ADR for authentication mechanisms.
- Define passkey lifecycle.
- Define MFA enrollment policy.
- Define recovery process.
- Define trusted-device expiration.
- Define step-up authentication triggers.

---

## IDEA-0016 — Explicit User, Role, and Permission Ownership

Category: Authorization

Priority: Critical

Status: Approved

Target: Platform Foundation

Description:

Authorization must use explicit roles and permissions.

The platform must avoid authorization logic based on hidden assumptions, UI visibility, route names, or hard-coded user identifiers.

Expected capabilities:

- Central roles
- Owner roles
- Brand roles
- Operator roles
- Content roles
- Permission inheritance policy
- Scoped permissions
- Brand ownership enforcement
- Sensitive-action permissions
- Permission auditability

Related documents:

- Permission Registry
- Route Registry
- Module Catalog

---

# Business Domain Ideas

## IDEA-0017 — Market Management

Category: Business Domain

Priority: High

Status: Approved

Target: Version 1.0

Description:

The Market domain owns prediction market configuration.

Expected capabilities:

- Market identity
- Market metadata
- Country or region
- Timezone
- Operating schedule
- Draw schedule
- Open and closed state
- Publication state
- Lifecycle management
- Ordering
- Availability rules

The Market domain must not own prediction content or official result data.

---

## IDEA-0018 — Prediction Management

Category: Business Domain

Priority: High

Status: Approved

Target: Version 1.0

Description:

The Prediction domain owns prediction content and its lifecycle.

Expected capabilities:

- Prediction creation
- Market association
- Scheduled publication
- Draft workflow
- Validation
- Editing
- Approval
- Publishing
- Visibility
- Revision history
- Archive
- Public listing
- Filtering
- Detail pages

Prediction logic must use centralized time abstractions where required.

---

## IDEA-0019 — Result Management

Category: Business Domain

Priority: High

Status: Approved

Target: Version 1.0

Description:

The Result domain is the authoritative source for official draw outcomes.

Expected capabilities:

- Result creation
- Result validation
- Result publication
- Correction workflow
- Verification
- Market association
- Historical archive
- Public listing
- Latest-result resolution
- Live Draw consumption

Live Draw may consume Result data but must not own official result data.

---

## IDEA-0020 — Shio Management

Category: Business Domain

Priority: High

Status: Approved

Target: Version 1.0

Description:

The Shio domain owns zodiac mapping and related reference knowledge.

Expected capabilities:

- Shio entities
- Number mapping
- Calendar mapping
- Reference datasets
- Administrative management
- Banner templates
- Banner generation
- Public presentation support

Shio business rules must remain independent from image storage and rendering infrastructure.

---

## IDEA-0021 — Promotion Management

Category: Content Domain

Priority: High

Status: Approved

Target: Version 1.0

Description:

The Promotion domain manages promotional campaigns and assets.

Expected capabilities:

- Promotion creation
- Campaign status
- Publication schedule
- Banner assets
- Landing content
- Ordering
- Featured promotion
- Brand scope
- Public listing
- Public detail

Promotion content must remain separate from Blog editorial content.

---

## IDEA-0022 — Blog Management

Category: Content Domain

Priority: High

Status: Approved

Target: Version 1.0

Description:

The Blog domain manages editorial publishing.

Expected capabilities:

- Articles
- Categories
- Tags
- Authors
- Drafts
- Editorial workflow
- Scheduled publishing
- SEO metadata
- Public listing
- Public detail
- Related articles
- Archive

---

## IDEA-0023 — Live Draw Management

Category: Operational Domain

Priority: High

Status: Approved

Target: Version 1.0

Description:

The Live Draw domain manages real-time draw presentation and automation.

Expected capabilities:

- Live sessions
- Market association
- Scheduling
- Live status
- Streaming metadata
- HLS playback
- Draw timeline
- Latest-result integration
- Replay references
- Public Live Draw pages
- Background automation
- Result user interface integration

Live Draw must consume official result data through documented contracts.

---

# SEO Ideas

## IDEA-0024 — SEO as a Platform Capability

Category: SEO

Priority: High

Status: Approved

Target: Version 1.0

Description:

SEO must be implemented as a centralized platform capability rather than duplicated independently inside every content module.

Expected capabilities:

- Metadata generation
- Canonical management
- Robots directives
- Sitemap generation
- Structured data
- Open Graph
- Twitter metadata
- Slug management
- Redirect management
- Indexation policy

Related documents:

- SEO Architecture
- SEO Standards
- SEO URL Policy

---

## IDEA-0025 — Brand Search Protection

Category: SEO

Priority: High

Status: Captured

Target: Future Planning

Description:

The platform should support brand protection and search-result defense strategies.

Potential capabilities:

- Brand entity consistency
- Official brand profile data
- Canonical brand identity
- Controlled title and description templates
- Brand keyword monitoring
- SERP occupancy planning
- Impersonation monitoring
- Brand protection content
- Official access-link management

Required follow-up:

- Complete BRAND_PROTECTION.md.
- Complete ENTITY_MANAGEMENT.md.
- Complete SERP_DEFENSE.md.
- Complete SERP_OCCUPANCY.md.

---

## IDEA-0026 — SEO Intelligence

Category: SEO

Priority: Medium

Status: Captured

Target: Future Planning

Description:

The platform may provide internal SEO intelligence and reporting capabilities.

Potential capabilities:

- Keyword registry
- Main keyword tracking
- Derived keyword tracking
- Page-to-keyword mapping
- Search visibility monitoring
- Indexation monitoring
- Canonical diagnostics
- Sitemap diagnostics
- SERP observation
- Content opportunity identification

Required follow-up:

- Complete KEYWORD_INTELLIGENCE.md.
- Complete SEO_INTELLIGENCE.md.
- Complete SERP_INTELLIGENCE.md.

---

# Engineering Ideas

## IDEA-0027 — Repository-First Workflow

Category: Engineering

Priority: Critical

Status: Approved

Target: All Phases

Description:

Every development action must begin by inspecting and updating repository documentation.

Implementation instructions must be derived from the current repository state.

The assistant must not rely solely on earlier conversation context when repository evidence is available.

---

## IDEA-0028 — One Controlled Implementation Step at a Time

Category: Engineering

Priority: High

Status: Approved

Target: All Phases

Description:

Implementation should proceed through small, complete, verifiable steps.

Each step should include:

- Clear objective
- Exact files
- Complete content
- Validation command
- Expected result
- Repository status review

Large undocumented implementation jumps are prohibited.

---

## IDEA-0029 — Full Paste-Ready Documentation

Category: Engineering Workflow

Priority: High

Status: Approved

Target: All Documentation Work

Description:

When documentation must be created or replaced, instructions should provide complete paste-ready content.

The Project Owner should not be required to manually combine partial sections.

Long documents should be edited using `nano`.

Navigation instructions should reference headings rather than line numbers.

---

## IDEA-0030 — Complete Terminal Command Blocks

Category: Engineering Workflow

Priority: High

Status: Approved

Target: All Repository Work

Description:

Whenever terminal execution is required, instructions should provide one complete command block for the current step.

Commands should be safe, explicit, and reviewable.

Heredoc-based editing should be avoided for long markdown documents.

---

## IDEA-0031 — No Premature Commit

Category: Engineering Workflow

Priority: High

Status: Approved

Target: Foundation

Description:

Repository commits should not be created until the relevant documentation or implementation foundation has been completed and validated.

Before committing:

- Audit files.
- Remove accidental backups.
- Remove obsolete duplicates.
- Validate documentation.
- Review repository status.
- Confirm scope completion.

---

# Testing and Quality Ideas

## IDEA-0032 — Automated Testing Foundation

Category: Quality

Priority: High

Status: Approved

Target: Platform Foundation

Description:

The repository requires a stable automated testing foundation.

Expected coverage:

- Unit tests
- Feature tests
- Domain tests
- Integration tests
- Authorization tests
- Security policy tests
- Architecture boundary tests
- Repository consistency tests

Tests should not depend directly on uncontrolled system time.

---

## IDEA-0033 — Clock Abstraction

Category: Architecture

Priority: High

Status: Approved

Target: Version 1.0

Description:

Time-dependent business behavior should use an application clock abstraction.

Expected benefits:

- Deterministic tests
- Consistent timezone behavior
- Predictable scheduling
- Easier simulation
- Reduced direct framework time coupling

Related ADRs:

- ADR-0004
- ADR-0005

---

## IDEA-0034 — Event-Driven Cross-Domain Communication

Category: Architecture

Priority: High

Status: Approved

Target: Version 1.0

Description:

Cross-domain reactions should use documented domain events where immediate synchronous coupling is not required.

All published events must be registered in the Event Registry.

Events should describe completed business facts.

---

# Administrative and Operational Ideas

## IDEA-0035 — Audit Logging

Category: Operations

Priority: Critical

Status: Approved

Target: Platform Foundation

Description:

Sensitive administrative and business actions must produce audit records.

Potential audit events include:

- Login
- Login failure
- MFA enrollment
- Recovery usage
- Trusted-device changes
- IP whitelist changes
- Role changes
- Permission changes
- User activation and suspension
- Content publishing
- Result correction
- Configuration changes
- Feature flag changes

Audit records must be immutable from normal operational interfaces.

---

## IDEA-0036 — Configuration Management

Category: Platform

Priority: High

Status: Approved

Target: Platform Foundation

Description:

Platform configuration must be centrally managed and registered.

Configuration must distinguish between:

- Environment configuration
- Application configuration
- Brand configuration
- Security configuration
- Module configuration
- Feature flags
- Sensitive secrets

Secrets must never be exposed through normal administrative interfaces or committed to the repository.

---

## IDEA-0037 — Feature Flags

Category: Platform

Priority: Medium

Status: Approved

Target: Platform Foundation

Description:

Feature flags should control approved capabilities that require gradual activation, testing, rollback, or brand-specific availability.

Feature flags must not become a substitute for authorization or architecture.

---

# Deferred and Future Ideas

## IDEA-0038 — AI-Assisted Content Generation

Category: Future Capability

Priority: Low

Status: Deferred

Target: After Version 1.0

Description:

AI-assisted content generation may be evaluated after Version 1.0.

It is excluded from Feature Freeze v1.0.

Any future implementation requires:

- Product review
- Content quality policy
- Security review
- Approval workflow
- Auditability
- SEO quality controls
- New ADR where architectural impact exists

---

## IDEA-0039 — Mobile Application

Category: Future Capability

Priority: Low

Status: Deferred

Target: Future

Description:

Native or dedicated mobile applications are outside Feature Freeze v1.0.

The web architecture should avoid decisions that unnecessarily block future mobile API consumers.

---

## IDEA-0040 — Plugin or Extension Marketplace

Category: Future Capability

Priority: Low

Status: Deferred

Target: Future

Description:

A plugin or extension marketplace is outside Version 1.0.

Future evaluation must consider:

- Security isolation
- Version compatibility
- Extension contracts
- Permission boundaries
- Upgrade policy
- Review and approval process

---

# Idea Submission Template

New ideas should be appended using the following structure.

## IDEA-XXXX — Idea Title

Category:

Priority:

Status: Captured

Target:

Description:

State the complete intent of the idea.

Expected capabilities:

- Capability
- Capability
- Capability

Constraints:

- Constraint
- Constraint

Dependencies:

- Dependency
- Dependency

Required follow-up:

- Documentation update
- Registry update
- ADR when required
- Sprint planning when approved

Related documents:

- Document path

---

# Idea Promotion Workflow

Every captured idea follows this workflow:

Capture

↓

Classification

↓

Impact Analysis

↓

Architecture Review

↓

Product Review

↓

Approval or Deferral

↓

Canonical Documentation Update

↓

Registry Synchronization

↓

Roadmap or Sprint Assignment

↓

Implementation

↓

Validation

Ideas must not skip directly from conversation to implementation.

---

# Repository Synchronization Rules

When an idea is approved:

- Update its status in this registry.
- Update the appropriate product document.
- Update architecture documentation when boundaries change.
- Add or update an ADR when required.
- Update the Feature Registry.
- Update the Capability Registry.
- Update affected module and domain registries.
- Update permissions, routes, events, and configuration registries.
- Assign the work to a roadmap phase or sprint.
- Add implementation validation requirements.

---

# Conversation Continuity Policy

The repository must contain enough information to continue the project in a new conversation without requiring the Project Owner to repeat previously documented ideas.

At the beginning of future repository work:

1. Inspect the repository.
2. Read the Project Constitution.
3. Read the Master Architecture.
4. Read Feature Freeze v1.0.
5. Read this Idea Registry.
6. Read relevant registries and sprint documents.
7. Continue from the documented repository state.

Conversation history may provide context, but it must not replace repository documentation.

---

# Validation Checklist

Before the Documentation Foundation is declared complete, verify:

- No meaningful idea exists only in conversation history.
- Every approved Version 1.0 capability is documented.
- Security ideas are recorded.
- Multi-brand ideas are recorded.
- Login and authentication ideas are recorded.
- SEO ideas are recorded.
- Future ideas are recorded or explicitly deferred.
- Every idea has a unique identifier.
- Every approved idea has a canonical destination.
- Registry references are valid.
- No accidental backup files remain.
- No obsolete duplicate documents remain.

---

# Governance

This registry is governed by:

- PROJECT_CONSTITUTION.md
- docs/architecture/MASTER_ARCHITECTURE.md
- docs/architecture/IMPLEMENTATION_STRATEGY.md
- docs/governance/DOCUMENTATION_GOVERNANCE.md
- docs/governance/CHANGE_MANAGEMENT.md
- docs/product/FEATURE_FREEZE_V1.md

No idea may override approved architecture without following the Architecture Decision Record and Change Management processes.

---

<!-- MASTER-PROMPT-V2-IDEA-REGISTRY-START -->
# Master Prompt v2.0 — Registered Product Ideas

Status: Active

The entries below record the approved ideas discussed during Repository Alignment.
They do not replace earlier valid registry entries.

## Mandatory Brand #1 Ideas

| Idea ID | Idea | Classification | Ownership | Status |
|---|---|---|---|---|
| MP2-IDEA-001 | Complete Brand #1 frontend and administration experience | Product | Brand and Platform | Approved |
| MP2-IDEA-002 | Slot Gacor / RTP management and publication | Operations | Brand-configured | Approved |
| MP2-IDEA-003 | Jackpot Proof publishing and moderation | Content | Brand-owned | Approved |
| MP2-IDEA-004 | Complaint workflow and case tracking | Workflow | Brand-owned | Approved |
| MP2-IDEA-005 | Guide publishing and navigation | Content | Brand-owned | Approved |
| MP2-IDEA-006 | BBFS deterministic generator | Lottery Tool | Shared engine with brand configuration | Approved |
| MP2-IDEA-007 | Buku Mimpi search and reference content | Lottery Tool | Shared reference or brand content | Approved |
| MP2-IDEA-008 | Paito generation from confirmed Result data | Lottery Tool | Shared engine | Approved |
| MP2-IDEA-009 | Market draw schedule and timezone management | Operations | Market-owned | Approved |
| MP2-IDEA-010 | Deterministic Toto conversion tools | Lottery Tool | Shared engine | Approved |
| MP2-IDEA-011 | Core operational automation framework | Platform Operations | Shared platform | Approved |
| MP2-IDEA-012 | Automation execution history and health visibility | Platform Operations | Shared platform | Approved |
| MP2-IDEA-013 | Configuration-driven activation for Brand #2 through Brand #5 | Multi-Brand | Platform | Approved |
| MP2-IDEA-014 | Brand #1 stabilization gate before additional brand activation | Governance | Platform | Approved |
| MP2-IDEA-015 | Media ownership and isolation per brand | Platform | Brand-owned | Approved |
| MP2-IDEA-016 | Brand-aware cache, queue, and scheduler execution | Platform | Shared platform | Approved |

## Approved Future Ideas

| Idea ID | Idea | Classification | Status |
|---|---|---|---|
| MP2-IDEA-F001 | Telegram integration | Integration | Future |
| MP2-IDEA-F002 | Expanded analytics | Analytics | Future |
| MP2-IDEA-F003 | Mobile API | Integration | Future |
| MP2-IDEA-F004 | Public or partner REST API | Integration | Future |
| MP2-IDEA-F005 | Referral capability | Commercial | Future |
| MP2-IDEA-F006 | Cashback capability | Commercial | Future |
| MP2-IDEA-F007 | Marketplace capability | Platform Expansion | Future |

## Explicitly Deferred AI Ideas

| Idea ID | Idea | Classification | Status |
|---|---|---|---|
| MP2-IDEA-AI001 | AI prediction assistance | Intelligence | Deferred |
| MP2-IDEA-AI002 | AI RTP assistance | Intelligence | Deferred |
| MP2-IDEA-AI003 | AI article assistance | Intelligence | Deferred |
| MP2-IDEA-AI004 | AI operational anomaly detection | Intelligence | Deferred |

AI-assisted ideas do not replace mandatory deterministic automation.
<!-- MASTER-PROMPT-V2-IDEA-REGISTRY-END -->
<!-- PROJECT-BRAIN-V1-START -->
## Project Brain Idea Registry Pointer — 2026-07-24

The complete current strategic backlog is maintained in `docs/project-brain/IDEA_BACKLOG.md` and grouped into SEO/discovery, content operations, brand platform, Owner operations, AI assistance, ecosystem, and infrastructure.

Ideas do not enter implementation merely by being listed. They require architecture review, dependency mapping, security assessment, roadmap placement, and test/release criteria. Explicitly deferred or prohibited concepts are also preserved to prevent accidental reintroduction.
<!-- PROJECT-BRAIN-V1-END -->
