# Sprint 05 — Shio Banner Generation Engine

## Status

Completed.

## Objective

Provide a reusable domain action that generates a Shio banner from an uploaded
template without coupling image processing to Filament pages.

## Implementation

- Added `GenerateShioBannerAction`.
- Reads templates through Laravel's public filesystem disk.
- Accepts valid JPEG, PNG, and WebP template images.
- Rejects files outside the Shio template directory.
- Rejects missing, invalid, unsupported, or undersized templates.
- Renders the Shio title, date range, names, and number mappings.
- Uses deterministic generated-banner paths.
- Produces PNG output under `shio/generated`.
- Updates the existing `generated_banner` model attribute.
- Preserves the original template dimensions.

## Architecture

The generator is implemented as a domain action and does not depend on:

- Filament actions
- HTTP requests
- controllers
- page lifecycle hooks
- server-specific public paths

The engine can therefore be invoked later from Filament, an Artisan command,
a queue job, or a scheduler.

## Database

No migration was required.

The existing `generated_banner` column stores the generated file path.

## Deferred Work

This update does not include:

- Filament generate button
- automatic generation after save
- configurable text coordinates
- custom font uploads
- AI image generation
- public frontend delivery
