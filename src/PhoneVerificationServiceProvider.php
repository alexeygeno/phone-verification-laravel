<?php

namespace AlexGeno\PhoneVerificationLaravel;


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
        $this->registerPhoneVerification();
        $this->registerStorage();
        $this->registerSender();
        $this->registerManager();
        $this->registerConfig();
    }

    protected function registerPhoneVerification(){
        $this->app->singleton(PhoneVerification::class, function() {
            return new PhoneVerification();
        });
    }

    protected function registerStorage():void{
        $this->app->bind(\AlexGeno\PhoneVerification\Storage\I::class, function() {
            $storage =  config('phone-verification.storage');
            $storageFactory = $this->app->make(PhoneVerification::class)->storageFactory();
            return (new $storageFactory)->{$storage}();
        });
    }

    protected function registerSender():void{
        $this->app->bind(ISender::class, function() {
            $sender =  config('phone-verification.sender');
            $senderFactory = $this->app->make(PhoneVerification::class)->senderFactory();
            return (new $senderFactory)->{$sender}();
        });
    }

    protected function registerManager():void{

        $this->app->bind(Initiator::class, function($container) {
            return (new Manager($container->make(IStorage::class), $this->app->make(PhoneVerification::class)->managerConfig()))
                ->sender($container->make(ISender::class));
        });
        $this->app->bind(Completer::class, function($container) {
            return (new Manager($container->make(IStorage::class), $this->app->make(PhoneVerification::class)->managerConfig()));
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
        if($this->app->make(PhoneVerification::class)->routes()) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        }
    }
    protected function bootLang(){
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang','phone-verification');
    }
}
