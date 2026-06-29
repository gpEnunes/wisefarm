<?php

namespace App\Providers;

use App\Models\Farm;
use App\Policies\FarmPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Application service provider.
 * Registers policies and other application-level bootstrapping.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Registers the FarmPolicy so that Gate and @can directives
     * resolve authorization checks for the Farm model.
     */
    public function boot(): void
    {
        Gate::policy(Farm::class, FarmPolicy::class);
    }
}
