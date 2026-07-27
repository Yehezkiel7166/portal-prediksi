# Sprint 19A-1 — Site Configuration Data Foundation

## Objective

Create the smallest safe persistence and resolution layer required for centralized, brand-scoped site configuration without changing public frontend behavior or exposing an incomplete admin interface.

## Baseline

- Branch: `feat/domain-management-foundation`
- Commit: `1d84f0735ad788aff6b45488cfef9dbc87b222c8`
- Tree: `00215be1be0f9a5683fb6ed30b7bcefc2bd9a222`
- Verified regression baseline: 470 tests, 1336 assertions

## Scope

- one `site_configurations` row per brand;
- identity fields: site name, tagline, logo URL, favicon URL;
- global SEO defaults;
- contact and WhatsApp fields;
- social links and footer text;
- active-state fallback protection;
- cached resolver with explicit invalidation;
- guarded brand-owned upsert action;
- model relationship and feature tests.

## Explicitly Out of Scope

- Filament resource and forms;
- frontend layout integration;
- navigation builder;
- analytics scripts or secrets;
- Open Graph rendering and JSON-LD;
- media upload handling.

## Acceptance Gates

- migration works on a clean test database;
- each brand has at most one site configuration;
- absent or inactive configuration produces safe brand/app fallbacks;
- updates invalidate cached resolution;
- ownership cannot be reassigned through payload input;
- full Laravel regression passes;
- repository governance and permanent completion gate pass;
- final repository is committed, pushed, remotely verified, and clean.
