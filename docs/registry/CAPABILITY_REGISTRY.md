# Capability Registry

Version: 1.0

Status: Canonical

---

# Purpose

This registry defines every business capability that exists or will exist
within Portal Prediksi CMS.

A capability represents WHAT the platform must be able to do,
independent of implementation details, frameworks, UI,
database schema, APIs, or infrastructure.

Capabilities provide the stable business layer connecting:

- Product Vision
- Architecture
- Feature Registry
- Sprint Planning
- ADR
- Implementation
- Testing

This document is the canonical reference for platform capabilities.

---

# Principles

Every capability must:

- Solve a business problem.
- Have a clear business owner.
- Be implementation independent.
- Be reusable.
- Be extensible.
- Support future evolution.
- Remain stable across technology changes.

---

# Capability Identifier

Every capability receives a permanent identifier.

Format:

CAP-001

CAP-002

CAP-003

...

Identifiers are never reused.

Deleted capabilities remain archived.

---

# Capability Lifecycle

Draft

↓

Proposed

↓

Approved

↓

Implemented

↓

Operational

↓

Deprecated

↓

Retired

---

# Capability Categories

## Platform

Core platform infrastructure.

Examples:

- Authentication
- Authorization
- Settings
- Audit

---

## Content

Content management.

Examples:

- Prediction Articles
- News
- Categories
- Tags
- Media

---

## User

Everything related to users.

Examples:

- User Management
- Roles
- Permissions
- Sessions

---

## Prediction

Prediction-specific business logic.

Examples:

- Prediction Templates
- Match Data
- Publishing
- Scheduling

---

## SEO

Organic search capabilities.

Examples:

- Metadata
- Sitemap
- Internal Linking
- Schema
- Canonical

---

## Security

Security services.

Examples:

- MFA
- IP Whitelist
- Login Protection
- Device Trust

---

## Analytics

Measurement capabilities.

Examples:

- Dashboard
- Statistics
- Reporting
- Tracking

---

## Automation

Automated platform behavior.

Examples:

- Scheduler
- Queue
- Notifications
- Background Jobs

---

## Integration

External system integration.

Examples:

- API
- Webhook
- Third-party Services

---

## Operations

Operational tooling.

Examples:

- Backup
- Maintenance
- Monitoring
- Health Check

---

# Registry Structure

Every capability should eventually contain:

- Capability ID
- Name
- Category
- Description
- Business Value
- Owner
- Dependencies
- Related Features
- Related ADR
- Related Sprints
- Current Status

---

# Capability Registry

