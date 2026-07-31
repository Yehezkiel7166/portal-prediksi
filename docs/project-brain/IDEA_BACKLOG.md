# Idea and Future Backlog

Status: Living backlog

This document preserves ideas without allowing them to disrupt Brand 1 delivery. An idea enters implementation only after architecture review, dependencies, security impact, tests, and roadmap placement are defined.

## SEO and discovery

- Crawl and index monitoring.
- SERP position and brand-entity monitoring.
- Search Console ingestion.
- Keyword clustering and content-gap analysis.
- Automated broken-link detection.
- Controlled internal-link recommendations.
- Redirect-chain and canonical conflict checks.
- Schema validation and rich-result monitoring.
- Duplicate/thin-content checks.
- Sitemap partitioning and submission status.

## Content operations

- Editorial calendar.
- Approval workflow.
- Revision comparison and rollback.
- Reusable content blocks.
- Landing page composition from approved components.
- Banner generation and image prompt workflow.
- Content localization.
- Content freshness scoring.
- Scheduled publication and expiration.
- Provenance and AI-assistance labels.

## Brand platform

- Brand creation wizard.
- Domain verification.
- Theme package selection.
- Brand templates and clone-safe configuration.
- Asset migration.
- Brand import/export excluding secrets.
- Brand health score.
- Feature package activation.
- Safe deactivation, archival, and recovery.

## Owner operations

- Cross-brand operational dashboard.
- User assignment matrix.
- Queue retry/cancel controls.
- Scheduler visibility.
- Cache invalidation controls.
- Backup catalog and restore workflow.
- Release/version view.
- Security event view.
- Audit exploration and export.
- Cost and usage visibility.

## AI assistance

- Draft generation from approved briefs.
- Rewrite, translation, and style variants.
- SEO metadata recommendations.
- Image prompt generation.
- Content consistency checks.
- Architecture and code-review suggestions.
- Test-case suggestions.
- Operational anomaly summaries.

AI remains assistive. Sensitive actions require explicit human authorization.

## Platform ecosystem

- Stable extension contracts.
- Signed plugin packages.
- Theme marketplace.
- Template marketplace.
- Installation and compatibility checks.
- Versioned update manager.
- Extension sandboxing and permission declarations.
- Rollback after failed extension update.
- Public or partner API.

## Infrastructure and scale

- Centralized logs and metrics.
- Error tracking.
- Distributed queues.
- Object storage and CDN strategy.
- Search cluster.
- Read replicas.
- Multi-region recovery planning.
- Infrastructure-as-code.
- Automated staging environments.

## Explicitly deferred or prohibited

- Arbitrary executable template code.
- Unsanitized third-party embed code.
- Unreviewed plugins in production.
- Fully autonomous AI deployment or permission changes.
- Mass page generation without content-quality controls.
- Separate repository forks per brand as the normal operating model.
- Security bypasses to meet a release date.

<!-- CURRENT-DIRECTION-START -->
## Canonical Direction — 2026-07-25

- Project started on 2026-07-16.
- Brand 1 usable deadline is 2026-07-30.
- Overall project deadline is 2026-10-14.
- Brand 1 contains exactly 10 main modules and 6 lottery tools.
- Brand 1 is completed before Owner Panel and Brand 2–5.
- Domain Management is implemented through Commit 14B.
- The former active 30-day Brand 1 plan is superseded.
- Every sprint requires repository synchronization and CTO crosscheck.

Canonical reference:

- `docs/governance/CURRENT_DIRECTION.md`
- `docs/delivery/BRAND-1-14-DAY-USABLE-PLAN.md`
<!-- CURRENT-DIRECTION-END -->

<!-- SPRINT-20A-BACKLOG-REVIEW -->
## Sprint 20A Backlog Review

No new idea was added. Owner Panel and Brand 2–5 remain after Brand 1
production readiness and stabilization.

<!-- SPRINT-20C-BACKLOG-REVIEW -->
## Sprint 20C Backlog Review

No new product feature was added.

Production runtime activation evidence remains ahead of Brand 1 stabilization,
Owner Panel, and Brand 2–5 activation. Existing future ideas remain deferred.
