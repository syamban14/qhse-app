<?php

namespace App\Providers;

use App\Models\Action; // Import Action model
use App\Models\Notification;
use App\Observers\ActionObserver; // Import ActionObserver
use App\Models\CorrectiveActionReport;
use App\Observers\CorrectiveActionReportObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        CorrectiveActionReport::observe(CorrectiveActionReportObserver::class);
        Paginator::useTailwind();

        View::composer('layouts.navigation', function ($view) {
            $unreadCount = 0;
            if (Auth::check()) {
                // Query the Notification model directly to ensure we use the correct ('pgsql') connection.
                // The correct column for the user is 'recipient_id'.
                $unreadCount = Notification::where('recipient_id', Auth::id())
                                           ->whereNull('read_at')
                                           ->count();
            }
            $view->with('unreadNotificationsCount', $unreadCount);
        });
    }
}