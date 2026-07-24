# FEATURE REGISTRY

Version: 1.0

Status: Active

Owner: Project Owner

Last Review: 2026-07-21

---

# Purpose

The Feature Registry is the canonical inventory of every feature within Portal Prediksi CMS.

Every implemented, planned, proposed, deprecated, or future feature must appear in this document.

No feature should exist outside this registry.

---

# Feature Status

| Status | Description |
|----------|-------------|
| Proposed | Idea captured but not approved |
| Planned | Approved for implementation |
| In Progress | Currently under development |
| Implemented | Completed |
| Deprecated | Scheduled for removal |
| Archived | Removed from product |

---

# Feature Priority

| Priority | Meaning |
|-----------|----------|
| Critical | Platform cannot operate without it |
| High | Core business capability |
| Medium | Important improvement |
| Low | Nice to have |
| Future | Long-term roadmap |

---

# Core Platform Features

| ID | Feature | Module | Priority | Status |
|----|----------|---------|----------|--------|
| F-001 | Authentication | Platform | Critical | Planned |
| F-002 | Authorization | Platform | Critical | Planned |
| F-003 | User Management | Platform | Critical | Planned |
| F-004 | Brand Management | Platform | Critical | Planned |
| F-005 | Configuration Management | Platform | Critical | Planned |
| F-006 | Audit Logging | Platform | High | Planned |
| F-007 | Notification Center | Platform | High | Planned |
| F-008 | Media Library | Platform | High | Planned |
| F-009 | SEO Management | Platform | Critical | Planned |
| F-010 | Dashboard | Platform | High | Planned |

---

# Business Modules

| ID | Feature | Module | Priority | Status |
|----|----------|---------|----------|--------|
| BM-001 | Prediction Management | Prediction | Critical | Planned |
| BM-002 | Result Management | Result | Critical | Planned |
| BM-003 | Market Management | Market | High | Planned |
| BM-004 | Shio Management | Shio | Medium | Planned |
| BM-005 | Promotion Management | Promotion | High | Planned |
| BM-006 | Blog Management | Blog | Medium | Planned |
| BM-007 | Static Pages | CMS | Medium | Planned |
| BM-008 | Banner Management | CMS | Medium | Planned |

---

# Future Features

New features should be appended here until approved.

| ID | Feature | Notes |
|----|----------|-------|
| FUT-001 | AI Content Generation | Future |
| FUT-002 | AI Prediction Assistance | Future |
| FUT-003 | Plugin Marketplace | Future |
| FUT-004 | Mobile Application | Future |

---

# Registry Rules

Every feature must include:

- Unique identifier
- Owner module
- Priority
- Current status
- Architectural ownership

Feature implementation should never begin before registration.

---

# Related Documents

- IDEA_REGISTRY.md
- CAPABILITY_REGISTRY.md
- IMPLEMENTATION_STRATEGY.md
- PRODUCT_ROADMAP.md
- MASTER_ARCHITECTURE.md

---

<!-- MASTER-PROMPT-V2-FEATURE-REGISTRY-START -->
# Master Prompt v2.0 — Feature Registry Alignment

Status: Active

## Multi-Brand Foundation Features

| Feature ID | Feature | Primary Capabilities | Milestone | Status |
|---|---|---|---|---|
| MP2-FEAT-001 | Brand Context resolution | MP2-CAP-001, MP2-CAP-002 | A1 | Mandatory |
| MP2-FEAT-002 | Owner platform control | MP2-CAP-003 | A1 | Mandatory |
| MP2-FEAT-003 | Brand Admin isolation | MP2-CAP-004 | A1 | Mandatory |
| MP2-FEAT-004 | Brand-to-market authorization | MP2-CAP-005 | A1 | Mandatory |
| MP2-FEAT-005 | Brand-aware persistence | MP2-CAP-006 | A1 | Mandatory |
| MP2-FEAT-006 | Brand-aware cache isolation | MP2-CAP-007 | A1 | Mandatory |
| MP2-FEAT-007 | Brand-aware queue context | MP2-CAP-008 | A1 | Mandatory |
| MP2-FEAT-008 | Brand-aware scheduler context | MP2-CAP-009 | A1 | Mandatory |
| MP2-FEAT-009 | Brand media isolation | MP2-CAP-010 | A1 | Mandatory |
| MP2-FEAT-010 | Brand-aware audit history | MP2-CAP-011 | A1 | Mandatory |
| MP2-FEAT-011 | Configuration-driven brand activation | MP2-CAP-012 | C | Mandatory |

## Mandatory Brand #1 Features

| Feature ID | Feature | Primary Capability | Module | Milestone | Status |
|---|---|---|---|---|---|
| MP2-FEAT-101 | Market configuration administration | MP2-CAP-101 | Market | B | Mandatory |
| MP2-FEAT-102 | Draw schedule and timezone administration | MP2-CAP-102 | Market | B | Mandatory |
| MP2-FEAT-103 | Result administration and publication | MP2-CAP-103 | Result | B | Mandatory |
| MP2-FEAT-104 | Prediction administration and publication | MP2-CAP-104 | Prediction | B | Mandatory |
| MP2-FEAT-105 | Live Draw administration and public delivery | MP2-CAP-105 | Live Draw | B | Mandatory |
| MP2-FEAT-106 | Shio reference administration and delivery | MP2-CAP-106 | Shio | B | Mandatory |
| MP2-FEAT-107 | Promotion administration and publication | MP2-CAP-107 | Promotion | B | Mandatory |
| MP2-FEAT-108 | Blog administration and publication | MP2-CAP-108 | Blog | B | Mandatory |
| MP2-FEAT-109 | Slot Gacor / RTP administration and publication | MP2-CAP-109 | RTP | B | Mandatory |
| MP2-FEAT-110 | Jackpot Proof administration and publication | MP2-CAP-110 | Jackpot Proof | B | Mandatory |
| MP2-FEAT-111 | Complaint workflow | MP2-CAP-111 | Complaint | B | Mandatory |
| MP2-FEAT-112 | Guide administration and publication | MP2-CAP-112 | Guide | B | Mandatory |

