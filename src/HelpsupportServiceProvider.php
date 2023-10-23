<?php

namespace Moeen\Helpsupport;

use Illuminate\Support\ServiceProvider;

class HelpsupportServiceProvider extends ServiceProvider
{
    public function register()
    {
       /*  $this->app->bind('helpsupport', function ($app) {
            return new Helpsupport();
        }); */

        $this->mergeConfigFrom(__DIR__ . '/config/helpsupport.php', 'helpsupport');
    }

    public function boot()
    {
        // Publish your assets
        $this->publishes([
            __DIR__ . '/public' => public_path('vendor/helpsupport'),
        ], 'public');

        // Load your package's routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Load your package's views
        $this->loadViewsFrom(__DIR__ . '/views', 'helpsupport');
    }
}
