<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Prediction\Models\Prediction;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

final class PredictionDetailController extends Controller
{
    public function __invoke(
        string $marketSlug,
        string $predictionDate,
    ): View {
        $prediction = Prediction::query()
            ->select([
                'id',
                'market_id',
                'prediction_date',
                'predicted_numbers',
                'bbfs',
                'colok_bebas',
                'prediction_2d',
                'prediction_3d',
                'prediction_4d',
                'kembar',
                'shio',
                'notes',
                'published_at',
            ])
            ->published()
            ->forDate($predictionDate)
            ->whereHas(
                'market',
                fn (Builder $query): Builder => $query
                    ->active()
                    ->where('slug', $marketSlug),
            )
            ->with([
                'market:id,name,slug,code,timezone,is_active',
            ])
            ->firstOrFail();

        return view('frontend.predictions.show', [
            'prediction' => $prediction,
        ]);
    }
}
