<?php

namespace SmartContact\SmartLogClient;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Jenssegers\Agent\Agent;

class SmartLogClient
{

    private $url;
    /**
     * @var string
     */
    private $channel;

    /**
     * @var string
     */
    private $apiToken;

    /**
     * @var array
     */
    private $data;

    const LEVEL = [
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug',
    ];

    /**
     * Client constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->channel = config('smart-log-client.channel');

        $this->apiToken = config('smart-log-client.apiToken');

        $this->url = config('smart-log-client.url') . "/api/v1/{$this->channel}/logs";

        $this->data = $data;
    }

    /**
     * @param $name
     * @param $arguments
     * @return array|bool|string
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        if($name === 'record') {
            $name = Arr::pull($arguments[0], 'level');
        }

        if (! in_array($name, self::LEVEL)) {
            throw new \Exception('Log Level Invalid');
        }

        $instance = new self($arguments[0]);

        return $instance->post($name);
    }

    /**
     * @param $level
     * @return array|mixed|string
     */
    private function post($level)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'token' => $this->apiToken
            ])
                ->post($this->url, $this->prepareBody($level))
                ->throw();

            if ($response->successful()) {
                return $response->json();
            } else {
                return [
                    'registered' => false,
                    'code' => $this->httpClient->getStatusCode(),
                    'message' => $this->httpClient->getResponse()
                ];
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $level
     * @return array
     */
    private function prepareBody($level): array
    {
        $device = new Agent();
        $browser = $device->browser();
        $platform = $device->platform();

        return [
            'user' => $this->data['user'],
            'status_code' => $this->data['status_code'] ?? null,
            'level' => $level,
            'level_code' => $level,
            'ip' => $this->data['ip'],
            'description' => $this->data['description'] ,
            'log' => $this->data['log'] ?? null,
            'extra' => $this->data['extra'] ?? null,
            'formatted' => $this->data['formatted'] ?? null,
            'registered_at' => $this->data['registered_at'] ?? Carbon::now()->format('Y-m-d H:i:s'),
            'user_agent' => $this->data['user_agent'] ?? $device->getUserAgent(),
            'browser' => $this->data['browser'] ?? (($browser) ? $browser : null),
            'browser_version' => $this->data['browser_version'] ?? ($device->version($browser) ? $device->version($browser) : null),
            'platform' => $this->data['platform'] ?? (($platform) ? $platform : null),
            'platform_version' => $this->data['platform_version'] ?? null,
        ];
    }

}
