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
