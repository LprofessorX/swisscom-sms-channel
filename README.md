# Swisscom SMS Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/swisscom.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/swisscom)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/swisscom/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/swisscom)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/swisscom.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/swisscom)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/swisscom/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/swisscom/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/swisscom.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/swisscom)

This package makes it easy to send notifications using [Swisscom's SMS API](https://digital.swisscom.com/products/text-messaging/info) with Laravel 5.5+, 6.x and 7.x

**Note:** Replace ```:style_ci_id``` ```:sensio_labs_id``` with their correct values

## Contents

- [Installation](#installation)
	- [Setting up the Swisscom SMS service](#setting-up-the-swisscom-sms-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install the package using composer:

```
composer require laravel-notification-channels/swisscom
```

Add the configuration to your services.php config file:

```php
'swisscom' => [
    'key' => env('SWISSCOM_KEY'),
    'from' => env('SWISSCOM_FROM'),
]
```

### Setting up the Swisscom SMS service

To get the necessary key you'll need to sign-up for an SMS plan using [Swisscom's Digital Marketplace](https://digital.swisscom.com/products/text-messaging/info).
After signing up head over to your subscriptions and extract the "customer key" under "Applications".

Depending on your selected plan you have the option to use custom sender names (`from` config).
The fallback sender value and default for entry plans is your within Swisscom registered mobile phone number. 

## Usage

Within your notification you need to add the Swisscom SMS channel to your `via()` method and configure the message using the `toSwisscom()` method:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Swisscom\SwisscomChannel;
use NotificationChannels\Swisscom\SwisscomMessage;

class SignedUp extends Notification
{
    public function via($notifiable)
    {
        return [SwisscomChannel::class];
    }

    public function toSwisscom($notifiable)
    {
        // Easiest way
        return 'My awesome SMS message';
    
        // Classic way
        return new SwisscomMessage('My awesome SMS message');
        
        // Advanced options
        return (new SwisscomMessage)
            ->text('My awesome SMS message')
            ->from('Acme Ltd.')
            ->to('+41791234567')
            ->callback('https://my-app.com/delivery-callback')
    }
}
```

Note on advanced options:
- If you don't provide a **sender** name/number using `from()` then the config option `services.swisscom.from` will be used
- If you don't provide a **receiver** number using `to()` in your notification please add a `routeNotificationForSwisscom()` method to your notifiable model


### Available Message methods

`text()`: Sets the message content of the SMS

`from()`: Sets the SMS sender name/number (overwrites the configured sender in services.php)

`to()`: Sets the SMS receiver number (overwrites return value of `routeNotificationForSwisscom()` in notifiable model)

`callback()`: Sets the callback URL for delivery notifications according to [Swisscom's API spec](https://digital.swisscom.com/products/text-messaging/documentation)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please notify [the maintainer](https://github.com/wapacro) instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Roman Ackermann](https://github.com/wapacro)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
