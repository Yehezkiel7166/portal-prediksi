# Sprint 09.2 — Public Live Draw

## Status

Completed.

## Objective

Menyediakan halaman publik Live Draw yang menampilkan konfigurasi
Live Draw dari pasaran aktif.

## Route

- URI: `/live-draw`
- Name: `live-draw.index`
- Controller: `Frontend\LiveDrawController`

## Visibility Rules

Halaman hanya menampilkan Live Draw ketika:

- status bukan `cancelled`
- Market terkait masih aktif

Urutan menggunakan:

1. priority
2. title
3. id

## Public States

### Live

Jika provider YouTube atau Vimeo dan stream type `iframe`, sistem
membuat embed URL yang telah dinormalisasi.

Jika stream type `url`, halaman menampilkan tombol menuju sumber live
dengan `noopener noreferrer`.

### Scheduled

Halaman menampilkan jadwal dan pemberitahuan bahwa siaran belum dimulai.

### Finished

Halaman menampilkan tombol menuju Data Result untuk Market terkait.

### Offline

Halaman menampilkan pemberitahuan bahwa siaran tidak tersedia.

## Media

Logo dan background menggunakan penyimpanan publik.

Tampilan media:

- responsif
- menggunakan container
- background memakai cover
- focal point mengikuti pilihan admin
- logo memakai object contain
- tidak membutuhkan pengaturan ukuran manual dari admin

## Security

- Raw iframe dan JavaScript tidak disimpan.
- Embed hanya dibentuk dari URL YouTube dan Vimeo yang dikenali.
- YouTube menggunakan domain `youtube-nocookie.com`.
- URL eksternal dibuka dengan `noopener noreferrer`.
- Source tidak ditampilkan ketika status bukan live.

## SEO

Halaman memiliki:

- title
- meta description
- canonical URL
- Open Graph title
- Open Graph description
- Open Graph URL

## Boundaries

Sprint ini belum mencakup:

- scheduler status otomatis
- pemutar HLS
- Result terbaru langsung di dalam card
- riwayat status
- webhook provider
