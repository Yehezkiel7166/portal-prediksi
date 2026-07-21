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
