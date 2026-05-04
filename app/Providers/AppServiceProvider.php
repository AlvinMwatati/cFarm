<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PricingSourceInterface;
use App\Services\Pricing\KamisPricingSource;
use App\Services\Pricing\ListingsPricingSource;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PricingSourceInterface::class, function ($app) {
            // Cache the result for 24 hours so we don't query the DB on every page load
            $hasKamisData = Cache::remember('has_kamis_pricing_data', now()->addDay(), function () {
                return \App\Models\MarketPrice::where('source', 'kamis')->exists();
            });


            if ($hasKamisData) {
                return $app->make(KamisPricingSource::class);
            }

            return $app->make(ListingsPricingSource::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\RateLimiter::for('web', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
