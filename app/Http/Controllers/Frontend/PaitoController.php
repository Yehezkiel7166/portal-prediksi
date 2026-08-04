<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Paito\Models\PaitoCellColor;
use App\Domains\Result\Models\Result;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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

        $results = Result::query()
            ->whereHas(
                'market',
                fn (Builder $query): Builder => $query->active(),
            )
            ->when(
                $validated['market'] ?? null,
                fn (Builder $query, string $slug): Builder => $query->whereHas(
                    'market',
                    fn (Builder $market): Builder => $market->where('slug', $slug),
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
            ->orderByDesc('result_date')
            ->orderByDesc('id')
            ->limit(180)
            ->get();

        $colors = PaitoCellColor::query()
            ->whereIn('result_id', $results->modelKeys())
            ->get(['result_id', 'position', 'color'])
            ->groupBy('result_id');

        $rows = $results->map(function (Result $result) use ($colors): array {
            $digits = preg_replace(
                '/\D/',
                '',
                (string) $result->winning_numbers,
            );

            $digits = substr(
                str_pad($digits, 4, '0', STR_PAD_LEFT),
                -4,
            );

            $values = [
                'as' => $digits[0],
                'kop' => $digits[1],
                'kepala' => $digits[2],
                'ekor' => $digits[3],
                'jumlah' => (string) (
                    array_sum(array_map('intval', str_split($digits))) % 10
                ),
            ];

            $resultColors = $colors
                ->get($result->getKey(), collect())
                ->pluck('color', 'position')
                ->all();

            return [
                'id' => $result->getKey(),
                'date' => $result->result_date->format('Y-m-d'),
                'market' => $result->market->name,
                'market_slug' => $result->market->slug,
                'winning_numbers' => $result->winning_numbers,
                'values' => $values,
                'colors' => $resultColors,
            ];
        });

        return view('frontend.tools.paito', [
            'markets' => $markets,
            'rows' => $rows,
            'filters' => $validated,
        ]);
    }
}
