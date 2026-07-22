# AI Handover

Dokumen ini menyediakan konteks operasional minimum bagi AI atau developer baru yang melanjutkan Portal Prediksi CMS.

## Primary Rule

Repository adalah Single Source of Truth.

Jangan mengandalkan riwayat chat, ingatan, asumsi, atau instruksi lama yang bertentangan dengan implementasi repository saat ini.

## Project Location

- Project path: `/home/u339134899/domains/santoto4d-prediksi.site/portal-prediksi`
- Public path: `/home/u339134899/domains/santoto4d-prediksi.site/public_html`
- PHP CLI: `/opt/alt/php83/usr/bin/php`
- Primary branch: `main`
- Baseline before Repository Foundation: `dbc3b17`

## Required First Actions

Jalankan inspeksi berikut sebelum merancang perubahan:

```bash
set -euo pipefail
cd /home/u339134899/domains/santoto4d-prediksi.site/portal-prediksi
PHP83=/opt/alt/php83/usr/bin/php

git branch --show-current
git rev-parse --short HEAD
git log -1 --oneline
git status --short
git fetch origin main
git rev-list --left-right --count HEAD...origin/main
```

Jangan melakukan patch jika working tree tidak bersih atau perubahan yang ada belum dipahami.

## Required Reading Order

1. `START_HERE.md`
2. `PROJECT_STATE.json`
3. `PROJECT_MANIFEST.md`
4. `ARCHITECTURE.md`
5. `ROADMAP.md`
6. `SECURITY.md`
7. `TESTING.md`
8. `CHANGELOG.md`
9. Sprint terkait dalam `docs/sprints/`
10. ADR terkait dalam `docs/architecture/`

## Implemented Domains

- Core
- Market
- Prediction
- Result
- Shio
- Promotion
- Blog
- Live Draw

Jangan membangun ulang capability yang sudah tersedia tanpa membuktikan adanya kebutuhan, bug, technical debt, atau perubahan requirement.

## Mandatory Workflow

`Inspect → Design → Patch → Syntax Check → Module Test → Full Test → Documentation → Git Clean → Commit → Push → Audit`

Setiap tahap wajib selesai sebelum tahap berikutnya.

## Patch Strategy

- Satu tujuan menghasilkan satu patch dan satu commit.
- Lakukan inspeksi terarah pada file dan arsitektur yang relevan.
- Hindari inspeksi luas berulang apabila repository state sudah diketahui dan belum berubah.
- Gunakan perubahan kecil, deterministik, dan mudah diverifikasi.
- Jangan mengedit file secara manual melalui editor interaktif.
- Jangan menggunakan `apply_patch` karena tidak tersedia di server.
- Jangan menggunakan Python atau Perl untuk patch.
- Hindari heredoc panjang karena paste terminal dapat terpotong.
- Gunakan `printf` atau blok pendek per file.
- Verifikasi isi dan `git diff --check` setelah setiap bagian.

## Database Rules

- Jangan mengubah migration historis yang pernah dijalankan.
- Gunakan migration baru untuk perubahan schema.
- Pertahankan data dan kompatibilitas lama.
- Tambahkan foreign key, unique constraint, atau index hanya setelah memeriksa data dan aturan bisnis.
- Buat rollback aman bila memungkinkan.
- Gunakan roll-forward apabila rollback dapat menghilangkan data.

## Laravel Architecture Rules

- Pertahankan batas domain pada `app/Domains`.
- Gunakan service atau support class reusable untuk logika bersama.
- Controller harus tetap tipis.
- Hindari query dan aturan bisnis yang terduplikasi.
- Gunakan application clock yang sudah tersedia bila waktu memengaruhi logika bisnis.
- Pertahankan event dan listener yang sudah menjadi bagian arsitektur.
- Jangan menambah dependency baru tanpa kebutuhan yang jelas.

## Filament Rules

- Pertahankan struktur resource yang sudah ada.
- Jangan membuat resource terpisah bila relation manager lebih sesuai.
- Validasi harus konsisten dengan database dan domain rules.
- Admin tidak boleh dibebani pengaturan teknis yang dapat diotomatisasi sistem.

