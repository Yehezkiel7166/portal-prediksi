# DOMAIN MAP

Version: 1.0

---

# Purpose

This document defines the business domain boundaries of Portal Prediksi CMS.

It identifies ownership, relationships, dependency direction, and interaction rules between all domains.

This document complements MASTER_ARCHITECTURE.md and MODULE_CATALOG.md.

---

# Domain Hierarchy

Core

├── Market

├── Prediction

├── Result

├── Shio

├── Promotion

├── Blog

└── Live Draw

Every business domain depends on Core.

Core depends on no business domain.

---

# Core Domain

Purpose

Provide shared platform capabilities.

Owns

- Authentication
- Authorization
- Users
- Roles
- Permissions
- Configuration
- Feature Flags
- Shared Contracts
- Shared Services
- Audit Logging

Consumed By

- Market
- Prediction
- Result
- Shio
- Promotion
- Blog
- Live Draw

---

# Market Domain

Purpose

Manage every supported prediction market.

Owns

- Market Metadata
- Market Status
- Draw Schedule
- Region Configuration
- Availability

Consumed By

- Prediction
- Result
- Live Draw

Depends On

- Core

---

# Prediction Domain

Purpose

Manage prediction publication.

Owns

- Prediction Lifecycle
- Validation
- Publishing
- Revision History
- Approval Workflow

Consumes

- Market

Depends On

- Core
- Market

Consumed By

- Public Presentation
- Administrative CMS

---

# Result Domain

Purpose

Manage official draw results.

Owns

- Result Publication
- Verification
- Corrections
- Synchronization
- Historical Archive

Consumes

- Market

Depends On

- Core
- Market

Consumed By

- Live Draw
- Public Website
- Reporting

---

# Shio Domain

Purpose

Manage zodiac reference information.

Owns

- Zodiac Mapping
- Calendar Mapping
- Conversion Rules
- Lookup Services

Depends On

- Core

---

# Promotion Domain

Purpose

Manage promotional content.

Owns

- Campaigns
- Promotional Banners
- Landing Pages
- Announcements

Depends On

- Core

---

# Blog Domain

Purpose

Manage editorial content.

Owns

- Articles
- Categories
- Tags
- Authors
- SEO Metadata

Depends On

- Core

---

# Live Draw Domain

Purpose

Present real-time draw information.

Owns

- Live Sessions
- Streaming Metadata
- Replay Information
- Draw Timeline
- Live Status

Consumes

- Result
- Market

Depends On

- Core
- Market
- Result

---

# Domain Dependency Rules

Allowed dependency direction:

Core

↓

Market

↓

Prediction

↓

Result

↓

Live Draw

Supporting domains:

- Blog
- Promotion
- Shio

depend only on Core unless explicitly documented.

---

# Forbidden Dependencies

The following are prohibited:

- Circular dependencies
- Direct infrastructure coupling
- Cross-domain database access
- Shared mutable business state
- Hidden service dependencies
- Undocumented ownership

---

# Domain Communication

Domains communicate through:

- Application Services
- Domain Events
- Published Contracts
- Repository Interfaces

Internal implementation details must never be accessed directly.

---

# Domain Ownership Principles

Every business capability must belong to exactly one domain.

If ownership is unclear:

- Refine the boundary.
- Split the responsibility.
- Update documentation before implementation.

No capability may have multiple owners.

---

# Future Domains

Every future domain must document:

- Purpose
- Ownership
- Responsibilities
- Dependencies
- Public Contracts
- Events
- Integration Points

Implementation begins only after documentation approval.
