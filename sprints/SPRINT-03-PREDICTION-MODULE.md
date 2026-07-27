# Sprint 03 — Prediction Module

## Status

Implemented.

## Objective

Membangun modul pengelolaan prediksi sebagai pola arsitektur
untuk modul bisnis Portal Prediksi CMS berikutnya.

## Architecture

- Business domain: `app/Domains/Prediction`
- Filament resource: `app/Filament/Resources/Predictions`
- Factory: `database/factories/PredictionFactory.php`
- Tests: `tests/Feature/Prediction`

Logika penyimpanan dipusatkan pada
`UpsertPredictionAction` agar tidak bergantung pada Filament.

## Database

Tabel `predictions` memiliki kolom:

- `id`
- `market`
- `prediction_date`
- `predicted_numbers`
- `status`
- `notes`
- `published_at`
- timestamps

Kombinasi `market` dan `prediction_date` harus unik.

## Status

- `draft`
- `published`
- `archived`

## Admin Features

- List, create, edit, and delete predictions
- Bulk deletion
- Search
- Status filter
- Status badge
- Automatic publication timestamp

## Testing Checklist

- [x] Create normalized draft prediction
- [x] Set publication time automatically
- [x] Update existing prediction
- [x] Reject duplicate market and date
- [x] Permit the same market on another date
- [x] Permit administrator access
- [x] Reject regular user access

## Manual Verification

1. Login through `/admin/login`.
2. Open **Konten Prediksi → Prediksi**.
3. Create a draft prediction.
4. Edit it and publish it.
5. Verify search, filtering, and deletion.
