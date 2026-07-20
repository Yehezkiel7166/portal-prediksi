# Deployment

## Purpose

Dokumen ini menjelaskan baseline deployment Portal Prediksi CMS secara aman, terverifikasi, dan dapat dipindahkan ke provider lain.

Deployment tidak boleh bergantung pada riwayat chat atau perubahan manual yang tidak tercatat di repository.

## Current Production Paths

- Project path: `/home/u339134899/domains/santoto4d-prediksi.site/portal-prediksi`
- Public path: `/home/u339134899/domains/santoto4d-prediksi.site/public_html`
- PHP CLI: `/opt/alt/php83/usr/bin/php`
- Primary branch: `main`

Path tersebut adalah konfigurasi deployment saat ini dan bukan requirement arsitektur permanen.

## Deployment Principles

- repository adalah sumber deployment;
- deploy hanya commit yang telah diuji;
- jangan deploy dari working tree kotor;
- backup sebelum migration production;
- gunakan maintenance mode hanya bila diperlukan;
- verifikasi hasil setiap tahap;
- hentikan deployment bila validasi gagal;
- simpan log deployment;
- hindari perubahan manual pada production;
- pertahankan arsitektur cloud-agnostic.

## Required Pre-Deployment Checks

Verifikasi:

- branch adalah `main`;
- local repository bersih;
- remote dapat diakses;
- target commit diketahui;
- full test suite sebelumnya lulus;
- `.env` production tersedia dan tidak berubah tanpa alasan;
- database backup berhasil;
- storage dan cache directory writable;
- PHP dan Composer memenuhi requirement;
- Node build tersedia bila asset harus dibangun di server.

Contoh pemeriksaan awal:

```bash
set -euo pipefail
cd /home/u339134899/domains/santoto4d-prediksi.site/portal-prediksi
PHP83=/opt/alt/php83/usr/bin/php

test "$(git branch --show-current)" = "main"
test -z "$(git status --porcelain)"
git fetch origin main
git log -1 --oneline
git rev-list --left-right --count HEAD...origin/main
$PHP83 --version
composer --version
```

## Recommended Deployment Sequence

1. verify baseline;
2. enable maintenance mode bila migration atau perubahan kritis memerlukannya;
3. create database backup;
4. fetch remote changes;
5. fast-forward local branch;
6. install Composer dependencies;
7. install and build frontend dependencies bila diperlukan;
8. run migration with force;
9. clear stale caches;
10. rebuild production caches;
11. ensure storage link and permissions;
12. restart queue worker;
13. disable maintenance mode;
14. run smoke checks;
15. verify scheduler, queue, logs, and Git state.

## Safe Git Update

Production branch harus diperbarui hanya dengan fast-forward.

```bash
git fetch origin main
git merge --ff-only origin/main
```

Jangan menggunakan:

- `git reset --hard` tanpa inspeksi dan persetujuan;
- force pull;
- force push;
- history rewrite;
- merge commit production yang tidak direncanakan.

## Composer Dependencies

Production installation:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
```

Rules:

- `composer.lock` harus digunakan;
- jangan menjalankan `composer update` saat deployment;
- jangan mengubah dependency langsung di production;
- hentikan deployment bila install gagal;
- audit dependency harus dilakukan dalam sprint terpisah bila ada issue.

## Frontend Assets

Apabila asset build dilakukan di server:

```bash
npm ci
npm run build
```

Rules:

- gunakan lock file;
- jangan menggunakan `npm install` untuk deployment rutin;
- build harus selesai sebelum public traffic menggunakan asset baru;
- verifikasi `public/build/manifest.json` tersedia;
- jangan menghapus asset lama sebelum build baru berhasil.

Apabila hosting tidak menyediakan Node.js, asset harus dibangun melalui pipeline atau environment build yang kompatibel dan hasilnya dideploy secara terkontrol.

## Environment Configuration

Production `.env` harus tetap berada di luar Git.

Minimum review:

- `APP_ENV=production`;
- `APP_DEBUG=false`;
- `APP_URL` benar;
- database credential benar;
- queue connection benar;
- mail configuration benar bila digunakan;
- filesystem disk benar;
- timezone application benar;
- session dan cookie aman;
- external provider credential tersedia bila diperlukan.

Jangan menjalankan `php artisan key:generate` pada production yang sudah aktif karena dapat membatalkan encrypted data dan session.

## Database Migration

Sebelum migration:

- buat backup database;
- periksa migration baru;
- evaluasi lock dan potensi downtime;
- pastikan migration tidak mengubah historical migration;
- pastikan rollback atau roll-forward plan tersedia.

Production command:

```bash
$PHP83 artisan migrate --force
```

Jangan menjalankan migration destructive tanpa persetujuan eksplisit Owner.

## Cache Management

Clear cache lama sebelum rebuild:

```bash
$PHP83 artisan optimize:clear
```

Rebuild production cache:

```bash
$PHP83 artisan config:cache
$PHP83 artisan route:cache
$PHP83 artisan view:cache
```

Jika route cache gagal karena closure route, hentikan deployment dan perbaiki implementation dalam sprint terpisah.

## Storage

Verifikasi storage link:

```bash
$PHP83 artisan storage:link
```

Perintah dapat gagal bila link sudah tersedia; verifikasi target link sebelum mengubahnya.

Directory berikut harus writable oleh runtime:

- `storage/`;
- `bootstrap/cache/`.

Jangan memberikan permission global `777` tanpa alasan yang tervalidasi.

## Queue Worker

Setelah deployment:

```bash
$PHP83 artisan queue:restart
```

Verifikasi worker aktif sesuai mekanisme hosting.

Queue harus dipantau untuk:

- failed jobs;
- worker berhenti;
- retry berulang;
- pertumbuhan tabel jobs;
- task yang tidak idempotent.

## Scheduler

Production cron harus menjalankan:

```bash
* * * * * cd /home/u339134899/domains/santoto4d-prediksi.site/portal-prediksi && /opt/alt/php83/usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

