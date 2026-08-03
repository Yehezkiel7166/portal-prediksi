<?php

declare(strict_types=1);

namespace App\Domains\Market\Support;

use App\Domains\Market\Models\Market;
use Carbon\CarbonImmutable;
use Throwable;

final class MarketScheduleStatusResolver
{
    /**
     * @return array{
     *     key:'open'|'closed'|'holiday'|'unknown',
     *     label:string
     * }
     */
    public function resolve(Market $market): array
    {
        if ($market->is_holiday) {
            return [
                'key' => 'holiday',
                'label' => filled($market->holiday_note)
                    ? 'Libur: '.$market->holiday_note
                    : 'Libur',
            ];
        }

        if (
            blank($market->timezone)
            || blank($market->open_time)
            || blank($market->close_time)
        ) {
            return [
                'key' => 'unknown',
                'label' => 'Status tidak tersedia',
            ];
        }

        try {
            $now = CarbonImmutable::now(
                $market->timezone
            );

            $activeDays = collect(
                $market->active_days ?? []
            )
                ->map(
                    static fn (mixed $day): string => strtolower(trim((string) $day))
                )
                ->filter();

            $todayCandidates = [
                strtolower($now->format('l')),
                strtolower($now->format('D')),
                (string) $now->dayOfWeekIso,
            ];

            if (
                $activeDays->isNotEmpty()
                && $activeDays
                    ->intersect($todayCandidates)
                    ->isEmpty()
            ) {
                return [
                    'key' => 'closed',
                    'label' => 'Tutup',
                ];
            }

            $openAt = CarbonImmutable::parse(
                $now->format('Y-m-d')
                    .' '
                    .$market->open_time,
                $market->timezone,
            );

            $closeAt = CarbonImmutable::parse(
                $now->format('Y-m-d')
                    .' '
                    .$market->close_time,
                $market->timezone,
            );

            if ($closeAt->lessThanOrEqualTo($openAt)) {
                if ($now->lessThan($openAt)) {
                    $openAt = $openAt->subDay();
                } else {
                    $closeAt = $closeAt->addDay();
                }
            }

            $open = $now->betweenIncluded(
                $openAt,
                $closeAt,
            );

            return [
                'key' => $open ? 'open' : 'closed',
                'label' => $open ? 'Buka' : 'Tutup',
            ];
        } catch (Throwable) {
            return [
                'key' => 'unknown',
                'label' => 'Status tidak tersedia',
            ];
        }
    }
}
