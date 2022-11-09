<?php

namespace KingsCode\DevTools\Providers;

use Illuminate\Support\ServiceProvider;

class DevToolsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Http/Routes.php');

        $this->loadViewsFrom(__DIR__.'/../../resources/', 'kc-dev-tools');

        $this->publishes([
            __DIR__.'/views/' => base_path('resources/views/vendor/kc-dev-tools'),
        ]);
    }
}