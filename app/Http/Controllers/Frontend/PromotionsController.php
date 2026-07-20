<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Promotion\Models\Promotion;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class PromotionsController extends Controller
{
    public function __invoke(): View
    {
        $promotions = Promotion::query()
            ->select([
                'id',
                'title',
                'slug',
                'excerpt',
                'media_source',
                'media_path',
                'media_url',
                'embed_url',
                'focal_point',
                'published_at',
            ])
            ->published()
            ->ordered()
            ->paginate(12);

        return view('frontend.promotions.index', [
            'promotions' => $promotions,
        ]);
    }
}
