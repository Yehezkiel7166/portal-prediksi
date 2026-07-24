# Brand 1 Production Gate

Status: Mandatory release checklist

Production approval requires evidence for every P0 item.

## Repository and release

- [ ] Approved branch and commit identified.
- [ ] Working tree clean.
- [ ] Full test suite passes.
- [ ] Frontend build passes.
- [ ] Changed PHP files pass syntax checks.
- [ ] Documentation and state are synchronized.
- [ ] Release and rollback commands reviewed.

## Security

- [ ] Brand-isolation tests pass for reads, writes, relationships, exports, jobs, caches, and admin resources.
- [ ] Policies protect all sensitive resources and actions.
- [ ] Privileged attributes cannot be mass-assigned through general forms or requests.
- [ ] Authentication rate limits and secure session settings are active.
- [ ] Production debug is disabled.
- [ ] Secrets are absent from Git and logs.
- [ ] Upload MIME, size, path, executable, SVG, and remote-source controls pass.
- [ ] Sensitive actions create audit records.
- [ ] Dependency vulnerabilities are reviewed and P0 findings resolved.

## Data and recovery

- [ ] Production backup completed before deployment.
- [ ] Automated backup schedule exists.
- [ ] Backup is stored outside public web paths.
- [ ] Restore rehearsal succeeded.
- [ ] Migration impact and roll-forward plan reviewed.

## Operations

- [ ] Queue worker is supervised and failures are visible.
- [ ] Scheduler runs every minute and heartbeat is current.
- [ ] Cache/storage/database health is verified.
- [ ] Application and server logs are accessible to authorized operators.
- [ ] Monitoring and incident contacts/runbook are available.

## Public delivery and SEO

- [ ] Critical routes return expected status and brand content.
- [ ] Canonical URLs are correct.
- [ ] Sitemap and robots behavior is correct.
- [ ] Metadata and structured data are valid for applicable pages.
- [ ] No staging, private, duplicate, or administrative pages are unintentionally indexable.
- [ ] Responsive media and HLS behavior are verified.

## Approval

Release is rejected when any unresolved P0 item exists. Accepted P1 risks must record owner, impact, mitigation, and due date.