Verifikasi scheduler:

```bash
$PHP83 artisan schedule:list
$PHP83 artisan system:scheduler-heartbeat
```

Scheduler heartbeat harus digunakan untuk memastikan cron benar-benar berjalan.

## Maintenance Mode

Gunakan hanya bila diperlukan:

```bash
$PHP83 artisan down --retry=60
```

Setelah deployment berhasil:

```bash
$PHP83 artisan up
```

Pastikan aplikasi tidak tertinggal dalam maintenance mode ketika deployment gagal. Recovery harus memasukkan langkah verifikasi `artisan up` bila aman.

## Smoke Checks

Setelah deployment, periksa minimal:

- homepage merespons;
- admin login merespons;
- public prediction listing merespons;
- public result listing merespons;
- promotion listing merespons;
- blog listing merespons;
- live draw page merespons;
- asset CSS dan JavaScript termuat;
- media tidak menghasilkan error kritis;
- log tidak berisi exception baru;
- scheduler heartbeat diperbarui;
- queue worker aktif.

HTTP check dapat menggunakan `curl` terhadap domain production.

## Post-Deployment Git Audit

Verifikasi:

```bash
git status --short
git log -1 --oneline
git rev-list --left-right --count HEAD...origin/main
```

Expected result:

- working tree bersih;
- HEAD sama dengan commit target;
- local branch tidak berbeda dari `origin/main`.

## Rollback Strategy

Rollback kode hanya boleh dilakukan setelah menentukan apakah database tetap compatible.

Preferred strategy:

- rollback aplikasi ke commit sebelumnya hanya jika schema compatible;
- gunakan roll-forward patch untuk database change berisiko;
- restore database hanya bila diperlukan dan backup telah diverifikasi;
- dokumentasikan incident dan root cause.

Jangan melakukan `migrate:rollback` secara otomatis pada production tanpa menilai risiko kehilangan data.

## Failed Deployment Recovery

Jika deployment gagal:

1. hentikan tahap berikutnya;
2. simpan output error;
3. periksa working tree dan HEAD;
4. jangan menghapus file secara acak;
5. pastikan maintenance mode sesuai kondisi;
6. tentukan apakah database telah berubah;
7. gunakan roll-forward atau restore yang telah direncanakan;
8. jalankan smoke check setelah recovery;
9. dokumentasikan hasil.

## Deployment Automation Requirements

Otomasi deployment di masa depan harus memiliki:

- baseline guard;
- dry-run atau preview bila relevan;
- fast-forward only Git update;
- backup verification;
- migration guard;
- syntax dan health verification;
- logging;
- queue restart;
- rollback atau roll-forward procedure;
- final clean-tree audit.

Webhook deployment tidak boleh menerima request tanpa authentication dan signature validation.

## Cloud-Agnostic Requirement

Deployment scripts tidak boleh mengikat business logic ke Hostinger.

Provider-specific path dan command harus dipisahkan dari application architecture.

Application harus tetap dapat dipindahkan ke:

- shared hosting yang compatible;
- VPS;
- container;
- managed Laravel hosting;
- cloud infrastructure lain.

## Prohibited Deployment Practices

- deploy dari working tree kotor;
- `composer update` di production;
- mengedit source production tanpa commit;
- menjalankan destructive migration tanpa backup;
- mengaktifkan debug production;
- menyimpan `.env` di Git;
- menggunakan database root account;
- force push;
- menghapus cache, storage, atau build output tanpa verifikasi;
- menyatakan deployment berhasil tanpa smoke check.
