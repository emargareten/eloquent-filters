<?php

namespace Emargareten\EloquentFilters;

use Illuminate\Support\ServiceProvider;

class EloquentFiltersServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/eloquent-filters.php', 'eloquent-filters');
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/eloquent-filters.php' => config_path('eloquent-filters.php'),
        ]);
    }
}
