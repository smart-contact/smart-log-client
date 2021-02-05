<?php

namespace SmartContact\SmartLogClient;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Monolog\Logger as Monolog;

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
        'success',
    ];

    const LEVEL_CODE = [
        'emergency' => Monolog::EMERGENCY,
        'alert' => Monolog::ALERT,
        'critical' => Monolog::CRITICAL,
        'error' => Monolog::ERROR,
        'warning' => Monolog::WARNING,
        'notice' => Monolog::NOTICE,
        'info' => Monolog::INFO,
        'debug' => Monolog::DEBUG,
        'success' => 1,
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
        if ($name === 'record') {
            $name = Arr::pull($arguments[0], 'level');
        }

        if (!in_array($name, self::LEVEL)) {
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
        } catch (\Exception $e) {
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
            'is_application_log' => $this->data['is_application_log'] ?? false,
            'incident_code' => $this->data['incident_code'] ?? null,
            'user' => $this->data['user'],
            'status_code' => $this->data['status_code'] ?? null,
            'level' => $level,
            'level_code' => $this->data['level_code'] ?? self::LEVEL_CODE[$level],
            'referrer' => $this->data['referer'] ?? request()->server->get('HTTP_REFERER'),
            'ip' => $this->data['ip'] ?? self::getClientIpAddress(),
            'description' => $this->data['description'],
            'log' => $this->data['log'] ?? null,
            'extra' => $this->data['extra'] ?? null,
            'formatted' => $this->data['formatted'] ?? self::getFormatted($level),
            'registered_at' => $this->data['registered_at'] ?? Carbon::now()->format('Y-m-d H:i:s'),
            'user_agent' => $this->data['user_agent'] ?? $device->getUserAgent(),
            'browser' => $this->data['browser'] ?? (($browser) ? $browser : null),
            'browser_version' => $this->data['browser_version'] ?? ($device->version($browser) ? $device->version($browser) : null),
            'platform' => $this->data['platform'] ?? (($platform) ? $platform : null),
            'platform_version' => $this->data['platform_version'] ?? null,
        ];
    }

    public static function getClientIpAddress()
    {
        return request()->server->get('HTTP_X_FORWARDED_FOR') ?
            explode(',', request()->server->get('HTTP_X_FORWARDED_FOR'))[0] :
            request()->server->get('REMOTE_ADDR');
    }

    /**
     * @param $level
     * @return string
     */
    public function getFormatted($level): string
    {
        $dateTime = now()->format('Y-m-d\TH:i:s.uP');
        $channel = App::environment();
        $level = Str::upper($level);
        $description = $this->data['description'];
        $context = isset($this->data['log']) ? json_encode($this->data['log']) : null;
        $extra = isset($this->data['extra']) ? json_encode($this->data['extra']) : null;

        return "[$dateTime] {$channel}.{$level}: {$description} {$context} {$extra}";
    }
}
