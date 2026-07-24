# Security Threat Model

Status: Canonical baseline
Version: 1.0

## Protected assets

- Brand-owned content and configuration.
- User accounts, roles, and permissions.
- Production credentials and environment secrets.
- Database integrity and backups.
- Media and generated assets.
- Queue, scheduler, and deployment control.
- SEO identity, domains, redirects, and public content.
- Audit records and operational logs.

## Trust boundaries

- Public browser to Laravel application.
- Administrator browser to Filament panel.
- Application to database, cache, queue, storage, and external providers.
- Scheduler/worker to brand-owned data.
- Server and deployment operator to production environment.
- AI provider or external content source to editorial workflow.
- Brand administrator to platform-wide resources.

## Primary threats

### Cross-brand data leakage

A user, query, job, cache key, export, relation, or search index exposes one brand's data to another.

Controls: explicit Brand Context, brand-scoped policies and queries, constraints/indexes, namespaced caches/storage, brand-aware jobs, and cross-brand regression tests.

### Privilege escalation

A user changes `is_admin`, role, permission, brand assignment, domain, or security setting outside authorized workflows.

Controls: server-side policies, guarded privileged attributes, dedicated actions, reauthentication for high-impact changes, audit records, and least privilege.

### Authentication/session compromise

Threats include credential stuffing, brute force, fixation, insecure cookies, excessive session lifetime, and stolen sessions.

Controls: rate limits, secure cookies, session rotation, timeout, password policy, optional 2FA, login audit, and account protection.

### Unsafe upload or remote media ingestion

Threats include executable files, polyglots, oversized files, malicious SVG, traversal, SSRF, and content-type spoofing.

Controls: MIME and signature validation, generated names, size limits, isolated storage, re-encoding, SVG sanitization/restriction, provider allowlists, DNS/IP checks, timeout, redirect limits, and executable blocking.

### Injection and unsafe rendering

Threats include SQL injection, stored/reflected XSS, command injection, unsafe HTML/embed code, and template execution.

Controls: query binding, request validation, output escaping, HTML sanitization, CSP/security headers, provider allowlists, and prohibition of arbitrary executable templates.

### Queue and scheduler abuse

Threats include duplicate execution, wrong-brand context, stale payloads, poisoned jobs, silent failure, and overlap.

Controls: signed/validated payloads where appropriate, brand identity, idempotency, locks, retry policy, failure alerts, and heartbeat monitoring.

### Secret and log exposure

Threats include `.env` commits, debug output, leaked tokens, public backups, and sensitive request logging.

Controls: Git ignore and secret scanning, production debug off, log redaction, protected backup paths, credential rotation, and least-privilege service accounts.

### Deployment and supply-chain compromise

Threats include vulnerable dependencies, unreviewed packages, modified artifacts, unsafe migrations, and failed rollback.

Controls: lock files, dependency audit, trusted sources, reviewed build, immutable release reference, backup, migration plan, and rollback rehearsal.

### SEO/domain takeover or poisoning

Threats include unauthorized domain/redirect/canonical changes, open redirects, index poisoning, or malicious schema/metadata.

Controls: restricted domain/SEO permissions, validation, allowlists, audit logs, preview, and post-deployment monitoring.

## Security acceptance

Threats rated critical or high require implemented controls and verification before production. Residual medium risks require an owner and mitigation date.
