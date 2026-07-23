<?php

namespace App\Providers;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Brand\Support\DatabaseBrandResolver;
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
            fn (): BrandContext => new BrandContext(),
        );
    }

    /**
     * Bootstrap Brand domain services.
     */
    public function boot(): void
    {
        //
    }
}
