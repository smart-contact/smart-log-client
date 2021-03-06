# SmartLogClient

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![License](https://poser.pugx.org/smart-contact/smart-log-client/license)](//packagist.org/packages/smart-contact/smart-log-client)

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require smart-contact/smart-log-client
```

To publish the config, run the vendor publish command:

``` shell script
php artisan vendor:publish --provider="SmartLogClient\SmartLogClientServiceProvider"
```

## Usage
```php
SmartLogClient::report([
    'level' => 'info', //required
    'level_code' => '', //required
    'status_code' => '',
    'user' => 'John Doe', //required
    'description' => 'New Log Line', //required
    'ip' => 'IP ADDRESS', //required
    'registered_at' => 'Y-m-d H:i:s', //default now()
    'log' => [
        'key' => 'value',
    ],
    'extra' => [
        'key' => 'value',
    ],
    'formatted' => '',
    'user_agent' => 'USER AGENT', //default Jenssegers\Agent::getUserAgent, 
    'browser' => 'BROWSER', //default Jenssegers\Agent::browser
    'browser_version' => 'BROWSER VERSION', //default Jenssegers\Agent::version
    'platform' => 'PLATFORM', //default Jenssegers\Agent::plaform
    'platform_version' => 'PLATFORM VERSION', //default null
]);
```

```php
SmartLogClient::info([
    'status_code' => '',
    'user' => 'John Doe', //required
    'description' => 'New Log Line', //required
    'ip' => 'IP ADDRESS', //required
    'registered_at' => 'Y-m-d H:i:s', //default now()
    'log' => [
        'key' => 'value',
    ],
    'extra' => [
        'key' => 'value',
    ],
    'formatted' => '',
    'user_agent' => 'USER AGENT', //default Jenssegers\Agent::getUserAgent, 
    'browser' => 'BROWSER', //default Jenssegers\Agent::browser
    'browser_version' => 'BROWSER VERSION', //default Jenssegers\Agent::version
    'platform' => 'PLATFORM', //default Jenssegers\Agent::plaform
    'platform_version' => 'PLATFORM VERSION', //default null
]);
```

### Available Log Level
```php
SmartLogClient::emergency($data);
SmartLogClient::critical($data);
SmartLogClient::error($data);
SmartLogClient::warning($data);
SmartLogClient::notice($data);
SmartLogClient::info($data);
SmartLogClient::debug($data);
```

### Laravel Custom Logging Channel
Apply these changes to the file config/logging.php
```php
'stack' => [
    'driver' => 'stack',
    'channels' => ['smartlog','single'],
    'ignore_exceptions' => false,
],

//Custom Channel
'smartlog' => [
    'driver' => 'custom',
    'handler' => \SmartContact\SmartLogClient\Logging\SmartLogHandler::class,
    'via' => \SmartContact\SmartLogClient\Logging\SmartLogLogger::class
],
```

### Use SmartLog Exceptions Handler
Apply these changes to the file app/Exceptions/Handler.php
```php
namespace App\Exceptions;

class Handler extends \SmartContact\SmartLogClient\Exceptions\Handler
{

}
```

### Track Model's event
Include the Trait `SmartContact\SmartLogClient\Traits\TrackingApplicationLogs` in the Model that you want to trace

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SmartContact\SmartLogClient\Traits\TrackingApplicationLogs;

class Order extends Model
{
    use HasFactory, TrackingApplicationLogs;
    
    //Default
    public static $recordEvents = [
        'retrieved', 
        'created', 
        'updated',
        'deleted',
        'restored' // if the model include SoftDeletes trait
    ];
}
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email matteo.meloni@smart-contact.it instead of using the issue tracker.

## Credits

- [Matteo Meloni][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/smart-contact/smart-log-client.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/smart-contact/smart-log-client.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/smart-contact/smart-log-client
[link-downloads]: https://packagist.org/packages/smart-contact/smart-log-client
[link-author]: https://github.com/smart-contact
[link-contributors]: ../../contributors
