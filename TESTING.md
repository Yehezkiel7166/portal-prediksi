# Testing

## Purpose

Dokumen ini menetapkan strategi pengujian Portal Prediksi CMS agar setiap perubahan dapat diverifikasi sebelum commit dan deployment.

Pengujian harus membuktikan behavior aktual, bukan hanya memastikan kode dapat dijalankan.

## Testing Principles

- test behavior yang penting;
- gunakan test level terdekat;
- regression bug harus memiliki regression test;
- test tidak boleh bergantung pada urutan eksekusi;
- test harus deterministik;
- gunakan application clock untuk behavior berbasis waktu;
- hindari external network dalam test;
- jangan menyembunyikan failure dengan skip tanpa alasan;
- full test suite wajib lulus sebelum commit foundation atau feature selesai.

## Test Layers

### Unit Tests

Unit test digunakan untuk class dengan logic terisolasi seperti:

- support class;
- resolver;
- formatter;
- catalog;
- value object;
- calculation;
- time rule;
- normalization.

Unit test sebaiknya cepat dan tidak bergantung pada database bila tidak diperlukan.

### Feature Tests

Feature test digunakan untuk behavior aplikasi seperti:

- HTTP route;
- controller response;
- database persistence;
- authentication;
- authorization;
- Filament access;
- Artisan command;
- scheduled behavior;
- event and listener integration;
- publication filtering;
- validation.

### Integration Tests

Integration test digunakan bila beberapa komponen perlu dibuktikan bersama, misalnya:

- model, service, dan database;
- event dan listener;
- scheduler dan command;
- media resolver dan storage;
- external provider abstraction dengan fake atau stub.

### Smoke Tests

Smoke test digunakan setelah deployment untuk memastikan jalur kritis dapat diakses.

Smoke test tidak menggantikan automated test suite.

## Mandatory Validation Sequence

Setiap sprint mengikuti urutan:

1. syntax check;
2. focused module test;
3. full test suite;
4. documentation verification;
5. Git diff check;
6. commit;
7. push;
8. final repository audit.

## PHP Syntax Checks

Jalankan syntax check untuk seluruh file PHP yang diubah.

Contoh:

```bash
$PHP83 -l app/Path/ChangedFile.php
$PHP83 -l tests/Feature/ChangedTest.php
```

Untuk beberapa file:

```bash
find app tests database -type f -name "*.php" -print0 | xargs -0 -n1 $PHP83 -l
```

Full syntax scan dapat digunakan pada foundation atau refactor besar, tetapi focused syntax check tetap diperlukan agar failure mudah dilacak.

## Focused Module Tests

Jalankan test yang paling dekat dengan perubahan.

Contoh:

```bash
$PHP83 artisan test --filter=PublicLiveDrawTest
$PHP83 artisan test tests/Feature/Frontend/PublicBlogFrontendTest.php
$PHP83 artisan test tests/Feature/Admin
```

Nama test harus dipilih berdasarkan repository aktual.

Jangan menganggap module test lulus bila filter tidak menemukan test.

## Full Test Suite

Setelah focused test lulus:

```bash
$PHP83 artisan test
```

Full test suite wajib dijalankan sebelum commit final.

Catat jumlah tests dan assertions dalam sprint documentation atau changelog bila relevan.

## Database Testing

Database test harus:

- menggunakan environment testing;
- tidak mengakses database production;
- menggunakan refresh strategy yang sesuai;
- membuat data melalui factory atau setup eksplisit;
- tidak bergantung pada data existing;
- memverifikasi constraint penting.

Test migration harus mempertimbangkan:

- fresh migration;
- schema compatibility;
- foreign key;
- unique constraint;
- default value;
- nullable behavior;
- data backfill bila ada.

## Time-Based Testing

Gunakan application clock atau time fake yang sudah menjadi bagian architecture.

Test berbasis waktu harus mencakup boundary penting seperti:

- sebelum jadwal;
- tepat pada jadwal;
- setelah jadwal;
- pergantian tanggal;
- timezone berbeda;
- daylight saving time bila relevan.

Jangan bergantung pada waktu server aktual dalam automated tests.

## Authentication and Authorization Tests

Test minimal harus membuktikan:

- guest tidak dapat mengakses admin;
- non-admin tidak dapat mengakses panel;
- admin yang valid dapat mengakses panel;
- server menolak action tanpa authorization;
- hidden UI bukan satu-satunya security control.

Setiap perubahan access rule harus memiliki regression test.

## Public Frontend Tests

Test frontend publik harus memverifikasi:

- route merespons;
- view yang benar dirender;
- data published tampil;
- draft atau inactive data tidak tampil;
- empty state aman;
- slug atau date invalid menghasilkan response yang benar;
- relation yang diperlukan tersedia;
- metadata penting bila behavior tersebut menjadi requirement.

## Filament Tests

Test Filament harus mencakup behavior penting, bukan seluruh detail presentation.

Prioritas:

- panel access;
- resource query scope;
- form validation;
- create and update behavior;
- relation manager behavior;
- custom action;
- authorization;
- data persistence.

## Event and Listener Tests

Test event architecture harus membuktikan:

- event didispatch pada perubahan state yang benar;
- event tidak didispatch ketika state tidak berubah;
- listener menghasilkan effect yang diharapkan;
- listener aman dijalankan ulang bila idempotency diperlukan;
- transaction boundary dipertimbangkan.

Gunakan fake hanya ketika tujuan test memang memverifikasi dispatch. Gunakan integration test bila effect listener juga harus dibuktikan.

