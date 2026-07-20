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
2. `PROJECT_STATE.json`
3. `PROJECT_MANIFEST.md`
4. `AI_HANDOVER.md`
5. `ARCHITECTURE.md`
6. `ROADMAP.md`

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

Status proyek tersedia pada `PROJECT_STATE.json` dan `PROJECT_MANIFEST.md`.
