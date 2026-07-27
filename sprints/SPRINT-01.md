# Sprint 01 — Filament Admin Foundation

## Status
Selesai pada 18 Juli 2026.

## Hasil
- Filament v5.7.1 terpasang.
- Panel admin tersedia di `/admin`.
- Login admin tersedia di `/admin/login`.
- Logout admin tersedia di `/admin/logout`.
- Hanya pengguna dengan `is_admin = true` yang dapat mengakses panel admin.
- Command `php artisan admin:create` tersedia untuk membuat atau mempromosikan admin.
- Password admin divalidasi minimal 12 karakter, huruf besar, huruf kecil, angka, dan simbol.
- Email admin baru otomatis ditandai terverifikasi.
- Migration `add_is_admin_to_users_table` sudah dijalankan di production.

## Bug yang diperbaiki
`email_verified_at` sebelumnya tidak tersimpan karena belum tercantum dalam `$fillable` pada model `User`. Field tersebut sudah ditambahkan dan seluruh test kembali lulus.

## Pengujian
- AdminPanelAccessTest: 3 test lulus.
- CreateAdminUserCommandTest: 3 test lulus.
- Seluruh project: 10 test, 28 assertion, semuanya lulus.

## Checklist
- [x] Filament terpasang
- [x] Provider admin terdaftar
- [x] Route admin aktif
- [x] Migration is_admin berjalan
- [x] Akses admin dibatasi
- [x] Command admin:create tersedia
- [x] Test akses admin lulus
- [x] Test pembuatan admin lulus
- [x] Seluruh test project lulus
