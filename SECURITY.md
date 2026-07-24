# Security

## Purpose

Dokumen ini menetapkan baseline keamanan Portal Prediksi CMS untuk development, administration, deployment, media handling, dan operasi production.

Security controls harus mengikuti implementation repository aktual dan diperbarui ketika architecture atau threat model berubah.

## Core Principles

- least privilege;
- deny by default;
- server-side validation;
- output escaping by default;
- secrets outside repository;
- explicit authorization;
- safe failure behavior;
- auditable operational changes;
- dependency minimization;
- backup before destructive database operations.

## Authentication

- Filament admin hanya dapat diakses oleh user yang memenuhi aturan akses panel.
- Password harus disimpan menggunakan Laravel hashing.
- Jangan menyimpan password plain text.
- Session configuration production harus menggunakan cookie aman.
- Authentication behavior harus memiliki feature test.
- Account creation atau promotion menjadi admin harus melalui command atau workflow yang tervalidasi.

## Authorization

- Authentication tidak menggantikan authorization.
- Setiap administrative action harus dibatasi pada actor yang berhak.
- Resource, page action, relation manager, dan custom endpoint harus diaudit terhadap akses langsung.
- Jangan hanya menyembunyikan tombol UI tanpa melindungi action di server.
- Penambahan role atau permission baru harus memiliki requirement bisnis yang jelas.

## Input Validation

- Semua input harus divalidasi di server.
- Gunakan validation rules Laravel atau Filament yang sesuai.
- Validasi harus konsisten dengan database constraints.
- Slug, date, timezone, status, URL, file, dan relation identifier harus diperiksa secara eksplisit.
- Jangan mempercayai hidden field, query string, route parameter, atau data dari browser.
- Error message tidak boleh membocorkan credential, query sensitif, atau internal path yang tidak diperlukan.

## Output Safety

- Blade escaping harus tetap aktif secara default.
- Jangan menggunakan raw output untuk data yang belum disanitasi.
- Rich text harus melalui sanitizer yang sesuai.
- HTML dari admin atau external provider tidak boleh dirender langsung tanpa policy yang jelas.
- JavaScript mentah dari content field dilarang.

## Media and Upload Security

Upload harus membatasi:

- MIME type;
- extension;
- file size;
- image dimensions bila relevan;
- storage disk;
- generated filename;
- public accessibility.

Aturan tambahan:

- jangan mempercayai extension file saja;
- jangan menggunakan nama file asli sebagai path final tanpa normalisasi;
- cegah path traversal;
- file executable tidak boleh disimpan pada lokasi yang dapat dieksekusi web server;
- metadata sensitif sebaiknya dihapus bila media diproses;
- gunakan fallback aman ketika media rusak atau tidak tersedia.

## External URL Security

Direct image URL atau external media URL harus divalidasi.

Implementation harus mempertimbangkan:

- scheme hanya `http` atau `https` sesuai kebutuhan;
- pembatasan redirect;
- timeout;
- response size limit;
- content type verification;
- perlindungan SSRF;
- larangan akses ke localhost, private network, metadata service, dan internal host;
- cache atau proxy lokal bila architecture memerlukannya.

External URL tidak boleh diambil dari server tanpa kontrol SSRF yang memadai.

## Embed Security

- Hanya provider dalam whitelist yang boleh digunakan.
- Arbitrary iframe dan arbitrary script dilarang.
- URL provider harus dinormalisasi dan divalidasi.
- Attribute iframe harus dibatasi.
- Gunakan sandbox policy bila compatible.
- Jangan menerima event handler HTML seperti `onclick`.
- Jangan menerima `javascript:` URL.
- Content Security Policy harus dipertimbangkan sebelum menambah provider.

## HLS and Live Stream Security

- Stream URL harus divalidasi.
- Jangan mengekspos credential stream dalam repository atau frontend.
- Signed URL atau token harus memiliki masa berlaku bila provider mendukungnya.
- Error playback tidak boleh menampilkan credential atau internal response.
- External stream origin harus diperiksa terhadap provider yang diizinkan.
- Cross-origin requirement harus didokumentasikan dan dibatasi.

## Database Security

- Database credential hanya berada di `.env` atau secret management production.
- Application database user harus memiliki privilege minimum yang diperlukan.
- Jangan menggunakan root database account untuk aplikasi.
- Query dinamis harus menggunakan Eloquent atau parameter binding.
- Jangan membangun SQL dari input mentah.
- Production migration harus didahului backup.
- Database dump tidak boleh disimpan dalam repository atau public directory.

## Secrets Management

Secret meliputi:

- `APP_KEY`;
- database credential;
- SMTP credential;
- API token;
- stream token;
- storage credential;
- webhook secret;
- deployment credential.

Rules:

- jangan commit `.env`;
- jangan menaruh secret dalam documentation;
- jangan menaruh secret dalam test fixture publik;
- jangan menampilkan secret dalam log;
- gunakan environment variable;
- lakukan rotasi bila secret diduga bocor;
- cabut secret lama setelah rotasi terverifikasi.

## Logging

