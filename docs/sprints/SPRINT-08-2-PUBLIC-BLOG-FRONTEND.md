# Sprint 08.2 — Public Blog Frontend

## Status

Completed.

## Objective

Provide public listing and detail pages for published Blog posts.

## Public Routes

- `/blog`
- `/blog/{slug}`

Route names:

- `blog.index`
- `blog.show`

## Controllers

- `Frontend\BlogsController`
- `Frontend\BlogDetailController`

## Views

- `frontend.blog.index`
- `frontend.blog.show`

## Listing Rules

The public Blog listing:

- Only displays published posts.
- Excludes draft and archived posts.
- Excludes posts with a publication time in the future.
- Uses the Blog model ordering scope.
- Paginates by 12 records.
- Supports uploaded images and direct image URLs.
- Uses responsive 16:9 media containers.
- Applies the configured focal point.

## Detail Rules

The detail page:

- Resolves posts by slug.
- Only displays currently published posts.
- Returns HTTP 404 for draft, future, archived, or unknown posts.
- Displays the title, excerpt, content, image, and publication time.

## SEO

The Blog listing includes:

- Page title.
- Meta description.
- Canonical URL.
- Open Graph title.
- Open Graph description.
- Open Graph URL.
- Open Graph website type.

The Blog detail includes:

- Custom SEO title with title fallback.
- Custom SEO description with excerpt fallback.
- Canonical URL.
- Open Graph article metadata.
- Publication timestamp.
- Open Graph image for upload and URL image sources.

## Tests

Coverage includes:

- Published-only listing.
- Draft exclusion.
- Future publication exclusion.
- Pagination.
- Published detail response.
- Draft detail 404.
- Future detail 404.
- Unknown slug 404.
- SEO metadata rendering.
