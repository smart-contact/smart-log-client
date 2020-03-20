<?php

namespace SmartLogClient;

use Seagulltools\Http\Client as HttpClient;
use SmartLogClient\SmartLogClient\Traits\GetLogs;
use SmartLogClient\SmartLogClient\Traits\PostNewLog;

class SmartLogClient
{
    use GetLogs, PostNewLog;

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

    /**
     * @return HttpClient
     */
    private function initHttpClient()
    {
        $httpClient = new HttpClient();
        $httpClient->url = config('smartlog.url') . "/api/v1/{$this->channel}/logs";

        $httpClient->headers([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'token' => $this->apiToken
        ]);

        $httpClient->addCustomHeaders(['token']);

        return $httpClient;
    }
}
