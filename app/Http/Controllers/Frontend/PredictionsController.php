<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Prediction\Models\Prediction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\PredictionIndexRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

final class PredictionsController extends Controller
{
    public function __invoke(PredictionIndexRequest $request): View
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

        $predictions = Prediction::query()
            ->select([
                'id',
                'market_id',
                'prediction_date',
                'predicted_numbers',
                'notes',
                'published_at',
            ])
            ->published()
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
                    $query->forDate($date),
            )
            ->with([
                'market:id,name,slug,code,is_active,sort_order',
            ])
            ->orderByDesc('prediction_date')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('frontend.predictions.index', [
            'filters' => $filters,
            'markets' => $markets,
            'predictions' => $predictions,
        ]);
    }
}
