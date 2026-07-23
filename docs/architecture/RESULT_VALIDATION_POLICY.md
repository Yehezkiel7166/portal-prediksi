# RESULT VALIDATION POLICY

## Principle

A result number is NOT globally unique.

The same result number MAY legally appear on different:

- draw dates
- draw periods
- markets
- brands

Therefore the system MUST NOT reject a result solely because the result number already exists.

## Canonical Draw Identity

A draw is uniquely identified by:

- brand_id
- market_id
- draw_date
- draw_period

NOT by the result number.

## Duplicate Handling

If the same draw identity already exists:

- administrator must receive a validation dialog
- administrator may:
    - cancel
    - update existing draw
    - save as correction

## Correction

Every correction MUST store:

- previous value
- new value
- reason
- administrator
- timestamp

No correction may overwrite history without audit logging.

## Automation

Prediction generation is triggered only after the draw has been confirmed.

Cache invalidation happens after confirmation.

LiveDraw fallback is updated after confirmation.

## Testing

Tests MUST verify:

- same number on different dates
- same number on different markets
- same number on different brands
- correction workflow
- audit logging
