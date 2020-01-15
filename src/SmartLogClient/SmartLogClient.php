<?php

namespace SmartLogClient;

use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use Seagulltools\Http\Client as HttpClient;

class SmartLogClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;

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
        $this->channel = config('smartlog.channel');

        $this->apiToken = config('smartlog.apiToken');

        $this->httpClient = $this->initHttpClient();

        $this->data = $data;
    }

    public static function __callStatic($name, $arguments)
    {
        if (! in_array($name, self::LEVEL)) {
            throw new \Exception('Log Level Invalid');
        }

        $instance = new self($arguments[0]);

        return $instance->send($name);
    }

    /**
     * @return HttpClient
     */
    private function initHttpClient()
    {
        $httpClient = new HttpClient();
        $httpClient->url = config('smartlog.url') . "/api/v1/{$this->channel}/logs";
        $httpClient->method = 'POST';

        $httpClient->headers([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'token' => $this->apiToken
        ]);
        $httpClient->addCustomHeaders(['token']);

        return $httpClient;
    }

    /**
     * @param $level
     * @return bool|string
     */
    private function send($level)
    {
        try {
            $this->getAgent();

            $this->httpClient->user = $this->data['user'];
            $this->httpClient->level = $level;
            $this->httpClient->ip = $this->data['ip'];
            $this->httpClient->description = $this->data['description'];
            $this->httpClient->log = $this->data['log'];
            $this->httpClient->registered_at = $this->data['registered_at'] ?? Carbon::now()->format('Y-m-d H:i:s');

            $this->httpClient->send();

            if ( $this->httpClient->getStatusCode() === 200 ) {
                return true;
            } else {
                return $this->httpClient->getResponse();
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function getAgent()
    {
        $device = new Agent();
        $browser = $device->browser();
        $platform = $device->platform();

        $this->httpClient->user_agent = $this->data['user_agent'] ?? $device->getUserAgent();
        $this->httpClient->browser = $this->data['browser'] ?? (($browser) ? $browser : null);
        $this->httpClient->browser_version = $this->data['browser_version'] ?? ($device->version($browser) ? $device->version($browser) : null);
        $this->httpClient->platform = $this->data['platform'] ?? (($platform) ? $platform : null);
        $this->httpClient->platform_version = $this->data['platform_version'] ?? null;
    }

}
