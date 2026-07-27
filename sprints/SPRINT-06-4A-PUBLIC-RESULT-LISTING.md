# Sprint 06.4A — Public Result Listing

## Status

Completed.

## Objective

Provide a public listing page for Result records without changing the
existing Result domain or database architecture.

## Route

The public listing page is available at:

- URI: `/data-result`
- Route name: `results.index`
- Controller: `Frontend\ResultsController`
- View: `frontend.results.index`

## Query Rules

The public listing:

- Displays Result records belonging to active Markets only.
- Loads the related Market using eager loading.
- Orders records by newest result date first.
- Uses the record ID as a deterministic secondary sort.
- Paginates records by 12 items per page.

Inactive Markets and their Result records are not displayed publicly.

## User Interface

The page displays:

- Market name.
- Market code.
- Result date.
- Winning numbers.
- Optional notes.
- Empty-state information when no public Result is available.
- Pagination when more than 12 records are available.

The main frontend navigation now links the Data Result menu item to the
public Result listing.

## Architecture

This sprint reuses the existing:

- `Result` model.
- Result-to-Market relationship.
- `Market::active()` scope.
- Result database structure.

No migration or Result domain modification was required.

## Automated Tests

Feature coverage verifies:

- Public route registration.
- Controller registration.
- Correct frontend view.
- Empty listing state.
- Results from active Markets are displayed.
- Results from inactive Markets are hidden.
- Newest result date is displayed first.
- Pagination uses 12 records per page.
- Header navigation links to the public Result listing.

## Validation

- PHP syntax checks passed.
- Route registration verified.
- Public Result module tests passed.
- Result domain tests passed.
- Full application test suite passed.