| ID | Capability | Category | Status | Owner |
|----|------------|----------|--------|-------|
| CAP-001 | Authentication | Platform | Planned | Core Platform |
| CAP-002 | Authorization | Platform | Planned | Core Platform |
| CAP-003 | User Management | User | Planned | Administration |
| CAP-004 | Role Management | User | Planned | Administration |
| CAP-005 | Permission Management | User | Planned | Administration |
| CAP-006 | Brand Management | Platform | Planned | Administration |
| CAP-007 | Prediction Management | Prediction | Planned | Editorial |
| CAP-008 | Match Management | Prediction | Planned | Editorial |
| CAP-009 | League Management | Prediction | Planned | Editorial |
| CAP-010 | Team Management | Prediction | Planned | Editorial |
| CAP-011 | Category Management | Content | Planned | Editorial |
| CAP-012 | Tag Management | Content | Planned | Editorial |
| CAP-013 | Article Management | Content | Planned | Editorial |
| CAP-014 | Media Library | Content | Planned | Editorial |
| CAP-015 | Draft Workflow | Content | Planned | Editorial |
| CAP-016 | Publishing Workflow | Content | Planned | Editorial |
| CAP-017 | Scheduling | Automation | Planned | Editorial |
| CAP-018 | Revision History | Content | Planned | Editorial |
| CAP-019 | Audit Logging | Security | Planned | Security |
| CAP-020 | Activity Timeline | Analytics | Planned | Administration |
| CAP-021 | Dashboard | Analytics | Planned | Administration |
| CAP-022 | Global Search | Platform | Planned | Core Platform |
| CAP-023 | Notification Center | Automation | Planned | Platform |
| CAP-024 | SEO Metadata | SEO | Planned | SEO |
| CAP-025 | Sitemap Generation | SEO | Planned | SEO |
| CAP-026 | Robots Management | SEO | Planned | SEO |
| CAP-027 | Schema Markup | SEO | Planned | SEO |
| CAP-028 | Internal Linking | SEO | Planned | SEO |
| CAP-029 | Canonical Management | SEO | Planned | SEO |
| CAP-030 | Redirect Management | SEO | Planned | SEO |
| CAP-031 | URL Management | SEO | Planned | SEO |
| CAP-032 | Slug Management | SEO | Planned | SEO |
| CAP-033 | Brand Protection | SEO | Planned | SEO |
| CAP-034 | Entity Management | SEO | Planned | SEO |
| CAP-035 | Keyword Intelligence | SEO | Planned | SEO |
| CAP-036 | SERP Intelligence | SEO | Planned | SEO |
| CAP-037 | SERP Defense | SEO | Planned | SEO |
| CAP-038 | SERP Occupancy | SEO | Planned | SEO |
| CAP-039 | Cache Management | Platform | Planned | Platform |
| CAP-040 | Queue Management | Automation | Planned | Platform |
| CAP-041 | Background Jobs | Automation | Planned | Platform |
| CAP-042 | API Management | Integration | Planned | Platform |
| CAP-043 | Webhook Management | Integration | Planned | Platform |
| CAP-044 | Settings Management | Platform | Planned | Platform |
| CAP-045 | Backup Management | Operations | Planned | Operations |
| CAP-046 | Health Monitoring | Operations | Planned | Operations |
| CAP-047 | Maintenance Mode | Operations | Planned | Operations |
| CAP-048 | System Logging | Operations | Planned | Operations |
| CAP-049 | Session Management | Security | Planned | Security |
| CAP-050 | Multi-factor Authentication | Security | Planned | Security |
| CAP-051 | Trusted Devices | Security | Planned | Security |
| CAP-052 | IP Whitelist | Security | Planned | Security |
| CAP-053 | Recovery Codes | Security | Planned | Security |
| CAP-054 | Access Policy Engine | Security | Planned | Security |
| CAP-055 | Feature Flags | Platform | Planned | Platform |
| CAP-056 | Capability Discovery | Platform | Planned | Architecture |

---

# Governance Rules

A capability may reference multiple features.

A feature belongs to at least one capability.

Capabilities should not describe implementation.

Implementation belongs to Architecture and Laravel.

---

# Relationship

Capability

↓

Feature Registry

↓

Sprint Registry

↓

ADR

↓

Implementation

---

# Change Policy

New capabilities must:

- receive a permanent identifier;
- be reviewed against Product Vision;
- avoid duplication;
- reference existing features whenever applicable;
- preserve backward compatibility whenever possible.

---

# Canonical Reference

Referenced by:

- PRODUCT_STRATEGY.md
- PRODUCT_ROADMAP.md
- FEATURE_REGISTRY.md
- IDEA_REGISTRY.md
- IMPLEMENTATION_STRATEGY.md
- SPRINT_GUIDE.md
- ADR_REGISTRY.md
- DECISION_REGISTRY.md

This document is part of the permanent Project Knowledge Base.

---

<!-- MASTER-PROMPT-V2-CAPABILITY-REGISTRY-START -->
# Master Prompt v2.0 — Capability Registry Alignment

Status: Active

## Multi-Brand Platform Capabilities

| Capability ID | Capability | Owner | Required Milestone | Status |
|---|---|---|---|---|
| MP2-CAP-001 | Resolve Brand Context from approved hostname | Platform / Brand | A1 | Mandatory |
| MP2-CAP-002 | Fail safely for unknown hostname | Platform / Brand | A1 | Mandatory |
| MP2-CAP-003 | Manage platform through Owner Context | Platform / Owner | A1 | Mandatory |
| MP2-CAP-004 | Restrict Brand Admin to assigned brands | Platform / Authorization | A1 | Mandatory |
| MP2-CAP-005 | Authorize brand access to markets | Platform / Market | A1 | Mandatory |
| MP2-CAP-006 | Preserve Brand Context in database operations | Platform / Persistence | A1 | Mandatory |
| MP2-CAP-007 | Preserve Brand Context in cache keys | Platform / Cache | A1 | Mandatory |
| MP2-CAP-008 | Preserve Brand Context in queued jobs | Platform / Queue | A1 | Mandatory |
| MP2-CAP-009 | Preserve brand and market context in scheduled jobs | Platform / Scheduler | A1 | Mandatory |
| MP2-CAP-010 | Enforce brand-aware media ownership | Platform / Media | A1 | Mandatory |
| MP2-CAP-011 | Record actor, brand, market, and action in audit history | Platform / Audit | A1 | Mandatory |
| MP2-CAP-012 | Activate additional brands through configuration | Platform / Brand | C | Mandatory |

