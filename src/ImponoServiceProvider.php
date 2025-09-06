<?php

namespace Impono;

use Illuminate\Support\ServiceProvider;
use Impono\Data\MimeData;
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
        $this->registerMimes();
    }

    /**
     * Register model observers
     */
    protected function registerObservers(): void
    {
    }

    protected function registerMimes(): void {
        foreach (config('impono.mimes', []) as $mime) {
            MimeRegistry::register(new MimeData(
                $mime['extension'],
                $mime['type'],
                $mime['mime']
            ));
        }
    }
}
