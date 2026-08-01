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

Repository Foundation, Phase 0.2 — Repository Governance Automation, dan Phase 0.3A — Canonical Repository Synchronization telah selesai. Completion commit Phase 0.3A adalah `45c4e5d`, yang telah sinkron dengan `origin/main`.

Phase 0.3B — Canonical Repository Validation adalah fase berikutnya yang direncanakan. Fase ini hanya boleh dimulai apabila inspeksi repository menemukan celah governance yang nyata dan belum tercakup oleh validasi saat ini.

Feature Freeze tetap berlaku. Jangan memulai sprint fitur baru selama Phase 0.3B belum diselesaikan atau belum secara eksplisit dibatalkan berdasarkan hasil inspeksi repository.

## Current Verified Repository State

- Branch: `main`
- Phase 0.2 completion commit: `5185ad7`
- Remote tracking branch: `origin/main`
- Phase 0.3A completion commit: `45c4e5d`
- Synchronization state at Phase 0.3A completion: local and remote synchronized
- Repository Foundation full suite: 174 tests and 480 assertions passed
- Repository governance audit: 5 checks passed, 0 failed
- GitHub Actions repository audit workflow: implemented and remote verification passed
- Completed milestone: Phase 0.3A — Canonical Repository Synchronization
- Next planned milestone: Phase 0.3B — Canonical Repository Validation
- Next recommended phase: Phase 0.3B — Canonical Repository Validation
<!-- PROJECT-BRAIN-V1-START -->
## Project Brain Handover

Before continuing work, read `docs/project-brain/README.md` and its canonical document list. The current top priority is Brand 1 production readiness within the 2026-07-24 to 2026-08-23 window. Do not let Owner Panel, marketplace, plugins, installer/updater, advanced AI, or Brand 2–5 implementation delay mandatory Brand 1 production gates.

Do not rely on chat history as implementation state. Use the latest repository snapshot, branch, commit, tests, and diff. The owner expects complete copy-paste-ready PowerShell or Bash instructions.
<!-- PROJECT-BRAIN-V1-END -->

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

<!-- SPRINT-15A-HANDOVER-START -->
## Sprint 15A Handover

The active baseline is commit `b702ef326147456d0e98ebb1ca8fbd4881f31d72`
on branch `feat/domain-management-foundation`.

Sprint 15A synchronizes repository knowledge only. It does not implement new
application behavior.

Read before continuing:

1. `docs/governance/CURRENT_DIRECTION.md`
2. `docs/governance/MASTER_PROMPT_V2_0_TO_V2_1_INHERITANCE.md`
3. `docs/project-brain/CANONICAL_REQUIREMENTS.md`
4. `docs/registry/LIFECYCLE_MODEL.md`
5. `PROJECT_STATE.json`
6. `docs/sprints/SPRINT-15A-REPOSITORY-BRAIN-CANONICAL-SYNC.md`

Next planned work is Sprint 15B Implementation Truth Audit.
<!-- SPRINT-15A-HANDOVER-END -->

<!-- SPRINT-COMPLETION-GATE-START -->
## Mandatory AI Continuation Rule

Before continuing any sprint, read the current repository instead of relying on
chat history.

Before declaring any sprint complete, re-read the repository and perform the
mandatory CTO crosscheck defined in
`docs/governance/SPRINT_COMPLETION_GATE.md`.

Do not commit a sprint completion state unless:

- regression and repository audit pass;
- implementation and documentation are synchronized;
- affected registries and state artifacts are synchronized;
- Brand 1 milestone alignment remains correct;
- a crosscheck report exists under `docs/sprints/crosschecks/`;
- its final CTO decision is `PASS`.

After push, verify local and remote HEAD equality, ahead/behind `0 0`, and a
clean working tree.
<!-- SPRINT-COMPLETION-GATE-END -->

<!-- SPRINT-15C-HANDOVER-START -->

## Sprint 15C Handover State

Baseline:

- branch: `feat/domain-management-foundation`;
- commit: `5ff3a08128e3f80f6dfef75fcaa26a5efa1cf3a7`;
- remote synchronized: yes;
- working tree at sprint start: clean.

