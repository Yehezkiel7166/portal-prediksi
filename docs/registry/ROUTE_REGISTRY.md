# ROUTE REGISTRY

Version: 1.0

---

# Purpose

The Route Registry is the authoritative inventory of every HTTP endpoint exposed by Portal Prediksi CMS.

Every route must be documented before implementation.

Undocumented routes are prohibited.

---

# Objectives

The Route Registry ensures:

- Consistent endpoint naming.
- Predictable URL structure.
- Clear ownership.
- Explicit authorization.
- API consistency.
- Traceable implementation.

---

# Route Categories

Routes are classified as:

- Public
- Authentication
- Administrative
- Internal
- System
- Health

---

# Route Standards

Every route must define:

- Route Name
- HTTP Method
- URI
- Module
- Owner
- Authentication
- Authorization
- Controller
- Status

---

# Route Registry

| Route Name | Method | URI | Module | Auth | Status |
|------------|--------|-----|--------|------|--------|
| home | GET | / | Core | Public | Planned |
| login | GET | /login | Core | Public | Planned |
| login.store | POST | /login | Core | Public | Planned |
| logout | POST | /logout | Core | Required | Planned |
| dashboard | GET | /admin | Core | Required | Planned |
| markets.index | GET | /admin/markets | Market | Required | Planned |
| predictions.index | GET | /admin/predictions | Prediction | Required | Planned |
| results.index | GET | /admin/results | Result | Required | Planned |
| shio.index | GET | /admin/shio | Shio | Required | Planned |
| promotions.index | GET | /admin/promotions | Promotion | Required | Planned |
| blog.index | GET | /admin/blog | Blog | Required | Planned |
| livedraw.index | GET | /admin/live-draw | Live Draw | Required | Planned |

---

# Naming Rules

Route names should:

- Be lowercase.
- Use dot notation.
- Follow Laravel conventions.
- Remain stable.

Examples:

- dashboard
- users.index
- users.store
- markets.edit
- predictions.update

---

# URI Rules

Administrative routes should begin with:

/admin/

Public routes should remain short and readable.

API routes should remain versionable.

---

# Authorization

Every protected route must define:

- Authentication requirement.
- Authorization policy.
- Responsible module.

Authorization must never be implemented directly inside controllers.

---

# Controller Rules

Controllers should:

- Receive requests.
- Delegate to Application services.
- Return responses.

Controllers must never contain business rules.

---

# Validation Checklist

Every route must define:

- Owner
- Module
- Authentication
- Authorization
- Controller
- Documentation
- Test Coverage

---

# Governance

The Route Registry must remain synchronized with:

- Module Registry
- Domain Registry
- Master Architecture
- Platform Layers
- Implementation Strategy

No undocumented route may be implemented.
