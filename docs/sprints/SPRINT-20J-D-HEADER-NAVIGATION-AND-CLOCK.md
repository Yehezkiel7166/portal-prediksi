# Sprint 20J-D — Header Navigation and Jakarta Clock

## Objective

Finalize the public header layout without changing existing public routes.

## Scope

- Display only the configured logo visually.
- Preserve site name and tagline as screen-reader-only content.
- Apply the required navigation labels and order.
- Position the live clock at the right side of the header.
- Use locale `id-ID` and timezone `Asia/Jakarta`.
- Render the clock every second.
- Preserve compatibility with Site Configuration and Sprint 20J clock behavior.

## Required Navigation Order

1. Home
2. LiveDraw
3. Prediksi
4. Slot Gacor
5. Result
6. Alat Togel
7. Bukti Jackpot
8. Panduan
9. Keluhan

## Validation

- Targeted regression: 13 tests, 77 assertions, PASS.
- Full regression: 528 tests, 1574 assertions, PASS.
- Current direction check: PASS.
- Permanent Sprint Completion Gate: PASS.
- Repository governance audit: 7/7 PASS.
- Secret and private path audit: PASS.
- Git diff check: PASS.

## Database and Operations

- No migration.
- No scheduler change.
- No queue change.
- No configuration change.
- No dependency change.

## Completion State

Implementation and regression are complete. Commit, push, and remote
verification are mandatory final gates.
