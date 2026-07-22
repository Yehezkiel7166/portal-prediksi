# Start Here

Dokumen ini adalah titik awal wajib bagi developer atau AI yang akan melanjutkan Portal Prediksi CMS.

## Repository Is the Source of Truth

Jangan menggunakan riwayat chat sebagai acuan implementasi.

Selalu verifikasi repository aktual sebelum membuat perubahan:

```bash
git branch --show-current
git rev-parse --short HEAD
git log -1 --oneline
git status --short
git remote -v
git fetch origin main
```

Branch utama adalah `main`.

Jangan melakukan patch apabila:

- working tree berisi perubahan yang belum dipahami;
- branch tidak sesuai;
- local branch tertinggal dari remote;
- sprint sebelumnya belum selesai;
- dokumentasi bertentangan dengan implementasi.

## Required Reading

Baca secara berurutan:

1. `PROJECT_STATE.md`
2. `PROJECT_STATE.json` (machine-readable compatibility artifact)
3. `SPRINT_STATE.md`
4. `PROJECT_MANIFEST.md`
5. `AI_HANDOVER.md`
6. `ARCHITECTURE.md`
7. `ROADMAP.md`
8. `SECURITY.md`
9. `TESTING.md`
10. `CHANGELOG.md`
11. Dokumentasi dalam `docs/sprints/`
12. ADR dalam `docs/architecture/`

Gunakan `PROJECT_STATE.md`, `PROJECT_STATE.json`, dan `SPRINT_STATE.md` untuk menentukan fase aktif, fase selesai, serta pekerjaan berikutnya. Jangan mengambil status fase dari dokumen historis atau riwayat chat.

## Inspect Before Patch

Inspeksi minimum:

```bash
git status --short
git log --oneline -10
find app -maxdepth 5 -type f | sort
find tests -maxdepth 6 -type f | sort
find database/migrations -maxdepth 1 -type f | sort
php artisan route:list
php artisan schedule:list
```

Setelah inspeksi umum, periksa hanya file yang relevan dengan tujuan sprint.

## Mandatory Workflow

`Inspect → Design → Patch → Syntax Check → Module Test → Full Test → Documentation → Git Clean → Commit → Push → Audit`

## Database Rules

- Jangan mengubah migration historis.
- Gunakan migration baru untuk perubahan schema.
- Pertahankan kompatibilitas dan data lama.
- Tambahkan index dan constraint berdasarkan aturan bisnis nyata.
- Backup production sebelum menjalankan migration.
- Gunakan roll-forward apabila rollback berisiko menghilangkan data.

## Testing Rules

Setiap perubahan harus menjalankan module test yang relevan dan full test:

```bash
php artisan test --filter=<RelevantTest>
php artisan test
```

File PHP yang berubah harus diperiksa dengan `php -l`.

Jangan menghapus atau menonaktifkan test hanya untuk membuat suite lulus.

## Documentation Rules

Perbarui dokumen berikut bila relevan:

- dokumentasi fitur atau sprint;
- `CHANGELOG.md`;
- `PROJECT_STATE.md`;
- `PROJECT_STATE.json` (compatibility artifact bila diperlukan);
- `PROJECT_MANIFEST.md`;
- `ROADMAP.md`;
- ADR untuk keputusan arsitektur penting.

## Git Rules

- Primary branch adalah `main`.
- Satu tujuan menghasilkan satu patch dan satu commit.
- Jangan force push atau rewrite history.
- Jangan commit `.env`, credential, dump database, log, atau backup.
- Push hanya dilakukan setelah seluruh validasi lulus.
- Working tree harus bersih setelah commit.

## Completion Criteria

Sprint selesai hanya apabila:

- implementasi sesuai tujuan;
- syntax check lulus;
- module test lulus;
- full test lulus;
- dokumentasi diperbarui;
- commit dan push berhasil;
- working tree bersih;
- audit akhir tidak menemukan ketidaksesuaian.
