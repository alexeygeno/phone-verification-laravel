<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;


use AlexGeno\PhoneVerificationLaravel\PhoneVerification;

class IgnoreRoutesTest extends FeatureTestCase
{

    protected function getEnvironmentSetUp($app):void
    {
        parent::getEnvironmentSetUp($app);
        $package = $app->make(PhoneVerification::class);
        $package->useRoutes(false);
    }

    public function test_initiate_not_available()
    {
        $response = $this->postJson('/phone-verification/initiate', ['to' =>'+380935258272']);
        $response->assertStatus(404);
    }

    public function test_complete_not_available()
    {
        $response = $this->postJson('/phone-verification/complete', ['to' =>'+380935258272', 'otp' => 1234]);
        $response->assertStatus(404);
    }
}
