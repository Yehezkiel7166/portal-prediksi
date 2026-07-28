# Sprint 19A-3 — Site Configuration Frontend Integration

## Objective

Integrate the brand-scoped Site Configuration resolver into every public frontend view through one shared view composer.

## Baseline

- Branch at inspection: `work`.
- Baseline HEAD: `6099abf713897fb7a59591d43fa279b500b00acc`.
- Sprint 19A-1 data foundation and Sprint 19A-2 administration are present in source and tests.
- No database migration is introduced.

## Delivered Scope

- shared `frontend.*` view composer;
- database-backed header identity, logo, and tagline;
- database-backed footer text, contact details, WhatsApp, and social links;
- default SEO title and description fallback;
- configured favicon consumption;
- HTTP/HTTPS-only rendering for external asset and social URLs;
- inactive or missing configuration fallback;
- frontend integration regression coverage.

## Security and Operations

External URLs are filtered at resolution time, so records written outside Filament cannot render non-HTTP schemes. Filament logo and favicon inputs also explicitly constrain URL schemes. Configuration continues to use the existing brand-scoped cache and invalidation behavior. No secret, queue, scheduler, backup, deployment, or migration change is introduced.

## Validation

- PHP syntax validation: PASS.
- Repository governance audit with the available PHP CLI: 7/7 PASS.
- Targeted/full Laravel regression: environment-blocked because dependencies cannot be downloaded through the network proxy and `vendor/autoload.php` is absent.
- Remote verification: environment-blocked because GitHub access returns HTTP 403.
