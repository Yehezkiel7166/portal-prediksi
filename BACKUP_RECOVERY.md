# Backup and Recovery

## Purpose

Dokumen ini menetapkan baseline backup dan recovery Portal Prediksi CMS agar perubahan production dapat dipulihkan secara terukur.

Backup tidak dianggap valid sebelum dapat diverifikasi dan direstore.

## Backup Scope

Backup production harus mencakup:

- database MySQL;
- file upload dan generated media;
- configuration penting yang tidak berada di Git;
- `.env` production melalui penyimpanan aman;
- scheduler dan cron configuration;
- queue worker configuration;
- deployment-specific configuration;
- daftar commit production aktif.

Source code tidak perlu dibackup sebagai arsip utama apabila seluruh commit telah tersedia di remote repository.

## Backup Principles

- backup sebelum migration;
- backup sebelum destructive operation;
- backup berada di luar public directory;
- backup tidak disimpan hanya pada server yang sama;
- akses backup dibatasi;
- backup diberi timestamp;
- backup diverifikasi;
- retention diterapkan;
- restore diuji secara berkala;
- secret tidak dicatat dalam log.

## Database Backup

Gunakan credential production melalui environment atau mekanisme aman.

Contoh umum:

```bash
mysqldump --single-transaction --quick --routines --triggers DATABASE_NAME > database-backup.sql
```

Jangan menaruh password langsung dalam command history.

Untuk database besar, pertimbangkan compression:

```bash
mysqldump --single-transaction --quick DATABASE_NAME | gzip > database-backup.sql.gz
```

## File Backup

Backup file harus mencakup storage yang berisi user upload dan generated asset.

Jangan memasukkan:

- cache sementara;
- session sementara;
- log yang tidak diperlukan;
- dependency yang dapat dipasang ulang;
- build sementara yang dapat diregenerasi.

Contoh archive:

```bash
tar -czf storage-backup.tar.gz storage/app/public
```

Path aktual harus diverifikasi sebelum menjalankan backup.

## Backup Naming

Gunakan format yang mudah diaudit:

```text
portal-prediksi-YYYYMMDD-HHMMSS-database.sql.gz
portal-prediksi-YYYYMMDD-HHMMSS-storage.tar.gz
```

Nama file tidak boleh mengandung password, token, email credential, atau secret lain.

## Backup Verification

Setelah backup:

- pastikan file tersedia;
- pastikan ukuran masuk akal;
- uji integritas compression;
- simpan checksum;
- verifikasi dump dapat dibaca;
- catat commit dan waktu backup;
- salin ke storage terpisah.

Contoh:

```bash
gzip -t database-backup.sql.gz
sha256sum database-backup.sql.gz storage-backup.tar.gz
```

## Retention

Retention harus disesuaikan dengan kapasitas dan kebutuhan bisnis.

Baseline yang disarankan:

- backup harian untuk beberapa hari terakhir;
- backup mingguan untuk beberapa minggu;
- backup bulanan untuk periode lebih panjang;
- backup khusus sebelum migration besar atau release penting.

Jangan menghapus backup terbaru sebelum backup pengganti selesai dan terverifikasi.

## Storage Location

Backup harus disimpan:

- di luar `public_html`;
- dengan permission terbatas;
- idealnya pada storage berbeda dari server production;
- menggunakan encryption bila storage tidak sepenuhnya dipercaya.

Backup yang hanya tersimpan pada server production tidak cukup untuk menghadapi kegagalan server atau account compromise.

## Recovery Planning

Sebelum melakukan recovery, tentukan:

- jenis insiden;
- waktu recovery target;
- commit aplikasi yang sesuai;
- backup database yang sesuai;
- compatibility schema;
- apakah storage juga harus dipulihkan;
- dampak terhadap queue dan scheduler;
- apakah traffic harus dihentikan.

## Recovery Types

### Application Code Recovery

Gunakan commit terakhir yang diketahui stabil.

Code rollback hanya aman jika database schema tetap compatible.

### Database Recovery

Restore database hanya setelah:

- backup dipilih dan diverifikasi;
- database target dikosongkan atau disiapkan dengan benar;
- application traffic dihentikan bila diperlukan;
- compatibility dengan commit aplikasi dikonfirmasi.

### Media Recovery

Restore upload dan generated media ke storage path yang benar.

Verifikasi ownership, permission, symlink, dan public accessibility setelah restore.

## Database Restore

Contoh restore dump tidak terkompresi:

```bash
mysql DATABASE_NAME < database-backup.sql
```

Contoh restore dump terkompresi:

```bash
gunzip -c database-backup.sql.gz | mysql DATABASE_NAME
```

Jangan menjalankan restore production tanpa memastikan target database benar.

## Recovery Sequence

1. hentikan perubahan lebih lanjut;
2. aktifkan maintenance mode bila diperlukan;
3. simpan log dan bukti insiden;
4. catat HEAD dan working tree;
5. pilih backup dan commit yang compatible;
6. buat backup tambahan dari kondisi rusak bila masih memungkinkan;
7. restore database;
8. restore storage;
9. deploy commit aplikasi yang sesuai;
10. clear dan rebuild cache;
11. restart queue worker;
12. jalankan migration hanya bila recovery plan memerlukannya;
13. nonaktifkan maintenance mode;
14. jalankan smoke test;
15. audit log, scheduler, queue, Git, dan data.

## Post-Recovery Validation

Periksa:

- homepage;
- admin login;
- market data;
- prediction data;
- result data;
- shio data;
- promotion dan blog;
- live draw;
- upload dan generated media;
- scheduler heartbeat;
- queue worker;
- log application;
- Git synchronization.

## Recovery Test

Recovery test harus dilakukan secara berkala pada environment non-production.

Test harus membuktikan:

- dump database dapat direstore;
- aplikasi dapat berjalan dengan database hasil restore;
- media dapat dipulihkan;
- permission benar;
- migration state benar;
- smoke checks lulus;
- waktu recovery tercatat.

## Backup Automation Requirements

Otomasi backup di masa depan harus memiliki:

- guard terhadap environment;
- timestamp;
- log;
- database dump verification;
- checksum;
- compression verification;
- copy ke remote storage;
- retention cleanup yang aman;
- failure notification;
- recovery documentation;
- dry-run untuk cleanup bila relevan.

Cleanup otomatis tidak boleh menghapus seluruh backup ketika backup terbaru gagal.

## Incident Documentation

Setelah recovery, dokumentasikan:

- waktu insiden;
- penyebab;
- data terdampak;
- backup yang digunakan;
- commit yang digunakan;
- durasi recovery;
- validation result;
- tindakan pencegahan;
- test atau automation baru.

## Prohibited Practices

- backup di public directory;
- backup hanya pada server yang sama;
- password dalam command history;
- backup tanpa verification;
- restore tanpa memastikan target database;
- menghapus backup lama sebelum backup baru terverifikasi;
- menyatakan recovery selesai tanpa smoke checks;
- menjalankan destructive cleanup tanpa guard;
- mengandalkan source code manual di production sebagai backup.
