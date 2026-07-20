# Architecture

## Overview

Portal Prediksi CMS adalah aplikasi modular Laravel dengan panel administrasi Filament dan frontend publik berbasis Blade.

Repository merupakan Single Source of Truth untuk implementasi, status proyek, keputusan arsitektur, pengujian, dan prosedur operasional.

## Technology Stack

- PHP 8.3
- Laravel 13
- Filament 5
- MySQL
- Database queue
- Blade
- Vite
- Tailwind CSS
- HLS.js

## Application Layers

### Domain Layer

Kode domain ditempatkan di bawah `app/Domains`.

Setiap domain dapat memiliki:

- models;
- events;
- listeners;
- services;
- support classes;
- enums atau value objects bila diperlukan;
- domain-specific actions.

Logika bisnis bersama tidak boleh tersebar di controller atau Blade.

### Application and HTTP Layer

Controller HTTP ditempatkan di `app/Http/Controllers`.

Controller harus:

- tetap tipis;
- menangani input dan response;
- mendelegasikan aturan bisnis ke domain service atau support class;
- menghindari query duplikat;
- menggunakan route model binding atau resolver yang jelas bila sesuai.

### Administration Layer

Panel administrasi menggunakan Filament.

Filament resources bertanggung jawab atas:

- form administrasi;
- table administrasi;
- filtering dan sorting;
- authorization presentation;
- relation manager bila data memang merupakan relasi child.

Filament resource tidak boleh menjadi lokasi utama aturan bisnis kompleks.

### Presentation Layer

Frontend publik menggunakan Blade views.

Views bertanggung jawab atas presentation dan tidak boleh memuat aturan bisnis atau query database langsung.

Seluruh layout dan media harus responsif terhadap mobile, tablet, dan desktop.

## Implemented Domains

### Core

Menyediakan infrastruktur bersama seperti application clock, scheduler heartbeat, events, listeners, dan reusable support services.

### Market

Mengelola pasaran, slug, kode, timezone, jadwal, status aktif, ordering, dan ketersediaan publik.

### Prediction

Mengelola prediksi berdasarkan market dan tanggal, termasuk publikasi, listing publik, dan halaman detail.

### Result

Mengelola hasil berdasarkan market dan tanggal, termasuk resolver hasil terbaru, listing publik, dan halaman detail.

### Shio

Mengelola periode shio, angka shio, perubahan periode, event domain, template banner, dan hasil banner.

### Promotion

Mengelola promosi yang dipublikasikan beserta listing dan detail publik.

### Blog

Mengelola artikel blog yang dipublikasikan beserta listing dan detail publik.

### Live Draw

Mengelola jadwal live draw, status otomatis, sumber stream, listing publik, dan playback HLS.

## Routing Architecture

Route publik berada dalam konfigurasi route Laravel dan menggunakan nama route yang stabil.

Route publik yang sudah tersedia:

- home;
- live draw;
- prediction listing dan detail;
- result listing dan detail;
- promotion listing dan detail;
- blog listing dan detail.

Perubahan URL publik harus mempertimbangkan compatibility, SEO, link lama, dan test.

## Data Access Rules

- Gunakan Eloquent query yang eksplisit.
- Pilih hanya kolom yang dibutuhkan bila query berada pada jalur publik yang sering digunakan.
- Gunakan eager loading untuk mencegah N+1 query.
- Gunakan scope atau resolver reusable untuk aturan query yang dipakai berulang.
- Gunakan index berdasarkan query nyata, bukan asumsi.
- Jangan menaruh query database dalam Blade.

## Time and Scheduling

Logika waktu bisnis harus menggunakan application clock yang sudah tersedia.

Jangan menggunakan waktu sistem secara langsung apabila behavior harus dapat diuji.

Scheduled commands yang sudah tersedia:

- scheduler heartbeat setiap lima menit;
- live draw status update setiap menit.

