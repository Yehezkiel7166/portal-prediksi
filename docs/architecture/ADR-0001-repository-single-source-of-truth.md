# ADR-0001

## Title

Repository as the Single Source of Truth

## Status

Accepted

## Date

2026-07-20

---

## Context

Portal Prediksi CMS telah berkembang menjadi sistem modular dengan banyak sprint.

Mengandalkan riwayat percakapan sebagai dokumentasi utama menyebabkan:

- sulit melakukan handover;
- sulit melakukan audit;
- risiko kehilangan konteks;
- implementasi tidak dapat diverifikasi hanya dari repository.

Repository harus dapat berdiri sendiri.

---

## Decision

Repository menjadi Single Source of Truth.

Seluruh keputusan teknis harus tercermin di repository.

Repository minimal harus menyediakan:

- README.md
- START_HERE.md
- PROJECT_STATE.json
- PROJECT_MANIFEST.md
- AI_HANDOVER.md
- ROADMAP.md
- ARCHITECTURE.md
- SECURITY.md
- DEPLOYMENT.md
- MIGRATION.md
- BACKUP_RECOVERY.md
- TESTING.md
- CHANGELOG.md
- docs/sprints
- docs/architecture

Setiap sprint wajib memperbarui dokumentasi yang relevan.

Chat digunakan hanya sebagai media komunikasi.

---

## Consequences

Positif:

- handover lebih mudah;
- audit lebih mudah;
- AI baru dapat melanjutkan proyek;
- developer baru tidak membutuhkan riwayat chat;
- deployment lebih aman;
- dokumentasi sinkron dengan implementasi.

Trade-off:

- dokumentasi harus selalu diperbarui;
- sprint tidak selesai tanpa update dokumentasi.

---

## Mandatory Workflow

Inspect

↓

Design

↓

Patch

↓

Syntax Check

↓

Module Test

↓

Full Test

↓

Documentation Update

↓

Git Audit

↓

Commit

↓

Push

↓

Repository Audit

---

## Completion Criteria

Repository dianggap menjadi Single Source of Truth apabila:

- implementasi sesuai dokumentasi;
- dokumentasi sesuai implementasi;
- test lulus;
- working tree bersih;
- commit telah dibuat;
- branch sinkron dengan origin.
