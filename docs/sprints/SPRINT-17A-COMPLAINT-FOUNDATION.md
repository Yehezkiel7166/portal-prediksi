# Sprint 17A — Complaint Foundation

## Objective

Implement a privacy-safe, brand-scoped complaint workflow for Brand 1.

## Delivered

- `Complaint` domain model and migration.
- Public `/keluhan` form with CSRF, validation, honeypot, and rate limiting.
- Non-public reference code returned after submission.
- Filament review workflow: Open, Reviewed, Resolved, Rejected.
- Brand isolation and protected operational metadata.
- Feature tests for form availability, submission, validation, and isolation.

## Safety decisions

- Complaints are never publicly listed.
- The form is `noindex,follow`.
- Users are warned not to submit passwords, OTPs, PINs, or sensitive payment data.
- Source IP and user agent are stored for abuse response but hidden from serialization.

## Production note

The production migration must be run separately after source deployment and verification.
