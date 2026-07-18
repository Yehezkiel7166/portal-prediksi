# ADR-0001: Update-Safe Domain Evolution

## Status

Accepted.

## Context

Portal Prediksi CMS harus terus berkembang tanpa menjadikan perubahan baru sebagai
patch manual yang sulit dilacak. Modul seperti Market, Prediction, Result, dan
LiveDraw akan menerima penambahan field, relasi, validasi, dan fitur administratif
secara bertahap.

## Decision

1. Migration lama yang sudah pernah dijalankan tidak diubah.
2. Perubahan struktur database dibuat melalui migration baru.
3. Setiap perubahan diberi test regresi.
4. Logika bersama ditempatkan dalam class atau service terpusat.
5. Nilai dinamis seperti timezone tidak disalin atau ditulis manual pada banyak file.
6. Perubahan besar dibagi menjadi patch kecil yang dapat diuji dan dikembalikan.
7. Setiap patch harus melewati syntax check, module test, dan full test.
8. Setiap perubahan yang berhasil disimpan sebagai commit Git tersendiri.

## Consequences

- Update berikutnya lebih mudah dilacak.
- Kesalahan lama dapat diperbaiki tanpa merusak instalasi aktif.
- Penambahan modul tidak perlu menduplikasi logika.
- Rollback dan investigasi masalah menjadi lebih aman.
