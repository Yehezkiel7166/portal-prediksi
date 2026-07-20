<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Promotion\Models\Promotion;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class PromotionDetailController extends Controller
{
    public function __invoke(string $slug): View
    {
        $promotion = Promotion::query()
            ->select([
                'id',
                'title',
                'slug',
                'excerpt',
                'content',
                'media_source',
                'media_path',
                'media_url',
                'embed_url',
                'focal_point',
                'published_at',
            ])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('frontend.promotions.show', [
            'promotion' => $promotion,
        ]);
    }
}
