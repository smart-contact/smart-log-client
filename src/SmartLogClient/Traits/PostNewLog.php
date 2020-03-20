<?php

namespace SmartLogClient\SmartLogClient\Traits;

use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use Seagulltools\Http\Client as HttpClient;

trait PostNewLog
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    public static function __callStatic($name, $arguments)
    {
        if (! in_array($name, self::LEVEL)) {
            throw new \Exception('Log Level Invalid');
        }

        $instance = new self($arguments[0]);

        return $instance->post($name);
    }

    /**
     * @param $level
     * @return bool|string
     */
    private function post($level)
    {
        try {
            $this->getAgent();

            $this->httpClient->method = 'POST';

            $this->httpClient->user = $this->data['user'];
            $this->httpClient->level = $level;
            $this->httpClient->ip = $this->data['ip'];
            $this->httpClient->description = $this->data['description'];
            $this->httpClient->log = $this->data['log'];
            $this->httpClient->registered_at = $this->data['registered_at'] ?? Carbon::now()->format('Y-m-d H:i:s');

            $this->httpClient->send();

            if ( $this->httpClient->getStatusCode() === 200 ) {
                return $this->httpClient->getResponse();
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
