<?php

namespace App\Providers;

use App\Domains\Blog\Models\BlogPost;
use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Observers\BrandOwnershipObserver;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Brand\Support\DatabaseBrandResolver;
use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Market\Models\Market;
use App\Domains\Prediction\Models\Prediction;
use App\Domains\Promotion\Models\Promotion;
use App\Domains\Result\Models\Result;
use Illuminate\Support\ServiceProvider;

class BrandServiceProvider extends ServiceProvider
{
    /**
     * Register Brand domain services.
     */
    public function register(): void
    {
        $this->app->singleton(
            BrandResolver::class,
            DatabaseBrandResolver::class,
        );

        $this->app->scoped(
            BrandContext::class,
            fn (): BrandContext => new BrandContext,
        );
    }

    /**
     * Bootstrap Brand domain services.
     */
    public function boot(): void
    {
        foreach ($this->brandOwnedModels() as $model) {
            $model::observe(BrandOwnershipObserver::class);
        }
    }

    /**
     * Models whose records must always belong to a Brand.
     *
     * @return array<int, class-string>
     */
    private function brandOwnedModels(): array
    {
        return [
            BlogPost::class,
            LiveDraw::class,
            Market::class,
            Prediction::class,
            Promotion::class,
            Result::class,
        ];
    }
}
