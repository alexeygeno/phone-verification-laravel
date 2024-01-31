<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use Illuminate\Http\Response;

class IgnoreRoutesTest extends FeatureTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        config(['phone-verification.routes' => false]);
    }

    /**
     * If the 'phone-verification/initiate' route is not available.
     *
     * @return void
     */
    public function test_initiation_not_available()
    {
        $response = $this->postJson('/phone-verification/initiate', ['to' => '+15417543010']);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * If the 'phone-verification/complete' route is not available.
     *
     * @return void
     */
    public function test_completion_not_available()
    {
        $response = $this->postJson('/phone-verification/complete', ['to' => '+15417543010', 'otp' => 1234]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
