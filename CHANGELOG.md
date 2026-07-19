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

## Core Update 02.1 — Event Infrastructure Foundation

- Added the application event service provider.
- Registered Laravel-native event infrastructure explicitly.
- Added provider and dispatcher integration tests.
- Documented event architecture rules.

## Core Update 02.2A — Shio Event Foundation

- Added the after-commit `ShioChanged` domain event.
- Added the after-commit banner generation listener.
- Registered the Shio event and listener explicitly.
- Added listener delegation and template guard tests.

## Core Update 02.2B — Shio Period Dispatch

- Dispatches `ShioChanged` when a Shio period is created.
- Dispatches the event when banner source fields are updated.
- Prevents recursive dispatch from generated banner path updates.
- Adds event dispatch regression tests.

## Sprint 06.1 — Public Frontend Foundation

### Added

- Added dedicated Frontend namespace.
- Added HomeController.
- Added reusable frontend layout.
- Added reusable header.
- Added reusable footer.
- Added public homepage.
- Added named route `home`.
- Added frontend feature test.

### Verified

- Homepage rendered through HomeController.
- Public layout separated from Laravel welcome page.
- Full test suite passed (66 tests, 158 assertions).

## Prediction Update 01 — Market Relation

### Changed

- Replaced the legacy Prediction `market` string with `market_id`.
- Added the Prediction-to-Market `belongsTo` relationship.
- Added the Market-to-Prediction `hasMany` relationship.
- Updated Prediction validation to require an existing Market.
- Changed duplicate protection to use Market ID and prediction date.
- Replaced the Prediction market text input with a searchable Market selector.
- Updated the Prediction table to display the related Market name.
- Updated the Prediction factory and feature tests for Market relationships.

### Database

- Added a new migration without changing previously executed migrations.
- Added the `predictions.market_id` foreign key.
- Replaced the legacy market/date unique index.
- Removed the duplicated Prediction market string.
- No production backfill was required because the Prediction table was empty.

### Documentation

- Added `PREDICTION-UPDATE-01-MARKET-RELATION.md`.

### Verified

- Prediction module passed: 9 tests, 16 assertions.
- Market module passed: 8 tests, 13 assertions.
- Result module passed: 7 tests, 9 assertions.
- Full test suite passed: 67 tests, 160 assertions.

## Sprint 06.2A — Public Prediction Listing

### Added

- Added the public `/prediksi-togel` route.
- Added the frontend Prediction listing controller.
- Added a responsive public Prediction listing view.
- Added an empty state when no published Prediction is available.
- Added twelve-record pagination.
- Added frontend feature tests for publication visibility, ordering, and pagination.
- Added Sprint 06.2A technical documentation.

### Changed

- Connected the public `Prediksi Togel` navigation item to the listing route.
- Added active-state styling to Home and Prediction navigation links.

### Publication Rules

- Only published Predictions with an effective publication timestamp are shown.
- Draft, archived, and future-scheduled Predictions remain hidden.
- Predictions from inactive Markets remain hidden.
- Market relationships are eager-loaded to prevent N+1 queries.

## Sprint 06.2C — Public Prediction Filtering

### Added

- Added validated Market and date filters to the public Prediction listing.
- Added `PredictionIndexRequest` for public query normalization and validation.
- Added active and ordered Market options to the filter form.
- Added filter-aware empty states and matching-result totals.
- Added frontend tests for Market, date, combined filtering, validation, and pagination.
- Added Sprint 06.2C technical documentation.

### Changed

- Public Prediction pagination now preserves active query parameters.
- Public Prediction queries now apply optional filters through conditional clauses.

### Validation

- Market filters must reference an active Market slug.
- Date filters must use the `YYYY-MM-DD` format.
- Invalid filters redirect to the clean Prediction listing with validation errors.

## Sprint 06.3A - Public Prediction Detail

### Added
- Public prediction detail route:
  `/prediksi-togel/{marketSlug}/{predictionDate}`
- Frontend PredictionDetailController.
- Public prediction detail Blade view.
- Detail links from prediction listing.
- Feature tests covering:
  - route registration
  - published prediction rendering
  - draft prediction returns 404
  - future publication returns 404
  - inactive market returns 404
  - invalid slug/date returns 404

### Validation
- Frontend prediction tests: PASS
- Full test suite: 84 tests / 230 assertions PASS


## Shio UI Fix

- Restored Create button on the Shio listing page.
- Added regression test to ensure the Create action remains available.

## Shio Listing UX

- Added Generate Banner action directly on the Shio listing page.
- Listing now allows quick banner generation without opening the edit page.

## Result Architecture

- Result Create page now delegates persistence to UpsertResultAction.
- Result Edit page now delegates persistence to UpsertResultAction.
- Result module now follows the same architecture as Market and Prediction.

## Sprint 07.1 — Promotion Foundation

### Added

- Added the Promotion domain model and database foundation.
- Added `UpsertPromotionAction` for persistence and validation.
- Added upload, direct URL, and HTTPS embed media-source foundations.
- Added focal-point presets without manual media-size controls.
- Added draft, published, and archived publication states.
- Added Promotion Filament resource, form, table, and CRUD pages.
- Added Promotion factory and feature tests.
- Added Promotion module technical documentation.

### Security

- Promotion embeds accept URL fields only.
- Raw JavaScript and arbitrary script input are not supported.
- Provider whitelist and embed sanitization remain isolated to the
  Promotion Media sprint.

### Validation

- Promotion module tests: PASS.
- Full test suite: PASS.
