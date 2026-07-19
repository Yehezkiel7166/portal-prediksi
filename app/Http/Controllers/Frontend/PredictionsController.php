<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Prediction\Models\Prediction;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class PredictionsController extends Controller
{
    public function __invoke(): View
    {
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
                fn ($query) => $query->active(),
            )
            ->with([
                'market:id,name,slug,code,is_active,sort_order',
            ])
            ->orderByDesc('prediction_date')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('frontend.predictions.index', [
            'predictions' => $predictions,
        ]);
    }
}
