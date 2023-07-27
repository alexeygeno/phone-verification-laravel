<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

class IgnoreRoutesTest extends FeatureTestCase
{

    protected function getEnvironmentSetUp($app):void
    {
        parent::getEnvironmentSetUp($app);
        $app->config->set('phone-verification.routes', false);
    }

    public function test_initiation_not_available()
    {
        $response = $this->postJson('/phone-verification/initiate', ['to' =>'+380935258272']);
        $response->assertStatus(404);
    }

    public function test_completion_not_available()
    {
        $response = $this->postJson('/phone-verification/complete', ['to' =>'+380935258272', 'otp' => 1234]);
        $response->assertStatus(404);
    }
}
