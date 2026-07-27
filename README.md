# Portal Prediksi CMS

Portal Prediksi CMS adalah aplikasi Laravel untuk mengelola pasaran, prediksi, hasil, shio, promosi, blog, dan live draw.

Repository GitHub ini merupakan **Single Source of Truth** proyek.

## Technology Stack

- PHP 8.3
- Laravel 13
- Filament 5
- MySQL
- Database Queue
- Vite 8
- Tailwind CSS 4
- HLS.js

## Implemented Domains

- Core
- Market
- Prediction
- Result
- Shio
- Promotion
- Blog
- Live Draw

## Public Routes

- `/`
- `/live-draw`
- `/prediksi-togel`
- `/prediksi-togel/{marketSlug}/{predictionDate}`
- `/data-result`
- `/data-result/{marketSlug}/{resultDate}`
- `/promosi`
- `/promosi/{slug}`
- `/blog`
- `/blog/{slug}`

## Administration

Filament Admin tersedia pada `/admin`.

Admin resources:

- Markets
- Predictions
- Results
- Shio Periods dan Shio Numbers
- Promotions
- Blog Posts
- Live Draws

## Required Reading

Developer atau AI yang melanjutkan proyek wajib membaca:

1. `START_HERE.md`
2. `PROJECT_STATE.md`
3. `PROJECT_STATE.json` (machine-readable compatibility artifact)
4. `SPRINT_STATE.md`
5. `PROJECT_MANIFEST.md`
6. `AI_HANDOVER.md`
7. `ARCHITECTURE.md`
8. `ROADMAP.md`
9. `SECURITY.md`
10. `TESTING.md`
11. `CHANGELOG.md`

## Development Workflow

`Inspect → Design → Patch → Syntax Check → Module Test → Full Test → Documentation → Git Clean → Commit → Push → Audit`

## Core Rules

- Repository adalah sumber kebenaran utama.
- Jangan bergantung pada riwayat chat.
- Jangan mengulang sprint yang sudah selesai.
- Jangan mengubah migration lama yang sudah digunakan.
- Gunakan migration baru untuk perubahan database.
- Satu tujuan menghasilkan satu patch dan satu commit.
- Setiap perubahan harus memiliki test yang sesuai.
- Dokumentasi diperbarui dalam sprint yang sama.

## Local Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan test
```

## Operational Documentation

- `DEPLOYMENT.md`
- `MIGRATION.md`
- `BACKUP_RECOVERY.md`
- `SECURITY.md`
- `TESTING.md`

Status proyek kanonis tersedia pada `PROJECT_STATE.md`.

`PROJECT_STATE.json` dipertahankan sebagai machine-readable compatibility artifact dan harus selalu konsisten dengan `PROJECT_STATE.md`.
<!-- PROJECT-BRAIN-V1-START -->
## Project Brain

The repository now includes a canonical Project Brain that preserves product vision, working agreements, decisions, architecture direction, feature ideas, security controls, and the Brand 1 delivery plan.

Start with [`docs/project-brain/README.md`](docs/project-brain/README.md).

Current delivery order:

1. Brand 1 production readiness by the maximum target date of 2026-08-23.
2. Brand 1 optimization and hardening.
3. Owner Panel.
4. Brand 2–5 activation.
5. Enterprise expansion.

Security is a mandatory production gate. See [`docs/security/THREAT_MODEL.md`](docs/security/THREAT_MODEL.md), [`docs/security/SECURITY_CONTROL_MATRIX.md`](docs/security/SECURITY_CONTROL_MATRIX.md), and [`docs/delivery/BRAND-1-PRODUCTION-GATE.md`](docs/delivery/BRAND-1-PRODUCTION-GATE.md).
<!-- PROJECT-BRAIN-V1-END -->

<!-- SPRINT-17B-README-START -->
## Current Delivery State — Sprint 17B

Sprint 17B completes the operational Visitor Complaint workflow with controlled status transitions, administrator responses, immutable status history, handler attribution, timestamps, and administrator notification. Production requires both complaint migrations to run after deployment.
<!-- SPRINT-17B-README-END -->
