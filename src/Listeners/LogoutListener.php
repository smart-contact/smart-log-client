<?php

namespace SmartContact\SmartLogClient\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use SmartContact\SmartLogClient\SmartLogClient;

class LogoutListener
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
     * @param Logout $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $data = [
            'is_application_log' => 1,
            'user' => $event->user->email,
            'description' => 'Logged Out.',
        ];

        SmartLogClient::info($data);
    }
}
