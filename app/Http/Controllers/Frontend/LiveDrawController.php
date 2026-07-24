<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Result\Support\LatestResultResolver;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

final class LiveDrawController extends Controller
{
    public function __invoke(
        LatestResultResolver $resolver,
    ): View {
        $liveDraws = LiveDraw::query()
            
            ->select([
                'id',
                'market_id',
                'title',
                'slug',
                'provider',
                'stream_type',
                'source_url',
                'draw_days',
                'draw_time',
                'timezone',
                'status',
                'headline',
                'footer',
                'logo_path',
                'background_path',
                'background_focal_point',
                'priority',
            ])
            ->visible()
            ->whereHas(
                'market',
                fn (Builder $query): Builder => $query->active(),
            )
            ->with([
                'market:id,name,slug,code,timezone,is_active,sort_order',
            ])
            ->ordered()
            ->get()
            ->each(function (LiveDraw $liveDraw) use ($resolver): void {
                $liveDraw->setRelation(
                    'latestResult',
                    $resolver->forMarketId($liveDraw->market_id),
                );
            });

        return view('frontend.live-draw.index', [
            'liveDraws' => $liveDraws,
        ]);
    }
}
