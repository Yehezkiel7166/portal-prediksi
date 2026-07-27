# Sprint 07.2 — Public Promotion Frontend

## Status

Completed.

## Objective

Provide public listing and detail pages for published promotions.

## Routes

- `GET /promosi`
- Route name: `promotions.index`
- Controller: `Frontend\PromotionsController`

- `GET /promosi/{slug}`
- Route name: `promotions.show`
- Controller: `Frontend\PromotionDetailController`

## Publication Rules

Public pages only expose promotions when:

- status is `published`
- `published_at` is not null
- `published_at` is not in the future

Draft, archived, future, and unknown promotions return no public detail.

## Frontend

Added:

- `resources/views/frontend/promotions/index.blade.php`
- `resources/views/frontend/promotions/show.blade.php`

The listing supports pagination with 12 promotions per page.

Supported media rendering:

- uploaded image
- direct image URL
- sanitized embed URL foundation

Media uses responsive aspect-ratio containers and focal-point positioning.

## SEO

Promotion detail pages include:

- dynamic page title
- meta description
- canonical URL
- Open Graph title
- Open Graph description
- Open Graph URL
- Open Graph image when available

## Tests

`PublicPromotionFrontendTest` covers:

- published-only listing
- pagination
- published detail rendering
- draft and future promotion 404 responses
- unknown promotion 404 response
