# MODULE REGISTRY

Version: 1.0

---

# Purpose

The Module Registry is the authoritative inventory of every functional module within Portal Prediksi CMS.

Every module must be registered before implementation begins.

No undocumented module may exist inside the repository.

---

# Registry Rules

Every module must define:

- Identifier
- Category
- Owner
- Status
- Responsibilities
- Dependencies
- Public Contracts
- Documentation
- Implementation Phase

---

# Module Status

Allowed status values:

- Planned
- Approved
- In Progress
- Implemented
- Deprecated
- Removed

---

# Registry

| Module | Category | Owner | Status | Depends On | Phase |
|---------|----------|-------|---------|------------|-------|
| Core | Platform | Platform Team | Planned | Framework | Phase 2 |
| Market | Business | Market Domain | Planned | Core | Phase 3 |
| Prediction | Business | Prediction Domain | Planned | Market, Core | Phase 3 |
| Result | Business | Result Domain | Planned | Market, Core | Phase 3 |
| Shio | Business | Shio Domain | Planned | Core | Phase 3 |
| Promotion | Content | Promotion Domain | Planned | Core | Phase 3 |
| Blog | Content | Blog Domain | Planned | Core | Phase 3 |
| Live Draw | Operations | Live Draw Domain | Planned | Result, Market, Core | Phase 3 |

---

# Registration Requirements

Every new module must provide:

## Identity

- Name
- Description
- Business Purpose

## Ownership

- Responsible Domain
- Maintainer
- Repository Location

## Dependencies

- Upstream Dependencies
- Downstream Consumers

## Documentation

- Architecture
- Domain Documentation
- API Documentation
- ADR References

## Testing

- Unit Tests
- Feature Tests
- Integration Tests

---

# Approval Workflow

New module

↓

Architecture Review

↓

Documentation Approval

↓

Registry Approval

↓

Implementation

↓

Testing

↓

Release

---

# Registry Validation

Every registry entry must satisfy:

- Unique ownership
- No duplicated responsibility
- Explicit dependencies
- Existing documentation
- Defined implementation phase

---

# Registry Governance

The Module Registry must always remain synchronized with:

- Master Architecture
- Platform Layers
- Module Catalog
- Domain Map
- Implementation Strategy

The registry is considered invalid whenever undocumented modules exist.

---

<!-- MASTER-PROMPT-V2-MODULE-REGISTRY-START -->
# Master Prompt v2.0 — Module Registry Alignment

Status: Active

Existing valid modules remain canonical. The following entries register the
additional bounded contexts and clarify ownership.

## Existing Module Classification

| Module ID | Module | Classification | Data Ownership | Status |
|---|---|---|---|---|
| MP2-MOD-001 | Core | Platform | Platform-owned | Existing |
| MP2-MOD-002 | Market | Business | Market-owned | Existing and expanded |
| MP2-MOD-003 | Prediction | Business | Market-owned with brand authorization | Existing |
| MP2-MOD-004 | Result | Business | Market-owned with brand authorization | Existing |
| MP2-MOD-005 | Shio | Shared Reference | Shared reference | Existing and clarified |
| MP2-MOD-006 | Promotion | Content | Brand-owned | Existing |
| MP2-MOD-007 | Blog | Content | Brand-owned | Existing |
| MP2-MOD-008 | Live Draw | Operations | Market-owned with brand authorization | Existing |

## Additional Mandatory Modules

| Module ID | Module | Classification | Data Ownership | Primary Dependencies | Milestone |
|---|---|---|---|---|---|
| MP2-MOD-101 | RTP | Operations | Brand-configured; history ownership defined by architecture | Core, Brand, Automation | B |
| MP2-MOD-102 | Jackpot Proof | Content | Brand-owned | Core, Brand, Media | B |
| MP2-MOD-103 | Complaint | Workflow | Brand-owned | Core, Brand, Authorization, Audit | B |
| MP2-MOD-104 | Guide | Content | Brand-owned | Core, Brand, Media, SEO | B |
| MP2-MOD-105 | BBFS | Lottery Tool | Shared engine with brand configuration | Core, Brand configuration | B |
| MP2-MOD-106 | Buku Mimpi | Lottery Tool | Shared reference or approved brand content | Core, Search, SEO | B |
| MP2-MOD-107 | Paito | Lottery Tool | Derived from market-owned Results | Result, Market, Brand authorization | B |
| MP2-MOD-108 | Converter | Lottery Tool | Shared deterministic engine | Core | B |
| MP2-MOD-109 | Automation | Platform Operations | Platform execution records with brand and market context | Core, Queue, Scheduler, Audit | B |
| MP2-MOD-110 | Media | Platform Service | Brand-owned resources | Core, Brand, Authorization | A1 |
| MP2-MOD-111 | SEO | Platform Service | Brand-owned configuration | Brand, Domain, Content modules | B |

## Market Module Responsibility Expansion

The Market module additionally owns:

- draw schedule;
- market timezone;
- operational status;
- result source configuration;
- Live Draw source configuration;
- automation configuration that is inherently market-specific;
- brand-to-market authorization relationships where assigned by architecture.

The Market module does not own brand-specific themes, brand navigation, or
brand-owned editorial content.

## Automation Module Boundaries

The Automation module owns reusable orchestration concerns:

- schedule definitions;
- execution dispatch;
- queue integration;
- retries;
- terminal failure handling;
- idempotency support;
- execution history;
- health status;
- Brand Context preservation;
- Market Context preservation;
- operational observability.

Business rules remain inside their owning modules.

Automation must invoke module-owned application services rather than duplicate
Prediction, Result, Live Draw, or RTP business logic.

## Lottery Tool Boundaries

Lottery tools must remain deterministic unless a future approved AI capability
is explicitly enabled.

- BBFS owns BBFS generation rules.
- Buku Mimpi owns reference search and presentation rules.
- Paito derives output only from approved Result data.
- Shio owns reference mappings.
- Jadwal remains under Market ownership.
- Converter owns deterministic conversion rules.

## Dependency Rules

1. Content modules may depend on Core, Brand, Media, and SEO services.
2. Business modules may depend on Core, Market, Brand authorization, and Audit.
3. Lottery tools may consume approved Result or shared reference data.
4. Automation may orchestrate modules but must not own their business rules.
5. No module may silently default to Brand #1.
6. No module may directly access another brand's records.
7. Market-owned data requires explicit brand-to-market authorization.
8. Shared reference data must not contain hidden brand-specific ownership.
9. Cross-module writes must use documented application services.
10. Circular module dependencies are prohibited.

## Future Modules

| Module ID | Module | Status |
|---|---|---|
| MP2-MOD-F001 | Integration | Future |
| MP2-MOD-F002 | Analytics | Future |
| MP2-MOD-F003 | Mobile API | Future |
| MP2-MOD-F004 | Public API | Future |
| MP2-MOD-F005 | Referral | Future |
| MP2-MOD-F006 | Cashback | Future |
| MP2-MOD-F007 | Marketplace | Future |
| MP2-MOD-AI001 | Intelligence | Deferred |
<!-- MASTER-PROMPT-V2-MODULE-REGISTRY-END -->
