# Data Model

Version: 1.0

Status: Canonical

---

# Purpose

This document defines the logical data architecture for Portal Prediksi
CMS.

The Data Model establishes the canonical business objects used by the
platform and the relationships between them.

It intentionally avoids implementation details such as Laravel models,
database engines, migrations, indexes, or SQL syntax.

Its purpose is to provide a stable business-oriented model that remains
consistent regardless of implementation technology.

---

# Objectives

The Data Model aims to:

- establish a unified data vocabulary;
- define core business objects;
- standardize relationships;
- improve long-term maintainability;
- reduce duplication;
- support modular architecture;
- provide implementation guidance.

---

# Core Principles

The data architecture should be:

- domain driven;
- implementation independent;
- normalized where appropriate;
- extensible;
- reusable;
- consistent;
- traceable.

---

# Data Domains

The platform consists of the following logical domains.

## Identity

Business objects related to users and access.

Examples:

- User
- Role
- Permission
- Session
- MFA
- Trusted Device

---

## Brand

Business objects representing managed brands.

Examples:

- Brand
- Brand Profile
- Brand Asset
- Organization

---

## Content

Editorial content.

Examples:

- Article
- Category
- Tag
- Media
- Revision

---

## Prediction

Prediction-related objects.

Examples:

- Match
- League
- Team
- Prediction
- Result

---

## SEO

SEO-related objects.

Examples:

- Metadata
- Redirect
- Sitemap
- Canonical
- Schema Profile
- Keyword
- Entity

---

## Operations

Operational objects.

Examples:

- Audit Log
- Notification
- Queue Job
- System Setting
- Backup

---

# Logical Relationships

Major relationships include:

User

↓

Role

↓

Permission

Brand

↓

Article

↓

Category

↓

Tag

League

↓

Match

↓

Prediction

↓

Result

Brand

↓

Entity

↓

Keyword

↓

SEO Metadata

---

# Entity Ownership

Each business object should define:

- Owner
- Lifecycle
- Status
- Relationships
- Canonical Identifier

---

# Data Lifecycle

Every entity should progress through a lifecycle.

Draft

↓

Active

↓

Archived

↓

Retired

Lifecycle stages may differ depending on the domain.

---

# Data Integrity

The platform should preserve:

- referential consistency;
- unique identifiers;
- canonical ownership;
- historical traceability;
- auditability.

---

# Platform Responsibilities

The implementation should eventually support:

- reusable business entities;
- centralized ownership;
- relationship validation;
- lifecycle management;
- audit support;
- version awareness.

Implementation details remain outside the scope of this document.

---

# Governance Rules

Business objects should not duplicate responsibility.

Each domain owns its own data.

Cross-domain relationships should be documented.

Breaking changes require documentation before implementation.

---

# Relationship

Domain Model

↓

Business Objects

↓

Relationships

↓

Implementation Models

↓

Database Schema

---

# Canonical Reference

Referenced by:

- MASTER_ARCHITECTURE.md
- DOMAIN_MAP.md
- MODULE_CATALOG.md
- IMPLEMENTATION_STRATEGY.md
- CAPABILITY_REGISTRY.md
- FEATURE_REGISTRY.md

This document is part of the permanent Project Knowledge Base.
