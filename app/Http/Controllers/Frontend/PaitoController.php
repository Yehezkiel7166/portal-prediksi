<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Paito\Support\PaitoColorMapper;
use App\Domains\Result\Models\Result;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

final class PaitoController extends Controller
{
    public function __invoke(Request $request, PaitoColorMapper $mapper): View
    {
        $validated = $request->validate([
            'market' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        $markets = Market::query()->active()->ordered()->get(['id', 'name', 'slug', 'code']);
        $version = (string) (Result::query()->max('updated_at') ?? 'empty');
        $cacheKey = 'paito:v1:'.sha1(json_encode([$validated, $version], JSON_THROW_ON_ERROR));

        $rows = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($validated, $mapper) {
            return Result::query()
                ->whereHas('market', fn (Builder $query): Builder => $query->active())
                ->when($validated['market'] ?? null, fn (Builder $query, string $slug): Builder => $query->whereHas('market', fn (Builder $market): Builder => $market->where('slug', $slug)))
                ->when($validated['from'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('result_date', '>=', $date))
                ->when($validated['to'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('result_date', '<=', $date))
                ->with('market:id,name,slug,code')
                ->orderByDesc('result_date')
                ->orderByDesc('id')
                ->limit(180)
                ->get()
                ->map(fn (Result $result): array => [
                    'date' => $result->result_date->format('Y-m-d'),
                    'market' => $result->market->name,
                    'market_slug' => $result->market->slug,
                    'winning_numbers' => $result->winning_numbers,
                    'digits' => $mapper->map($result->winning_numbers),
                ])
                ->all();
        });

        return view('frontend.tools.paito', [
            'markets' => $markets,
            'rows' => $rows,
            'filters' => $validated,
            'legend' => $mapper->legend(),
        ]);
    }
}
