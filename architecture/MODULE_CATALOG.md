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

---

<!-- MASTER-PROMPT-V2-MODULE-CATALOG-START -->
# Master Prompt v2.0 Module Catalog Alignment

Status: Active

This section extends the existing catalog without replacing valid module
definitions already present.

## Mandatory Module Classification

| Module | Classification | Primary Ownership |
|---|---|---|
| Core | Platform | Platform primitives |
| Market | Business | Market-owned |
| Result | Business | Market-owned |
| Prediction | Business | Market-owned |
| Live Draw | Operations | Market-owned |
| Shio | Shared Reference | Shared reference |
| Promotion | Content | Brand-owned |
| Blog | Content | Brand-owned |
| RTP | Operations | Brand-configured |
| Jackpot Proof | Content | Brand-owned |
| Complaint | Workflow | Brand-owned |
| Guide | Content | Brand-owned |
| BBFS | Lottery Tool | Shared deterministic engine |
| Buku Mimpi | Lottery Tool | Approved reference content |
| Paito | Lottery Tool | Result-derived |
| Converter | Lottery Tool | Shared deterministic engine |
| Automation | Platform Operations | Contextual execution |
| Media | Platform Service | Brand-owned resources |
| SEO | Platform Service | Brand-owned configuration |

## RTP

RTP owns publication, history, scheduling, rotation, brand visibility, and
RTP-specific automation contracts.

Automation must invoke RTP-owned application services.

## Jackpot Proof

Jackpot Proof owns brand-scoped proof records, moderation, publication status,
media references, visibility, and audit history.

## Complaint

Complaint owns brand-scoped cases, lifecycle status, resolution information,
internal workflow data, and audit history.

Complaint data must never cross brand boundaries.

## Guide

Guide owns brand-scoped instructional content, hierarchy, publication lifecycle,
navigation exposure, SEO metadata, and media references.

## BBFS

BBFS owns deterministic input validation, generation rules, and output
formatting.

BBFS must remain independently testable and must not silently depend on AI
output.

## Buku Mimpi

Buku Mimpi owns approved reference content, search, indexing, presentation, and
applicable SEO exposure.

Its data ownership must be explicitly classified as shared reference or
brand-owned content.

## Paito

Paito owns Result-derived datasets, filtering, ordering, presentation, and
caching.

Paito may consume confirmed Results through Result-owned contracts but must not
modify official Result data.

## Converter

Converter owns deterministic and validated number conversion rules.

Conversion behavior must remain independently testable and versionable when its
rules change.

## Automation

Automation owns reusable orchestration including:

- scheduled definition processing;
- contextual queue dispatch;
- retries;
- terminal failure handling;
- execution history;
- health status;
- idempotency support;
- Brand Context reconstruction;
- Market Context reconstruction.

Automation does not own Result, Prediction, Live Draw, or RTP business rules.

## Media

Media provides storage, upload handling, retrieval, transformation, metadata,
deletion, and ownership enforcement.

Business modules retain ownership of the records that reference media.

## SEO

SEO provides reusable metadata, canonical URL, sitemap, structured data, robots,
and brand SEO infrastructure.

The originating content or business module retains ownership of its public
content and SEO values.

## Dependency Rules

Cross-module reads must use documented repositories, queries, or application
contracts.

Cross-module writes must use the owning module's application service or command.

Prohibited dependencies include:

- Automation directly writing another module's tables;
- Paito modifying official Results;
- Live Draw owning official Results;
- Media owning content lifecycle;
- SEO owning editorial content;
- one brand accessing another brand's records;
- market-owned modules bypassing brand-to-market authorization;
- circular module dependencies.

## Required Documentation

Every mandatory module must document:

- purpose;
- ownership;
- entities;
- application services;
- public contracts;
- routes;
- permissions;
- events;
- configuration;
- dependencies;
- cache behavior;
- automation interaction;
- audit behavior;
- tests;
- frontend exposure;
- SEO behavior where applicable.
<!-- MASTER-PROMPT-V2-MODULE-CATALOG-END -->
