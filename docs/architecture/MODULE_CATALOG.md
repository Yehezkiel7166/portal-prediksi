# MODULE CATALOG

Version: 1.0

---

# Purpose

This document defines every functional module within Portal Prediksi CMS.

It complements MASTER_ARCHITECTURE.md by describing module ownership, responsibilities, dependencies, and future extensibility.

Every module listed here represents a bounded context.

---

# Module Classification

Modules are grouped into the following categories:

- Core Platform
- Business Domains
- Content Management
- Operational Services
- Integration Services

---

# Core Platform

## Core

Purpose

Provides shared platform capabilities.

Responsibilities

- Authentication
- Authorization
- Users
- Roles
- Permissions
- Configuration
- Feature Flags
- Audit Logs
- Shared Contracts
- Shared Services

Dependencies

None except framework abstractions.

Used By

Every module.

---

# Business Domains

## Market

Purpose

Owns every prediction market.

Responsibilities

- Market Metadata
- Draw Schedule
- Region Configuration
- Market Status
- Market Lifecycle

Depends On

Core

---

## Prediction

Purpose

Owns prediction publishing.

Responsibilities

- Prediction Creation
- Validation
- Approval
- Publishing
- Revision History

Depends On

Market

Core

---

## Result

Purpose

Owns official draw results.

Responsibilities

- Result Publication
- Verification
- Synchronization
- Corrections
- History

Depends On

Market

Core

---

## Shio

Purpose

Owns zodiac reference information.

Responsibilities

- Zodiac Mapping
- Calendar Mapping
- Lookup Services
- Conversion Rules

Depends On

Core

---

# Content Management

## Promotion

Purpose

Owns promotional assets.

Responsibilities

- Campaigns
- Landing Pages
- Banners
- Featured Promotions
- Announcements

Depends On

Core

---

## Blog

Purpose

Owns editorial content.

Responsibilities

- Articles
- Categories
- Tags
- Authors
- SEO Metadata

Depends On

Core

---

# Operational Services

## Live Draw

Purpose

Provides real-time draw presentation.

Responsibilities

- Live Sessions
- Streaming Metadata
- Replay Information
- Timeline
- Live Status

Depends On

Result

Market

Core

---

# Future Modules

Future modules must:

- Define ownership.
- Avoid duplicated responsibilities.
- Publish dependency rules.
- Follow Master Architecture.
- Be documented before implementation.

---

# Dependency Matrix

| Module | Depends On |
|---------|------------|
| Core | Framework |
| Market | Core |
| Prediction | Market, Core |
| Result | Market, Core |
| Shio | Core |
| Promotion | Core |
| Blog | Core |
| Live Draw | Result, Market, Core |

---

# Architectural Rules

Every module must:

- Have a single owner.
- Have explicit responsibilities.
- Avoid circular dependencies.
- Maintain documentation.
- Include automated tests.
- Preserve backward compatibility whenever practical.
