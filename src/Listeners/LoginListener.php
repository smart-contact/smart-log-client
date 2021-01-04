<?php

namespace SmartContact\SmartLogClient\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use SmartContact\SmartLogClient\SmartLogClient;

class LoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        $data = [
            'user' => $event->user->email,
            'description' => 'Logged In.',
        ];

        SmartLogClient::info($data);
    }
}
