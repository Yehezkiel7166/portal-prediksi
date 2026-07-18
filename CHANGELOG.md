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
