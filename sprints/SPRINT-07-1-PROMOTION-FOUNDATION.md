# Sprint 07.1 — Promotion Foundation

## Status

Completed.

## Objective

Create the CMS-driven Promotion domain and Filament administration
foundation without introducing a public frontend route.

## Architecture

The module uses:

- `App\Domains\Promotion\Models\Promotion`
- `App\Domains\Promotion\Actions\UpsertPromotionAction`
- `App\Filament\Resources\Promotions\PromotionResource`
- Dedicated Form, Table, List, Create, and Edit classes
- `Database\Factories\PromotionFactory`

Filament Create and Edit pages delegate persistence to the domain action.

## Media Foundation

Promotion media supports three source types:

- Uploaded image
- Direct image URL
- HTTPS embed URL

Administrators select the source and focal point. They do not configure
manual image dimensions.

Raw JavaScript and arbitrary script input are not supported.

Provider whitelisting, sanitization, remote media caching, responsive
derivatives, WebP/AVIF generation, and automatic crop presets are reserved
for the Promotion Media sprint.

## Publication

Supported statuses:

- Draft
- Published
- Archived

Published records require `published_at`. The `published` scope excludes
draft, archived, and future-scheduled records.

## Database Safety

The sprint adds a new `promotions` migration. No existing migration was
modified.

## Validation

The module includes tests for:

- Creation and normalization
- Updating existing records
- Unique slugs
- Media-source validation
- Publication visibility
- Filament access control
- Filament delegation to `UpsertPromotionAction`
