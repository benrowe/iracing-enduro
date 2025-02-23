<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use iRacingPHP\iRacing;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(iRacing::class, function () {
            $cfg = config('app.iracing');
            return new iRacing($cfg['email'], $cfg['password']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
