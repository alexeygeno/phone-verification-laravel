<?php

namespace AlexGeno\PhoneVerificationLaravel;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;

use AlexGeno\PhoneVerification\Manager;
use AlexGeno\PhoneVerification\Manager\Initiator;
use AlexGeno\PhoneVerification\Manager\Completer;
use AlexGeno\PhoneVerification\Storage\I as IStorage;
use AlexGeno\PhoneVerification\Sender\I as ISender;


class PhoneVerificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerPhoneVerification();
        $this->registerStorage();
        $this->registerSender();
        $this->registerManager();
    }

    protected function registerPhoneVerification(){
        $this->app->singleton(Manager::class, function() {
            return new Manager();
        });
    }

    protected function storages():array
    {
        return [
            'redis' => [\AlexGeno\PhoneVerification\Storage\Redis::class, fn() => Redis::connection()->client()],
            'mongodb' => [\AlexGeno\PhoneVerification\Storage\MongoDb::class, fn() => DB::connection('mongodb')->getMongoClient()]
        ];
    }


    protected function senders():array
    {
        return [
            'vonage' => \AlexGeno\PhoneVerificationLaravel\Senders\Vonage::class,
            'twilio' => \AlexGeno\PhoneVerificationLaravel\Senders\Twilio::class,
            'messagebird' => \AlexGeno\PhoneVerificationLaravel\Senders\Messagebird::class,
        ];
    }

    protected function registerStorage():void{
        $this->app->bind(IStorage::class, function() {
            $storage =  config('phone-verification.storage');
            $storages = $this->storages();
            $className = $storages[$storage][0];
            $client = $storages[$storage][1]();
            return $this->app->make($className, ['client'=>$client]);
        });
    }

    protected function registerSender():void{
        $this->app->bind(ISender::class, function() {
            $sender =  config('phone-verification.sender');
            $senders = $this->senders();
            return $this->app->make($senders[$sender]);
        });
    }

    protected function config():array{
        $config = config('phone-verification.manager');
        // load translated messages
        $langPrefix = 'phone-verification::messages';
        $config['otp']['message'] = fn($otp) =>
            trans("$langPrefix.otp", ['code' => $otp]);
        $config['rate_limits']['initiate']['message'] = fn($phone, $periodSecs, $count) =>
            trans("$langPrefix.initiation_rate_limit", ['sms' => $count, 'hours' => $periodSecs/60/60]);
        $config['rate_limits']['complete']['message'] = fn($phone, $periodSecs, $count) =>
            trans("$langPrefix.completion_rate_limit", ['times' => $count, 'minutes' => $periodSecs/60]);
        $config['otp']['message_expired'] = fn($periodSecs, $otp) =>
            trans("$langPrefix.expired", ['minutes' => $periodSecs/60]);
        $config['otp']['message_incorrect'] = fn($otp) =>
            trans("$langPrefix.incorrect");
        return $config;
    }

    protected function registerManager():void{

        $this->app->bind(Initiator::class, function ($container) {
            return (new Manager($container->make(IStorage::class), $this->config()))
                ->sender($container->make(ISender::class));
        });
        $this->app->bind(Completer::class, function($container)  {
            return (new Manager($container->make(IStorage::class), $this->config()));
        });
    }

    protected function registerConfig():void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/phone-verification.php', 'phone-verification');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->bootRoutes();
        $this->bootLang();
        $this->bootPublishing();
    }

    protected function bootPublishing(){
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/phone-verification.php' => config_path('phone-verification.php')
            ], 'phone-verification-config');
            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/phone-verification')
            ], 'phone-verification-lang');
        }
    }

    protected function bootRoutes(){
        if($this->app->config->get('phone-verification.routes')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        }
    }
    protected function bootLang(){
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang','phone-verification');
    }
}
