# BRAND 1 FRONTEND BASELINE

## Status

This document defines the mandatory functional baseline for Brand 1, also known as the trial brand or default brand.

Brand 1 MUST be completed, tested, and stable before the platform activates more than one production brand.

Brand 1 is not a reduced demonstration brand.

Brand 1 MUST provide the complete public frontend, administration workflow, automation, SEO, cache, scheduler, queue, event, audit, and operational behavior required by the project.

## Canonical Brand Identity

During the single-brand phase:

- exactly one default active brand may be used;
- the application MUST resolve Brand 1 automatically;
- public users MUST NOT be required to select a brand;
- console commands, queues, events, listeners, and schedulers MUST use Brand 1 automatically;
- existing application behavior MUST remain compatible with the pre-multi-brand system;
- absence of a second brand MUST NOT disable any feature.

The canonical seeded trial brand is:

- code: `DEFAULT`
- slug: `default`
- role: Brand 1 / trial brand / single-brand fallback

Production identity values such as name, domain, logo, and visual configuration may be changed later without reducing the required capabilities.

## Mandatory Public Navigation

Brand 1 MUST provide the following public navigation:

1. Home
2. Live Draw
3. Data Result
4. Prediksi Togel
5. Slot Gacor / RTP
6. Bukti Jackpot
7. Promosi
8. Keluhan
9. Panduan
10. Alat Togel

## Mandatory Alat Togel Modules

The Alat Togel navigation MUST provide:

1. Jadwal Togel
2. BBFS Generator
3. Buku Mimpi
4. Paito Togel Warna
5. Konversi Angka SGP
6. Tabel Shio

## Mandatory Frontend Capabilities

Every applicable Brand 1 frontend module MUST support:

- responsive desktop layout;
- responsive mobile layout;
- public routes;
- navigation integration;
- empty state;
- loading state where applicable;
- failure state;
- active and inactive state;
- publication status;
- stable pagination where applicable;
- cache compatibility;
- SEO metadata;
- canonical URL;
- Open Graph metadata;
- structured data where applicable;
- sitemap integration where applicable;
- breadcrumb integration where applicable;
- Brand 1 visual identity;
- safe output rendering;
- automated tests.

## Home

The Brand 1 homepage MUST be capable of presenting:

- active Live Draw information;
- latest Result fallback;
- current Predictions;
- active RTP content;
- latest Jackpot Proof;
- active Promotions;
- public guides;
- access to complaints;
- access to all lottery tools;
- configured banners;
- configured navigation;
- configured footer;
- brand SEO metadata.

## Live Draw

Brand 1 Live Draw MUST support:

- market schedule awareness;
- automatic live status;
- HLS playback when configured;
- stream unavailable handling;
- latest Result fallback when Live Draw is unavailable or offline;
- cache invalidation;
- scheduler integration;
- audit and operational logging;
- market-specific display;
- stable behavior when only Brand 1 exists.

## Result

Brand 1 Result MUST support:

- listing;
- detail or contextual display where required;
- filtering by market;
- filtering by date;
- latest Result resolution;
- Live Draw fallback integration;
- Prediction automation integration;
- Result-based Paito generation;
- validation before confirmation;
- administrator correction workflow;
- audit history;
- cache invalidation.

A Result number is not globally unique.

The same number MAY exist for different dates, periods, markets, or brands.

Duplicate validation MUST use draw identity:

- brand_id;
- market_id;
- draw_date;
- draw_period.

If an existing draw identity is found, administrator validation is required.

## Prediction

Brand 1 Prediction MUST support:

- automatic generation;
- scheduled generation;
- manual generation where permitted;
- draft workflow;
- publication workflow;
- market relation;
- Result-triggered generation where configured;
- duplicate-safe and idempotent execution;
- cache invalidation;
- audit logging.

## Slot Gacor / RTP

Brand 1 RTP MUST support:

- RTP listing;
- game information;
- active and inactive state;
- ordering;
- optional categorization;
- scheduled updates where configured;
- administrator lock where configured;
- value validation;
- historical or audit information where required;
- cache invalidation;
- SEO metadata.

RTP automation MUST NOT publish invalid values.

Fallback MUST use the last confirmed valid value when an automated update fails.

## Bukti Jackpot

Brand 1 Jackpot Proof MUST support:

