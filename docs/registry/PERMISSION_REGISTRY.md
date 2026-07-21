# PERMISSION REGISTRY

Version: 1.0

---

# Purpose

The Permission Registry is the authoritative catalog of every permission used by Portal Prediksi CMS.

Every permission must be documented before implementation.

Undocumented permissions are prohibited.

---

# Objectives

The Permission Registry ensures:

- Consistent authorization.
- Explicit ownership.
- Predictable access control.
- Clear permission naming.
- Stable role management.
- Repository-wide consistency.

---

# Permission Naming Standard

Permissions use the following format:

resource.action

Examples:

- users.view
- users.create
- users.update
- users.delete

- markets.view
- markets.create
- predictions.publish
- results.verify

Permission names are lowercase.

Permission names remain stable.

---

# Permission Categories

Permissions are grouped into:

- Core
- Market
- Prediction
- Result
- Shio
- Promotion
- Blog
- Live Draw
- System

---

# Permission Registry

| Permission | Module | Description | Default |
|------------|--------|-------------|---------|
| dashboard.view | Core | View dashboard | Owner |
| users.view | Core | View users | Owner |
| users.create | Core | Create users | Owner |
| users.update | Core | Update users | Owner |
| users.delete | Core | Delete users | Owner |
| roles.manage | Core | Manage roles | Owner |
| permissions.manage | Core | Manage permissions | Owner |
| markets.manage | Market | Manage markets | Owner |
| predictions.manage | Prediction | Manage predictions | Owner |
| predictions.publish | Prediction | Publish predictions | Owner |
| results.manage | Result | Manage results | Owner |
| results.publish | Result | Publish results | Owner |
| shio.manage | Shio | Manage shio data | Owner |
| promotions.manage | Promotion | Manage promotions | Owner |
| blog.manage | Blog | Manage articles | Owner |
| livedraw.manage | Live Draw | Manage live draw | Owner |

---

# Role Mapping

Default platform roles:

- Central
- Owner
- Brand Admin
- Operator
- Viewer

Role assignments may evolve without changing permission names.

---

# Authorization Rules

Every permission must define:

- Module owner
- Business purpose
- Protected resource
- Authorized roles

Permissions are enforced through policies and authorization services.

Controllers must never contain authorization rules.

---

# Validation Checklist

Every permission should have:

- Unique name
- Module owner
- Description
- Default role
- Documentation
- Test coverage

---

# Governance

The Permission Registry must remain synchronized with:

- Module Registry
- Domain Registry
- Route Registry
- Master Architecture
- Platform Layers
- Implementation Strategy

No undocumented permission may be implemented.
