# Migration

## Purpose

Dokumen ini menetapkan aturan perubahan database Portal Prediksi CMS agar aman, dapat diaudit, dan tidak merusak data production.

## Core Rules

- Jangan mengubah migration historis yang sudah pernah dijalankan.
- Semua perubahan schema menggunakan migration baru.
- Perubahan harus mempertahankan data lama.
- Backup production wajib dibuat sebelum migration.
- Migration harus diuji pada database non-production terlebih dahulu.
- Destructive migration membutuhkan persetujuan eksplisit Owner.
- Gunakan roll-forward bila rollback berisiko menghilangkan data.

## Before Creating a Migration

Periksa:

- migration yang sudah ada;
- model dan relation terkait;
- factory dan seeder;
- query publik dan admin;
- validation rules;
- test yang sudah tersedia;
- volume data production;
- index dan constraint yang sudah ada.

Jangan menambah column, index, foreign key, atau unique constraint berdasarkan asumsi tanpa inspeksi.

## Naming

Gunakan nama migration yang menjelaskan perubahan secara spesifik.

Contoh:

- `add_status_to_live_draws_table`;
- `create_site_settings_table`;
- `add_market_id_to_predictions_table`;
- `add_unique_index_to_results_table`.

Hindari nama umum seperti `update_table` atau `fix_database`.

## Adding Columns

Ketika menambah column:

- tentukan nullable atau default dengan sengaja;
- pertimbangkan data existing;
- gunakan type yang sesuai;
- tambahkan index hanya bila query memerlukannya;
- perbarui model casts atau fillable bila relevan;
- perbarui validation, factory, test, dan documentation.

Column wajib baru pada tabel berisi data harus menggunakan strategi aman:

1. tambahkan nullable atau default;
2. lakukan backfill;
3. verifikasi data;
4. ubah menjadi required dalam migration terpisah bila diperlukan.

## Renaming Columns

Sebelum rename:

- cari seluruh penggunaan nama lama;
- periksa query, tests, Blade, Filament, factory, seeder, command, event, dan documentation;
- pertimbangkan deployment dua tahap untuk compatibility.

Untuk perubahan production berisiko, gunakan strategi expand and contract:

1. tambahkan column baru;
2. tulis ke column lama dan baru bila diperlukan;
3. backfill data;
4. pindahkan semua pembacaan ke column baru;
5. hapus column lama dalam sprint terpisah setelah verifikasi.

## Foreign Keys

Sebelum menambah foreign key:

- pastikan seluruh data existing valid;
- cari orphan records;
- tentukan behavior `cascade`, `restrict`, atau `set null` berdasarkan aturan bisnis;
- jangan menggunakan cascade delete tanpa memahami dampaknya;
- tambahkan index bila database tidak membuatnya secara otomatis.

Data cleanup harus dipisahkan dan diverifikasi sebelum constraint diterapkan.

## Unique Constraints

Sebelum unique constraint:

- cari duplicate existing;
- tentukan scope uniqueness dengan benar;
- periksa case sensitivity dan collation;
- tambah validation yang sesuai;
- buat regression test.

Jangan mengandalkan validation aplikasi saja untuk invariant database penting.

## Indexes

Index harus berdasarkan pola query nyata.

Pertimbangkan index untuk:

- foreign key;
- publication status;
- active status;
- slug;
- date filtering;
- market and date combinations;
- sorting yang sering digunakan.

Hindari index berlebihan karena menambah biaya write dan storage.

## Data Backfill

Backfill besar tidak boleh dilakukan tanpa memperhitungkan timeout dan lock.

Untuk volume besar:

- gunakan batch;
- gunakan command terpisah;
- buat operation idempotent;
- catat progress;
- sediakan dry-run bila relevan;
- verifikasi jumlah record sebelum dan sesudah.

Jangan memasukkan proses berat dan tidak terukur langsung ke migration production.

## Destructive Changes

Destructive changes meliputi:

- drop table;
- drop column;
- mengubah type yang dapat memotong data;
- menghapus relation;
- menghapus unique data;
- mass update tanpa recovery plan.

Tindakan tersebut membutuhkan:

- persetujuan eksplisit Owner;
- backup terverifikasi;
- impact analysis;
- recovery plan;
- test;
- deployment window bila diperlukan.

## Migration Testing

Minimum validation:

```bash
$PHP83 artisan migrate:fresh --seed --env=testing
$PHP83 artisan test --filter=RelevantTest
$PHP83 artisan test
```

Bila test environment tidak menggunakan seeder penuh, gunakan setup yang sesuai repository.

Periksa juga rollback migration baru pada database non-production bila rollback aman.

## Production Execution

Sebelum menjalankan:

```bash
$PHP83 artisan migrate --force
```

Pastikan:

- backup selesai;
- migration list telah diperiksa;
- application code compatible;
- maintenance mode dipertimbangkan;
- queue dan scheduler impact diketahui;
- rollback atau roll-forward tersedia.

## Rollback Policy

Rollback hanya digunakan bila aman terhadap data.

Jangan menjalankan `migrate:rollback` secara otomatis hanya karena deployment gagal.

Pilih berdasarkan kondisi:

- rollback code bila schema masih compatible;
- roll-forward migration bila data sudah berubah;
- restore backup hanya bila diperlukan dan telah diverifikasi.

## Model and Application Updates

Migration tidak dianggap selesai sampai bagian berikut diperbarui bila relevan:

- Eloquent model;
- casts;
- fillable atau guarded;
- relation;
- validation;
- Filament form dan table;
- factory;
- seeder;
- query resolver;
- frontend;
- tests;
- documentation.

## Documentation

Setiap migration sprint harus mencatat:

- tujuan schema change;
- compatibility impact;
- data migration atau backfill;
- rollback atau roll-forward plan;
- test result;
- deployment requirement.

Perbarui `CHANGELOG.md`, sprint document, dan ADR bila perubahan bersifat arsitektural.

## Prohibited Practices

- mengedit migration lama yang sudah dijalankan;
- destructive migration tanpa backup;
- foreign key tanpa audit orphan data;
- unique constraint tanpa audit duplicate;
- backfill besar tanpa batching;
- migration yang bergantung pada request atau external API;
- production migration tanpa `--force` dalam deployment terkontrol;
- menyatakan migration aman tanpa test dan recovery plan.
