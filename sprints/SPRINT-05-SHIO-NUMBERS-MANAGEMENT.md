# Sprint 05 — Shio Numbers Management

## Status

Completed.

## Objective

Manage Shio entries directly inside each Shio calendar period through a
Filament relation manager.

A separate Shio Number resource is intentionally not used.

## Implementation

- Added `ShioNumbersRelationManager`.
- Registered the relation manager on `ShioPeriodResource`.
- Used the existing `ShioPeriod::shios()` relationship.
- Added Shio creation, editing, deletion, and bulk deletion.
- Added searchable and sortable Shio records.
- Added drag-and-drop ordering through `sort_order`.
- Added JSON-backed number entry through Filament `TagsInput`.
- Added owner-period scope tests to prevent records from another period
  appearing in the relation manager.

## Managed Fields

Each Shio entry contains:

- Shio name
- JSON array of numbers
- optional icon reference
- sort order

## Validation Completed

- PHP syntax checks
- Shio module tests
- Full application test suite
- Git diff validation

## Deferred Work

The following items are not included in this update:

- Shio banner generator
- AI image generator
- public Shio dropdown
- banner template positioning
