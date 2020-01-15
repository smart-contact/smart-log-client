<?php

namespace SmartLogClient;

use Illuminate\Support\ServiceProvider;

class SmartLogClientServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__.'/config/smartlog.php' => config_path('smartlog.php'),
        ]);
    }

    public function boot()
    {
        $this->app->make('SmartLogClient\Client');
    }
}