## Scheduler and Command Tests

Command test harus memverifikasi:

- exit code;
- output penting;
- database changes;
- guard;
- idempotency;
- invalid state handling.

Scheduler test atau inspection harus membuktikan:

- command terdaftar;
- frequency benar;
- `withoutOverlapping` digunakan bila diperlukan;
- heartbeat dapat dijalankan.

## Queue Tests

Queue behavior harus diuji dengan fake atau sync execution sesuai tujuan test.

Periksa:

- job didispatch;
- payload tidak memuat secret;
- retry behavior;
- failure path;
- idempotency;
- database state setelah job.

## Media Security Tests

Ketika Media Foundation diimplementasikan, test minimal mencakup:

- file type valid;
- file type invalid;
- file size limit;
- direct URL valid;
- private network URL ditolak;
- unsupported provider ditolak;
- unsafe iframe ditolak;
- arbitrary JavaScript ditolak;
- focal point tersimpan;
- responsive preset dipilih otomatis;
- fallback media bekerja.

## Test Data Rules

- gunakan nama dan email dummy;
- jangan menggunakan credential production;
- jangan menyalin data pribadi ke fixture;
- gunakan factory state untuk scenario penting;
- hindari random data pada assertion kritis;
- gunakan tanggal eksplisit atau frozen clock.

## External Services

Automated tests tidak boleh bergantung pada layanan eksternal nyata.

Gunakan:

- fake;
- mock;
- stub;
- local fixture;
- provider abstraction.

Network integration nyata harus ditempatkan pada test manual atau environment khusus dan tidak boleh menghalangi test suite utama.

## Regression Testing

Setiap bug fix harus:

1. memiliki test yang gagal sebelum fix;
2. memiliki patch minimal;
3. membuat test tersebut lulus;
4. menjalankan full suite;
5. mendokumentasikan root cause bila signifikan.

## Test Naming

Nama test harus menjelaskan behavior.

Contoh yang baik:

- `guest_cannot_access_admin_panel`;
- `published_blog_post_is_visible_publicly`;
- `inactive_market_is_excluded_from_public_listing`;
- `live_draw_status_updates_when_schedule_starts`.

Hindari nama seperti `test_one`, `works`, atau `basic_test`.

## Failure Handling

Jika test gagal:

- hentikan commit;
- baca failure pertama;
- identifikasi apakah code, test, fixture, atau environment yang salah;
- jangan menghapus assertion hanya agar test lulus;
- jangan menambah skip tanpa alasan terdokumentasi;
- jalankan focused test setelah perbaikan;
- jalankan full suite kembali.

## Documentation Verification

Perubahan documentation harus diverifikasi dengan:

```bash
test -s DOCUMENT.md
grep -Fq "Expected Section" DOCUMENT.md
git diff --check
```

JSON documentation harus diparse.

Contoh:

```bash
$PHP83 -r 'json_decode(file_get_contents("PROJECT_STATE.json"), true, 512, JSON_THROW_ON_ERROR); echo "JSON VALID\n";'
```

## Git Verification

Sebelum commit:

```bash
git diff --check
git status --short
git diff --stat
git diff --cached --check
```

Setelah commit dan push:

```bash
git status --short
git log -1 --oneline
git fetch origin main
git rev-list --left-right --count HEAD...origin/main
```

Expected final state:

- working tree bersih;
- commit tersedia;
- local dan origin synchronized.

## Coverage Guidance

Coverage percentage bukan satu-satunya ukuran kualitas.

Prioritaskan coverage untuk:

- business rules;
- authorization;
- publication state;
- time boundaries;
- data integrity;
- error handling;
- security-sensitive input;
- resolver dan reusable services.

## Performance Testing

Performance review diperlukan ketika:

- menambah listing besar;
- menambah eager loading kompleks;
- menambah media processing;
- menambah scheduled batch;
- menambah backfill;
- menambah external request;
- query production menjadi lambat.

Periksa query count, N+1, index, memory, timeout, dan batch size.

## Prohibited Testing Practices

- mengakses production database;
- menggunakan production credential;
- test yang bergantung pada waktu server aktual;
- test yang bergantung pada external network;
- menghapus assertion agar test lulus;
- skip tanpa alasan;
- menyatakan sprint selesai hanya karena focused test lulus;
- commit tanpa full test suite;
- mengabaikan test failure yang dianggap tidak terkait tanpa investigasi.

<!-- BEGIN SPRINT-20B-SAFE-TEST-RUNNER -->

## Safe PHPUnit runner

Production repository menggunakan cached configuration pada
`bootstrap/cache/config.php`. Menjalankan PHPUnit secara langsung dapat membuat
Laravel membaca konfigurasi production sebelum konfigurasi testing digunakan.

Seluruh test repository wajib dijalankan melalui:

```bash
bin/test-safe
```

Target test dan opsi PHPUnit dapat diteruskan langsung:

```bash
bin/test-safe \
    --filter=test_action_creates_normalized_blog_post \
    tests/Feature/Blog/BlogModuleTest.php
```

Runner ini:

- menetapkan `APP_ENV=testing`;
- menetapkan SQLite `:memory:`;
- menggunakan cache, queue, session, dan mail non-persistent;
- mengisolasi seluruh Laravel bootstrap cache ke direktori sementara;
- tidak menghapus atau mengubah production config cache;
- membersihkan isolated cache setelah proses selesai.

Perintah `vendor/bin/phpunit` secara langsung tidak digunakan pada production
working copy ini.

<!-- END SPRINT-20B-SAFE-TEST-RUNNER -->
