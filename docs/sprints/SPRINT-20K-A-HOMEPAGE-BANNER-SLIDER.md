# Sprint 20K-A — Homepage Banner Slider

## Objective

Add brand-scoped homepage banner management and a responsive homepage slider.

## Delivered Scope

- Homepage banner migration, model, factory, and validated upsert action.
- Brand ownership and query isolation.
- Filament CRUD with desktop and mobile image uploads.
- Publication and expiration scheduling.
- Sort order and focal-point configuration.
- Optional HTTP/HTTPS CTA.
- Responsive desktop and mobile images.
- Five-second autoplay.
- Previous, next, and indicator controls.
- Pause on hover, focus, and hidden browser tab.
- Static fallback when no published banner exists.
- Inline runtime JavaScript because Node.js and npm are unavailable on server.

## Validation

- Targeted regression: 32 tests / 114 assertions / PASS.
- Full regression: 554 tests / 1641 assertions / PASS.
- Blade compile: PASS.
- Migration pretend: PASS.
- Governance audit: PASS.
- Secret audit: PASS.
- Git diff check: PASS.

## Operational State

The production migration has not been executed. It remains a separate guarded
deployment step followed by admin and public homepage smoke verification.