- administrator upload;
- title and description;
- image optimization;
- thumbnail generation where configured;
- brand watermark where configured;
- draft status;
- moderation;
- publication;
- ordering;
- publication date;
- SEO metadata;
- cache invalidation.

Uploaded proof MUST NOT be published automatically without the configured moderation workflow.

## Promosi

Brand 1 Promotion MUST support:

- listing;
- detail;
- start date;
- end date;
- scheduled publication;
- automatic activation;
- automatic expiration;
- active and inactive state;
- banners or images;
- SEO metadata;
- cache invalidation.

## Keluhan

Brand 1 Complaint MUST support:

- public complaint submission;
- ticket number generation;
- validation;
- anti-spam protection;
- rate limiting;
- Open status;
- In Progress status;
- Resolved status;
- Rejected or Closed status where configured;
- administrator response;
- status history;
- audit history;
- optional attachment restrictions;
- administrator notification;
- safe public privacy behavior.

Complaint data MUST NOT be exposed publicly unless explicitly designed as anonymized public content.

## Panduan

Brand 1 Guide MUST support:

- listing;
- detail;
- categories where configured;
- draft;
- publication;
- scheduled publication;
- ordering;
- version-aware updates where configured;
- table of contents where applicable;
- internal links;
- SEO metadata;
- sitemap integration;
- cache invalidation.

Guides may include operational instructions such as registration, deposit, withdrawal, account usage, security, and general platform navigation when configured.

## Jadwal Togel

Jadwal Togel MUST use the Market configuration as its source of truth.

It MUST support:

- active days;
- opening time;
- closing time;
- Result time;
- timezone;
- holiday status;
- upcoming status;
- open status;
- live status;
- closed status;
- Result available status.

A separate contradictory manual schedule MUST NOT become the canonical source.

## BBFS Generator

The BBFS Generator MUST support:

- validated numeric input;
- configurable input limits;
- deterministic generation for the same algorithm and input;
- safe output;
- rate limiting;
- automated tests;
- no unnecessary storage of visitor input.

## Buku Mimpi

Buku Mimpi MUST support:

- indexed content;
- search;
- slug;
- detail page;
- pagination where required;
- related content where configured;
- SEO metadata;
- sitemap integration;
- cache compatibility.

## Paito Togel Warna

Paito Togel Warna MUST use confirmed Result data as its source of truth.

It MUST support:

- market selection;
- date or period range;
- automatic update after confirmed Results;
- color mapping;
- stable historical display;
- cache invalidation;
- automated tests.

Result data MUST NOT be duplicated manually solely for Paito generation.

## Konversi Angka SGP

Konversi Angka SGP MUST support:

- validated input;
- immediate conversion;
- documented conversion rules;
- safe output;
- deterministic behavior;
- automated tests;
- no unnecessary database persistence.

## Tabel Shio

Tabel Shio MUST support:

- year-aware mapping;
- active mapping;
- historical mapping where required;
- administrator verification;
- scheduled yearly activation where configured;
- cache invalidation;
- automated tests.

## Mandatory Automation

Brand 1 MUST preserve and support:

- Market schedule automation;
- Live Draw status automation;
- Live Draw latest Result fallback;
- Result confirmation workflow;
- Result cache invalidation;
- Prediction generation;
- Promotion scheduling;
- RTP update scheduling where enabled;
- Paito regeneration after confirmed Result;
- complaint notification;
- SEO and sitemap updates;
- queue retry;
- failed-job handling;
- scheduler health monitoring;
- backup automation;
- audit logging.

## Result Confirmation Rule

A newly entered Result MUST NOT be rejected solely because its number appeared previously.

The canonical flow is:

1. validate format;
2. resolve Brand 1;
3. resolve Market;
4. resolve draw date and period;
5. search by canonical draw identity;
6. allow creation when no identical draw identity exists;
7. request administrator confirmation when the draw identity exists;
8. record correction reason when replacing confirmed data;
9. record previous and new values;
10. invalidate relevant caches;
11. update Live Draw fallback;
12. trigger configured downstream automation.

Prediction automation MUST run only after the Result has been confirmed.

## Administration

Brand 1 MUST have administration capability for every module that requires managed content or configuration.

Administration MUST include, where applicable:

- create;
- read;
- update;
- activate;
- deactivate;
- draft;
- publish;
- schedule;
- reorder;
- archive;
- moderate;
- correct;
- audit;
- permission checks.

## Stability Gate

