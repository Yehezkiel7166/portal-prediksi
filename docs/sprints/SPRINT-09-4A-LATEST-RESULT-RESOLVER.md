# Sprint 09.4A — Latest Result Resolver

## Status

Completed.

## Objective

Provide a reusable domain service for retrieving the latest Result
belonging to a Market.

## Component

- `App\Domains\Result\Support\LatestResultResolver`

## API

The resolver supports:

- `forMarket(Market $market): ?Result`
- `forMarketId(int $marketId): ?Result`

## Ordering

The latest Result is determined by:

1. `result_date` descending
2. `id` descending as defensive deterministic ordering

The database already enforces a unique combination of `market_id` and
`result_date`, so a Market cannot normally contain two Results for the
same date.

## Database

No migration was required.

The resolver uses the existing `market_id` relationship between Markets
and Results.

## Boundaries

This sprint does not include:

- Live Draw controller integration
- Live Draw frontend rendering
- caching
- status automation changes
- Result creation or synchronization
