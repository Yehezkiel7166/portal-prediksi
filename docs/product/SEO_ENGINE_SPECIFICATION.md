# SEO ENGINE SPECIFICATION

## Status

This document defines the canonical SEO, SERP, indexing, metadata, schema,
sitemap, redirect, robots, internal-linking, and SEO automation behavior for
Brand 1 and future brands.

This specification is part of the Brand 1 completion baseline.

Implementation MUST follow an explicitly approved sprint while Feature Freeze
is active.

## Primary Objective

The platform MUST provide a durable and sensible automated SEO system.

Automation MUST improve consistency and reduce repetitive administration.

Automation MUST NOT:

- rewrite valid manually approved SEO values without authorization;
- continuously change stable titles;
- create artificial date-based title churn;
- create duplicate or doorway pages;
- fabricate indexing status;
- promise rankings;
- publish invalid metadata;
- bypass brand isolation;
- bypass publication status;
- generate unsafe output.

## Core SEO Philosophy

SEO pages SHOULD accumulate authority on stable canonical URLs.

Recurring-content pages such as Prediction, Result, Market, RTP, Guide, Tool,
and other continuously updated resources SHOULD use evergreen SEO titles.

Bad default example:

`Prediksi SGP Hari Ini 24-07-2026`

This title becomes stale and encourages unnecessary title changes.

Preferred stable example:

`Prediksi Singapore (SGP) Terbaru | Analisis Angka`

The page content may show the latest update date where useful, but the canonical
SEO title SHOULD remain stable unless search intent or page identity changes.

Dates MAY be used in an SEO title only when the date is part of the canonical
identity of the page, such as:

- a historical archive page;
- a specific draw page;
- a time-bound event;
- an annual report;
- a dated announcement;
- a result record whose date is part of the requested search intent.

## Independent Presentation Fields

The CMS MUST treat the following as separate fields or resolved values:

- internal record title;
- display title;
- SEO title;
- H1;
- navigation label;
- breadcrumb label;
- social title;
- slug;
- canonical URL.

These values MAY share a fallback but MUST NOT be assumed to have identical
purposes.

## Manual Locking Rule

Every editable SEO field SHOULD support an explicit manual lock.

At minimum, the system SHOULD support locks for:

- SEO title;
- meta description;
- slug;
- canonical override;
- robots directive;
- social title;
- social description;
- structured-data override.

When a field is locked:

- automated jobs MUST NOT replace it;
- scheduled refreshes MUST preserve it;
- fallback generation MUST preserve it;
- imports MUST preserve it unless an administrator explicitly approves a
  replacement.

Automation SHOULD fill missing or invalid unlocked values.

Automation MUST NOT silently rewrite valid manually approved values.

## SEO Resolution Priority

The canonical resolution priority SHOULD be:

1. valid locked manual value;
2. valid unlocked manual value;
3. module-specific SEO template;
4. brand-level SEO template;
5. safe system fallback.

Invalid values MUST fall back safely and SHOULD generate an audit warning.

## Dynamic SEO Templates

The system MUST support templates by:

- brand;
- module;
- content type;
- market;
- category where applicable;
- locale where applicable.

Supported template variables MAY include:

- brand name;
- market name;
- market code;
- content title;
- category;
- content summary;
- result period;
- result date where canonically required;
- configured suffix.

Template variables MUST be escaped and validated.

Missing variables MUST NOT produce broken punctuation or empty placeholders.

## Recommended Stable Templates

### Home

SEO title:

`{brand_name} | Prediksi, Result, Live Draw dan Alat Togel`

### Prediction Listing

SEO title:

`Prediksi Togel Terbaru | {brand_name}`

### Market Prediction

SEO title:

`Prediksi {market_name} ({market_code}) Terbaru | {brand_name}`

A daily date MUST NOT be appended automatically.

### Result Listing

SEO title:

`Data Result Togel Terbaru | {brand_name}`

### Market Result

SEO title:

`Result {market_name} ({market_code}) Terbaru | {brand_name}`

### Live Draw

SEO title:

`Live Draw Togel dan Hasil Terbaru | {brand_name}`

### RTP

SEO title:

`RTP Slot Terbaru dan Informasi Game | {brand_name}`

### Promotion

SEO title:

`Promosi dan Bonus Terbaru | {brand_name}`

### Jackpot Proof

SEO title:

`Bukti Jackpot Terbaru | {brand_name}`

### Guide

SEO title:

`Panduan Lengkap | {brand_name}`

### Dream Book

SEO title:

`Buku Mimpi dan Tafsir Angka | {brand_name}`

### Lottery Schedule

SEO title:

`Jadwal Togel Terbaru | {brand_name}`

### Paito

SEO title:

`Paito Togel Warna dan Data Result | {brand_name}`

### Shio

SEO title:

`Tabel Shio Togel Terbaru | {brand_name}`

Templates are defaults and MAY be overridden by valid locked manual values.

## Meta Description Engine

The platform MUST support:

