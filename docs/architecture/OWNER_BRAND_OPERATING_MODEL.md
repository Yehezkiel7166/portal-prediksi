# OWNER AND BRAND OPERATING MODEL

Status: APPROVED
Target: 90 DAYS

## Owner Context

Owner mengelola:

- Brand provisioning;
- Brand activation dan suspension;
- Theme Registry;
- Homepage Template Registry;
- Widget Registry;
- provider global;
- game global;
- feature activation;
- platform security;
- global audit;
- global analytics;
- queue, scheduler, backup, dan deployment health.

## Brand Context

Setiap Brand mengelola:

- domain Brand;
- SEO;
- keyword;
- SERP;
- homepage content;
- Slot Gacor;
- RTP;
- Prediction;
- Result;
- Market;
- Live Draw;
- Promotion;
- Blog;
- Shio;
- BBFS;
- Buku Mimpi;
- Kode Alam;
- Paito;
- Panduan publik;
- Keluhan pengunjung;
- media;
- pengguna Brand;
- analytics Brand.

## Isolation Rules

- Owner Panel dan Brand Panel terpisah.
- Brand hanya dapat mengakses data Brand sendiri.
- Semua query Brand wajib menggunakan Brand Context.
- Cache, media, queue, scheduler, export, API, dan analytics wajib Brand-scoped.
- Cross-Brand access hanya untuk Owner dengan permission eksplisit.
- Unknown domain harus gagal dengan aman.
- Tidak boleh fallback otomatis ke Brand 1.
