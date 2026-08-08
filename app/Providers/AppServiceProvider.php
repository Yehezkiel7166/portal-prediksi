<?php

namespace App\Providers;

use App\Core\Contracts\Clock;
use App\Core\Support\SystemClock;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\SiteConfiguration\Support\SiteConfigurationResolver;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Gate::before(
            static function (
                User $user,
                string $ability,
            ): ?bool {
                return $user->isTemporaryOwner()
                    ? true
                    : null;
            },
        );
        View::composer('frontend.*', function ($view): void {
            $view->with(
                'siteConfiguration',
                app(SiteConfigurationResolver::class)->resolve(app(BrandContext::class)->get()),
            );
        });
    }
}
