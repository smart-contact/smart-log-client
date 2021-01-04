<?php

return [
    'url' => env('SMART_LOG_URL', null),

    'channel' => env('SMART_LOG_CHANNEL_UNIQID', null),

    'apiToken' => env('SMART_LOG_API_TOKEN', null),

    'dontFlash' => explode(',', env('SMART_LOG_DONT_FLASH_INPUT', null)),
];
