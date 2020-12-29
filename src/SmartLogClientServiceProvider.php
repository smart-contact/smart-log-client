<?php

namespace SmartContact\SmartLogClient;

use Illuminate\Support\ServiceProvider;

class SmartLogClientServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'smart-contact');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'smart-contact');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/smart-log-client.php', 'smart-log-client');

        // Register the service the package provides.
        $this->app->singleton('smart-log-client', function ($app) {
            return new SmartLogClient;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['smart-log-client'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/smart-log-client.php' => config_path('smart-log-client.php'),
        ], 'smart-log-client.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/smart-contact'),
        ], 'smart-log-client.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/smart-contact'),
        ], 'smart-log-client.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/smart-contact'),
        ], 'smart-log-client.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
