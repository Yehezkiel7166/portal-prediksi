<?php

namespace App\Domains\LiveDraw\Actions;

use App\Core\Contracts\Clock;
use App\Domains\LiveDraw\Models\LiveDraw;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use InvalidArgumentException;

final class UpdateLiveDrawStatusAction
{
    public function __construct(
        private readonly Clock $clock,
    ) {
    }

    public function execute(): int
    {
        $updated = 0;

        LiveDraw::query()
            ->whereNotIn('status', [
                LiveDraw::STATUS_CANCELLED,
            ])
            ->orderBy('id')
            ->chunkById(
                100,
                function (Collection $liveDraws) use (&$updated): void {
                    foreach ($liveDraws as $liveDraw) {
                        if ($this->update($liveDraw)) {
                            $updated++;
                        }
                    }
                },
            );

        return $updated;
    }

    public function update(LiveDraw $liveDraw): bool
    {
        $targetStatus = $this->determineStatus($liveDraw);

        if ($liveDraw->status === $targetStatus) {
            return false;
        }

        $liveDraw->forceFill([
            'status' => $targetStatus,
        ])->save();

        return true;
    }

    public function determineStatus(LiveDraw $liveDraw): string
    {
        if ($liveDraw->status === LiveDraw::STATUS_CANCELLED) {
            return LiveDraw::STATUS_CANCELLED;
        }

        $drawDays = collect($liveDraw->draw_days ?? [])
            ->map(fn (mixed $day): int => (int) $day)
            ->filter(fn (int $day): bool => $day >= 1 && $day <= 7)
            ->unique()
            ->sort()
            ->values()
            ->all();

        if ($drawDays === [] || blank($liveDraw->draw_time)) {
            return LiveDraw::STATUS_OFFLINE;
        }

        $timezone = trim((string) $liveDraw->timezone);

        if ($timezone === '') {
            throw new InvalidArgumentException(
                'Live Draw timezone cannot be empty.',
            );
        }

        $now = $this->clock
            ->now()
            ->setTimezone($timezone);

        $scheduledLeadMinutes = max(
            0,
            (int) config(
                'live-draw.scheduled_lead_minutes',
                60,
            ),
        );

        $liveDurationMinutes = max(
            1,
            (int) config(
                'live-draw.live_duration_minutes',
                30,
            ),
        );

        $previousDraw = $this->previousDrawAt(
            $now,
            $drawDays,
            (string) $liveDraw->draw_time,
        );

        $nextDraw = $this->nextDrawAt(
            $now,
            $drawDays,
            (string) $liveDraw->draw_time,
        );

        if (
            $now->greaterThanOrEqualTo($previousDraw)
            && $now->lessThan(
                $previousDraw->addMinutes($liveDurationMinutes),
            )
        ) {
            return LiveDraw::STATUS_LIVE;
        }

        if (
            $now->greaterThanOrEqualTo(
                $nextDraw->subMinutes($scheduledLeadMinutes),
            )
            && $now->lessThan($nextDraw)
        ) {
            return LiveDraw::STATUS_SCHEDULED;
        }

        return LiveDraw::STATUS_FINISHED;
    }

    /**
     * @param array<int, int> $drawDays
     */
    private function previousDrawAt(
        CarbonImmutable $now,
        array $drawDays,
        string $drawTime,
    ): CarbonImmutable {
        foreach (range(0, 7) as $daysAgo) {
            $candidateDate = $now->subDays($daysAgo);

            if (! in_array($candidateDate->dayOfWeekIso, $drawDays, true)) {
                continue;
            }

            $candidate = $this->atDrawTime(
                $candidateDate,
                $drawTime,
            );

            if ($candidate->lessThanOrEqualTo($now)) {
                return $candidate;
            }
        }

        throw new InvalidArgumentException(
            'Unable to determine previous Live Draw schedule.',
        );
    }

    /**
     * @param array<int, int> $drawDays
     */
    private function nextDrawAt(
        CarbonImmutable $now,
        array $drawDays,
        string $drawTime,
    ): CarbonImmutable {
        foreach (range(0, 7) as $daysAhead) {
            $candidateDate = $now->addDays($daysAhead);

            if (! in_array($candidateDate->dayOfWeekIso, $drawDays, true)) {
                continue;
            }

            $candidate = $this->atDrawTime(
                $candidateDate,
                $drawTime,
            );

            if ($candidate->greaterThan($now)) {
                return $candidate;
            }
        }

        throw new InvalidArgumentException(
            'Unable to determine next Live Draw schedule.',
        );
    }

    private function atDrawTime(
        CarbonImmutable $date,
        string $drawTime,
    ): CarbonImmutable {
        $parts = array_map(
            'intval',
            explode(':', $drawTime),
        );

        return $date->setTime(
            $parts[0] ?? 0,
            $parts[1] ?? 0,
            $parts[2] ?? 0,
        );
    }
}
