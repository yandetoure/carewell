<?php declare(strict_types=1); 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        // View Composer pour charger automatiquement la relation service
        view()->composer('layouts.dashboard', function ($view) {
            if (Auth::check()) {
                $user = Auth::user()->load('service');
                $view->with('currentUser', $user);
            }
        });
    }
}
