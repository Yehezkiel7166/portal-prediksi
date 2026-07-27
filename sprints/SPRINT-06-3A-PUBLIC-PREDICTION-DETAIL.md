# Sprint 06.3A — Public Prediction Detail

## Status

Completed.

## Objective

Provide a dedicated public page for each published Prediction without
changing the existing listing architecture.

## Route

The public detail page is available at:

- URI: `/prediksi-togel/{marketSlug}/{predictionDate}`
- Route name: `predictions.show`
- Controller: `Frontend\PredictionDetailController`

## Query Rules

The detail page only returns a Prediction when all conditions are met:

- Status is `published`.
- Publication timestamp exists.
- Publication timestamp is not in the future.
- Related Market is active.
- Market slug matches the requested URL.
- Prediction date matches the requested URL.

Otherwise the request returns HTTP 404.

## User Interface

The page displays:

- Market name.
- Market code.
- Prediction date.
- Market timezone.
- Predicted numbers.
- Optional notes.
- Publication timestamp.
- Link back to the filtered Prediction listing.

## Automated Tests

Feature coverage verifies:

- Route registration.
- Controller registration.
- Published Prediction rendering.
- Draft Prediction returns 404.
- Future publication returns 404.
- Inactive Market returns 404.
- Invalid slug returns 404.
- Invalid date returns 404.

## Validation

- PHP syntax checks passed.
- Frontend Prediction module:
  17 tests passed.
- Full application suite:
  84 tests / 230 assertions passed.
