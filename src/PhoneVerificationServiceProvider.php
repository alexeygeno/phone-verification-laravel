<?php

namespace AlexGeno\PhoneVerificationLaravel;

use AlexGeno\PhoneVerification\Manager;
use AlexGeno\PhoneVerification\Manager\Completer;
use AlexGeno\PhoneVerification\Manager\Initiator;
use AlexGeno\PhoneVerification\Sender\I as ISender;
use AlexGeno\PhoneVerification\Storage\I as IStorage;
use AlexGeno\PhoneVerificationLaravel\Commands\Complete;
use AlexGeno\PhoneVerificationLaravel\Commands\Initiate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;

class PhoneVerificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerStorage();
        $this->registerSender();
        $this->registerManager();
        $this->registerPhoneVerification();
    }

    protected function registerPhoneVerification()
    {
        $this->app->singleton(PhoneVerification::class, function ($container) {
            return new PhoneVerification();
        });
    }

    protected function storages()
    {
        return [ // storage class name, the array of constructor params a for the class
            'redis' => [
                \AlexGeno\PhoneVerification\Storage\Redis::class,
                fn (array $config):array => [ Redis::connection($config['connection'])->client(), $config['settings'] ],
            ],
            'mongodb' => [
                \AlexGeno\PhoneVerification\Storage\MongoDb::class,
                function (array $config):array {
                    $connection = DB::connection($config['connection']);
                    $config['settings']['db'] = $connection->getDatabaseName();

                    return [ $connection->getMongoClient(), $config['settings'] ];
                },
            ],
        ];
    }

    protected function registerStorage()
    {
        $this->app->bind(IStorage::class, function ($container) {
            $config = config('phone-verification.storage');
            $driver = $config['driver'];
            $storage = $this->storages()[$driver];

            $className = current($storage);
            $params = next($storage)($config[$driver]);

            return new $className(...$params);
        });
    }

    protected function registerSender()
    {
        $this->app->when(Sender::class)->needs('$channel')->giveConfig('phone-verification.sender.channel');
        $this->app->when(Sender::class)->needs('$toLog')->giveConfig('phone-verification.sender.to_log');
        $this->app->bind(ISender::class, function ($container) {
            return $container->make(Sender::class);
        });
    }

    protected function config()
    {
        $config = config('phone-verification.manager');
        // load translated messages
        $langPrefix = 'phone-verification::messages';
        $config['otp']['message'] = fn ($otp) => trans("$langPrefix.otp", ['code' => $otp]);
        $config['rate_limits']['initiate']['message'] = fn ($phone, $periodSecs, $count) => trans("$langPrefix.initiation_rate_limit", ['sms' => $count, 'hours' => $periodSecs / 60 / 60]);
        $config['rate_limits']['complete']['message'] = fn ($phone, $periodSecs, $count) => trans("$langPrefix.completion_rate_limit", ['times' => $count, 'minutes' => $periodSecs / 60]);
        $config['otp']['message_expired'] = fn ($periodSecs, $otp) => trans("$langPrefix.expired", ['minutes' => $periodSecs / 60]);
        $config['otp']['message_incorrect'] = fn ($otp) => trans("$langPrefix.incorrect");

        return $config;
    }

    protected function registerManager()
    {
        $this->app->bind(Initiator::class, function ($container) {
            return (new Manager($container->make(IStorage::class), $this->config()))
                    ->sender($container->make(ISender::class));
        });
        $this->app->bind(Completer::class, function ($container) {
            return new Manager($container->make(IStorage::class), $this->config());
        });
    }

    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/phone-verification.php', 'phone-verification');
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->bootRoutes();
        $this->bootLang();
        $this->bootMigrations();
        $this->bootPublishing();
        $this->bootCommands();
    }

    protected function bootPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/phone-verification.php' => config_path('phone-verification.php'),
            ], 'phone-verification-config');
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/phone-verification'),
            ], 'phone-verification-lang');
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'phone-verification-migrations');
        }
    }

    protected function bootCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Initiate::class,
                Complete::class,
            ]);
        }
    }

    protected function bootRoutes()
    {
        if (config('phone-verification.routes')) {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        }
    }

    protected function bootLang()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'phone-verification');
    }

    protected function bootMigrations()
    {
        // Only the mongodb driver needs migrations
        if (config('phone-verification.storage.driver') === 'mongodb') {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }
}