## Mandatory Business and Content Capabilities

| Capability ID | Capability | Owner Module | Required Milestone | Status |
|---|---|---|---|---|
| MP2-CAP-101 | Manage markets and market operational configuration | Market | B | Mandatory |
| MP2-CAP-102 | Manage draw schedules and market timezones | Market | B | Mandatory |
| MP2-CAP-103 | Create, publish, correct, and audit Results | Result | B | Mandatory |
| MP2-CAP-104 | Create, generate, schedule, and publish Predictions | Prediction | B | Mandatory |
| MP2-CAP-105 | Operate Live Draw sessions and source configuration | Live Draw | B | Mandatory |
| MP2-CAP-106 | Manage Shio reference mappings | Shio | B | Mandatory |
| MP2-CAP-107 | Publish brand-owned Promotions | Promotion | B | Mandatory |
| MP2-CAP-108 | Publish brand-owned Blog content | Blog | B | Mandatory |
| MP2-CAP-109 | Publish and rotate Slot Gacor / RTP entries | RTP | B | Mandatory |
| MP2-CAP-110 | Publish and moderate Jackpot Proof records | Jackpot Proof | B | Mandatory |
| MP2-CAP-111 | Manage Complaint lifecycle | Complaint | B | Mandatory |
| MP2-CAP-112 | Publish brand-owned Guide content | Guide | B | Mandatory |

## Mandatory Lottery Tool Capabilities

| Capability ID | Capability | Owner Module | Required Milestone | Status |
|---|---|---|---|---|
| MP2-CAP-201 | Generate BBFS output deterministically | BBFS | B | Mandatory |
| MP2-CAP-202 | Search and display Buku Mimpi reference data | Buku Mimpi | B | Mandatory |
| MP2-CAP-203 | Generate Paito from confirmed Results | Paito | B | Mandatory |
| MP2-CAP-204 | Display Shio reference output | Shio | B | Mandatory |
| MP2-CAP-205 | Display market schedules using market timezone | Market | B | Mandatory |
| MP2-CAP-206 | Convert Toto numbers deterministically | Converter | B | Mandatory |

## Mandatory Automation Capabilities

| Capability ID | Capability | Owner | Required Milestone | Status |
|---|---|---|---|---|
| MP2-CAP-301 | Schedule automation definitions | Automation | B | Mandatory |
| MP2-CAP-302 | Dispatch automation through queues | Automation / Queue | B | Mandatory |
| MP2-CAP-303 | Retry recoverable execution failures | Automation | B | Mandatory |
| MP2-CAP-304 | Record terminal execution failures | Automation / Audit | B | Mandatory |
| MP2-CAP-305 | Preserve automation execution history | Automation | B | Mandatory |
| MP2-CAP-306 | Report automation health status | Automation / Operations | B | Mandatory |
| MP2-CAP-307 | Execute Live Draw automation | Live Draw / Automation | B | Mandatory |
| MP2-CAP-308 | Execute Result automation idempotently | Result / Automation | B | Mandatory |
| MP2-CAP-309 | Execute Prediction automation | Prediction / Automation | B | Mandatory |
| MP2-CAP-310 | Execute RTP rotation automation | RTP / Automation | B | Mandatory |
| MP2-CAP-311 | Prevent duplicate automation effects | Automation | B | Mandatory |
| MP2-CAP-312 | Isolate automation by brand and market | Automation / Brand / Market | B | Mandatory |

## Future and Deferred Capabilities

| Capability ID | Capability | Status |
|---|---|---|
| MP2-CAP-F001 | Telegram notification integration | Future |
| MP2-CAP-F002 | Expanded cross-brand analytics | Future |
| MP2-CAP-F003 | Mobile API delivery | Future |
| MP2-CAP-F004 | Public or partner REST API delivery | Future |
| MP2-CAP-AI001 | AI prediction assistance | Deferred |
| MP2-CAP-AI002 | AI RTP assistance | Deferred |
| MP2-CAP-AI003 | AI article assistance | Deferred |
| MP2-CAP-AI004 | AI operational anomaly detection | Deferred |
<!-- MASTER-PROMPT-V2-CAPABILITY-REGISTRY-END -->
