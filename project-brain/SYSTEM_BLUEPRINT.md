# System Blueprint

Status: Approved direction
Version: 1.0

## Platform layers

1. Public delivery and administration.
2. Application use cases.
3. Domain rules.
4. Shared platform services.
5. Infrastructure adapters.
6. Persistence, cache, queue, storage, and search.
7. Operations and observability.

Dependencies point inward toward domain contracts. Shared platform code must not absorb business rules owned by a domain.

## Brand context

Every request or background operation that touches brand data must resolve an explicit Brand Context containing at minimum:

- brand identifier;
- domain or execution source;
- active state;
- timezone and locale;
- configuration namespace;
- theme and asset context;
- authorization scope;
- cache namespace.

Brand Context must be available to:

- middleware;
- controllers and public rendering;
- Filament resources;
- policies;
- application services;
- jobs and scheduled commands;
- cache keys;
- media paths;
- SEO generators;
- exports, reports, and search indexes.

No query may rely only on a UI filter for isolation.

## Core engines

### Brand Engine

Owns brand identity, domain bindings, theme, assets, configuration, feature flags, locale, timezone, and lifecycle.

### Site Configuration Engine

Provides typed, validated, cached, brand-scoped settings with defaults, audit history, and safe invalidation.

### Menu and Navigation Engine

Provides brand-scoped menu structures, visibility rules, ordering, active-state resolution, and safe link validation.

### Media Engine

Provides upload, generated names, validation, transformation, responsive variants, focal point, storage policy, metadata, and controlled remote ingestion.

### SEO Engine

Provides canonical resolution, metadata, Open Graph, schema, sitemap, robots, redirects, breadcrumbs, internal links, indexability, and monitoring signals.

### Content and AI Engine

Coordinates editorial content, drafts, approval, scheduling, revisions, reusable briefs, AI suggestions, quality checks, and provenance.

### Automation Engine

Coordinates scheduled work, jobs, retries, overlap prevention, dead-letter handling, metrics, and operational controls.

### Security and Audit Engine

Coordinates authentication, authorization, brand-scoped permissions, sensitive-action confirmation, audit records, login events, and security monitoring.

### Owner Operations Engine

Future shared control plane for cross-brand status, security, users, jobs, schedules, backups, deployments, configuration, and audit.

## Data rules

- Brand-owned tables use explicit `brand_id` unless the entity is intentionally global.
- Unique constraints include brand scope where business uniqueness is per brand.
- Foreign keys and indexes support isolation and common query paths.
- Historical migrations are not edited after shared use; new migrations evolve the schema.
- Destructive migrations require backup, impact review, and roll-forward strategy.
- Eloquent global scopes may provide defense in depth but cannot replace policies and explicit application boundaries.

## Cache rules

- Cache keys are namespaced by brand, environment, capability, and version.
- Configuration and public fragments support targeted invalidation.
- Cache must never expose content from another brand.
- Deployment includes deterministic cache clear or warm-up behavior.

## Queue and scheduler rules

- Job payloads include brand identity where relevant.
- Jobs validate that the target brand and entity still exist.
- Critical jobs are idempotent or use deduplication locks.
- Scheduler uses overlap protection and health heartbeat.
- Failed jobs and delayed execution are visible to operators.

## Extension rules

Future extensions use contracts, manifests, compatibility versions, permissions, migration review, and disable/rollback controls. Extensions cannot execute arbitrary unreviewed code or bypass platform authorization.
