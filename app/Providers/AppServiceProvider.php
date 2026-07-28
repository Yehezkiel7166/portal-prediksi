<?php

namespace App\Providers;

use App\Core\Contracts\Clock;
use App\Core\Support\SystemClock;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\SiteConfiguration\Support\SiteConfigurationResolver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Clock::class, SystemClock::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('frontend.*', function ($view): void {
            $view->with(
                'siteConfiguration',
                app(SiteConfigurationResolver::class)->resolve(app(BrandContext::class)->get()),
            );
        });
    }
}