- manual descriptions;
- template-generated descriptions;
- safe content-derived fallback;
- maximum-length guidance;
- whitespace normalization;
- HTML stripping;
- duplicate-description warnings;
- empty-description fallback.

Descriptions SHOULD describe the actual page.

Descriptions MUST NOT:

- include unsupported claims;
- contain broken template placeholders;
- contain raw HTML;
- be regenerated on every request;
- change merely because the current date changed.

## Slug Engine

The slug system MUST support:

- deterministic normalization;
- duplicate detection;
- reserved-slug protection;
- brand-aware uniqueness;
- module-aware uniqueness;
- safe transliteration where configured;
- administrator override;
- slug lock;
- historical slug preservation;
- redirect creation after approved slug changes.

Recurring pages SHOULD use evergreen slugs.

Preferred:

`/prediksi-singapore`

Avoid:

`/prediksi-singapore-24-juli-2026`

unless the route represents a canonical dated archive.

## Canonical Engine

Canonical URLs MUST be generated automatically from the resolved public route.

The canonical engine MUST:

- use the correct active brand domain;
- use the preferred protocol and host;
- normalize trailing-slash policy;
- remove irrelevant tracking parameters;
- preserve meaningful pagination behavior;
- avoid cross-brand canonicals;
- avoid canonicalizing published pages to unrelated pages;
- support reviewed administrator override;
- validate overrides;
- audit canonical changes.

Filtered, sorted, and paginated pages MUST follow a documented canonical policy.

## Robots Engine

The system MUST provide controlled robots behavior for:

- index;
- noindex;
- follow;
- nofollow where justified;
- archive behavior where configured;
- snippet controls where configured.

Robots directives MUST be resolved using:

1. protected system rules;
2. publication state;
3. module policy;
4. valid administrator override.

Draft, preview, private, complaint-administration, and unsafe internal routes MUST
NOT become indexable.

The system SHOULD provide a managed robots.txt generator.

robots.txt MUST NOT be treated as a security mechanism.

## Sitemap Engine

The platform MUST generate sitemap output automatically.

The sitemap system SHOULD support:

- sitemap index;
- static-page sitemap;
- prediction sitemap;
- result sitemap;
- market sitemap;
- promotion sitemap;
- guide sitemap;
- blog sitemap where Blog remains public;
- RTP sitemap;
- jackpot-proof sitemap where appropriate;
- dream-book sitemap;
- tool sitemap;
- image sitemap where appropriate;
- video sitemap where Live Draw or configured media qualifies.

Only canonical, published, indexable URLs may appear.

Sitemaps MUST:

- be brand-isolated;
- use the active brand domain;
- remove expired or unpublished entries;
- update after relevant publication changes;
- update after approved slug changes;
- use meaningful last-modified values;
- avoid fake last-modified updates;
- support cache invalidation;
- support deterministic regeneration;
- be covered by automated tests.

## Redirect Manager

The CMS MUST support controlled redirects:

- 301 permanent redirect;
- 302 temporary redirect;
- 410 gone response;
- approved canonical override.

The redirect manager MUST support:

- source-path normalization;
- destination validation;
- redirect-loop prevention;
- redirect-chain warnings;
- cross-brand destination validation;
- conflict detection;
- hit tracking where configured;
- activation and deactivation;
- audit history;
- administrator permission checks.

Approved slug changes SHOULD create a 301 redirect from the previous canonical
slug.

Automation MUST NOT create redirects based only on guesses.

## Structured Data Engine

The system MUST generate valid structured data only when the page content
supports it.

Supported types MAY include:

- Organization;
- WebSite;
- WebPage;
- BreadcrumbList;
- Article;
- BlogPosting;
- FAQPage;
- HowTo;
- ItemList;
- VideoObject where valid;
- ImageObject where valid.

Structured data MUST:

- match visible content;
- use the active brand identity;
- use canonical URLs;
- avoid fabricated ratings;
- avoid fabricated authors;
- avoid fabricated dates;
- avoid unsupported claims;
- validate required fields;
- fail safely when incomplete.

Result, Prediction, Live Draw, and lottery-tool pages MUST NOT be assigned an
unrelated schema type merely to obtain rich results.

## Open Graph and Social Metadata

The platform MUST support automatic and manual:

- Open Graph title;
- Open Graph description;
- Open Graph image;
- Open Graph URL;
- Open Graph type;
- Twitter/X-compatible metadata where configured.

Social metadata SHOULD inherit from resolved SEO metadata when no dedicated
value exists.

Images MUST use brand-aware media and valid absolute URLs.

## SERP Preview

Administration SHOULD provide previews for:

- desktop search appearance;
- mobile search appearance;
- Open Graph sharing;
- Twitter/X-compatible sharing.

The preview MUST be labeled as an approximation.

The system MUST NOT claim that a search engine will display the preview exactly.

## SEO Audit and Scoring

The CMS SHOULD provide deterministic SEO checks for:

