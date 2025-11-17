<?php

namespace App\Providers;

use App\Models\Action; // Import Action model
use App\Observers\ActionObserver; // Import ActionObserver
use Illuminate\Support\ServiceProvider;

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
     */
    public function boot(): void
    {
        Action::observe(ActionObserver::class);
    }
}