# Sprint 09.4C — Live Draw Result UI

## Status

Completed.

## Objective

Display the latest published market result on the public Live Draw page
when a market is not currently broadcasting.

## Baseline

The Live Draw controller already attaches a `latestResult` relation to
every visible Live Draw using `LatestResultResolver`.

The Blade view previously did not present that relation to visitors.

## Implementation

The public Live Draw card now displays a latest-result panel when:

- The Live Draw status is not `live`.
- A latest result is available for the associated market.

The panel contains:

- The label `Hasil terbaru`.
- The latest result date.
- The winning number.
- Links to the filtered public Data Result page for that market.

During an active Live Draw broadcast, the historical result panel is
hidden so the live stream remains the primary content.

When no result is available, the existing scheduled, finished, or offline
status presentation remains unchanged.

## Scope

This sprint changes only:

- Public Live Draw result presentation.
- Regression coverage for the result presentation.

This sprint does not change:

- `LiveDrawController`.
- `LatestResultResolver`.
- Live Draw status automation.
- Result persistence.
- Database migrations.
- Live stream embed behavior.
- Admin resources.

## Regression Coverage

Feature tests verify that:

- A non-live market displays only its latest result.
- The result date and winning number are rendered.
- The result panel links to the filtered Data Result page.
- An active live stream hides the previous result panel.
- A Live Draw without results keeps the existing status UI.

## Verification

The sprint was verified with:

- PHP syntax checks.
- Blade compilation.
- Live Draw frontend module tests.
- Full automated test suite.
- Git whitespace validation.
