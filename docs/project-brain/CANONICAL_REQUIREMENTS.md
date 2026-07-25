# Canonical Project Requirements

Status: Canonical
Version: 1.0
Effective date: 2026-07-25

This document consolidates active product, architecture, operational, delivery,
and governance requirements. Historical chat is not a source of truth.

## Product Identity

- Framework: Enterprise Digital Platform Framework.
- First implementation: Portal Prediksi CMS.
- Delivery principle: build for many, release for one.
- One shared repository and application architecture.
- No normal per-brand repository forks.

## Delivery Direction

- Project start: 2026-07-16.
- Brand 1 usable deadline: 2026-07-30.
- Overall project target: 2026-10-14.
- Brand 1 is completed before Owner Panel.
- Brand 1 stabilization follows initial usability.
- Brand 2 through Brand 5 follow Owner Panel readiness.

## Brand 1 Public Scope

Brand 1 contains exactly 10 public modules:

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

The Lottery Tool Suite contains exactly:

1. Jadwal Togel
2. BBFS Generator
3. Buku Mimpi
4. Paito Togel Warna
5. Konversi Angka SGP
6. Tabel Shio

Platform services are not counted as additional public modules.

## Owner Control Plane

The Owner Panel is separate from Brand administration.

Owner responsibilities include:

- brand creation, activation, suspension, archival, and recovery;
- Owner accounts and delegated Owner administrators;
- assignment of Brand Super Administrators;
- Brand Admin login-domain control;
- global provider and game catalog;
- Theme Registry;
- Homepage Template Registry;
- Widget Registry;
- platform security and audit;
- server and infrastructure registry;
- deployment, backup, restore, queue, scheduler, and cache operations;
- cross-brand health and operational visibility.

Owner Panel work begins after Brand 1 usable delivery.

## Brand Administration Plane

A Brand administrator operates only inside explicit Brand Context.

Brand responsibilities include:

- identity and frontend appearance;
- frontend primary domain and aliases;
- SEO, keyword, entity, canonical, sitemap, schema, and SERP operations;
- markets, predictions, results, Shio, promotions, blog, and Live Draw;
- Slot Gacor and RTP configuration;
- Jackpot Proof;
- public Guide and visitor Complaint;
- brand users, media, analytics, and publication;
- preview of permitted frontend settings.

Cross-brand access must be denied by default.

## Domain Management

- Brand identity is independent from domain identity.
- Frontend and admin domains are separate concepts.
- Owner controls Brand Admin login domains.
- Brand controls permitted frontend domains and aliases.
- Domain changes require no source-code modification.
- Unknown domains fail safely.
- Domain aliases support verification and lifecycle states.
- Canonical domain, HTTPS, redirects, robots, and indexability are automated
  through safe policies.
- Domain health checks, verification history, monitoring, and migration are
  required platform capabilities.

## Server and Migration Management

Planned capabilities include:

- server registry;
- server health monitoring;
- Brand deployment placement;
- frontend and admin domain migration;
- Brand migration between servers;
- preflight validation;
- maintenance mode;
- data and file transfer;
- rollback;
- post-migration verification;
- audit history.

Migration must be repeatable, observable, and reversible.

## Storage and Media

- Each Brand has isolated file ownership and storage boundaries.
- Cross-brand file access is prohibited.
- Storage must not depend on a shared public directory without isolation.
- Media supports uploads and approved external sources.
- Responsive variants, crop, focal point, ratio, thumbnails, and output
  resolution are application-controlled.
- Unsafe executable templates and unsanitized embeds are prohibited.
- Brand migration includes owned media and generated assets.

## Theme, Homepage, Menu, Banner, and Widget

- Owner controls reusable structures, schemas, versions, and allowed behavior.
- Brand controls permitted content and settings inside those structures.
- Admin preview includes logo and background.
- Frontend preview includes background, colors, component placement, and
  responsive behavior.
- Frontend content listing supports Card and List views.
- View preference is persisted when technically appropriate.

## SEO and SERP

SEO supports Manual, Auto, and Hybrid modes.

Requirements include:

- evergreen stable recurring titles;
- no automatic current-date title or slug churn;
- separate display title, SEO title, H1, label, breadcrumb, social title,
  slug, and canonical;
- locked approved manual values are preserved;
- canonical isolation by Brand;
- automatic publication-aware sitemap;
- safe robots output;
- redirect validation;
- schema matching visible content;
- relevant internal linking;
- verified indexing data or unknown status;
- keyword and entity intelligence;
- SERP observation and brand defense;
- no ranking guarantees.

## Automation

Automation must be:

- idempotent where practical;
- brand-aware;
- market-aware where relevant;
- queue-capable;
- retry-safe;
- overlap-protected;
- observable;
- auditable;
- recoverable.

Automation scope includes:

- Live Draw status;
- result workflows;
- prediction workflows;
- RTP rotation;
- SEO maintenance;
- health checks;
- scheduler heartbeat;
- cache invalidation;
- backup and restore operations;
- migration orchestration.

## Security

- Secrets never enter Git.
- Production data is isolated from automated tests.
- Authorization uses policies and scoped roles.
- A single `is_admin` flag is transitional only.
- Uploads, embeds, plugins, themes, and external integrations are validated.
- Privileged actions require audit records.
- Backup and restore validation are release gates.
- Security is never bypassed to meet a deadline.

## AI Governance

AI is assistive by default.

AI may support:

- draft content;
- rewriting and translation;
- SEO recommendations;
- image prompt preparation;
- consistency review;
- architecture review;
- code review;
- test suggestions;
- operational summaries.

AI may not independently:

- publish sensitive content;
- alter permissions;
- modify production domains;
- deploy production;
- delete data;
- bypass review or security controls.

## Repository Governance

The repository is the single source of truth.

Every material item must have one canonical owner:

- current direction: `docs/governance/CURRENT_DIRECTION.md`;
- requirements: this document;
- decisions: `docs/registry/DECISION_REGISTRY.md`;
- ideas: `docs/registry/IDEA_REGISTRY.md`;
- implementation status: `docs/registry/IMPLEMENTATION_STATUS.md`;
- architecture decisions: ADR files and `docs/registry/ADR_REGISTRY.md`;
- active sprint: `SPRINT_STATE.md`;
- machine-readable state: `PROJECT_STATE.json`.

Historical documents remain available but must be marked superseded when no
longer active.

## Future Capabilities

Future capabilities include:

- AI-assisted content and operations;
- centralized metrics and logs;
- error tracking;
- distributed queue infrastructure;
- object storage and CDN;
- read replicas;
- multi-region recovery;
- infrastructure as code;
- extension contracts;
- signed plugins;
- theme and template marketplace;
- public or partner API;
- mobile API;
- advanced analytics;
- referral and cashback modules.

Future status does not authorize implementation before roadmap approval.

## Rejected or Prohibited Direction

- normal repository fork per Brand;
- arbitrary executable templates;
- unsanitized third-party scripts;
- unreviewed production plugins;
- autonomous AI permission or deployment changes;
- mass-generated low-quality SEO pages;
- fake indexing or ranking claims;
- cross-brand canonical URLs;
- silent deletion of historical decisions;
- security bypass for delivery speed.
