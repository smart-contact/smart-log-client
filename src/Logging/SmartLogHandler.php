<?php

namespace SmartContact\SmartLogClient\Logging;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Monolog\Handler\AbstractProcessingHandler;
use SmartContact\SmartLogClient\SmartLogClient;

class SmartLogHandler extends AbstractProcessingHandler
{
    public function __construct($level = 'DEBUG', $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $data = [
            'user' => auth()->user() ? auth()->user()->id : null,
            'status_code' => Arr::has($record['context'], 'status_code') ? $record['context']['status_code'] : null,
            'level' => Str::lower($record['level_name']),
            'level_code' => $record['level'],
            'ip' => '127.0.0.1',
            'description' => $record['message'],
            'log' => $record['context']['context'] ?? null,
            'incident_code' => $record['context']['incident_code'] ?? null,
            'extra' => request()->input(),
            'formatted' => $record['formatted'],
        ];

       SmartLogClient::record($data);
    }
}
