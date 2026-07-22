# ADR-0005: Prediction Publishing Uses the Application Clock

## Status

Accepted.

## Context

Prediction publishing previously called Laravel's global `now()` helper directly.
That made the publication timestamp depend on the system clock and prevented
focused tests from supplying an exact time through dependency injection.

The application already provides the `App\Core\Contracts\Clock` abstraction
and its production implementation, `SystemClock`.

## Decision

`UpsertPredictionAction` receives `Clock` through constructor injection.

When a prediction changes to the published status without an explicitly
provided publication timestamp, the action uses `Clock::now()`.

The existing behavior remains unchanged for draft and archived predictions:
their `published_at` value is cleared.

## Consequences

- Prediction publishing can be tested with deterministic timestamps.
- Domain behavior no longer directly depends on Laravel's global time helper.
- Production behavior continues to use `SystemClock`.
- Other direct time usages will be migrated separately to keep changes small.
