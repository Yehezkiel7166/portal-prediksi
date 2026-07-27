# Sprint 06.2C — Public Prediction Filtering

## Status

Completed.

## Objective

Add validated public filtering to the Prediction listing without changing
the underlying route or publication rules.

## Supported Filters

The listing supports the following optional GET parameters:

- `market`: active Market slug.
- `date`: Prediction date in `YYYY-MM-DD` format.

Both filters may be used independently or together.

## Request Validation

`PredictionIndexRequest` validates and normalizes public query parameters.

Market filters must reference an active Market. Inactive or unknown Market
slugs are rejected.

Date filters must use the exact `YYYY-MM-DD` format.

Invalid filters redirect to the clean public Prediction listing and expose
validation errors through the session.

## Query Architecture

The base public query continues to enforce:

- Published status.
- Effective publication timestamp.
- Active Market relationship.
- Eager-loaded Market data.
- Newest-first ordering.

Optional filters are applied through conditional Eloquent query clauses.

## Market Options

Filter options are loaded from active Markets using the shared `active` and
`ordered` model scopes.

This supports a large and configurable Market catalogue without hardcoded
frontend options.

## Pagination

The existing twelve-record pagination is retained.

`withQueryString()` preserves active Market and date filters while users
navigate between result pages.

## User Interface

The public listing now includes:

- Active Market selector.
- Prediction date selector.
- Filter submission button.
- Reset link when a filter is active.
- Active-filter indicator.
- Filter-aware empty state.
- Total matching Prediction count.

## Automated Tests

Feature coverage verifies:

- Only active Markets appear as filter options.
- Market options respect configured ordering.
- Filtering by Market.
- Filtering by date.
- Combined Market and date filtering.
- Invalid Market rejection.
- Invalid date rejection.
- Filter preservation across pagination.

## Deferred Work

The following remain separate updates:

- Public Prediction detail pages.
- Canonical URLs.
- Open Graph metadata.
- Structured data.
- Breadcrumbs.
- Public result pages.
- Live Draw pages.
