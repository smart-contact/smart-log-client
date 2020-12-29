# SmartLogClient

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require smart-contact/smart-log-client
```

## Usage
```php
SmartLogClient::report([
    'level' => 'info',
    'user' => 'John Doe', //required
    'description' => 'New Log Line', //required
    'ip' => 'IP ADDRESS', //required
    'registered_at' => 'Y-m-d H:i:s', //default now()
    'log' => [
        'key' => 'value',
    ],
    'user_agent' => 'USER AGENT', //default Jenssegers\Agent::getUserAgent, 
    'browser' => 'BROWSER', //default Jenssegers\Agent::browser
    'browser_version' => 'BROWSER VERSION', //default Jenssegers\Agent::version
    'platform' => 'PLATFORM', //default Jenssegers\Agent::plaform
    'platform_version' => 'PLATFORM VERSION', //default null
]);
```

```php
SmartLogClient::info([
    'user' => 'John Doe', //required
    'description' => 'New Log Line', //required
    'ip' => 'IP ADDRESS', //required
    'registered_at' => 'Y-m-d H:i:s', //default now()
    'log' => [
        'key' => 'value',
    ],
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
[ico-travis]: https://img.shields.io/travis/smart-contact/smart-log-client/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/smart-contact/smart-log-client
[link-downloads]: https://packagist.org/packages/smart-contact/smart-log-client
[link-travis]: https://travis-ci.org/smart-contact/smart-log-client
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/smart-contact
[link-contributors]: ../../contributors