Scheduled jobs harus menggunakan `withoutOverlapping` ketika concurrent execution berisiko.

## Event Architecture

Gunakan domain event ketika perubahan state perlu diberitahukan kepada bagian aplikasi lain tanpa coupling langsung.

Event dan listener harus:

- memiliki tanggung jawab tunggal;
- dapat diuji;
- tidak menyembunyikan perubahan data penting;
- mempertimbangkan idempotency jika dapat dijalankan ulang.

## Database Architecture

- MySQL adalah database utama.
- Historical migration tidak boleh diedit.
- Perubahan schema menggunakan migration baru.
- Foreign key, unique constraint, dan index harus mencerminkan aturan domain.
- Migration production harus didahului backup.
- Perubahan berisiko menggunakan strategi roll-forward.

## Queue Architecture

Queue driver menggunakan database.

Task asynchronous harus:

- aman dijalankan ulang bila memungkinkan;
- memiliki retry behavior yang terukur;
- tidak menyimpan secret dalam payload;
- mencatat failure yang dapat ditindaklanjuti;
- tidak bergantung pada request lifecycle.

## Media Architecture

Media harus dikelola melalui fondasi terpusat saat implementasinya tersedia.

Prinsip wajib:

- responsive output;
- preset berdasarkan jenis aset;
- automatic ratio dan crop;
- focal point atau alignment yang dipilih admin;
- automatic thumbnail dan resolution;
- upload, direct URL, atau approved embed provider;
- provider whitelist;
- sanitization;
- larangan arbitrary JavaScript atau script mentah.

Admin tidak boleh mengatur ukuran pixel dan breakpoint secara manual.

## Security Boundaries

- Authentication dan authorization wajib diterapkan pada admin operations.
- Input harus divalidasi di server.
- Output HTML harus di-escape secara default.
- Rich content dan embed harus disanitasi.
- Secret hanya berada di environment configuration.
- Upload harus membatasi type, size, dan storage location.
- External URL tidak boleh dipercaya tanpa validation.

## Testing Architecture

Test suite terdiri dari unit dan feature tests.

Setiap perubahan behavior harus memiliki test pada level terdekat yang dapat membuktikan behavior tersebut.

Urutan validasi:

1. syntax check;
2. focused module test;
3. full test suite;
4. documentation verification;
5. Git audit.

## Documentation Architecture

- `README.md` memberikan ringkasan repository.
- `START_HERE.md` memberikan langkah awal.
- `PROJECT_STATE.json` memberikan state mesin-baca.
- `PROJECT_MANIFEST.md` memberikan manifest kemampuan.
- `AI_HANDOVER.md` memberikan aturan kelanjutan proyek.
- `ROADMAP.md` memberikan prioritas pengembangan.
- `ARCHITECTURE.md` memberikan struktur teknis.
- `SECURITY.md` memberikan aturan keamanan.
- `DEPLOYMENT.md` memberikan prosedur deployment.
- `MIGRATION.md` memberikan aturan database migration.
- `BACKUP_RECOVERY.md` memberikan prosedur backup dan recovery.
- `TESTING.md` memberikan strategi pengujian.
- `docs/sprints/` menyimpan catatan sprint.
- `docs/architecture/` menyimpan ADR.

## Architectural Change Rules

Keputusan arsitektur signifikan harus:

- dimulai dari inspeksi implementation;
- menjelaskan masalah dan constraint;
- membandingkan alternatif;
- dicatat dalam ADR;
- memiliki test atau verification yang sesuai;
- memperbarui dokumentasi terkait;
- tetap dalam satu tujuan dan satu commit.

## Prohibited Patterns

- aturan bisnis kompleks di controller;
- query database langsung di Blade;
- duplikasi resolver atau filter publication;
- hardcoded timezone list;
- perubahan migration historis;
- arbitrary script dari admin content;
- media dimension manual per device;
- force push pada shared branch;
- implementasi berdasarkan chat tanpa inspeksi repository.
