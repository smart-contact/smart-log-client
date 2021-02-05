<?php

namespace SmartContact\SmartLogClient\Logging;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Monolog\Handler\AbstractProcessingHandler;
use SmartContact\SmartLogClient\SmartLogClient;

class SmartLogHandler extends AbstractProcessingHandler
{
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * SmartLogHandler constructor.
     * @param string $level
     * @param bool $bubble
     */
    public function __construct($level = 'DEBUG', $bubble = true)
    {
        $this->dontFlash = array_merge($this->dontFlash, config('smart-log-client.dontFlash'));

        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     */
    protected function write(array $record): void
    {
        $data = [
            'user' => auth()->user() ? auth()->user()->email : null,
            'status_code' => Arr::has($record['context'], 'status_code') ? $record['context']['status_code'] : null,
            'level' => Str::lower($record['level_name']),
            'level_code' => $record['level'],
            'referrer' => request()->server->get('HTTP_REFERER'),
            'ip' => SmartLogClient::getClientIpAddress(),
            'description' => $record['message'],
            'log' => $record['context']['context'] ?? null,
            'incident_code' => $record['context']['incident_code'] ?? null,
            'extra' => request()->except($this->dontFlash),
            'formatted' => $record['formatted'],
        ];

       SmartLogClient::record($data);
    }
}
