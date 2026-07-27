# Sprint 04 - Markets Module

## Tujuan

Menyediakan master data pasaran yang digunakan secara konsisten oleh modul
Prediction, Result, dan LiveDraw.

## Implementasi

- Tabel `markets`
- Model domain `Market`
- Factory dan seeder
- `UpsertMarketAction`
- Filament Market Resource
- Form tambah dan ubah pasaran
- Daftar, pencarian, filter, pengurutan, dan penghapusan
- Validasi kode, slug, timezone, status, dan urutan
- Feature tests

## Struktur Data

Kolom utama:

- `code`
- `name`
- `slug`
- `timezone`
- `is_active`
- `sort_order`
- `notes`

## Master Data Awal

- Singapore
- Hong Kong
- Sydney
- Japan
- Taiwan
- Cambodia

## Route Admin

- `/admin/markets`
- `/admin/markets/create`
- `/admin/markets/{record}/edit`

## Verifikasi

- Migration berhasil
- Seeder berhasil
- Route Filament tersedia
- Halaman admin dapat diakses
- Semua automated tests lulus
