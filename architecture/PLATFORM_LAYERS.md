# PLATFORM LAYERS

Version: 1.0

---

# Purpose

This document expands the platform layering defined by the Master Architecture.

It specifies the responsibilities, boundaries, dependency rules, and implementation expectations for each architectural layer within Portal Prediksi CMS.

This document must remain consistent with MASTER_ARCHITECTURE.md.

---

# Layer Hierarchy

Presentation

↓

Application

↓

Domain

↓

Infrastructure

↓

Persistence

Dependencies always flow downward.

Reverse dependencies are prohibited.

---

# Presentation Layer

## Purpose

Provides every user-facing interface.

This layer accepts requests and returns responses.

It must never contain business rules.

### Responsibilities

- Web Routes
- API Routes
- Controllers
- Request Validation Entry
- Response Formatting
- Blade Views
- API Resources
- Authentication Entry Points

### Allowed Dependencies

- Application Layer

### Forbidden Responsibilities

- Business Rules
- Database Queries
- Domain Decisions
- Infrastructure Logic

---

# Application Layer

## Purpose

Coordinates business use cases.

The Application Layer orchestrates workflows between domains.

### Responsibilities

- Use Cases
- Actions
- Transactions
- Workflow Coordination
- Event Dispatching
- Authorization Coordination

### Allowed Dependencies

- Domain
- Core Contracts

### Forbidden Responsibilities

- Infrastructure Implementation
- Database Logic
- UI Logic

---

# Domain Layer

## Purpose

Contains all business knowledge.

The Domain Layer represents the business itself.

### Responsibilities

- Entities
- Value Objects
- Domain Services
- Policies
- Specifications
- Business Rules
- Domain Events

### Allowed Dependencies

- Core Contracts

### Forbidden Responsibilities

- Controllers
- Framework Logic
- Database Access
- HTTP Logic

---

# Infrastructure Layer

## Purpose

Implements technical capabilities required by the application.

### Responsibilities

- Repository Implementations
- Queue Providers
- Cache Providers
- Storage Providers
- Search Providers
- Notification Providers
- External API Clients

### Allowed Dependencies

- Domain Contracts
- Core Contracts

### Forbidden Responsibilities

- Business Decisions
- Business Validation
- Domain Ownership

---

# Persistence Layer

## Purpose

Provides durable data storage.

### Responsibilities

- Database Schema
- Migrations
- ORM Models
- Indexes
- Read Models
- Cache Persistence

### Allowed Dependencies

Persistence should remain passive.

### Forbidden Responsibilities

- Business Logic
- Domain Decisions
- Workflow Logic

---

# Dependency Rules

The dependency direction is mandatory.

Presentation

↓

Application

↓

Domain

↓

Infrastructure

↓

Persistence

No layer may depend upward.

---

# Layer Communication

Communication should occur only through documented interfaces.

Allowed mechanisms include:

- Service Contracts
- Domain Events
- Repository Interfaces
- Application Services

Hidden communication paths are prohibited.

---

# Architectural Principles

Every layer should remain:

- Cohesive
- Loosely Coupled
- Independently Testable
- Predictable
- Well Documented

---

# Layer Validation Checklist

Before implementation each layer should verify:

- Responsibilities are clearly defined.
- Dependencies follow architectural rules.
- No duplicated logic exists.
- Business rules remain inside Domain.
- Documentation matches implementation.