Completed:

- Sprint 15B Permanent Sprint Completion Gate;
- commit, push, and remote verification;
- governance audit 7/7 PASS;
- Domain Management regression 169 tests / 512 assertions.

Active:

- Sprint 15C Repository Truth Synchronization;
- documentation and machine-readable state only;
- no application behavior changes.

After Sprint 15C:

- inspect synchronized implementation truth;
- select the highest-priority incomplete Brand 1 capability;
- preserve Brand 1 before Owner Panel;
- execute the mandatory completion workflow.
<!-- SPRINT-15C-HANDOVER-END -->

<!-- SPRINT-15C-HANDOVER-COMPLETED -->

## Sprint 15C Completed Handover

Sprint 15C — Repository Truth Synchronization is complete.

Before starting the next sprint:

1. preserve the completed Sprint 15C repository truth;
2. do not reopen repository reconciliation without new contradictory evidence;
3. select the highest-priority incomplete Brand 1 capability;
4. preserve the Brand 1 usable deadline of 2026-07-30;
5. keep Owner Panel and Brand 2–5 after Brand 1 usable completion and stabilization.

<!-- SPRINT-16A-HANDOVER-START -->

## Sprint 16A Completed Handover

Sprint 16A implements `MP21-F017 — Brand 1 Production Homepage`.

Do not recreate or replace the Homepage Engine without proving a verified defect
or changed canonical requirement.

Verified Homepage Engine evidence:

- explicit Brand-scoped content aggregation;
- Live Draw, latest Result, current Prediction, Promotion, and Article sections;
- mandatory Brand 1 module access;
- canonical and Open Graph metadata;
- safe null-context empty homepage;
- cross-brand isolation tests;
- full project regression: 421 tests / 1,166 assertions / PASS;
- governance audit: 7/7 PASS.

The following capabilities remain separate and incomplete where indicated by
their registries:

- Slot Gacor / RTP;
- Jackpot Proof;
- Complaint;
- Guide;
- Theme Engine;
- Widget Engine;
- incomplete Lottery Tool modules.

The next sprint must be selected from synchronized Brand 1 implementation truth.
Owner Panel and Brand 2–5 remain after Brand 1 completion and stabilization.

<!-- SPRINT-16A-HANDOVER-END -->

<!-- SPRINT-16D-AI-HANDOVER-START -->
## Sprint 16D — Post-Implementation Truth Synchronization

Canonical implementation truth at baseline `af99d9a6ab748188698b0cb09c6093d3f81ca891`:

- Sprint 16A Homepage Engine: IMPLEMENTED.
- Sprint 16B Slot Gacor / RTP: IMPLEMENTED.
- Sprint 16C Jackpot Proof: IMPLEMENTED and production migration completed.
- Latest full regression: 433 tests / 1,204 assertions / PASS.
- Governance audit: 7/7 PASS.
- Owner Panel and Brand 2–5 remain after Brand 1 completion and stabilization.
- Next implementation candidate: Visitor Complaint Engine.

This synchronization introduces no application behavior change.
<!-- SPRINT-16D-AI-HANDOVER-END -->

<!-- SPRINT-17B-AI-HANDOVER-START -->
## Sprint 17B Handover

Source baseline is commit `ac0303b5e90b17abf3abc6914783f75f46f2f27f` on `feat/domain-management-foundation`. Sprint 17A is complete and must not be repeated. Sprint 17B completes the complaint operational workflow through guarded transitions, administrator response, history/audit records, timestamps, and administrator notification. Run the supplied Hostinger script to validate, migrate, commit, push, and remotely verify. Do not select the next sprint until that remote verification passes.
<!-- SPRINT-17B-AI-HANDOVER-END -->

<!-- SPRINT-18A-HANDOVER-START -->
## Sprint 18A Guide Foundation Package

