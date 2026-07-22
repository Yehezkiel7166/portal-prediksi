# Phase 1.1 — EDPF Repository Alignment

Status: In Progress
Priority: P0
Feature Freeze: Active
Product Release Priority: Brand #1

## Objective

Align the canonical repository identity and Feature Freeze v1.0 with the
Enterprise Digital Platform Framework (EDPF) direction before multi-brand code
implementation begins.

## Scope

This phase establishes:

- EDPF as the governing framework;
- Portal Prediksi CMS as the first implementation;
- one Owner Panel;
- isolated Brand Admin contexts;
- a five-brand-compatible foundation;
- Brand #1 as the first production release;
- Owner control of brand admin login domains;
- Brand Super Administrator control of frontend domains;
- permanent brand identity independent from domains;
- dynamic domain replacement without source-code modification;
- zero-repeat-mistake and deterministic copy-paste execution principles.

## Non-Goals

This phase does not:

- create database migrations;
- change application behavior;
- create Filament panels;
- implement hostname resolution;
- activate additional brands;
- claim complete Master Prompt v2.0 traceability without the canonical prompt
  source being stored or fully mapped.

## Deliverables

- Updated `PROJECT_CONSTITUTION.md`.
- Updated `docs/product/FEATURE_FREEZE_V1.md`.
- This sprint record.
- Canonical state updated to identify Phase 1.1 as active.
- Subsequent traceability and architecture alignment plan.

## Risks

- Duplicating existing repository authority.
- Introducing terminology that conflicts with Portal Prediksi CMS.
- Claiming implementation before code exists.
- Delaying Brand #1 with nonessential enterprise scope.

## Mitigations

- Extend canonical documents instead of creating duplicate constitutions.
- Preserve Portal Prediksi CMS as the first concrete product.
- Clearly distinguish documented target architecture from implemented state.
- Keep the implementation roadmap focused on the minimum five-brand-compatible
  foundation required for Brand #1.

## Validation

- Repository governance audit passes.
- Markdown references remain valid.
- No source code or database files change.
- Git diff contains only approved documentation alignment.
- Working tree is reviewed before commit.

## Completion Criteria

Phase 1.1 is complete only when:

- all canonical identity documents agree;
- Feature Freeze v1.0 includes the approved multi-brand operating model;
- Master Prompt v2.0 traceability is represented by a canonical repository
  artifact;
- architecture documents reflect the same Owner, Brand, and domain boundaries;
- validation passes;
- changes are committed and pushed;
- the working tree is clean.

## Next Phase

Phase 1.2 — Master Prompt v2.0 Traceability and Canonical Identity Alignment.
