# WIDGET ENGINE SPECIFICATION

## Status

This document defines controlled homepage and landing-page composition for
Brand 1.

## Objective

Allow administrators to arrange approved content modules without allowing
arbitrary code execution or unsafe free-form page building.

## Principle

The Widget Engine is a controlled composition system, not an unrestricted page
builder.

Only approved widget types and validated configuration may be used.

## Approved Widget Types

Brand 1 MAY support:

- Hero;
- Announcement;
- Live Draw;
- Latest Result;
- Predictions;
- RTP;
- Jackpot Proof;
- Promotions;
- Guides;
- Blog;
- Complaints information;
- Lottery Tools;
- Banner;
- Rich Text;
- Call to Action.

## Widget Contract

Every widget type MUST define:

- identifier;
- allowed page locations;
- configuration schema;
- validation rules;
- data resolver;
- loading state;
- empty state;
- failure state;
- cache behavior;
- brand behavior;
- publication behavior;
- desktop rendering;
- mobile rendering;
- accessibility requirements;
- test requirements.

## Layout

The engine SHOULD support:

- ordering;
- activation;
- optional schedule;
- full-width section;
- contained section;
- approved column layouts;
- responsive stacking.

Arbitrary HTML, JavaScript, or PHP MUST NOT be accepted as normal widget
configuration.

## Data Integrity

Widgets MUST only display:

- current-brand data;
- published data;
- authorized public data;
- canonical links;
- safe media.

## Card and List Modes

Listing widgets MAY support Card View and List View in accordance with the
Brand 1 frontend baseline.

Changing presentation mode MUST NOT change the underlying result set.

## Cache

Each widget MUST define deterministic cache identity.

Cache keys SHOULD include:

- brand;
- widget type;
- widget instance;
- relevant configuration;
- publication context.

Relevant data or configuration changes MUST invalidate affected widgets.

## Failure Behavior

A failed widget MUST NOT break the entire page.

The system SHOULD:

- render a safe failure state;
- preserve surrounding widgets;
- log the failure;
- avoid exposing internal errors.

## SEO

Widget configuration MUST NOT independently overwrite canonical page SEO unless
the page contract explicitly permits it.

Heading hierarchy MUST remain valid.

## Administration

Administration SHOULD provide:

- approved widget selector;
- ordering;
- configuration form;
- desktop preview;
- mobile preview;
- activation;
- schedule;
- duplication;
- deletion warning;
- audit history.

## Testing Requirements

Tests MUST cover:

- brand isolation;
- publication filtering;
- configuration validation;
- ordering;
- cache identity;
- cache invalidation;
- failure isolation;
- responsive rendering;
- heading hierarchy;
- prohibited arbitrary code.

## Completion Gate

The engine is complete only when:

- Brand 1 homepage can be composed from approved widgets;
- unsafe arbitrary code is impossible;
- failed widgets do not break the page;
- brand and publication isolation work;
- cache behavior is verified;
- tests pass.
