# Sprint 09.1 — Live Draw Foundation

## Status

Completed.

## Objective

Membangun fondasi domain dan administrasi Live Draw berdasarkan
ADR-0005.

## Database

Tabel `live_draws` menyimpan:

- relasi Market
- identitas Live Draw
- provider dan jenis stream
- source URL
- jadwal hari dan jam
- timezone IANA
- lifecycle status
- headline dan footer
- logo dan background
- focal point background
- priority
- catatan internal

## Domain

- Model: `App\Domains\LiveDraw\Models\LiveDraw`
- Action: `App\Domains\LiveDraw\Actions\UpsertLiveDrawAction`
- Factory: `Database\Factories\LiveDrawFactory`

## Status

- offline
- scheduled
- live
- finished
- cancelled

## Providers

- official
- youtube
- vimeo
- custom

## Stream Types

- url
- iframe
- hls

Raw JavaScript tidak diterima. Source hanya berupa URL HTTP atau HTTPS.

## Admin

Filament Resource tersedia pada:

`/admin/live-draws`

Admin dapat:

- membuat Live Draw
- memilih Market
- mengatur provider
- mengatur URL stream
- mengatur jadwal
- mengatur timezone
- mengatur status
- mengunggah logo dan background
- memilih focal point background
- mengatur headline, footer, dan priority

## Boundaries

Sprint ini belum mencakup:

- halaman frontend publik
- pergantian status otomatis
- scheduler automation
- sinkronisasi Result
- validasi whitelist iframe provider
- pemutar HLS frontend

Fitur tersebut tetap dikerjakan sebagai sprint terpisah.