## Mandatory Lottery Tool Features

| Feature ID | Feature | Primary Capability | Module | Milestone | Status |
|---|---|---|---|---|---|
| MP2-FEAT-201 | BBFS Generator | MP2-CAP-201 | BBFS | B | Mandatory |
| MP2-FEAT-202 | Buku Mimpi search | MP2-CAP-202 | Buku Mimpi | B | Mandatory |
| MP2-FEAT-203 | Paito generation | MP2-CAP-203 | Paito | B | Mandatory |
| MP2-FEAT-204 | Shio lookup | MP2-CAP-204 | Shio | B | Mandatory |
| MP2-FEAT-205 | Market schedule display | MP2-CAP-205 | Market | B | Mandatory |
| MP2-FEAT-206 | Toto number conversion | MP2-CAP-206 | Converter | B | Mandatory |

## Mandatory Automation Features

| Feature ID | Feature | Primary Capabilities | Module | Milestone | Status |
|---|---|---|---|---|---|
| MP2-FEAT-301 | Automation scheduler | MP2-CAP-301, MP2-CAP-309 | Automation | B | Mandatory |
| MP2-FEAT-302 | Automation queue execution | MP2-CAP-302 | Automation | B | Mandatory |
| MP2-FEAT-303 | Automation retry and terminal failure handling | MP2-CAP-303, MP2-CAP-304 | Automation | B | Mandatory |
| MP2-FEAT-304 | Automation execution history | MP2-CAP-305 | Automation | B | Mandatory |
| MP2-FEAT-305 | Automation health monitoring | MP2-CAP-306 | Automation | B | Mandatory |
| MP2-FEAT-306 | Live Draw automation | MP2-CAP-307 | Live Draw | B | Mandatory |
| MP2-FEAT-307 | Result automation | MP2-CAP-308 | Result | B | Mandatory |
| MP2-FEAT-308 | Prediction automation | MP2-CAP-309 | Prediction | B | Mandatory |
| MP2-FEAT-309 | RTP rotation automation | MP2-CAP-310 | RTP | B | Mandatory |
| MP2-FEAT-310 | Automation idempotency protection | MP2-CAP-311 | Automation | B | Mandatory |
| MP2-FEAT-311 | Brand and market automation isolation | MP2-CAP-312 | Automation | B | Mandatory |

## Future Features

| Feature ID | Feature | Status |
|---|---|---|
| MP2-FEAT-F001 | Telegram integration | Future |
| MP2-FEAT-F002 | Expanded analytics | Future |
| MP2-FEAT-F003 | Mobile API | Future |
| MP2-FEAT-F004 | Public or partner REST API | Future |
| MP2-FEAT-F005 | Referral | Future |
| MP2-FEAT-F006 | Cashback | Future |
| MP2-FEAT-F007 | Marketplace | Future |

## Deferred AI Features

| Feature ID | Feature | Status |
|---|---|---|
| MP2-FEAT-AI001 | AI prediction assistance | Deferred |
| MP2-FEAT-AI002 | AI RTP assistance | Deferred |
| MP2-FEAT-AI003 | AI article assistance | Deferred |
| MP2-FEAT-AI004 | AI operational anomaly detection | Deferred |
<!-- MASTER-PROMPT-V2-FEATURE-REGISTRY-END -->
<!-- PROJECT-BRAIN-V1-START -->
## Project Brain Feature Registry Pointer — 2026-07-24

The expanded capability inventory, priorities, and lifecycle statuses are maintained in `docs/project-brain/FEATURE_CATALOG.md`.

This registry remains authoritative for existing registered implementation identifiers. The Project Brain catalog adds product-wide capabilities and future ideas without falsely marking them implemented. Status meanings are defined in the catalog and `docs/project-brain/KNOWLEDGE_MAINTENANCE.md`.
<!-- PROJECT-BRAIN-V1-END -->

<!-- MASTER-PROMPT-V2-1-SYNC-2026-07-24-FEATURES-START -->

# Master Prompt v2.1 Feature Alignment

| ID | Feature | Priority | Status |
|---|---|---|---|
| MP21-F001 | Dedicated Owner Panel | Critical | PLANNED |
| MP21-F002 | Isolated Brand Panel | Critical | PARTIALLY_IMPLEMENTED |
| MP21-F003 | Theme Registry | High | PLANNED |
| MP21-F004 | Homepage Template Registry | Critical | PLANNED |
| MP21-F005 | Widget Registry | Critical | PLANNED |
| MP21-F006 | Global Provider Catalog | Critical | PLANNED |
| MP21-F007 | Global Game Catalog | Critical | PLANNED |
| MP21-F008 | Brand Slot Gacor | Critical | PLANNED |
| MP21-F009 | RTP Snapshot and History | Critical | PLANNED |
| MP21-F010 | Public Guide Center | High | PLANNED |
| MP21-F011 | Visitor Complaint Workflow | High | PLANNED |
| MP21-F012 | Manual SEO | Critical | PLANNED |
| MP21-F013 | Hybrid SEO | Critical | PLANNED |
| MP21-F014 | Automatic SEO | High | PLANNED |
| MP21-F015 | SERP Intelligence | High | PLANNED |
| MP21-F016 | Automation Engine | High | PLANNED |
| MP21-F017 | Brand 1 Production Homepage | Critical | PLANNED |
<!-- MASTER-PROMPT-V2-1-SYNC-2026-07-24-FEATURES-END -->