Use commit `53b5a85a53796c1f12b3c37b01d416673798731f` as the required baseline. The package implements the next incomplete Brand 1 public module: Guide. Do not declare completion before guarded Hostinger validation and remote verification.
<!-- SPRINT-18A-HANDOVER-END -->

<!-- SPRINT-18B-HANDOVER-START -->
## Sprint 18B Handover

Baseline is `de2ac4da66cddb760c3d1c679d09ec737b5c94b5` on `feat/domain-management-foundation`. Sprint 18B implements Jadwal Togel from Market configuration and confirmed Result data, plus Tabel Shio from the existing Shio domain. Do not mark complete before server validation and remote verification.
<!-- SPRINT-18B-HANDOVER-END -->

<!-- SPRINT-18C-HANDOVER-START -->
## Sprint 18C Handover

Baseline is `53968bc1084fadd1e695b23cbe99088567cf551a` on `feat/domain-management-foundation`. Sprint 18C implements BBFS Generator and Konversi Angka SGP as stateless deterministic tools. BBFS is limited by `config/lottery-tools.php`; the converter uses the documented ABCD positional rule. No database migration is required. Do not mark complete before PHP 8.3 targeted regression, full regression, governance audit, commit, push, and remote verification.
<!-- SPRINT-18C-HANDOVER-END -->


<!-- SPRINT-18D-HANDOVER-START -->
## Sprint 18D Handover

Baseline is `2ef3a9c84b6f9a5926009f9c5c97ac7f673dab0f` on `feat/domain-management-foundation`. Sprint 18D implements the final lottery tools: repository-owned Buku Mimpi reference search/detail pages and Result-derived Paito Togel Warna. No migration is required. Paito must never duplicate official Result data. Do not mark complete before PHP 8.3 targeted regression, full regression, governance audit, completion gate, commit, push, and remote verification.
<!-- SPRINT-18D-HANDOVER-END -->


<!-- SPRINT-18E-HANDOVER-START -->
## Sprint 18E Handover

Baseline is `20838e11d52af369fcb5b6274d089cecfa57429e` with tree `b05daff4acad282035e8b171ee53611b22d9eceb` on `feat/domain-management-foundation`. Sprint 18D is production-validated, committed, pushed, and remotely verified with 470 tests, 1336 assertions, governance audit 7/7, and completion gate PASS. Sprint 18E is documentation-only truth synchronization. No migration or application behavior change is allowed. The next approved implementation candidate is Site Configuration Foundation after a fresh repository inspection.
<!-- SPRINT-18E-HANDOVER-END -->

## Sprint 19A Increment Boundary

Sprint 19A is split into bounded increments. Sprint 19A-1 introduces only the site-configuration persistence and resolver foundation. Do not claim Filament administration, frontend metadata integration, analytics, Open Graph, JSON-LD, or media upload support until their later increments are implemented and validated. The required Sprint 19A-1 baseline is commit `1d84f0735ad788aff6b45488cfef9dbc87b222c8`, tree `00215be1be0f9a5683fb6ed30b7bcefc2bd9a222`.

<!-- SPRINT-19A-2-HANDOVER-START -->
## Sprint 19A-2 Handover

Sprint 19A-2 adds Filament administration for the current brand's single Site Configuration record. It must be validated on the Hostinger environment before completion. No migration is introduced. After remote verification, continue with Sprint 19A-3 frontend integration.
<!-- SPRINT-19A-2-HANDOVER-END -->

<!-- SPRINT-19A-3-HANDOVER-START -->
## Sprint 19A-3 Handover

The Site Configuration frontend integration is prepared on local branch `work` from baseline `6099abf713897fb7a59591d43fa279b500b00acc`. Before declaring completion, install the locked Composer dependencies, run targeted and full Laravel regression, change the Sprint 19A-3 CTO decision to `PASS` only if all gates pass, push to the intended canonical branch, and verify local/remote equality with ahead/behind `0 0`. No migration is required.
<!-- SPRINT-19A-3-HANDOVER-END -->

<!-- SPRINT-20A-AI-HANDOVER-START -->
## Sprint 20A Historical Handover

