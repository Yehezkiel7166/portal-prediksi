# PROJECT CONSTITUTION

Version: 1.0
Status: Active
Repository Role: Single Source of Truth

---

# Purpose

Portal Prediksi CMS is developed as a modular enterprise-grade Laravel platform.

This constitution defines the permanent engineering principles that govern architecture, implementation, documentation, testing, deployment, and future evolution.

Every contributor must follow this constitution.

---

# Core Principles

1. Repository is the Single Source of Truth.
2. Documentation evolves together with implementation.
3. Every feature must belong to a domain.
4. Every domain must have clear ownership.
5. No undocumented architecture changes.
6. No direct production fixes without repository updates.
7. Every change must be reproducible.
8. Feature Freeze applies unless explicitly lifted.
9. Architecture takes precedence over implementation speed.
10. Long-term maintainability is preferred over short-term convenience.

---

# Engineering Workflow

Inspect

↓

Design

↓

Implement

↓

Syntax Validation

↓

Module Testing

↓

Integration Testing

↓

Documentation

↓

Git Review

↓

Commit

↓

Push

↓

Repository Audit

---

# Documentation Rules

Every architectural decision must be documented.

Every sprint must produce documentation.

Repository documentation must remain synchronized with implementation.

Chat history is never considered canonical documentation.

---

# Architecture Rules

The application follows modular architecture.

Business logic must not be duplicated.

Reusable services belong in Core.

Every domain must remain isolated whenever practical.

Dependencies must remain explicit.

---

# Testing Rules

Every completed feature requires automated tests.

Bug fixes require regression tests whenever applicable.

Testing is part of the implementation process.

---

# Security Rules

Secrets never belong in Git.

Production data must never be modified by automated tests.

Security changes require documentation.

---

# Future Evolution

The platform is designed for continuous expansion.

New domains, modules, and capabilities must follow this constitution without breaking existing architecture.

<!-- EDPF-ALIGNMENT-V2-START -->

# EDPF Framework and Product Identity

## Framework Identity

Enterprise Digital Platform Framework (EDPF) is the governing enterprise
framework represented by this repository.

EDPF defines the reusable platform architecture, governance, operating model,
security boundaries, configuration model, domain model, and long-term
expansion principles used by its implementations.

## First Product Implementation

Portal Prediksi CMS is the first product implementation of EDPF.

Portal Prediksi CMS provides the prediction, result, market, shio, promotion,
blog, live draw, SEO, administration, and operational capabilities required by
the product while remaining governed by EDPF principles.

The framework and product relationship is:

`EDPF → Portal Prediksi CMS → Brand Instances`

EDPF must not become a second application beside Portal Prediksi CMS. It is the
framework and governing architecture through which Portal Prediksi CMS evolves.

# Delivery Strategy

The official delivery principle is:

> Build for many, release for one.

The platform foundation must support at least five brands without creating
separate repositories or application forks.

Delivery order:

1. Complete the minimum shared multi-brand foundation.
2. Release Brand #1 to production as quickly and safely as possible.
3. Stabilize Brand #1.
4. Add Brand #2 through Brand #5 through configuration and data.
5. Continue enterprise expansion only after the first five-brand model is
   validated.

Architecture must support multiple brands from the beginning, but incomplete
enterprise capabilities must not unnecessarily delay Brand #1 production.

# Platform Ownership Model

## Owner Control Plane

The platform has exactly one Owner Panel.

The Owner Panel belongs to the platform layer and is not itself a brand.

An Owner user:

- is not required to belong to a brand;
- can create, activate, suspend, and inspect brands;
- assigns or removes Brand Super Administrators;
- controls the login domain used by each Brand Admin Panel;
- controls platform-wide configuration, security, health, and audit functions;
- may inspect every brand when exercising explicitly authorized platform
  responsibilities.

The Owner does not manage frontend domains during normal brand operations.

## Brand Administration Plane

Every brand has an isolated administration context.

A Brand Super Administrator:

- belongs to an explicitly assigned brand;
- manages only that brand's operational data and users;
- manages the brand's frontend primary domain and permitted frontend aliases;
- manages the brand identity, frontend settings, SEO, media, content, markets,
  predictions, results, shio, promotions, blog, and live draw capabilities;
- cannot change the Brand Admin Panel login domain;
- cannot access another brand's data or configuration;
- cannot access the Owner Panel unless independently granted the Owner role.

# Domain Independence

A brand has a permanent internal identity that does not depend on a domain
name.

The domain model must distinguish:

1. Brand Admin Login Domain
   - controlled by the Owner;
   - identifies the administration entry point for one brand;
   - replaceable without source-code modification.

2. Brand Frontend Domain
   - controlled by the Brand Super Administrator;
   - identifies the public delivery address for that brand;
   - replaceable without source-code modification.

3. Frontend Domain Aliases
   - controlled within the owning brand;
   - may be pending, verified, active, redirecting, or archived;
   - cannot be shared by multiple brands.

Domain changes must not require:

- repository forks;
- source-code edits;
- route duplication;
- database schema changes;
- replacement of the permanent brand identity;
- migration of brand-owned business data.

Unknown hostnames must fail safely. They must never silently resolve to
Brand #1 or expose data from another brand.

# Isolation Requirements

All brand-owned data must have explicit brand ownership or an equally strong
documented isolation mechanism.

The platform must prevent cross-brand leakage through:

- database queries;
- authorization policies;
- panel access;
- route resolution;
- cache keys;
- queued jobs;
- scheduled operations;
- media paths;
- generated URLs;
- SEO metadata;
- logs and audit records.

Every brand-sensitive implementation requires automated isolation tests.

# Production-First Governance

Repository evolution toward EDPF remains the highest documentation and
architecture priority.

Implementation priority is limited to capabilities required to:

1. preserve repository correctness;
2. establish the five-brand-compatible foundation;
3. place Brand #1 into production safely;
4. add Brand #2 through Brand #5 without architectural redesign.

Work that does not contribute to these objectives remains outside the active
Feature Freeze unless separately approved.

# Zero Repeat Mistake Principle

Every material implementation or operational error must produce:

- a root-cause statement;
- a corrective action;
- a prevention rule or regression test where applicable;
- an update to canonical repository guidance when the lesson is reusable.

A known failure must not be repeated because its cause remained only in chat
history.

# Execution Interface

The preferred operational workflow is copy-paste execution:

1. inspect repository evidence;
2. design one bounded change;
3. provide one complete executable command block;
4. validate syntax and behavior;
5. review the resulting diff;
6. run relevant and full tests;
7. synchronize documentation;
8. commit and push only after validation.

Manual editing on the server should be avoided when a deterministic repository
patch can perform the same work.

<!-- EDPF-ALIGNMENT-V2-END -->
