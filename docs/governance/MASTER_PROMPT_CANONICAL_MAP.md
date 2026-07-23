# Master Prompt v2.0 Canonical Map

Status: Active

## Canonical Authority

Master Prompt v2.0
↓
PROJECT_CONSTITUTION.md
↓
PROJECT_MANIFEST.md
↓
PROJECT_STATE.md
↓
FEATURE_FREEZE_V1.md
↓
Architecture
↓
ADR
↓
Sprint
↓
Implementation
↓
Tests
↓
Production

---

## Canonical Repository Rules

Repository is the Single Source of Truth.

The prompt explains the repository.

The repository governs implementation.

Implementation never becomes the source of architecture.

---

## Repository Evolution Order

1. Constitution
2. Manifest
3. State
4. Feature Freeze
5. Architecture
6. ADR
7. Sprint
8. Code
9. Tests
10. Production

---

## Production Strategy

Build for Many.

Release for One.

Validate.

Expand.

---

## Product Hierarchy

Enterprise Digital Platform Framework (EDPF)

↓

Portal Prediksi CMS

↓

Owner Platform

↓

Brand

↓

Market

↓

Prediction

↓

Result

↓

Promotion

↓

Blog

↓

Live Draw

---

## Ownership Hierarchy

Platform Owner

↓

Brand Super Administrator

↓

Brand Administrator

↓

Editor

↓

Operator

---

## Domain Hierarchy

Owner controls:

- Admin Login Domain

Brand controls:

- Frontend Primary Domain
- Frontend Alias
- Redirect Domain

---

## Permanent Identity

Brand Identity

↓

UUID

↓

Configuration

↓

Domain

Domain may change.

Identity never changes.

---

## Repository Principle

Documentation

↓

Architecture

↓

Implementation

↓

Testing

↓

Deployment

Never reverse this order.

---

<!-- MASTER-PROMPT-V2-GOVERNANCE-ALIGNMENT-START -->
# Master Prompt v2.0 Repository Alignment Amendment

Status: Active

Authority: Master Prompt v2.0

Purpose: Align every approved product idea, platform rule, architecture decision,
roadmap item, implementation task, automated process, and test with the canonical
repository hierarchy.

## Versioning Rule

This amendment does not create Master Prompt v2.1.

Master Prompt v2.0 remains the governing product and architecture direction.

Repository documents may receive their own minor or patch revisions while
remaining subordinate to Master Prompt v2.0.

## Complete Canonical Flow

Every new requirement must follow this flow:

Idea
↓
Idea Registry
↓
Capability Registry
↓
Feature Registry
↓
Module Registry
↓
Product Scope
↓
Architecture or ADR
↓
Roadmap
↓
Sprint
↓
Implementation
↓
Automated Tests
↓
Production Validation

A requirement may not move directly from conversation, memory, or an informal
instruction into implementation.

## Repository Alignment Rules

1. Every meaningful idea must be recorded in the repository.
2. Every approved capability must have a permanent identifier.
3. Every production feature must have explicit module ownership.
4. Every module must define its dependencies and data ownership.
5. Every implementation sprint must reference canonical requirements.
6. Every completed production feature must include automated tests.
7. Every brand-aware feature must include isolation validation.
8. Every automation must include scheduling, retries, logging, health visibility,
   execution history, and brand isolation where applicable.
9. Documentation conflicts must be resolved before implementation continues.
10. Existing implementation never overrides approved canonical documentation.

## Milestone B — Brand #1 Ready

Brand #1 is not complete merely because the CMS, administration panel, or public
frontend is accessible.

Milestone B is complete only when all mandatory Brand #1 modules, lottery tools,
automation capabilities, authorization boundaries, frontend experiences, SEO
requirements, and automated validation gates are implemented and accepted.

The detailed scope and acceptance criteria are governed by:

- `docs/product/FEATURE_FREEZE_V1.md`
- `docs/product/BRAND_1_FRONTEND_BASELINE.md`
- `docs/product/PRODUCT_ROADMAP.md`
- `docs/registry/FEATURE_REGISTRY.md`
- `docs/registry/CAPABILITY_REGISTRY.md`
- `docs/registry/MODULE_REGISTRY.md`
- `docs/governance/MASTER_PROMPT_TRACEABILITY.md`

## Product Delivery Sequence

Build for Many.

Release for One.

Validate Brand #1.

Stabilize Brand #1.

Activate Brand #2 through Brand #5 using configuration.

Expand only after the shared multi-brand model is validated.

## Ownership Classification

Features must use one of the following ownership classifications.

### Brand-Owned

Content belongs to one brand and must be isolated by Brand Context.

Examples:

- Blog
- Promotion
- Complaint
- Guide
- Jackpot Proof
- Brand configuration
- Brand media
- Brand SEO

### Market-Owned

Operational data belongs to a market and may be presented by multiple authorized
brands.

Examples:

- Result
- Prediction
- Live Draw
- Draw schedule
- Market timezone
- Market operational status

Brand access to market-owned data must be explicit and authorized.

### Shared Reference

Reference data may be shared across brands when it has no brand-specific
ownership.

Examples:

- Shio reference mappings
- Buku Mimpi reference data
- Deterministic number conversion tables

Shared reference data must remain immutable or version-controlled where required.

### Shared Engine with Brand Configuration

A reusable engine may be shared while its presentation, availability, rules,
defaults, and SEO remain brand-configurable.

Examples:

- BBFS generator
- Paito generator
- Number converters
- Scheduling engines
- Automation framework

## Extensibility Rule

Future capabilities must be addable without rebuilding or forking the CMS.

Examples include:

- AI prediction assistance
- AI RTP assistance
- AI article assistance
- Referral
- Cashback
- Telegram integration
- Analytics
- Mobile API
- REST API

Future features remain outside the mandatory Brand #1 scope unless separately
approved through Feature Freeze change control.

## Compatibility Decisions

The following existing classifications remain valid:

- Blog remains a Content module.
- Promotion remains a Content module.
- Live Draw remains an Operational module.
- Shio remains an explicit module but is treated primarily as shared reference
  capability.
- Market remains a Business module and owns schedules, timezone, operational
  status, and source configuration.

These classifications avoid unnecessary architectural churn while allowing the
new Brand #1 requirements to be integrated consistently.
<!-- MASTER-PROMPT-V2-GOVERNANCE-ALIGNMENT-END -->
