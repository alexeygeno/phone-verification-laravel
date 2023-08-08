# Phone Verification via [Laravel Notification Channels](https://laravel-notification-channels.com/)

[![Build Status](https://github.com/alexeygeno/phone-verification-laravel/workflows/tests/badge.svg)](https://github.com/alexeygeno/phone-verification-laravel/actions)
[![Build Status](https://github.com/alexeygeno/phone-verification-laravel/workflows/pint/badge.svg)](https://github.com/alexeygeno/phone-verification-php/actions)
[![Coverage Status](https://coveralls.io/repos/github/alexeygeno/phone-verification-laravel/badge.svg)](https://coveralls.io/github/alexeygeno/phone-verification-laravel)


Signing in or signing up on a modern website or mobile app typically follows these steps:
- A user initiates verification by submitting a phone number
- The user receives an SMS or a call with a one-time password [(OTP)](https://en.wikipedia.org/wiki/One-time_password)
- The user completes verification by submitting the [OTP](https://en.wikipedia.org/wiki/One-time_password)

This library is built on top of [ alexeygeno/phone-verification-php ](https://github.com/alexeygeno/phone-verification-php) and allows to set this up

Supported features: 
 - Easy(**.env**) switching between different storages and notification channels
 - Configurable length and expiration time for [OTP](https://en.wikipedia.org/wiki/One-time_password) 
 - Configurable rate limits
 - Localization
 - Usage with different Laravel approaches: [automatic injection](https://laravel.com/docs/9.x/container#automatic-injection), [facade](https://laravel.com/docs/9.x/facades), and [commands](https://laravel.com/docs/9.x/artisan#writing-commands)
 - Logging notifications instead of sending real ones, beneficial for non-production environments
 - Out-of-the-box routes for quick start

## Requirements
[Laravel 9.x](https://laravel.com/docs/9.x)

One of Laravel notification channels: [vonage](https://github.com/laravel/vonage-notification-channel), [twilio](https://github.com/laravel-notification-channels/twilio), [messagebird](https://github.com/laravel-notification-channels/messagebird)  and [many more ](https://github.com/laravel-notification-channels?q=&type=all&language=php&sort=)

One of the supported storages: [predis/predis](https://github.com/predis/predis), [jenssegers/laravel-mongodb](https://github.com/jenssegers/laravel-mongodb)
## Installation
```shell
composer require alexgeno/phone-verification-laravel predis/predis laravel/vonage-notification-channel
```
**Note:** Redis as a storage and Vonage as a notification channel are defaults in the configuration 

## Usage
#### Automatic injection
```php
public function initiate(\AlexGeno\PhoneVerification\Manager\Initiator $manager)
{
    $manager->initiate('+15417543010');
}
```
```php
public function complete(\AlexGeno\PhoneVerification\Manager\Completer $manager)
{
    $manager->complete('+15417543010', 1234);
}
```
#### Facade
```php
\AlexGeno\PhoneVerificationLaravel\Facades\PhoneVerification::initiate('+15417543010');
```
```php
\AlexGeno\PhoneVerificationLaravel\Facades\PhoneVerification::complete('+15417543010', 1234);
```
#### Commands
```shell
php artisan phone-verification:initiate --to=+15417543010
```
```shell
php artisan phone-verification:complete --to=+15417543010 --otp=1234
```
#### Routes
```shell
curl -d "to=+380935259282" localhost/phone-verification/initiate
#{"ok":true,"message":"Sms has been sent. Check your Phone!"}
```
```shell
curl -d "to=+380935259282&otp=1234" localhost/phone-verification/complete
#{"ok":true,"message":"The verification is done!"}
```
**Note**: The package routes are available by default. To make them unavailable without redefining the service provider, change the bool key **phone-verification.sender.to_log** in the configuration

## Different storages and notification channels
To switch between [available](#requirements) storages and notifications channels, install the respective package and update the **.env** file

For example, to use **Mongodb** as a storage and **Twilio** as a notification channel:
```shell
composer require jenssegers/laravel-mongodb laravel-notification-channels/twilio
```
```dotenv
PHONE_VERIFICATION_SENDER=twilio
PHONE_VERIFICATION_STORAGE=mongodb
```
If the available options are not sufficient, you can redefine the service provider and add a custom storage (implementing **\AlexGeno\PhoneVerification\Storage\I**) or/and a sender (implementing **\AlexGeno\PhoneVerification\Sender\I**)
## Configuration
```php
[
    'storage' => [
        'driver' => env('PHONE_VERIFICATION_STORAGE', 'redis'), // redis || mongodb
        'redis' => [
            'connection' => 'default',
            // the key settings - normally you don't need to change them
            'settings' => [
                'prefix' => 'pv:1',
                'session_key' => 'session',
                'session_counter_key' => 'session_counter',
            ],
        ],
        'mongodb' => [
            'connection' => 'mongodb',
            // the collection settings - normally you don't need to change them
            'settings' => [
                'collection_session' => 'session',
                'collection_session_counter' => 'session_counter',
            ],
        ],
    ],
    'sender' => [
        // vonage || twilio || messagebird and many more https://github.com/laravel-notification-channels
        'channel' => env('PHONE_VERIFICATION_SENDER', 'vonage'),
        'to_log' => false, // if enabled: instead of sending a real notification, debug it to the app log
    ],
    'routes' => true, // managing the availability of the package routes without redefining the service provider
    'manager' => [
        'otp' => ['length' => env('PHONE_VERIFICATION_OTP_LENGTH', 4)],
        'rate_limits' => [
            'initiate' => [ // for every 'to' no more than 10 initiations over 24 hours
                'period_secs' => 86400,
                'count' => 10,
            ],
            'complete' => [ // for every 'to' no more than 5 failed completions over 5 minutes
                'period_secs' => 300, // this is also the expiration period for OTP
                'count' => 5,
            ],
        ],
    ],
];
```

## Publishing
#### Config
```shell
php artisan vendor:publish --tag=phone-verification-config
```
#### Localization
```shell
php artisan vendor:publish --tag=phone-verification-lang
```
#### Migrations
```shell
php artisan vendor:publish --tag=phone-verification-migrations
```
**Note**: Only the **MongoDB** storage driver requires migrations



