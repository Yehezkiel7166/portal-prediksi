# Prediction Update 01 — Market Relation

## Status

Completed.

## Objective

Replace the legacy Prediction market string with a proper relationship to
the centralized Market master data.

This update was completed before building the public Prediction listing so
the frontend does not depend on duplicated or inconsistent market values.

## Previous Structure

The `predictions` table stored the market as a standalone string:

- `market`
- `prediction_date`
- unique combination of `market` and `prediction_date`

This structure duplicated data already managed by the Market module.

## New Structure

The `predictions` table now stores:

- `market_id`
- `prediction_date`
- unique combination of `market_id` and `prediction_date`

`market_id` is a foreign key referencing the `markets` table.

Foreign-key behavior:

- Market ID updates cascade.
- Markets referenced by predictions cannot be deleted.
- The database enforces one prediction per market and date.

## Domain Changes

### Prediction

The Prediction model now:

- Includes `market_id` in mass-assignable attributes.
- Defines a `belongsTo` relationship to Market.
- No longer stores a standalone market string.

### Market

The Market model now defines:

- `predictions()` as a `hasMany` relationship.
- The existing `results()` relationship remains available.

### UpsertPredictionAction

Prediction creation and updates now:

- Require a valid `market_id`.
- Validate that the selected Market exists.
- Enforce unique Market and prediction-date combinations.
- Preserve application-clock handling for publication timestamps.

## Administration Changes

The Filament Prediction resource now:

- Uses a searchable Market relationship selector.
- Preloads available Markets.
- Orders Markets by `sort_order` and name.
- Displays and searches the related Market name in the table.

## Factory and Test Changes

The Prediction factory now creates or associates a Market through
`market_id`.

Prediction tests now verify:

- Creation using an existing Market.
- Prediction-to-Market relationship.
- Rejection of invalid Market IDs.
- Duplicate Market and date protection.
- Use of the application Clock when publishing.
- Filament administrator access rules.

## Migration Safety

At deployment time, the production Prediction table contained zero records.

The update therefore required no data backfill. The migration:

1. Adds the Market foreign key.
2. Replaces the legacy unique index.
3. Removes the legacy market string.
4. Makes `market_id` required.

No historical migration was modified.

## Validation

The following checks passed:

- PHP syntax checks for all modified PHP files.
- Prediction module: 9 tests, 16 assertions.
- Market module: 8 tests, 13 assertions.
- Result module: 7 tests, 9 assertions.
- Full application suite: 67 tests, 160 assertions.

## Result

Prediction, Result, and future public frontend features can now use the same
centralized Market records.

The next frontend update can safely build public Prediction listings using
the Prediction-to-Market relationship.
