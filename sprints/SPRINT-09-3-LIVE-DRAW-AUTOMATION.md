# Sprint 09.3 — Live Draw Automation

## Status

Completed.

## Objective

Mengotomatisasi lifecycle status Live Draw berdasarkan hari draw,
jam draw, timezone, lead time, dan durasi live.

## Components

- Action:
  `App\Domains\LiveDraw\Actions\UpdateLiveDrawStatusAction`
- Command:
  `live-draw:update-status`
- Configuration:
  `config/live-draw.php`
- Scheduler:
  setiap satu menit dengan `withoutOverlapping()`

## Lifecycle

- Jadwal tidak lengkap: `offline`
- Menjelang draw: `scheduled`
- Saat live window: `live`
- Setelah live window: `finished`
- Menjelang jadwal berikutnya: kembali ke `scheduled`
- `cancelled` tidak diubah automation

## Timezone

Setiap record dihitung berdasarkan timezone Live Draw masing-masing.

## Configuration

- `scheduled_lead_minutes`
- `live_duration_minutes`

## Database

Tidak ada migration baru karena struktur tabel Live Draw yang tersedia
sudah mencukupi.

## Boundaries

Sprint ini belum mencakup:

- integrasi Result terbaru
- status history
- webhook provider
- pemutar HLS
