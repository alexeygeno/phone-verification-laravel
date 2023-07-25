<?php

namespace AlexGeno\PhoneVerificationLaravel;

class PhoneVerification{

    protected bool $routes = true;
    protected string $senderFactory = 'AlexGeno\\PhoneVerificationLaravel\\Factories\\Sender';
    protected string $storageFactory = 'AlexGeno\\PhoneVerificationLaravel\\Factories\\Storage';

    public function managerConfig():array {
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

    public function useRoutes(bool $routes):self{
        $this->routes = $routes;
        return $this;
    }

    public function routes():bool{
        return $this->routes;
    }

    public function useSenderFactory(string $class):self{
        $this->senderFactory = $class;
        return $this;
    }

    public function senderFactory():string{
        return $this->senderFactory;
    }

    public function useStorageFactory(string $class):self{
        $this->storageFactory = $class;
        return $this;
    }

    public function storageFactory():string{
        return $this->storageFactory;
    }
}
