<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Domains\Blog\Models\BlogPost;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Prediction\Models\Prediction;
use App\Domains\Promotion\Models\Promotion;
use App\Domains\Result\Models\Result;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class HomeController extends Controller
{
    public function __construct(
        private readonly BrandContext $brandContext,
    ) {
    }

    public function __invoke(): View
    {
        $brandId = $this->brandContext->get()?->getKey();

        if ($brandId === null) {
            return $this->renderEmptyHomepage();
        }

        $liveDraws = LiveDraw::query()
            ->where('brand_id', $brandId)
            ->with('market')
            ->visible()
            ->ordered()
            ->limit(4)
            ->get();

        $latestResults = Result::query()
            ->where('brand_id', $brandId)
            ->with('market')
            ->orderByDesc('result_date')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $currentPredictions = Prediction::query()
            ->where('brand_id', $brandId)
            ->with('market')
            ->published()
            ->orderByDesc('prediction_date')
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();

        $activePromotions = Promotion::query()
            ->where('brand_id', $brandId)
            ->published()
            ->ordered()
            ->limit(4)
            ->get();

        $latestArticles = BlogPost::query()
            ->where('brand_id', $brandId)
            ->published()
            ->ordered()
            ->limit(4)
            ->get();

        return $this->renderHomepage(
            liveDraws: $liveDraws,
            latestResults: $latestResults,
            currentPredictions: $currentPredictions,
            activePromotions: $activePromotions,
            latestArticles: $latestArticles,
        );
    }

    private function renderEmptyHomepage(): View
    {
        return $this->renderHomepage(
            liveDraws: collect(),
            latestResults: collect(),
            currentPredictions: collect(),
            activePromotions: collect(),
            latestArticles: collect(),
        );
    }

    /**
     * @param Collection<int, LiveDraw> $liveDraws
     * @param Collection<int, Result> $latestResults
     * @param Collection<int, Prediction> $currentPredictions
     * @param Collection<int, Promotion> $activePromotions
     * @param Collection<int, BlogPost> $latestArticles
     */
    private function renderHomepage(
        Collection $liveDraws,
        Collection $latestResults,
        Collection $currentPredictions,
        Collection $activePromotions,
        Collection $latestArticles,
    ): View {
        return view('frontend.home', [
            'liveDraws' => $liveDraws,
            'latestResults' => $latestResults,
            'currentPredictions' => $currentPredictions,
            'activePromotions' => $activePromotions,
            'latestArticles' => $latestArticles,
        ]);
    }
}
