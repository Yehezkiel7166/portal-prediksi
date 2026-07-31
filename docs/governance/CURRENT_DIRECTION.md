# Current Project Direction

Status: **Active and canonical**
Project start: **2026-07-16**
Brand 1 usable deadline: **2026-07-30**
Overall project deadline: **2026-10-14**

## Product Scope

Brand 1 tetap memiliki tepat:

- 10 modul utama;
- 6 alat togel.

### 10 Modul Utama

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

### 6 Alat Togel

1. Jadwal Togel
2. BBFS Generator
3. Buku Mimpi
4. Paito Togel Warna
5. Konversi Angka SGP
6. Tabel Shio

Domain Management, SEO, Media, Canonical, HTTPS, Health, Audit,
Cache, Queue, Security, dan Automation adalah foundation atau
platform service. Mereka bukan modul publik tambahan.

## Delivery Order

1. Brand 1 usable.
2. Brand 1 stabilization dan hardening.
3. Owner Panel.
4. Brand 2–5.
5. Multi-brand operational hardening.

Owner Panel tidak boleh menggantikan prioritas penyelesaian Brand 1.

## Mandatory Sprint Workflow

INSPECT → SYNC → RED → GREEN → REGRESSION → AUDIT →
CTO CROSSCHECK → COMMIT → PUSH → REMOTE VERIFY

Setiap ide, keputusan, perubahan prioritas, dan perubahan arsitektur
dari percakapan harus dicatat di repository pada sprint yang sama.

Setiap sprint wajib menyinkronkan:

- kode;
- test;
- roadmap;
- project state;
- sprint state;
- decision registry;
- idea registry;
- manifest;
- changelog;
- AI handover;
- dokumentasi arsitektur yang berkaitan.

Repository wajib dibaca ulang sebelum dan sesudah setiap sprint.

<!-- SPRINT-COMPLETION-GATE-START -->
## Sprint Completion Enforcement

The mandatory enforcement specification is:

- `docs/governance/SPRINT_COMPLETION_GATE.md`
- `docs/sprints/crosschecks/TEMPLATE.md`
- `scripts/repository/check-sprint-completion-gate.sh`

Every sprint begins with a repository re-read and ends with a second repository
re-read plus a CTO-level crosscheck before commit.

No sprint may be declared completed without a `PASS` crosscheck report and
successful remote verification.
<!-- SPRINT-COMPLETION-GATE-END -->

<!-- SPRINT-15C-DIRECTION-START -->
## Current Repository Execution State

- Sprint 15A: completed.
- Sprint 15B: completed and remote verified at
  `5ff3a08128e3f80f6dfef75fcaa26a5efa1cf3a7`.
- Sprint 15C: active repository truth synchronization.
- Domain Management: implemented through Sprint 14B.
- No application behavior is changed during Sprint 15C.
- After Sprint 15C, implementation returns to Brand 1 usable completion.
<!-- SPRINT-15C-DIRECTION-END -->

<!-- SPRINT-15C-DIRECTION-COMPLETED -->

## Direction After Sprint 15C

Sprint 15C repository truth synchronization is complete.

Implementation now returns to Brand 1 usable completion. Repository-only
synchronization work must not displace the highest-priority incomplete Brand 1
capability unless new verified repository contradictions are found.

<!-- SPRINT-20A-CURRENT-DIRECTION-START -->
## Sprint 20A Historical Direction

Sprint 20A repository truth reconciliation is completed. Its selection of
Sprint 20B is retained as historical evidence.
<!-- SPRINT-20A-CURRENT-DIRECTION-END -->

<!-- SPRINT-20C-CURRENT-DIRECTION-START -->
## Current Execution State — Sprint 20C

- Canonical branch: `main`.
- Baseline: `19d863ace576bac9941cf7baa3f70b2b5af406ab`.
- Current sprint: Sprint 20C — Repository Truth Synchronization.
- Application behavior change: none.
- Migration change: none.
- Sprint 20B backup automation and restore rehearsal: implemented.
- Next sprint: Sprint 20D — Production Runtime Activation Evidence.

Mandatory workflow:

`INSPECT → RED → GREEN → REGRESSION → AUDIT →
RE-READ → CTO CROSSCHECK → COMMIT → PUSH → REMOTE VERIFY`

The next sprint must collect actual production runtime evidence for scheduler
and queue execution. Repository command registration alone is not proof that
the corresponding production cron jobs are active.
<!-- SPRINT-20C-CURRENT-DIRECTION-END -->

<!-- SPRINT-20D-CURRENT-DIRECTION-START -->
## Current Execution State - Sprint 20D Complete

- Canonical branch: `main`.
- Baseline: `5da4d24646bc39e9ca5a2c3f326a2e43b6a78d17`.
- Sprint 20D production runtime evidence: complete.
- Scheduler production runtime: PASS.
- Queue cron production runtime: PASS.
- Scheduled production backup verification: PASS.
- Application behavior change: none.
- Database migration change: none.
- Next sprint: Sprint 20E - Brand 1 Usable and Production Gate.

Mandatory workflow:

`INSPECT -> RED -> GREEN -> REGRESSION -> AUDIT ->
RE-READ -> CTO CROSSCHECK -> COMMIT -> PUSH -> REMOTE VERIFY`

<!-- SPRINT-20D-CURRENT-DIRECTION-END -->
