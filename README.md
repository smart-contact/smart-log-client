#Smart Log Client

###Installation
Require this package in the composer.json of your Laravel project. This will download the package.

`composer require smart-contact/smart-log-client`


<br/>

To publish the config, run the vendor publish command:

`php artisan vendor:publish --provider="SmartLogClient\SmartLogClientServiceProvider"`

This will create a new config file named config/smartlog.php.


###Available Log Level
```php
SmartLogClient::emergency($data);
SmartLogClient::critical($data);
SmartLogClient::error($data);
SmartLogClient::warning($data);
SmartLogClient::notice($data);
SmartLogClient::info($data);
SmartLogClient::debug($data);
```

###Usage
```php
SmartLogClient::info([
    'user' => 'John Doe', //required
    'description' => 'New Log Line', //required
    'ip' => 'IP ADDRESS', //required
    'registered_at' => 'Y-m-d H:i:s', //default now()
    'log' => [
        'key' => 'value'
    ],
    'user_agent' => 'USER AGENT', //default Jenssegers\Agent::getUserAgent, 
    'browser' => 'BROWSER', //default Jenssegers\Agent::browser
    'browser_version' => 'BROWSER VERSION', //default Jenssegers\Agent::version
    'platform' => 'PLATFORM', //default Jenssegers\Agent::plaform
    'platform_version' => 'PLATFORM VERSION', //default null
]);
```


