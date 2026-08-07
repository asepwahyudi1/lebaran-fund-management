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

    public function boot(): void
    {
        if (app()->environment('local') && !app()->runningInConsole() && request()->getHost()) {
            $scheme = request()->getScheme();
            $host = request()->getHttpHost(); // includes port if not default
            config(['app.url' => "{$scheme}://{$host}"]);
            \Illuminate\Support\Facades\URL::forceRootUrl("{$scheme}://{$host}");
        }
    }
}
