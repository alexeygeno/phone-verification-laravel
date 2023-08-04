# Phone Verification Laravel  #
Extensible and configurable Laravel library on top of [ alexeygeno/phone-verification-php ](https://github.com/alexeygeno/phone-verification-php)
## Requirements
Laravel 9.x

One of the Laravel notification channels: [ vonage ](https://github.com/laravel/vonage-notification-channel), [ twilio ](https://github.com/laravel-notification-channels/twilio), [ messagebird ](https://github.com/laravel-notification-channels/messagebird)  and [many more ](https://github.com/laravel-notification-channels?q=&type=all&language=php&sort=)

One of the supported storages: [ predis/predis ](https://github.com/predis/predis), [ jenssegers/laravel-mongodb ](https://github.com/jenssegers/laravel-mongodb)
## Installation
```shell
composer require alexgeno/phone-verification-laravel predis/predis laravel/vonage-notification-channel
```
**Note:** redis as a storage and vonage as a notification channel are defaults in config 

## Usage
#### Type-hitting
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
use \AlexGeno\PhoneVerificationLaravel\Facades\PhoneVerification;

PhoneVerification::initiate('+15417543010');
```
```php
use \AlexGeno\PhoneVerificationLaravel\Facades\PhoneVerification;

PhoneVerification::complete('+15417543010', 1234);
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
curl -d "to=+380935259282" http://localhost/phone-verification/initiate
#{"ok":true,"message":"Sms has been sent. Check your Phone!"}
```
```shell
curl -d "to=+380935259282&otp=1234" http://localhost/phone-verification/complete
#{"ok":true,"message":"The verification is done!"}
```
**Note**: The package routes are available by default. To make them unavailable without redefining the service provider just change the bool key **phone-verification.sender.to_log** in config
##Configuration
```php
[
    'storage' => [
        'driver' => env('PHONE_VERIFICATION_STORAGE', 'redis'), // redis || mongodb
        'redis' => [
            'connection' => 'default',
            // keys settings - normally you don't need to change it
            'settings' => [
                'prefix' => 'pv:1',
                'session_key' => 'session',
                'session_counter_key' => 'session_counter',
            ],
        ],
        'mongodb' => [
            'connection' => 'mongodb',
            // collections settings - normally you don't need to change it
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
            'complete' => [ // for every 'to' no more than 5 failed completion over 5 minutes
                'period_secs' => 300, // this is also the expiration period for an otp
                'count' => 5,
            ],
        ],
    ],
];

```


##Publishing
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
**Note**: Only the mongodb storage driver needs migrations

## Using different storages and notification channels
To switch between [ available ](#Requirements) storages and notifications channels you need only to install the respective package and change .env.

For instance if you need **mongodb** as a storage and **twilio** as a notification channel just do this:
```shell
composer require jenssegers/laravel-mongodb laravel-notification-channels/twilio
```
```dotenv
PHONE_VERIFICATION_SENDER=twilio
PHONE_VERIFICATION_STORAGE=mongodb
```
If what's available is not enough you can redefine the service provider and add a storage (implementing *\AlexGeno\PhoneVerification\Storage\I*) or/and a sender (implementing \AlexGeno\PhoneVerification\Sender\I)




