<?php

namespace App\Domains\Market\Support;

use App\Domains\Market\Models\Market;
use Carbon\CarbonImmutable;

final class MarketScheduleStatus
{
    /**
     * @return array{key:string,label:string,description:string}
     */
    public function resolve(Market $market, ?CarbonImmutable $now = null): array
    {
        $now = ($now ?? CarbonImmutable::now($market->timezone))
            ->setTimezone($market->timezone);

        if (! $market->is_active) {
            return $this->status('inactive', 'Nonaktif', 'Pasaran sedang dinonaktifkan.');
        }

        if ($market->is_holiday) {
            return $this->status(
                'holiday',
                'Libur',
                $market->holiday_note ?: 'Pasaran sedang libur.',
            );
        }

        $activeDays = $market->active_days ?? [];

        if ($activeDays !== [] && ! in_array($now->dayOfWeekIso, $activeDays, true)) {
            return $this->status('closed', 'Tutup', 'Pasaran tidak aktif pada hari ini.');
        }

        if (! $market->open_time || ! $market->close_time || ! $market->result_time) {
            return $this->status('upcoming', 'Akan Datang', 'Jadwal belum dikonfigurasi lengkap.');
        }

        $open = $now->setTimeFromTimeString($market->open_time);
        $close = $now->setTimeFromTimeString($market->close_time);
        $result = $now->setTimeFromTimeString($market->result_time);

        if ($now->lt($open)) {
            return $this->status('upcoming', 'Akan Datang', 'Pasaran belum dibuka.');
        }

        if ($now->lt($close)) {
            return $this->status('open', 'Buka', 'Pasaran sedang menerima aktivitas sesuai jadwal.');
        }

        if ($now->lt($result)) {
            return $this->status('live', 'Live', 'Pasaran telah ditutup dan menunggu hasil.');
        }

        $hasResult = $market->results()
            ->whereDate('result_date', $now->toDateString())
            ->exists();

        if ($hasResult) {
            return $this->status('result_available', 'Hasil Tersedia', 'Hasil hari ini telah tersedia.');
        }

        return $this->status('closed', 'Tutup', 'Jadwal hari ini telah selesai.');
    }

    /**
     * @return array{key:string,label:string,description:string}
     */
    private function status(string $key, string $label, string $description): array
    {
        return compact('key', 'label', 'description');
    }
}
