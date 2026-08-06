<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Paito\Models\PaitoCellColor;
use App\Domains\Result\Models\Result;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class PaitoController extends Controller
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate([
            'market' => [
                'nullable',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            ],
            'from' => [
                'nullable',
                'date_format:Y-m-d',
            ],
            'to' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:from',
            ],
        ]);

        $markets = Market::query()
            ->active()
            ->ordered()
            ->get(['id', 'name', 'slug', 'code']);

        $selectedMarket = $markets->firstWhere(
            'slug',
            $validated['market'] ?? null,
        );

        $results = Result::query()
            ->whereHas(
                'market',
                fn (Builder $query): Builder => $query->active(),
            )
            ->when(
                $selectedMarket !== null,
                fn (Builder $query): Builder => $query->where(
                    'market_id',
                    $selectedMarket->getKey(),
                ),
            )
            ->when(
                $selectedMarket === null,
                fn (Builder $query): Builder => $query->whereRaw(
                    '1 = 0',
                ),
            )
            ->when(
                $validated['from'] ?? null,
                fn (Builder $query, string $date): Builder => $query->whereDate(
                    'result_date',
                    '>=',
                    $date,
                ),
            )
            ->when(
                $validated['to'] ?? null,
                fn (Builder $query, string $date): Builder => $query->whereDate(
                    'result_date',
                    '<=',
                    $date,
                ),
            )
            ->with('market:id,name,slug,code')
            ->orderBy('result_date')
            ->orderBy('id')
            ->limit(365)
            ->get();

        $colors = PaitoCellColor::query()
            ->whereIn('result_id', $results->modelKeys())
            ->get(['result_id', 'position', 'color'])
            ->groupBy('result_id');

        $weeks = $this->buildWeeks(
            $results,
            $colors,
        );

        return view('frontend.tools.paito', [
            'markets' => $markets,
            'selectedMarket' => $selectedMarket,
            'weeks' => $weeks,
            'rows' => $weeks,
            'filters' => $validated,
            'palette' => [
                'red' => '#ef4444',
                'blue' => '#3b82f6',
                'green' => '#22c55e',
                'yellow' => '#facc15',
                'orange' => '#f97316',
                'purple' => '#a855f7',
                'pink' => '#ec4899',
                'cyan' => '#06b6d4',
                'gray' => '#64748b',
            ],
        ]);
    }

    private function buildWeeks(
        Collection $results,
        Collection $colors,
    ): Collection {
        return $results
            ->groupBy(function (Result $result): string {
                return $result->result_date
                    ->copy()
                    ->startOfWeek(Carbon::MONDAY)
                    ->format('Y-m-d');
            })
            ->map(function (
                Collection $weekResults,
                string $weekStart,
            ) use ($colors): array {
                $days = collect(range(1, 7))
                    ->mapWithKeys(
                        fn (int $day): array => [
                            $day => null,
                        ],
                    )
                    ->all();

                foreach ($weekResults as $result) {
                    $date = $result->result_date;
                    $day = (int) $date->isoWeekday();

                    $digits = str_split(
                        str_pad(
                            preg_replace(
                                '/\D/',
                                '',
                                $result->winning_numbers,
                            ),
                            4,
                            '0',
                            STR_PAD_LEFT,
                        ),
                    );

                    $digits = array_slice(
                        $digits,
                        -4,
                    );

                    $sumDigit = (string) (
                        array_sum(
                            array_map('intval', $digits)
                        ) % 10
                    );

                    $storedColors = $colors
                        ->get($result->getKey(), collect())
                        ->pluck('color', 'position')
                        ->all();

                    $days[$day] = [
                        'id' => $result->getKey(),
                        'date' => $date->format('Y-m-d'),
                        'market' => $result->market->name,
                        'winning_numbers' => implode('', $digits),
                        'cells' => [
                            'as' => $digits[0],
                            'kop' => $digits[1],
                            'kepala' => $digits[2],
                            'ekor' => $digits[3],
                            'jumlah' => $sumDigit,
                        ],
                        'colors' => $storedColors,
                    ];
                }

                return [
                    'week_start' => $weekStart,
                    'days' => $days,
                ];
            })
            ->sortBy('week_start')
            ->values();
    }
}
