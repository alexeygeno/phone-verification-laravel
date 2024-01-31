<?php

namespace AlexGeno\PhoneVerificationLaravel;

use AlexGeno\PhoneVerification\Manager;
use AlexGeno\PhoneVerification\Manager\Completer;
use AlexGeno\PhoneVerification\Manager\Initiator;
use AlexGeno\PhoneVerification\Sender\I as ISender;
use AlexGeno\PhoneVerification\Storage\I as IStorage;
use AlexGeno\PhoneVerification\Storage\MongoDb;
use AlexGeno\PhoneVerification\Storage\Redis;
use AlexGeno\PhoneVerificationLaravel\Commands\Complete;
use AlexGeno\PhoneVerificationLaravel\Commands\Initiate;
use AlexGeno\PhoneVerificationLaravel\Notifications\Otp;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class PhoneVerificationServiceProvider extends ServiceProvider
{
    /**
     * Register the package's services.
     */
    public function register()
    {
        $this->registerStorage();
        $this->registerSender();
        $this->registerOtp();
        $this->registerManager();
        $this->registerPhoneVerification();
        $this->registerConfig();
    }

    /**
     * Register a singleton for PhoneVerification.
     *
     * @return void
     */
    protected function registerPhoneVerification()
    {
        $this->app->singleton(PhoneVerification::class, function (Application $container) {
            return new PhoneVerification();
        });
    }

    /**
     * Return the Redis storage instance.
     *
     * @param  array<mixed>  $config ['settings' => [...], 'connection' => string]
     */
    protected function redisStorage(array $config): Redis
    {
        $connection = $this->app->make('redis')->connection($config['connection']);

        return new Redis($connection->client(), $config['settings']);
    }

    /**
     * Return the Mongodb storage instance.
     *
     * @param  array<mixed>  $config ['settings' => [...], 'connection' => string]
     */
    protected function mongodbStorage(array $config): MongoDb
    {
        /**
         * @var \Jenssegers\Mongodb\Connection
         */
        $connection = $this->app->make('db')->connection($config['connection']);
        $config['settings']['db'] = $connection->getDatabaseName();

        return new MongoDb($connection->getMongoClient(), $config);
    }

    /**
     * Register the Storage service.
     *
     * @see \AlexGeno\PhoneVerification\Storage\I
     *
     * @return void
     */
    protected function registerStorage()
    {
        $this->app->bind(IStorage::class, function (Application $container) {
            $config = config('phone-verification.storage');
            $driver = $config['driver'];
            $method = $driver.'Storage';
            if (! method_exists($this, $method)) {
                throw new Exception("Not supported storage driver: $driver");
            }

            return $this->$method($config[$driver]);
        });
    }

    /**
     * Register the Sender service.
     *
     * @see \AlexGeno\PhoneVerificationLaravel\Sender
     * @see \AlexGeno\PhoneVerification\Sender\I
     *
     * @return void
     */
    protected function registerSender()
    {
        $this->app->when(Sender::class)->needs('$driver')->giveConfig('phone-verification.sender.driver');
        $this->app->when(Sender::class)->needs('$toLog')->giveConfig('phone-verification.sender.to_log');
        $this->app->bind(ISender::class, function (Application $container) {
            return $container->make(Sender::class);
        });
    }

    /**
     * Register the Otp(Notification) service.
     *
     * @return void
     */
    protected function registerOtp()
    {
        $this->app->when(Otp::class)->needs('$channel')->giveConfig('phone-verification.sender.channel');
    }

    /**
     * Return the configuration for the Manager service.
     *
     * @see \AlexGeno\PhoneVerification\Manager
     *
     * @return array<mixed>
     */
    protected function managerConfig(): array
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

    /**
     * Register the Manager service.
     *
     * @see \AlexGeno\PhoneVerification\Manager
     * @see \AlexGeno\PhoneVerification\Manager\Initiator
     * @see \AlexGeno\PhoneVerification\Manager\Completer
     *
     * @return void
     */
    protected function registerManager()
    {
        // a sender and a storage for initiation process
        $this->app->bind(Initiator::class, function (Application $container) {
            return (new Manager($container->make(IStorage::class), $this->managerConfig()))
                ->sender($container->make(ISender::class));
        });
        // only a storage for completion process
        $this->app->bind(Completer::class, function (Application $container) {
            return new Manager($container->make(IStorage::class), $this->managerConfig());
        });
    }

    /**
     * Register the package's configuration.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/phone-verification.php', 'phone-verification');
    }

    /**
     * Boot the package's services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootRoutes();
        $this->bootLang();
        $this->bootMigrations();
        $this->bootPublishing();
        $this->bootCommands();
    }

    /**
     * Boot the package's publishable resources.
     *
     * @return void
     */
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

    /**
     * Boot the package's commands.
     *
     * @return void
     */
    protected function bootCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Initiate::class,
                Complete::class,
            ]);
        }
    }

    /**
     * Boot the package's routes.
     *
     * @return void
     */
    protected function bootRoutes()
    {
        if (config('phone-verification.routes')) {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        }
    }

    /**
     * Boot the package's translations.
     *
     * @return void
     */
    protected function bootLang()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'phone-verification');
    }

    /**
     * Boot the package's migrations.
     *
     * @return void
     */
    protected function bootMigrations()
    {
        // Only the mongodb driver needs migrations
        if (config('phone-verification.storage.driver') === 'mongodb') {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }
}
