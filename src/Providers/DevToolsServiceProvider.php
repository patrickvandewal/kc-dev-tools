<?php

namespace KingsCode\DevTools\Providers;

use Illuminate\Support\ServiceProvider;

class DevToolsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Http/Routes.php');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/kc-dev-tools'),
        ], 'kc-dev-tools');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'kc-dev-tools');
    }
}