# Sprint 08.1 — Blog Foundation

## Status

Completed.

## Objective

Provide the Blog domain foundation and Filament administration interface for
creating, editing, publishing, ordering, and managing blog articles.

## Database

The `blog_posts` table stores:

- Title
- Unique slug
- Excerpt
- Rich article content
- Image source
- Uploaded image path
- Direct image URL
- Image focal point
- Publication status
- Publication timestamp
- Sort order
- SEO title
- SEO description
- Internal notes

The migration is:

- `2026_07_20_150000_create_blog_posts_table.php`

## Domain

The Blog domain contains:

- `App\Domains\Blog\Models\BlogPost`
- `App\Domains\Blog\Actions\UpsertBlogPostAction`

The model provides:

- Draft, published, and archived statuses
- Upload and URL image sources
- Published article scope
- Consistent article ordering
- Factory support

The upsert action provides:

- Title normalization
- Automatic slug generation
- Unique slug validation
- Image source validation
- Direct URL validation
- Unused image field cleanup
- Publication validation
- Centralized create and update handling

## Filament Administration

The Blog resource is available at:

- `/admin/blog-posts`
- `/admin/blog-posts/create`
- `/admin/blog-posts/{record}/edit`

The administration interface supports:

- Article title and slug
- Excerpt
- Rich article content
- Uploaded image or direct image URL
- Focal point selection
- Draft, published, and archived statuses
- Publication scheduling
- Sort order
- SEO metadata
- Internal notes
- Searchable and sortable article listing
- Edit and delete actions

Administrators only choose the image source and focal point. Responsive image
sizing, crop behavior, ratios, thumbnails, and frontend breakpoints remain
system responsibilities.

## Tests

The Blog foundation includes:

- Domain action creation test
- Domain action update test
- Unique slug validation test
- Image URL validation test
- Published scope test
- Admin resource access test
- Non-admin access rejection test
- Create page test
- Edit page test
- Filament create action integration test
- Filament edit action integration test

## Scope Boundary

This sprint only establishes the Blog domain and administration foundation.

Public Blog listing, detail pages, frontend routes, templates, metadata output,
and public navigation integration belong to a separate sprint.
