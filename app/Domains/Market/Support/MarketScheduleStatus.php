<?php

declare(strict_types=1);

namespace App\Domains\Market\Support;

use App\Domains\Market\Models\Market;
use Carbon\CarbonImmutable;

final class MarketScheduleStatus
{
    /**
     * @return array{key:string,label:string,description:string}
     */
    public function resolve(
        Market $market,
        ?CarbonImmutable $now = null,
    ): array {
        $timezone = $market->timezone ?: config(
            'app.timezone',
            'UTC',
        );

        $now = ($now ?? CarbonImmutable::now($timezone))
            ->setTimezone($timezone);

        if (! $market->is_active) {
            return $this->status(
                'inactive',
                'Nonaktif',
                'Pasaran sedang dinonaktifkan.',
            );
        }

        if ($market->is_holiday) {
            return $this->status(
                'holiday',
                'Libur',
                $market->holiday_note
                    ?: 'Pasaran sedang libur.',
            );
        }

        if (
            ! $market->open_time
            || ! $market->close_time
            || ! $market->result_time
        ) {
            return $this->status(
                'upcoming',
                'Akan Datang',
                'Jadwal belum dikonfigurasi lengkap.',
            );
        }

        $cycle = $this->cycleContaining($market, $now);
        $open = $cycle['open'];
        $close = $cycle['close'];
        $result = $cycle['result'];
        $nextOpen = $cycle['next_open'];

        $activeDays = $market->active_days ?? [];

        if (
            $activeDays !== []
            && ! $this->isActiveCalendarDay(
                $now,
                $open,
                $close,
                $result,
                $activeDays,
            )
        ) {
            return $this->status(
                'holiday',
                'Libur',
                'Pasaran tidak beroperasi pada hari ini.',
            );
        }

        if ($now->gte($open) && $now->lt($close)) {
            return $this->status(
                'open',
                'Buka',
                'Pasaran sedang buka sesuai jadwal.',
            );
        }

        if ($now->gte($close) && $now->lt($result)) {
            return $this->status(
                'live',
                'Menunggu Hasil',
                'Pasaran telah tutup dan menunggu hasil.',
            );
        }

        if ($now->gte($result) && $now->lt($nextOpen)) {
            $hasResult = $market->results()
                ->whereDate(
                    'result_date',
                    $result->toDateString(),
                )
                ->exists();

            if (! $hasResult) {
                $hasResult = $market->results()
                    ->whereDate(
                        'result_date',
                        $open->toDateString(),
                    )
                    ->exists();
            }

            if ($hasResult) {
                return $this->status(
                    'result_available',
                    'Hasil Tersedia',
                    'Hasil pasaran telah tersedia.',
                );
            }

            return $this->status(
                'closed',
                'Tutup',
                'Pasaran telah selesai dan menunggu siklus berikutnya.',
            );
        }

        return $this->status(
            'upcoming',
            'Akan Datang',
            'Pasaran belum memasuki waktu buka.',
        );
    }

    /**
     * @return array{
     *     open:CarbonImmutable,
     *     close:CarbonImmutable,
     *     result:CarbonImmutable,
     *     next_open:CarbonImmutable
     * }
     */
    private function cycleContaining(
        Market $market,
        CarbonImmutable $now,
    ): array {
        $todayOpen = $now
            ->startOfDay()
            ->setTimeFromTimeString($market->open_time);

        $open = $now->gte($todayOpen)
            ? $todayOpen
            : $todayOpen->subDay();

        $close = $open->setTimeFromTimeString(
            $market->close_time
        );

        if ($close->lte($open)) {
            $close = $close->addDay();
        }

        $result = $close->setTimeFromTimeString(
            $market->result_time
        );

        if ($result->lte($close)) {
            $result = $result->addDay();
        }

        $nextOpen = $open
            ->addDay()
            ->setTimeFromTimeString($market->open_time);

        while ($nextOpen->lte($result)) {
            $nextOpen = $nextOpen->addDay();
        }

        return [
            'open' => $open,
            'close' => $close,
            'result' => $result,
            'next_open' => $nextOpen,
        ];
    }

    /**
     * Hari operasional menggunakan hari kalender saat ini.
     *
     * Siklus hari sebelumnya hanya boleh diteruskan setelah tengah
     * malam apabila waktu tutup atau hasil berada pada awal hari.
     * Ini mempertahankan kontrak market overnight tanpa membuat
     * market non-operasional tetap Buka sepanjang hari libur.
     *
     * @param  array<int, int|string>  $activeDays
     */
    private function isActiveCalendarDay(
        CarbonImmutable $now,
        CarbonImmutable $open,
        CarbonImmutable $close,
        CarbonImmutable $result,
        array $activeDays,
    ): bool {
        $normalizedDays = array_values(
            array_unique(
                array_map(
                    static function (int|string $day): int {
                        $number = (int) $day;

                        return $number === 0
                            ? 7
                            : $number;
                    },
                    $activeDays,
                )
            )
        );

        if (
            in_array(
                $now->dayOfWeekIso,
                $normalizedDays,
                true,
            )
        ) {
            return true;
        }

        $opensOnActiveDay = in_array(
            $open->dayOfWeekIso,
            $normalizedDays,
            true,
        );

        if (! $opensOnActiveDay) {
            return false;
        }

        $isPreviousDayCarry = ! $open->isSameDay($now);

        if (! $isPreviousDayCarry) {
            return false;
        }

        $finishesToday = $close->isSameDay($now)
            || $result->isSameDay($now);

        if (! $finishesToday) {
            return false;
        }

        $overnightBoundaryHour = 6;

        $closeIsOvernight = $close->hour
            < $overnightBoundaryHour;

        $resultIsOvernight = $result->hour
            < $overnightBoundaryHour;

        return $closeIsOvernight || $resultIsOvernight;
    }

    /**
     * @return array{key:string,label:string,description:string}
     */
    private function status(
        string $key,
        string $label,
        string $description,
    ): array {
        return compact('key', 'label', 'description');
    }
}
