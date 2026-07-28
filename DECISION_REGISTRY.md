
<!-- MASTER-PROMPT-V2-1-SYNC-2026-07-24-DECISIONS-START -->

# Master Prompt v2.1 Decisions

## DEC-2026-07-24-001 — Separate Owner and Brand Panels

Status: APPROVED

Owner dan Brand menggunakan panel, permission, dan administration context yang terpisah.

## DEC-2026-07-24-002 — Owner Design Ownership

Status: APPROVED

Owner mengelola Theme Registry, Homepage Template Registry, Widget Registry, provider global, dan game global.

## DEC-2026-07-24-003 — Brand Operational Ownership

Status: APPROVED

Brand mengelola SEO, keyword, SERP, konten, Slot Gacor, RTP, Prediction, Result, Live Draw, Promotion, Blog, Panduan, dan Keluhan milik Brand.

## DEC-2026-07-24-004 — Public Guide Definition

Status: APPROVED

Panduan adalah konten publik Brand kepada pengunjung mengenai cara menggunakan dan memainkan layanan Brand.

Interpretasi Panduan sebagai dokumentasi Brand kepada Owner berstatus SUPERSEDED.

## DEC-2026-07-24-005 — Visitor Complaint Definition

Status: APPROVED

Keluhan adalah laporan pengunjung kepada Brand mengenai pengalaman mereka selama menggunakan layanan Brand.

Interpretasi Keluhan sebagai tiket Brand kepada Owner berstatus SUPERSEDED.

## DEC-2026-07-24-006 — SEO Modes

Status: APPROVED

SEO mendukung MANUAL, AUTO, dan HYBRID berdasarkan keyword, SERP, intent, kompetitor, konten, dan performa.

## DEC-2026-07-24-007 — Slot and RTP Ownership

Status: APPROVED

Owner mengelola katalog provider dan game global. Brand mengelola RTP, pola, jadwal, badge, urutan, publikasi, dan histori.

## DEC-2026-07-24-008 — Master Prompt Inheritance

Status: APPROVED

Master Prompt v2.1 mewarisi seluruh requirement dan otomatisasi aktif Master Prompt v2.0 kecuali yang secara eksplisit SUPERSEDED.

## DEC-2026-07-24-009 — Repository Project Memory

Status: APPROVED

Keputusan yang disetujui wajib disinkronkan ke repository dan tidak boleh berhenti hanya di chat.

## DEC-2026-07-24-010 — 90-Day Delivery Target

Status: APPROVED

Target aktif pembangunan, validasi, hardening, dan production readiness adalah 90 hari.
<!-- MASTER-PROMPT-V2-1-SYNC-2026-07-24-DECISIONS-END -->

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

<!-- SPRINT-17B-DECISION-START -->
## DEC-17B-001 — Complete Complaint Operations Before Starting Guide

Status: Accepted.

Sprint 17B completes the already-started Complaint module before selecting another Brand 1 module. Complaint status history is append-only, public complaint records remain private, and terminal status transitions cannot be reopened through the standard workflow.
<!-- SPRINT-17B-DECISION-END -->

<!-- SPRINT-19A-3-DECISION-START -->
## DEC-19A-3-001 — Shared Frontend Site Configuration Composition

Status: Accepted.

All public `frontend.*` views receive one resolved, brand-scoped Site Configuration value through a shared view composer. Templates consume the resolved value rather than querying persistence directly. External logo, favicon, and social URLs are rendered only when their scheme is HTTP or HTTPS.
<!-- SPRINT-19A-3-DECISION-END -->