## Frontend Rules

- Seluruh tampilan harus responsif untuk mobile, tablet, dan desktop.
- Jangan gunakan ukuran visual statis yang merusak layout.
- Gunakan data published dan active sesuai aturan domain.
- Pertahankan route name dan URL publik yang sudah digunakan.
- Metadata SEO harus berasal dari data yang relevan dan aman.
- Jangan merender HTML, iframe, atau embed tanpa sanitasi dan whitelist.

## Media Rules

- Sistem menentukan ratio, crop, object fit, thumbnail, breakpoint, dan resolusi output.
- Admin hanya memilih sumber media dan focal point atau alignment bila diperlukan.
- Sumber media dapat berupa upload, direct URL, atau approved provider embed.
- Arbitrary script dan JavaScript mentah dilarang.
- External provider harus melalui whitelist dan sanitasi.

## Testing Rules

- Jalankan syntax check untuk seluruh file PHP yang berubah.
- Jalankan module test yang relevan.
- Jalankan full test suite sebelum commit.
- Jangan menghapus atau melemahkan test untuk membuat suite lulus.
- Tambahkan regression test untuk bug fix.
- Tambahkan feature test untuk behavior baru.

Contoh:

```bash
$PHP83 -l app/Path/ChangedFile.php
$PHP83 artisan test --filter=RelevantTest
$PHP83 artisan test
```

## Documentation Rules

Perubahan harus memperbarui dokumen yang relevan dalam sprint yang sama:

- sprint record;
- `CHANGELOG.md`;
- `PROJECT_STATE.json`;
- `PROJECT_MANIFEST.md`;
- `ROADMAP.md`;
- ADR untuk keputusan arsitektur signifikan.

## Git Rules

- Branch utama adalah `main`.
- Jangan force push.
- Jangan rewrite history.
- Jangan commit `.env`, credential, secret, log, database dump, atau backup.
- Commit hanya setelah seluruh validasi lulus.
- Push setelah commit berhasil.
- Verifikasi local branch sama dengan `origin/main`.
- Working tree akhir harus bersih.

## Destructive Actions

Tindakan berikut membutuhkan persetujuan eksplisit:

- menghapus data production;
- menghapus migration atau tabel;
- force push atau rewrite history;
- reset branch yang menghilangkan commit;
- mengganti requirement bisnis utama;
- menghapus fitur yang sudah digunakan;
- menjalankan migration berisiko tanpa backup.

## Definition of Done

Pekerjaan selesai hanya jika:

- tujuan sprint terpenuhi;
- implementation review tidak menemukan perubahan di luar scope;
- syntax check lulus;
- module test lulus;
- full test suite lulus;
- dokumentasi sesuai implementation;
- commit dan push berhasil;
- branch sinkron dengan remote;
- working tree bersih;
- audit akhir lulus.

## Current Handover State

Repository Foundation dan Phase 0.2 — Repository Governance Automation telah selesai. Completion commit Phase 0.2 adalah `5185ad7`, yang telah sinkron dengan `origin/main` dan berhasil diverifikasi melalui GitHub Actions.

Phase 0.3A — Canonical Repository Synchronization sedang berlangsung. Scope aktif terbatas pada sinkronisasi dokumen kanonis dengan kondisi repository setelah selesainya Phase 0.2, tanpa perubahan perilaku aplikasi.

Feature Freeze tetap berlaku. Jangan memulai sprint fitur baru sebelum Phase 0.3A selesai, di-commit, di-push, diverifikasi, dan working tree kembali bersih.

## Current Verified Repository State

- Branch: `main`
- Phase 0.2 completion commit: `5185ad7`
- Remote tracking branch: `origin/main`
- Synchronization state before Phase 0.3A changes: local and remote synchronized
- Current working tree: modified for Phase 0.3A documentation synchronization
- Repository Foundation full suite: 174 tests and 480 assertions passed
- Repository governance audit: 5 checks passed, 0 failed
- GitHub Actions repository audit workflow: implemented and remote verification passed
- Current milestone: Phase 0.3A — Canonical Repository Synchronization
- Next recommended phase: Phase 0.3B — Canonical Repository Validation
