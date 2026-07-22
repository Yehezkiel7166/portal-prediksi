# ADR-0004 - Clock Abstraction

## Status

Accepted

## Context

Beberapa bagian CMS membutuhkan waktu saat ini, termasuk prediction
publishing, result processing, scheduling, banners, SEO, cache, dan jobs.

Pemanggilan waktu sistem secara langsung pada domain dan application service
akan menyulitkan testing serta meningkatkan coupling terhadap Carbon.

## Decision

Aplikasi menyediakan kontrak `App\Core\Contracts\Clock`.

Implementasi production menggunakan
`App\Core\Support\SystemClock` dan mengembalikan `CarbonImmutable`.

Binding kontrak terhadap implementasi didaftarkan sebagai singleton melalui
Laravel service container.

Kode baru yang membutuhkan waktu saat ini sebaiknya menerima `Clock` melalui
constructor injection, bukan memanggil `now()` atau `Carbon::now()` secara
langsung.

## Consequences

### Positive

- Waktu dapat diganti dengan fake clock saat testing.
- Domain service menjadi lebih deterministik.
- Implementasi waktu dapat diganti tanpa mengubah konsumennya.
- Seluruh modul menggunakan sumber waktu yang konsisten.

### Trade-offs

- Menambah satu dependency untuk service yang membutuhkan waktu.
- Implementasi lama belum otomatis dimigrasikan ke Clock.

## Compatibility

Perubahan ini bersifat additive dan tidak mengubah perilaku implementasi lama.
Migrasi penggunaan waktu dilakukan secara bertahap pada patch terpisah.
