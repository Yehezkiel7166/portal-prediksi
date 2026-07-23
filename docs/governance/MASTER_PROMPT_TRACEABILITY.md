# Master Prompt v2.0 Traceability

Status: Active

| Master Prompt | Canonical Repository |
|---------------|---------------------|
| Repository SSOT | PROJECT_CONSTITUTION.md |
| Governance | PROJECT_CONSTITUTION.md |
| Product Identity | PROJECT_CONSTITUTION.md |
| Feature Freeze | docs/product/FEATURE_FREEZE_V1.md |
| Repository State | PROJECT_STATE.md |
| Sprint | docs/sprints/PHASE-1-1-EDPF-REPOSITORY-ALIGNMENT.md |
| Future Architecture | ADR + Architecture documents |
| Implementation | Source Code |

Execution flow:

Master Prompt
↓
Repository
↓
Architecture
↓
Implementation
↓
Production

Repository always precedes implementation.

<!-- BRAND-1-BASELINE-START -->

## Brand 1 Complete Frontend Traceability

| Requirement | Canonical source | Validation target |
|---|---|---|
| Brand 1 complete baseline | `docs/product/BRAND_1_FRONTEND_BASELINE.md` | Governance and regression tests |
| Public navigation | `docs/product/BRAND_1_FRONTEND_BASELINE.md` | Route and frontend tests |
| Live Draw | Existing Live Draw architecture and Brand 1 baseline | Integration and fallback tests |
| Result | Result validation policy and Brand 1 baseline | Admin, correction, and automation tests |
| Prediction | Existing Prediction architecture and Brand 1 baseline | Generation and publication tests |
| Slot Gacor / RTP | Brand 1 baseline | Module and frontend tests |
| Bukti Jackpot | Brand 1 baseline | Moderation and media tests |
| Promosi | Existing Promotion domain and Brand 1 baseline | Scheduling and frontend tests |
| Keluhan | Brand 1 baseline | Ticket workflow and security tests |
| Panduan | Brand 1 baseline | Content and SEO tests |
| Jadwal Togel | Market configuration and Brand 1 baseline | Schedule consistency tests |
| BBFS Generator | Brand 1 baseline | Algorithm and validation tests |
| Buku Mimpi | Brand 1 baseline | Search, content, and SEO tests |
| Paito Togel Warna | Confirmed Result data and Brand 1 baseline | Result-driven generation tests |
| Konversi Angka SGP | Brand 1 baseline | Deterministic conversion tests |
| Tabel Shio | Existing Shio domain and Brand 1 baseline | Mapping and yearly activation tests |
| Multi-brand gate | Feature Freeze, Roadmap, and Brand 1 baseline | Complete Brand 1 stability gate |

<!-- BRAND-1-BASELINE-END -->

---

<!-- MASTER-PROMPT-V2-EXPANDED-TRACEABILITY-START -->
# Expanded Master Prompt v2.0 Traceability

Status: Active

## Governance and Delivery Traceability

| Requirement | Canonical repository target | Validation |
|---|---|---|
| Repository Single Source of Truth | `PROJECT_CONSTITUTION.md` and governance documents | Repository review |
| Documentation-first delivery | `docs/architecture/IMPLEMENTATION_STRATEGY.md` | Sprint and pull-request review |
| Requirement registration | Registry documents | Registry consistency audit |
| Multi-brand foundation | Feature Freeze and architecture documents | Isolation and domain-resolution tests |
| Brand #1 Ready | Feature Freeze, Brand 1 baseline, and Roadmap | Milestone B acceptance suite |
| Brand #1 stabilization | Product Roadmap | Regression, operations, and production validation |
| Brand #2–#5 activation | Product Roadmap and configuration architecture | Configuration-only activation tests |
| Future extensibility | Master Architecture and Module Catalog | Architecture review |

## Ownership Traceability