- missing title;
- invalid title;
- title length guidance;
- duplicate title;
- unstable date-based title patterns;
- missing description;
- duplicate description;
- missing H1;
- multiple H1 elements;
- invalid canonical;
- cross-brand canonical;
- missing image alternative text;
- broken internal links;
- orphan content;
- noindex conflicts;
- sitemap exclusion;
- structured-data errors;
- redirect chains;
- redirect loops;
- unpublished linked content.

An SEO score MAY summarize these checks.

The score MUST be treated as guidance, not a ranking guarantee.

SEO scoring MUST NOT automatically overwrite content.

## Internal Linking Engine

The system SHOULD generate sensible contextual internal links based on verified
relations.

Examples:

- Market Prediction to the same Market Result;
- Market Result to the same Market Paito;
- Market page to its schedule;
- Guide to relevant tool;
- Prediction to relevant Shio or reference content;
- Promotion to a valid configured destination;
- Blog or Guide to related published content.

Internal links MUST:

- remain within the correct brand;
- target published canonical URLs;
- avoid circular spam patterns;
- avoid excessive repeated anchors;
- avoid hidden links;
- respect administrator exclusions;
- use deterministic relevance rules;
- fail safely when no relevant target exists.

Automation MUST NOT insert irrelevant keyword-stuffed links.

## Indexing Status

The system MAY integrate with verified external sources such as Google Search
Console when credentials and permissions are configured.

Indexing states MUST distinguish:

- known indexed;
- known not indexed;
- submitted;
- excluded;
- blocked;
- unknown;
- not checked.

The CMS MUST NOT infer `Indexed` merely because a URL appears in a sitemap.

The CMS MUST NOT fabricate external indexing data.

Without a verified external source, status MUST remain `unknown` or
`not checked`.

## SERP Monitoring

The platform MAY support SERP monitoring for configured keywords.

SERP monitoring MUST:

- store the keyword;
- store target URL;
- store market, locale, and device context where available;
- store observation date;
- preserve historical observations;
- distinguish brand and non-brand keywords;
- avoid claiming causation from correlation;
- avoid automatic destructive content changes.

Ranking data SHOULD be imported from a verified provider or entered through a
controlled workflow.

No ranking guarantee may be displayed.

## SEO Automation Events

SEO-related regeneration SHOULD occur after relevant confirmed changes:

- content publication;
- content unpublication;
- approved title update;
- approved slug update;
- approved canonical update;
- brand domain change;
- navigation change;
- result confirmation;
- promotion activation or expiration;
- market activation or deactivation;
- media replacement;
- guide publication;
- sitemap-affecting configuration change.

Events SHOULD trigger only the required recalculation.

The system SHOULD avoid rebuilding all SEO data after every minor request.

## Safe Automation Rules

SEO automation MUST be:

- deterministic;
- idempotent;
- brand-aware;
- publication-aware;
- cache-aware;
- queue-safe;
- auditable;
- retry-safe;
- testable.

Automation MUST preserve the last confirmed valid value when a scheduled
generation fails.

Automation failures MUST NOT publish broken metadata.

## Cache and Queue Behavior

SEO output MAY be cached.

Cache keys MUST include the relevant brand and canonical content identity.

SEO cache MUST be invalidated after relevant approved changes.

Queued SEO jobs MUST:

- resolve the correct brand;
- remain idempotent;
- tolerate retries;
- record failure details;
- avoid cross-brand writes.

## Administration

SEO administration SHOULD include:

- brand SEO defaults;
- module templates;
- content-level overrides;
- lock controls;
- preview;
- validation;
- publication visibility;
- canonical override;
- robots override;
- redirect management;
- sitemap status;
- schema validation;
- audit history;
- permission checks.

## Brand Isolation

Every SEO record and generated output MUST resolve the correct brand.

The following MUST NOT leak between brands:

- title templates;
- descriptions;
- canonical domains;
- Open Graph images;
- schemas;
- sitemaps;
- redirects;
- robots policies;
- internal links;
- SERP keyword configurations;
- audit records.

## Testing Requirements

Automated tests MUST cover at least:

- stable evergreen title generation;
- preservation of locked manual titles;
- missing-field fallback;
- invalid-template fallback;
- no automatic daily title mutation;
- canonical brand isolation;
- sitemap brand isolation;
- published-only sitemap inclusion;
- redirect-loop prevention;
- historical slug redirect;
- structured-data validity;
- internal-link brand isolation;
- queue idempotency;
- cache invalidation;
- safe failure behavior.

## Completion Gate

The SEO Engine is not complete until:

- canonical templates exist for all mandatory Brand 1 modules;
- manual locking works;
- titles do not mutate daily without canonical reason;
- sitemap automation works;
- canonical output is verified;
- robots behavior is verified;
- redirect behavior is verified;
- structured data is validated;
- internal linking is brand-safe;
- mobile and desktop output is verified;
- automated tests pass;
- governance validation passes;
- production smoke tests pass.

## Ranking Disclaimer

The system can improve technical consistency, crawlability, metadata quality,
content relationships, and operational discipline.

The system MUST NOT promise or represent guaranteed search ranking.
