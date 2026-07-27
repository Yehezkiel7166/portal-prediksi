# Project Decisions

Status: Canonical
Version: 1.0
Effective date: 2026-07-24

This document records current project-level decisions that govern all future work. Detailed architecture decisions should also receive an ADR.

## PD-001 — Delivery order

Decision: Deliver Brand 1 production readiness first, followed by optimization and hardening, then Owner Panel, then Brand 2–5 activation.

Reason: Shipping all brands or the Owner Panel first would increase risk and delay validation of the shared platform.

## PD-002 — Multi-brand architecture, single-brand release

Decision: Shared code must remain multi-brand compatible, while initial production serves Brand 1 only.

Reason: Avoid future rewrite while preserving delivery focus.

## PD-003 — Project Brain

Decision: Repository stores code, architecture, product vision, decisions, idea backlog, delivery plans, security controls, operational procedures, and collaboration rules.

Reason: Project continuity must not depend on chat history or individual memory.

## PD-004 — Append-first governance

Decision: Preserve historical documents and decisions. Supersede explicitly rather than silently deleting or rewriting history.

## PD-005 — Security as a release gate

Decision: Brand isolation, authorization, secrets, upload safety, production configuration, auditability, backup, restore validation, dependency review, and incident readiness are mandatory release controls.

## PD-006 — Brand authorization

Decision: A single `is_admin` flag is transitional. Production authorization must use policies and brand-scoped role/permission assignments. Privileged fields cannot be mass-assigned through general requests.

## PD-007 — Automation safety

Decision: Jobs and scheduled actions must be idempotent where practical, retry-safe, observable, brand-aware, and protected from overlap where concurrency would cause harm.

## PD-008 — AI boundaries

Decision: AI output is advisory or draft by default. AI cannot independently publish sensitive content, change permissions, alter domains, deploy production, delete data, or bypass editorial and security controls.

## PD-009 — Controlled extensibility

Decision: Plugin, marketplace, installer, updater, extension API, and theme-package concepts are future capabilities. They must use signed or trusted packages, stable contracts, strict permissions, compatibility checks, rollback, and audit logging. Arbitrary executable templates are prohibited.

## PD-010 — Thirty-day Brand 1 window

Status: SUPERSEDED by PD-013.

Historical decision: Brand 1 was previously planned for completion no later than 2026-08-23.

## PD-013 — Fourteen-day Brand 1 usable target

Status: ACTIVE.

Decision: Project work started on 2026-07-16. Brand 1 must reach usable status by 2026-07-30. The overall project target is 2026-10-14.

Reason: The user corrected the actual project start date and confirmed Brand 1 must be completed before Owner Panel work.

## PD-011 — Command delivery

Decision: Operational instructions are delivered as copy-paste-ready PowerShell or Bash blocks, with explicit shell context and validation.

## PD-012 — Source verification

Decision: No claim that repository changes are complete is valid until the change is produced against the latest supplied baseline and verified by diff or repository commands.

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
