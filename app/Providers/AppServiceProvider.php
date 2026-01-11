<?php

namespace App\Providers;

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
        // Share reports count with sidebar/layout
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            $reportsCount = 0;
            if (auth()->check() && auth()->user()->is_admin) {
                $reportsCount = \App\Models\Report::count();
            }
            $view->with('reportsCount', $reportsCount);

            $noticesCount = 0;
            if (auth()->check()) {
                $noticesCount = auth()->user()->notices()->where('is_read', false)->count();
            }
            $view->with('noticesCount', $noticesCount);
        });
    }
}
