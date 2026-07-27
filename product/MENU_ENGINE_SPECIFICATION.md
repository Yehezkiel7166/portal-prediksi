# MENU ENGINE SPECIFICATION

## Status

This document defines configurable Brand 1 public-navigation behavior.

## Objective

Allow authorized administrators to manage navigation without source-code edits
while preserving required routes, accessibility, SEO, and brand isolation.

## Menu Locations

The system SHOULD support:

- primary desktop navigation;
- mobile navigation;
- footer navigation;
- secondary navigation;
- sidebar navigation where applicable;
- mobile bottom navigation where approved;
- utility navigation.

## Menu Item Types

Supported item types MAY include:

- internal route;
- internal content;
- external URL;
- menu group;
- label-only group;
- configured action.

Every item MUST define:

- label;
- destination type;
- destination;
- sort order;
- activation state;
- visibility context;
- optional icon;
- optional parent;
- brand ownership.

## Required Brand 1 Navigation

The mandatory Brand 1 navigation baseline MUST remain available:

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

Administrators MUST NOT accidentally remove all access to mandatory modules.

The system MAY protect required items or provide safe restoration.

## Validation

The engine MUST prevent:

- invalid internal routes;
- broken content references;
- unsafe URL schemes;
- circular parent relationships;
- excessive nesting;
- duplicate conflicting positions;
- cross-brand content references;
- links to unpublished content unless explicitly previewing.

## External Links

External URLs MUST be validated.

Opening in a new tab SHOULD automatically include safe relationship attributes.

## Accessibility

Navigation MUST support:

- keyboard access;
- visible focus;
- semantic landmarks;
- valid nested-menu structure;
- accessible labels;
- mobile dismissal behavior;
- current-page indication.

## SEO Behavior

Navigation links MUST use canonical internal URLs.

The engine MUST NOT generate:

- hidden keyword links;
- duplicate spam links;
- links to noncanonical parameter variants;
- cross-brand canonical confusion.

## Cache

Resolved navigation MAY be cached per:

- brand;
- menu location;
- publication state;
- locale where applicable.

Relevant menu changes MUST invalidate affected cache.

## Administration

Administration SHOULD provide:

- drag-and-drop ordering;
- hierarchical organization;
- destination selector;
- preview;
- activation;
- scheduling where needed;
- protected-item warning;
- audit history;
- permission control.

## Testing Requirements

Tests MUST cover:

- brand isolation;
- protected required items;
- route validation;
- unsafe URL rejection;
- parent-cycle prevention;
- ordering;
- cache invalidation;
- unpublished-content rejection;
- accessibility markup;
- mobile and desktop rendering.

## Completion Gate

The engine is complete only when:

- mandatory Brand 1 navigation remains reachable;
- menus can be managed safely;
- invalid destinations are rejected;
- frontend rendering is accessible;
- brand isolation is enforced;
- tests pass.