Brand 1 MUST NOT be declared complete until:

- all mandatory routes are available;
- all mandatory navigation items are available;
- all required administration workflows are available;
- all required automation is operational;
- existing Live Draw behavior remains stable;
- existing Result behavior remains stable;
- existing Prediction behavior remains stable;
- scheduler execution is verified;
- queue execution is verified;
- cache invalidation is verified;
- SEO output is verified;
- mobile frontend is verified;
- desktop frontend is verified;
- regression tests pass;
- governance checks pass;
- production smoke tests pass.

## Multi-Brand Gate

Multi-brand production activation is prohibited until Brand 1 passes the complete stability gate.

Future brands MUST extend the stable Brand 1 architecture.

Multi-brand development MUST NOT remove, bypass, or reduce Brand 1 functionality.

<!-- BEGIN BRAND-1-LISTING-DISPLAY-MODES -->
## Frontend Listing Display Modes

Every applicable Brand 1 frontend listing MUST support two presentation modes:

1. Card View
2. List View

The requirement applies, where structurally appropriate, to:

- Live Draw;
- Data Result;
- Prediction;
- Slot Gacor / RTP;
- Jackpot Proof;
- Promotion;
- Guide;
- Blog;
- Lottery Schedule;
- Dream Book;
- Color Paito.

Interactive tools that do not present reusable listing output are not required
to expose a display-mode toggle.

### Card View

Card View MUST support:

- responsive grid presentation;
- image or thumbnail where available;
- title;
- summary where applicable;
- relevant metadata;
- status indicator where applicable;
- primary action or detail link;
- accessible keyboard navigation;
- consistent empty, loading, and failure states.

### List View

List View MUST support:

- compact vertical presentation;
- image or thumbnail where available;
- title;
- summary where applicable;
- relevant metadata;
- status indicator where applicable;
- primary action or detail link;
- accessible keyboard navigation;
- consistent empty, loading, and failure states.

### Display Preference

The frontend MUST preserve the visitor's selected display mode through a safe
client-side preference mechanism such as localStorage or a first-party cookie.

The stored preference:

- MUST NOT contain sensitive information;
- MUST NOT change the underlying query;
- MUST NOT bypass publication or brand visibility rules;
- MUST fall back safely when unavailable or invalid;
- SHOULD be reusable between compatible listing pages where appropriate.

Each module MAY define its own default display mode.

### Behavioral Consistency

Switching between Card View and List View MUST NOT change:

- canonical query results;
- brand isolation;
- publication visibility;
- filtering;
- sorting;
- pagination;
- canonical URL;
- SEO metadata;
- cache identity;
- authorization;
- result ordering.

Only presentation behavior may change.

### Shared Listing Architecture

Applicable modules SHOULD use reusable frontend listing behavior for:

- display-mode toggle;
- filter controls;
- sort controls;
- pagination;
- empty state;
- loading state;
- failure state;
- responsive behavior;
- accessibility;
- preference persistence.

Module-specific renderers MAY determine the content shown in each Card View or
List View item.
<!-- END BRAND-1-LISTING-DISPLAY-MODES -->

<!-- BEGIN BRAND-1-SEO-ENGINE -->
## Mandatory SEO and SERP Engine

Brand 1 MUST implement the canonical behavior defined in
[SEO_ENGINE_SPECIFICATION.md](SEO_ENGINE_SPECIFICATION.md).

Brand 1 SEO completion includes:

- stable evergreen SEO titles;
- independently resolved display title, SEO title, H1, navigation label,
  breadcrumb label, social title, slug, and canonical URL;
- manual lock protection for approved SEO values;
- module-level and brand-level SEO templates;
- safe meta-description generation;
- automatic canonical URLs;
- automatic robots directives;
- automatic sitemap generation;
- managed redirects;
- safe structured data;
- Open Graph and social metadata;
- SERP preview;
- deterministic SEO auditing;
- sensible internal linking;
- optional verified indexing integration;
- optional verified SERP monitoring;
- cache, queue, event, scheduler, and audit compatibility;
- brand isolation;
- automated tests.

Recurring pages MUST NOT automatically append the current date to their SEO
title or slug unless that date is part of the canonical page identity.

Automation MUST preserve valid manually approved and locked values.

SEO automation MUST NOT promise rankings or fabricate indexing data.
<!-- END BRAND-1-SEO-ENGINE -->
