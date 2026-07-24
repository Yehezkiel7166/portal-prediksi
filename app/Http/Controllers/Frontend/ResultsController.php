<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ResultIndexRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

final class ResultsController extends Controller
{
    public function __invoke(ResultIndexRequest $request): View
    {
        $filters = $request->filters();
$markets = Market::query()
            
            ->active()
            ->ordered()
            ->get([
                'id',
                'name',
                'slug',
                'code',
            ]);

        $results = Result::query()
            
            ->select([
                'id',
                'market_id',
                'result_date',
                'winning_numbers',
                'notes',
            ])
            ->whereHas(
                'market',
                fn (Builder $query): Builder => $query->active(),
            )
            ->when(
                $filters['market'],
                fn (Builder $query, string $market): Builder =>
                    $query->whereHas(
                        'market',
                        fn (Builder $marketQuery): Builder =>
                            $marketQuery->where('slug', $market),
                    ),
            )
            ->when(
                $filters['date'],
                fn (Builder $query, string $date): Builder =>
                    $query->whereDate('result_date', $date),
            )
            ->with([
                'market:id,name,slug,code,is_active,sort_order',
            ])
            ->orderByDesc('result_date')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('frontend.results.index', [
            'filters' => $filters,
            'markets' => $markets,
            'results' => $results,
        ]);
    }
}