Sprint 20A is completed. It selected Sprint 20B as the next bounded
production-readiness audit.
<!-- SPRINT-20A-AI-HANDOVER-END -->

<!-- SPRINT-20C-AI-HANDOVER-START -->
## Sprint 20C Handover

Canonical baseline:
`19d863ace576bac9941cf7baa3f70b2b5af406ab` on `main`.

Sprint 20B implementation is committed, pushed, and remotely verified. Its
repository implementation includes backup creation, manifest and checksum
verification, scheduled backup registration, and isolated restore rehearsal.

Sprint 20C changes documentation and machine-readable state only.

Do not begin Owner Panel or Brand 2–5 work. The next bounded gate is:

**Sprint 20D — Production Runtime Activation Evidence**

That gate must verify actual production scheduler and queue execution using
timestamped evidence and failure handling. Repository registration is not
equivalent to active cPanel cron execution.
<!-- SPRINT-20C-AI-HANDOVER-END -->

<!-- SPRINT-20D-HANDOVER-START -->
## Sprint 20D Handover

Sprint 20D verified actual production scheduler, queue cron, and scheduled
backup execution at baseline `5da4d24646bc39e9ca5a2c3f326a2e43b6a78d17`.

Runtime evidence is stored locally at
`storage/logs/sprint-20d-runtime-evidence-20260731-023616.txt` and must not
be committed.

Governance passed 7/7 and runtime verification left the tracked repository
clean.

Next bounded gate: **Sprint 20E - Brand 1 Usable and Production Gate**.

<!-- SPRINT-20D-HANDOVER-END -->

<!-- SPRINT-20E-HANDOVER-START -->
## Sprint 20E RED Handover

Sprint 20E inspected Brand 1 production usability at baseline
`7ba7734c13bec7e44665014cb4af897bc05c03cc`.

Infrastructure runtime is healthy, but Brand 1 production acceptance is
blocked by missing canonical identity, production domain ownership, site
configuration, administrator access, minimum content, tenant-data
reconciliation, and security headers.

Inspection evidence remains runtime-local at:

`storage/logs/sprint-20e-inspection-20260731-044332.txt`

No application, database, cron, or tracked repository mutation occurred during
inspection.

Next bounded sprint: **Sprint 20F - Brand 1 Production Bootstrap and Data Remediation**.

<!-- SPRINT-20E-HANDOVER-END -->

<!-- SPRINT-20G-AI-HANDOVER-START -->
## Sprint 20G Handover

Canonical baseline:

`c53c742a6e526a8772e87023893311edc3786c81` on `main`.

Sprint 20F application implementation, regression, audit, commit, push, and
remote verification are complete.

Verified Sprint 20F evidence:

- full regression: `493` tests / `1428` assertions / PASS;
- canonical production Brand: ID `7`, name `SANTOTO4D`;
- production domain: `santoto4d-prediksi.site`;
- Site Configuration: present;
- administrator: verified;
- nullable Brand ownership in Result, Prediction, and Live Draw: zero;
- production domain BrandResolver: PASS;
- production security headers: implemented;
- local and remote HEAD: `c53c742a6e526a8772e87023893311edc3786c81`;
- ahead/behind: `0 0`;
- final working tree: clean.

Sprint 20G synchronizes repository truth only. It introduces no application
behavior, migration, or production database mutation.

After Sprint 20G completion, run:

**Sprint 20H - Brand 1 Production Acceptance Re-verification**

Do not repeat the Sprint 20F bootstrap implementation. Sprint 20H must perform
an independent runtime acceptance inspection.
<!-- SPRINT-20G-AI-HANDOVER-END -->

## Sprint 20G Completion Handover

Sprint 20G Completion Truth Synchronization passed regression, audit, and CTO
crosscheck.

- Regression: `493` tests / `1428` assertions / PASS.
- Governance checks: `7/7` PASS.
- CTO decision: PASS.
- Application behavior change: none.
- Database migration change: none.
- Production database mutation: none.

Next bounded sprint:

**Sprint 20H - Brand 1 Production Acceptance Re-verification**
