## Quick start

Initiate
```shell
curl -d "to=+380935259282" http://localhost/phone-verification/initiate
{"ok":true,"message":"Sms has been sent. Check your Phone!"}
```

Complete
```shell
curl -d "to=+380935259282&otp=8756" http://localhost/phone-verification/complete
{"ok":true,"message":"The verification is done!"}
```

##Publishing
```shell
php artisan vendor:publish --tag=phone-verification-config
```
```shell
php artisan vendor:publish --tag=phone-verification-lang
```

##Extending
###To add a new storage:

**Note:** if you don't understand what a storage is then take a look at [ the documentation of the base package ](https://github.com/alexeygeno/phone-verification-php/blob/master/README.md)
1. Add a storage class implementing *AlexGeno\PhoneVerification\Storage\I*
```php
namespace App\Storages;

class DynamoDb implements AlexGeno\PhoneVerification\Storage\I
{ 
    //...
}
```
2. Extend the storage factory
```php
namespace App\Factories;

class Storage extends AlexGeno\PhoneVerificationLaravel\Factories\Storage
{

    public function dynamodb()
    {
        return new \App\Storages\DynamoDb(/*...*/);
    }
}
```
3. Customize the storage factory class using the **customStorageFactory** method. This method should typically be called in the boot method of your AppServiceProvider class

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use AlexGeno\PhoneVerificationLaravel\PhoneVerification;
class AppServiceProvider extends ServiceProvider
{
    //...

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //...
        (new PhoneVerification)->storageFactory(\App\Factories\Storage::class);
        //...
    }
}
```
4. Finally, put the new storage name into *config/phone-verification.php*
```php
return [
//...
    'storage' => 'dynamodb'
//...
];
```
###To add a new sender(using a laravel notification channel):
**Note:** if you don't understand what a sender is then take a look at [ the documentation of the base package ](https://github.com/alexeygeno/phone-verification-php/blob/master/README.md)

1. Install a package
```shell
composer require laravel-notification-channels/vodafone
```
2. Add a notification class

```php
namespace App\Notifications;

use AlexGeno\PhoneVerificationLaravel\Notifications\Otp;
use NotificationChannels\Vodafone\VodafoneChannel;
use NotificationChannels\Vodafone\VodafoneMessage;

class Vodafone extends Otp
{ 
    protected function channel():string
    {
        return VodafoneChannel::class;
    }

    public function toVodafone($notifiable):VodafoneMessage
    {
        return (new VodafoneMessage)->content($this->text);
    }
}
```
3. Add a sender class implementing *AlexGeno\PhoneVerification\Sender\I*
```php

namespace App\Senders;

use AlexGeno\PhoneVerification\Sender\I;

use Illuminate\Support\Facades\Notification;

class Vodafone implements I {

    public function invoke(string $to, string $text)
    {
        Notification::route('vodafone', $to)->notify(new \App\Notifications\Vodafone($text));
    }
}

```
4. Extend the sender factory
```php
namespace App\Factories;

class Sender extends AlexGeno\PhoneVerificationLaravel\Factories\Sender
{

    public function vodafone()
    {
        return new \App\Senders\Vodafone();
    }
}
```
5. Customize the sender factory class using the **customSenderFactory** method. This method should typically be called in the boot method of your AppServiceProvider class

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use AlexGeno\PhoneVerificationLaravel\PhoneVerification;
class AppServiceProvider extends ServiceProvider
{
    //...

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //...
        (new PhoneVerification)->storageFactory(\App\Factories\Sender::class);
        //...
    }
}
```
6. Finally, put the new sender name into *config/phone-verification.php*
```php
return [
//...
    'sender' => 'vodafone'
//...
];
```

##Tests

```shell
vendor/bin/testbench package:test  --filter 'AlexGeno\\PhoneVerificationLaravel\\Tests\\Feature\\UseRoutesTest::test_initiation_ok'
```

```shell
vendor/bin/testbench package:test --coverage
```