| Ownership model | Canonical target | Mandatory validation |
|---|---|---|
| Brand-owned content | Domain Map, Module Catalog, registries | Cross-brand isolation tests |
| Market-owned operational data | Market architecture and domain documents | Brand-market authorization tests |
| Shared reference data | Module Catalog and capability registry | Versioning and deterministic behavior tests |
| Shared engine with brand configuration | Master Architecture and module documentation | Configuration and isolation tests |
| Owner-only platform control | Permission Registry and Feature Freeze | Authorization tests |
| Brand Admin control | Permission Registry and Feature Freeze | Scope and isolation tests |

## Mandatory Brand #1 Module Traceability

| Requirement | Planned canonical owner | Required validation |
|---|---|---|
| Live Draw | Live Draw module | Session, stream, fallback, result, and automation tests |
| Result | Result module | Publication, correction, validation, automation, and isolation tests |
| Prediction | Prediction module | Creation, generation, publication, scheduling, and isolation tests |
| Promotion | Promotion module | Scheduling, visibility, SEO, and brand-isolation tests |
| Blog | Blog module | Editorial, publishing, SEO, and brand-isolation tests |
| Slot Gacor / RTP | RTP module or approved operational bounded context | Rotation, scheduling, visibility, history, and isolation tests |
| Jackpot Proof | Jackpot Proof content module | Moderation, media ownership, publishing, and isolation tests |
| Complaint | Complaint workflow module | Ticket lifecycle, authorization, privacy, and audit tests |
| Guide | Guide content module | Publishing, navigation, SEO, and isolation tests |

## Mandatory Lottery Tool Traceability

| Requirement | Planned canonical owner | Required validation |
|---|---|---|
| BBFS Generator | Shared BBFS engine with brand configuration | Input validation and deterministic generation tests |
| Buku Mimpi | Shared reference/content capability | Search, indexing, content, and SEO tests |
| Paito | Shared generator using confirmed Result data | Result consistency and rendering tests |
| Shio | Shio shared-reference module | Year activation and mapping tests |
| Jadwal | Market module | Timezone and schedule consistency tests |
| Konversi Toto | Shared deterministic converter | Conversion table and edge-case tests |

## Automation Traceability

| Requirement | Canonical target | Required validation |
|---|---|---|
| Scheduler | Automation architecture and Capability Registry | Due-job execution tests |
| Queue | Platform infrastructure documentation | Context preservation tests |
| Retry policy | Automation architecture | Retry and terminal-failure tests |
| Failure logging | Audit and operations documentation | Failure record tests |
| Health checks | Operations capability | Healthy, degraded, and failed state tests |
| Execution history | Automation capability | History persistence and authorization tests |
| Live Draw automation | Live Draw module | Brand and market isolation tests |
| Result automation | Result module | Idempotency and correction-safety tests |
| Prediction automation | Prediction module | Scheduling and publication tests |
| RTP automation | RTP module | Rotation and visibility tests |

## Extensibility Traceability

The following capabilities remain future-facing unless approved through Feature
Freeze change control:

| Future capability | Registry destination | Current treatment |
|---|---|---|
| AI prediction assistance | Idea, Capability, and Feature registries | Deferred |
| AI RTP assistance | Idea, Capability, and Feature registries | Deferred |
| AI article assistance | Idea, Capability, and Feature registries | Deferred |
| Referral | Idea Registry | Future |
| Cashback | Idea Registry | Future |
| Telegram integration | Idea and Capability registries | Future |
| Analytics expansion | Capability Registry | Future |
| Mobile API | Capability and Feature registries | Future |
| REST API | Capability and Feature registries | Future |

Future capabilities must use extension points and must not require repository or
application forks.

## Conflict Resolution

Where an earlier document conflicts with this alignment:

1. Master Prompt v2.0 remains authoritative.
2. Existing valid architecture is preserved where possible.
3. Mandatory Brand #1 scope must be registered before implementation.
4. Core non-AI automation is considered mandatory operational behavior.
5. AI-assisted automation remains deferred.
6. Shio remains an explicit module but primarily serves shared reference data.
7. Blog remains a Content module.
8. Promotion remains a Content module.
9. Live Draw remains an Operational module.
10. Market remains the owner of schedule, timezone, operational status, and
    market-source configuration.
<!-- MASTER-PROMPT-V2-EXPANDED-TRACEABILITY-END -->
