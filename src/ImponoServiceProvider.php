<?php

namespace Impono;

use Illuminate\Support\ServiceProvider;
use Impono\Services\ImponoManager;
use Impono\Support\MimeRegistry;

class ImponoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/impono.php', 'impono'
        );

        $this->app->singleton(ImponoManager::class, function () {
            return new ImponoManager();
        });

        // mime registry
        $this->app->singleton(MimeRegistry::class, function ($app) {
            $extensions = $app['config']->get('impono.extensions', []);

            return new MimeRegistry($extensions);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/impono.php' => config_path('impono.php'),
        ], 'impono-config');

        $this->registerObservers();
    }

    /**
     * Register model observers
     */
    protected function registerObservers(): void
    {
    }
}
