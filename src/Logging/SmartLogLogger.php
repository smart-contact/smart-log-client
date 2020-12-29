<?php

namespace SmartContact\SmartLogClient\Logging;

use Monolog\Logger;

class SmartLogLogger
{
    /**
     * Create a custom Monolog instance.
     *
     *
     * @param  array  $config
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger("SmartLogHandler");

        return $logger->pushHandler(new SmartLogHandler());
    }
}
