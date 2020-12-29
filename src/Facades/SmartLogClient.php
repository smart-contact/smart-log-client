<?php

namespace SmartContact\SmartLogClient\Facades;

use Illuminate\Support\Facades\Facade;

class SmartLogClient extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'smart-log-client';
    }
}
