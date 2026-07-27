# Sprint 06.2A — Public Prediction Listing

## Status

Completed.

## Objective

Provide a public, paginated Prediction listing using the normalized
Prediction-to-Market relationship.

This update intentionally focuses only on the listing foundation. Market
filters, date filters, detail pages, canonical metadata, structured data,
and other SEO enhancements remain separate updates.

## Route

The public listing is available at:

- URI: `/prediksi-togel`
- Route name: `predictions.index`
- Controller: `Frontend\PredictionsController`

## Query Rules

The listing only includes Predictions that:

- Have status `published`.
- Have a non-null publication timestamp.
- Have a publication timestamp that is not in the future.
- Belong to an active Market.

The query eager-loads the related Market to avoid N+1 database queries.

Predictions are ordered by:

1. Prediction date, newest first.
2. Publication time, newest first.
3. Record ID, newest first.

## Pagination

The public page displays twelve Predictions per page.

Laravel's standard paginator is used so future filtering can preserve query
parameters without replacing the listing architecture.

## User Interface

Each Prediction card displays:

- Market name.
- Market code.
- Prediction date.
- Predicted numbers.
- Optional notes.
- Publication timestamp.

An empty state is displayed when no public Prediction is available.

The main navigation now links the `Prediksi Togel` menu item to the listing
route and visually indicates the active page.

## Security and Publication Safety

Draft Predictions are not displayed.

Archived Predictions are not displayed.

Future-scheduled Predictions are not displayed before their publication
time.

Predictions belonging to inactive Markets are not displayed.

## Automated Tests

The frontend feature tests verify:

- Route and controller registration.
- Empty-list rendering.
- Published Prediction visibility.
- Draft Prediction exclusion.
- Future publication exclusion.
- Inactive Market exclusion.
- Newest-first ordering.
- Twelve-record pagination.

## Deferred Work

The following are intentionally excluded from this patch:

- Market filtering.
- Date filtering.
- Prediction detail pages.
- Canonical URLs.
- Open Graph metadata.
- Structured data.
- Breadcrumbs.
- XML or RSS feeds.
- Public search.

These capabilities can be added incrementally after the listing foundation
is stable.
