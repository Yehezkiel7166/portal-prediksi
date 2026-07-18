# Changelog

All notable changes to Portal Prediksi CMS will be documented here.

## [Unreleased]

### Added

- Laravel 13 application foundation
- MySQL production database connection
- Initial Laravel database migrations
- Secure public directory deployment
- Indonesian locale configuration
- Asia/Jakarta application timezone
- Initial project documentation structure
- Local Git repository

### Security

- Application source stored outside `public_html`
- Debug mode disabled in production
- Environment secrets excluded from Git
- Database password rotated after exposure

## Sprint 01 — Filament Admin Foundation

### Added
- Filament v5.7.1 admin panel.
- Administrator access control using `is_admin`.
- `admin:create` command.
- Admin access and admin command feature tests.
- Sprint 01 documentation.

### Fixed
- Added `email_verified_at` to the User model fillable attributes.

### Verified
- Production migration completed.
- Admin routes active.
- Full test suite passed: 10 tests and 28 assertions.

## Sprint 03 — Prediction Module

### Added

- Modular Prediction domain.
- Predictions database table.
- Prediction model and factory.
- Centralized create/update action.
- Filament Prediction CRUD resource.
- Search, filtering, badges, and bulk deletion.
- Prediction feature and authorization tests.
- Sprint 03 technical documentation.

### Verified

- Prediction values are normalized before saving.
- Duplicate market and date combinations are rejected.
- Publication timestamps are managed automatically.
- Prediction administration is restricted to admins.
## Sprint 04 - Markets Module

- Added centralized market master data.
- Added Market model, migration, factory, and seeder.
- Added Market administration through Filament.
- Added market normalization and validation action.
- Added automated feature tests and sprint documentation.

---

## Core Update 01.3

### Added

- Global PHPUnit Test Database Guard.
- SQLite in-memory test isolation.
- Test Environment Isolation Feature Test.
- ADR-0002 Core Test Isolation.

### Result

- PHPUnit menggunakan database testing yang terisolasi.
- Production database tidak dapat digunakan saat menjalankan test.


---

## Core Update 01.4

### Added

- Core `Clock` contract for application-wide time abstraction.
- Immutable production clock implementation through `SystemClock`.
- Singleton service-container binding for `Clock`.
- Feature tests for resolution, immutable output, and singleton behavior.
- ADR-0003 documenting the clock abstraction decision.

### Compatibility

- Existing modules remain unchanged.
- Clock adoption will be performed incrementally in separate updates.

### Validation

- Clock module: 3 tests, 3 assertions.
- Full suite: 32 tests, 68 assertions.

## Core Update 01.5 - Prediction Clock Adoption

### Changed

- Injected the application `Clock` contract into `UpsertPredictionAction`.
- Replaced direct publication-time access with `Clock::now()`.

### Tests

- Added a deterministic Prediction publishing test using a test Clock binding.
- Verified the Prediction module and complete application test suite.

### Documentation

- Added ADR-0004 for Prediction Clock adoption.

## Shio Foundation

### Added

- Added Shio domain foundation.
- Added shio periods management structure.
- Added shio numbers mapping structure.
- Added ShioPeriod and ShioNumber domain models.
- Added Shio factories and relationship tests.

### Verified

- Shio period can contain multiple shio number mappings.
- Full application test suite passed.

## Shio Admin Resource

### Added

- Added Filament Shio management resource.
- Added Shio period CRUD administration.
- Added Shio resource access protection.
- Added Shio resource feature tests.

### Verified

- Administrator can access Shio resource.
- Regular users cannot access Shio resource.
- Full application test suite passed.

## Shio Update 03 — Shio Numbers Management

- Added embedded Shio number management to each Shio calendar period.
- Added create, edit, delete, bulk delete, search, sorting, and reordering.
- Added JSON-backed number input using Filament tags.
- Added relation manager registration, rendering, and owner-period scope tests.

## Shio Update 04.1 — Banner Template Foundation

- Replaced the manual Shio banner-template path with a secure image upload.
- Added JPEG, PNG, and WebP validation with a 10 MB size limit.
- Stored templates under the public `shio/banner-templates` directory.
- Added template preview and a read-only generated-banner preview field.
- Added Shio create-form and edit-form rendering tests.
- Reused the existing banner columns without modifying old migrations.

## Shio Update 04.2 — Banner Generation Engine

- Added a reusable Shio banner generation domain action.
- Added JPEG, PNG, and WebP template decoding through GD.
- Added template-directory, existence, format, and dimension validation.
- Added deterministic PNG generation under `shio/generated`.
- Added title, date-range, Shio-name, and number rendering.
- Added generated-banner model persistence.
- Added generation, replacement, validation, and storage tests.

## Shio Update 04.3 — Banner Page Action

- Added a Generate Banner action to the Shio period edit page.
- Disabled generation when no template is selected.
- Added confirmation before replacing an existing generated banner.
- Connected the Filament page to the reusable banner generation engine.
- Refreshed the generated-banner preview after successful generation.
- Added success and failure notifications.
- Added Shio page-action rendering and contract tests.