- Log harus cukup untuk diagnosis tanpa membocorkan data sensitif.
- Jangan mencatat password, token, cookie, authorization header, atau full credential URL.
- PII hanya dicatat bila benar-benar diperlukan dan telah diminimalkan.
- Exception production tidak boleh ditampilkan penuh kepada user publik.
- Log file harus berada di luar public path.
- Retention dan rotation harus dikonfigurasi sesuai kapasitas hosting.

## Production Configuration

Production harus menggunakan:

- `APP_ENV=production`;
- `APP_DEBUG=false`;
- HTTPS;
- secure cookies;
- trusted host configuration bila diperlukan;
- cache configuration yang sesuai;
- restricted filesystem permissions;
- scheduler dan queue worker yang terpantau.

Web server document root harus menunjuk ke public directory yang benar dan tidak mengekspos project root.

## HTTP Security Headers

Review production harus mempertimbangkan:

- Content-Security-Policy;
- X-Content-Type-Options;
- Referrer-Policy;
- Permissions-Policy;
- Strict-Transport-Security setelah HTTPS stabil;
- frame protection sesuai kebutuhan embed;
- secure cookie attributes.

Header harus diuji agar tidak merusak HLS, external media, Filament, atau asset build.

## CSRF and Request Protection

- Form state-changing harus menggunakan CSRF protection Laravel.
- Jangan menggunakan GET untuk operasi yang mengubah data.
- Endpoint custom harus mempertahankan middleware yang sesuai.
- Rate limiting harus digunakan untuk endpoint publik yang berisiko abuse.
- Webhook harus menggunakan signature validation bila ditambahkan.

## Dependency Security

- Gunakan dependency hanya bila diperlukan.
- Review package source, maintenance status, dan license sebelum instalasi.
- Jalankan Composer security audit secara berkala.
- Jalankan npm audit sebagai signal tambahan, lalu evaluasi impact nyata.
- Jangan melakukan upgrade mayor tanpa inspection, tests, dan migration review.
- Lock file harus di-commit.

## Git Security

- Jangan commit credential, `.env`, dump, backup, atau private key.
- Jangan force push ke shared branch.
- Jangan rewrite history untuk menyembunyikan secret tanpa prosedur incident yang benar.
- Jika secret terlanjur ter-commit, rotasi secret terlebih dahulu.
- Review `git diff --check` dan staged diff sebelum commit.

## Backup Security

- Backup harus disimpan di luar public directory.
- Backup harus memiliki access control yang ketat.
- Jangan menaruh credential dalam nama file atau log backup.
- Enkripsi backup bila storage atau transport tidak sepenuhnya dipercaya.
- Recovery test harus dilakukan secara berkala.
- Backup yang tidak dapat direstore tidak boleh dianggap valid.

## Security Testing

Setiap sprint harus mempertimbangkan test untuk:

- authorization;
- unpublished atau inactive content access;
- invalid input;
- malicious URL;
- unsupported media type;
- unauthorized admin access;
- unsafe embed rejection;
- fallback behavior;
- data exposure melalui public route.

## Incident Response

Jika terjadi dugaan insiden:

1. hentikan perubahan yang dapat menghapus bukti;
2. catat waktu dan scope awal;
3. batasi akses atau nonaktifkan credential terdampak;
4. rotasi secret;
5. periksa log dan perubahan Git;
6. identifikasi data atau service terdampak;
7. lakukan recovery dari backup terverifikasi bila diperlukan;
8. tambahkan regression test;
9. dokumentasikan root cause dan tindakan pencegahan.

## Security Review Triggers

Lakukan security review ketika:

- menambah upload atau media source;
- menambah external provider;
- menambah rich text atau embed;
- menambah API atau webhook;
- mengubah authentication atau authorization;
- mengubah storage;
- mengubah deployment architecture;
- menambah dependency;
- menangani data sensitif baru;
- menerima laporan vulnerability.

## Prohibited Practices

- password atau token dalam source code;
- `.env` dalam repository;
- raw SQL dengan input pengguna;
- raw HTML tanpa sanitizer;
- arbitrary JavaScript dari admin;
- unrestricted iframe;
- unrestricted server-side URL fetch;
- executable upload di public storage;
- production debug mode;
- database root account untuk aplikasi;
- backup dalam public directory;
- mengabaikan authorization karena halaman sudah tersembunyi.
<!-- PROJECT-BRAIN-V1-START -->
## Project Brain Security Baseline — 2026-07-24

Security is a Brand 1 production gate and an architectural concern across HTTP, administration, persistence, queue, scheduler, cache, storage, SEO, deployment, and operations.

Canonical additions:

- `docs/security/THREAT_MODEL.md`
- `docs/security/SECURITY_CONTROL_MATRIX.md`
- `docs/delivery/BRAND-1-PRODUCTION-GATE.md`

Mandatory P0 controls include:

- explicit Brand Context;
- brand-scoped policies and queries;
- cross-brand regression tests;
- guarded privileged attributes;
- authentication and session hardening;
- safe upload and remote media handling;
- secrets and log protection;
- sensitive-action audit records;
- dependency review;
- backup automation and successful restore rehearsal;
- queue failure visibility and scheduler heartbeat;
- production debug disabled and secure environment configuration.

The broad `is_admin` access pattern is transitional and is not sufficient for production multi-brand authorization. Policies and brand-scoped role/permission assignments are required before release.
<!-- PROJECT-BRAIN-V1-END -->
