# ADR-0006 — Live Draw Architecture

## Status

Accepted.

## Context

Promotion dan Blog telah selesai.

Tahap berikutnya adalah Live Draw.

Live Draw bukan sekadar halaman frontend, tetapi merupakan modul inti
yang akan menjadi penghubung antara:

- Market
- Result
- Frontend
- Scheduler
- Automation

Karena migration lama tidak boleh diubah, desain harus selesai sebelum
implementasi dimulai.

## Goals

- Desain stabil.
- Tidak membutuhkan perubahan migration lama.
- Mendukung banyak provider.
- Mendukung banyak pasaran.
- Mendukung otomatisasi di masa depan.

## Proposed Modules

### LiveDraw

Entity utama.

Menyimpan konfigurasi live draw setiap pasaran.

Contoh field:

- market_id
- title
- slug
- provider
- draw_schedule
- timezone
- live_source
- stream_type
- status
- priority
- notes

### LiveDrawStatus

Status runtime.

Kemungkinan status:

- offline
- scheduled
- live
- finished
- cancelled

### LiveDrawProvider

Provider video.

Contoh:

- YouTube
- Vimeo
- Official iframe
- Direct HLS
- URL internal

Provider dibuat extensible.

## Market Relation

Market

1

↓

N

LiveDraw

## Result Relation

Result tetap menjadi sumber hasil resmi.

Saat status Live Draw berubah menjadi finished, frontend akan
mengambil data Result terbaru.

Tidak ada duplikasi data result di tabel Live Draw.

## Frontend

Halaman:

/live-draw

Halaman detail tidak diperlukan pada tahap awal.

## CMS

Admin dapat mengatur:

- provider
- jadwal
- timezone
- URL stream
- headline
- logo
- background
- warna
- footer
- status

tanpa edit kode.

## Automation

Scheduler nantinya dapat:

- membuka status live
- menutup status
- menghubungkan Result terbaru

tanpa perubahan arsitektur.

## Future Sprint

Sprint 09.1

Live Draw Foundation

Sprint 09.2

Live Draw Admin

Sprint 09.3

Public Live Draw

Sprint 09.4

Automation

Sprint 09.5

Result Integration

