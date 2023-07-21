<?php

namespace AlexGeno\PhoneVerificationLaravel;

class PhoneVerification{

    public static bool $registerRoutes = true;
    public static string $senderFactory = 'AlexGeno\\PhoneVerificationLaravel\\Factories\\Sender';
    public static string $storageFactory = 'AlexGeno\\PhoneVerificationLaravel\\Factories\\Storage';

    public static function managerConfig():array {
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

    public static function ignoreRoutes():void{
        static::$registerRoutes = false;
    }

    public static function customSenderFactory(string $senderFactory):void{
        static::$senderFactory = $senderFactory;
    }

    public static function customStorageFactory(string $storageFactory):void{
        static::$storageFactory = $storageFactory;
    }
}
