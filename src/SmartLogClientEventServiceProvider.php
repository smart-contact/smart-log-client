<?php

namespace SmartContact\SmartLogClient;


use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SmartContact\SmartLogClient\Listeners\LoginListener;
use SmartContact\SmartLogClient\Listeners\LogoutListener;

class SmartLogClientEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            LoginListener::class
        ],

        Logout::class => [
            LogoutListener::class
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}
