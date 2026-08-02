# Sprint 20I — Bulk Import Result (CSV/XLSX)

## Status

**COMPLETE — pending commit push and remote verification**

## Baseline

- Branch: `main`
- Baseline commit: `68cd3af15d7079f94e9bd39c62e5d01b895c2664`
- PHP: `8.3.30`
- Database migration: not required

## RED

The Result module did not contain bulk CSV/XLSX import capability.

## Implementation

- Brand-scoped CSV import.
- Brand-scoped XLSX import through OpenSpout.
- Existing `UpsertResultAction` reused.
- Existing records updated by market and result date.
- New records created transactionally.
- Unknown markets rejected.
- Cross-brand markets rejected.
- Duplicate market/date rows rejected.
- Invalid files produce no partial writes.
- Maximum 2,000 data rows.
- Filament Result import action added.
- Temporary uploads deleted after processing.

## File format

Required headers:

- `market_code`
- `result_date`
- `winning_numbers`

Optional header:

- `notes`

Supported date formats:

- `YYYY-MM-DD`
- `DD-MM-YYYY`
- `DD/MM/YYYY`
- `YYYY/MM/DD`

## Verification

- Targeted tests: `20`
- Targeted assertions: `36`
- Full tests: `508`
- Full assertions: `1487`
- PHP syntax validation: PASS
- Git diff check: PASS
