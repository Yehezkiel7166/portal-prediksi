# SITE CONFIGURATION ENGINE SPECIFICATION

## Status

This document defines the canonical Brand 1 site-configuration architecture.

Implementation requires an explicitly approved sprint while Feature Freeze
remains active.

## Objective

Allow authorized administrators to configure Brand 1 without editing source
code or environment files for ordinary presentation and business settings.

Configuration MUST remain:

- brand-aware;
- validated;
- auditable;
- cache-aware;
- permission-controlled;
- safely reversible;
- production-safe.

## Configuration Domains

The engine SHOULD support:

### Brand Identity

- brand name;
- short name;
- legal or display company name where applicable;
- logo;
- compact logo;
- favicon;
- default social image;
- brand description;
- copyright text.

### Visual Theme

- primary color;
- secondary color;
- accent color;
- surface color;
- text color;
- optional dark-mode values;
- typography selection from approved system options;
- border-radius preference;
- container width;
- component-density preference.

Arbitrary unsafe CSS MUST NOT be accepted as normal configuration.

### Header

- logo;
- primary navigation assignment;
- announcement bar;
- header call-to-action;
- sticky-header behavior;
- desktop visibility;
- mobile visibility.

### Footer

- footer navigation assignment;
- copyright;
- contact information;
- social links;
- disclaimer;
- trust or informational text;
- optional footer logo.

### Contact and Conversion Channels

- WhatsApp;
- Telegram;
- email;
- phone;
- live-chat provider;
- primary conversion URL;
- secondary conversion URL.

External destinations MUST be validated.

### Social Accounts

- Facebook;
- Instagram;
- X or Twitter;
- YouTube;
- TikTok;
- Telegram;
- other approved social profiles.

### Announcement and Maintenance

- announcement message;
- announcement activation;
- announcement schedule;
- maintenance mode;
- maintenance message;
- authorized bypass behavior.

### Analytics and Marketing Integrations

- Google Analytics identifier;
- Google Tag Manager identifier;
- Meta Pixel identifier;
- TikTok Pixel identifier;
- other explicitly approved identifiers.

Secrets MUST NOT be stored in ordinary public configuration records.

### Default SEO

SEO defaults MUST delegate to the canonical SEO Engine specification.

The Site Configuration Engine MUST NOT duplicate or bypass SEO resolution
rules.

## Data Model Principles

Configuration SHOULD use typed keys or structured domain records.

Every value MUST define:

- type;
- validation rule;
- default;
- brand ownership;
- edit permission;
- cache behavior;
- audit behavior;
- whether it is public or protected.

The engine MUST avoid unstructured arbitrary key-value sprawl.

## Manual Locking

Important brand settings SHOULD support administrator locking.

Automated import, seeding, or synchronization MUST preserve locked values unless
an authorized administrator explicitly approves replacement.

## Publication and Activation

Configuration changes MAY support:

- draft;
- scheduled;
- active;
- inactive;
- archived.

Critical changes SHOULD support preview before activation.

## Validation

Validation MUST cover:

- invalid colors;
- malformed URLs;
- unsupported file types;
- unsafe scripts;
- invalid analytics identifiers;
- invalid phone or contact formats where applicable;
- missing required brand identity;
- cross-brand media references.

## Cache

Public configuration MAY be cached.

Cache keys MUST include brand identity.

Relevant configuration changes MUST invalidate only affected cache entries.

## Brand Isolation

One brand MUST NOT read or render another brand's:

- identity;
- theme;
- contacts;
- analytics;
- social links;
- navigation;
- footer;
- maintenance state;
- media.

## Administration

Administration SHOULD provide:

- grouped settings;
- clear validation errors;
- preview;
- reset to safe default;
- audit history;
- publication state;
- permission checks;
- protected-field warnings.

## Testing Requirements

Tests MUST cover:

- brand isolation;
- default fallback;
- validation;
- cache invalidation;
- locked-value preservation;
- safe URL handling;
- permission enforcement;
- activation behavior;
- preview behavior;
- failure fallback.

## Completion Gate

The engine is complete only when:

- Brand 1 can be configured without source-code edits;
- public rendering is brand-aware;
- unsafe values are rejected;
- cache invalidation works;
- audit history exists;
- locked values are preserved;
- regression tests pass.
