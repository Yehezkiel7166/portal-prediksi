# STAGE 7 — ENTERPRISE BRAND ISOLATION HARDENING

Status:
PLANNED

---

## Objective

Strengthen Brand isolation before implementing the Owner Panel.

No functional expansion is performed during this stage.

---

## Current State

Implemented

- BrandContext
- BrandResolver
- BrandContextInitializer
- Brand Middleware
- Brand relationship
- Brand tests
- Resource isolation tests

Not yet hardened

- Automatic Brand isolation
- Strict unknown-domain handling
- Global Brand enforcement
- Owner separation
- Policy layer

---

## Work Packages

### WP-1

Strict Brand Resolution

- Resolve from HTTP Host
- No config fallback
- No implicit Brand

Acceptance

- Unknown host does not expose Brand data.

---

### WP-2

Brand Ownership

- Validate every brand_id
- Remove nullable ownership where appropriate
- Strengthen FK rules

Acceptance

Every business record belongs to exactly one Brand.

---

### WP-3

Automatic Brand Isolation

Current

Manual scopeForCurrentBrand()

Target

Automatic Brand-aware querying.

Acceptance

Developer cannot accidentally read another Brand.

---

### WP-4

Owner Separation

Prepare architecture for

- Owner Panel
- Brand Panel

without changing existing modules.

---

### WP-5

Regression

Re-run complete test suite.

Target

No regression.

