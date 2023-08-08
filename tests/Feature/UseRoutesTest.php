<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use phpmock\phpunit\PHPMock;

class UseRoutesTest extends FeatureTestCase
{
    use PHPMock;

    protected const LANG_MESSAGES = 'phone-verification::messages.';

    /**
     * Test the route phone-verification/initiate
     *
     * @return void
     */
    public function test_initiation_ok()
    {
        $response = $this->postJson('/phone-verification/initiate', ['to' => '+15417543010']);

        $response->assertStatus(200);

        $response->assertJson(['ok' => true, 'message' => trans(self::LANG_MESSAGES.'initiation_success')]);
    }

    /**
     * Test the initiation rate limit
     *
     * @return void
     */
    public function test_initiation_rate_limit_exceeded()
    {
        config(['phone-verification.manager.rate_limits.initiate' => ['count' => 0, 'period_secs' => 3600]]);
        $response = $this->postJson('/phone-verification/initiate', ['to' => '+15417543010']);

        $response->assertStatus(406);

        $response->assertJson(['ok' => false, 'message' => trans(self::LANG_MESSAGES.'initiation_rate_limit', ['sms' => 0, 'hours' => 1])]);
    }

    /**
     * Test verification process using the routes
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     *
     * @see https://github.com/php-mock/php-mock-phpunit#restrictions
     * @see https://github.com/orchestral/testbench/issues/371#issuecomment-1649239817
     *
     * @return void
     */
    public function test_process_ok()
    {
        $otp = 1234;
        $to = '+15417543010';

        $rand = $this->getFunctionMock('AlexGeno\PhoneVerification', 'rand');
        $rand->expects($this->once())->willReturn($otp);

        $response = $this->postJson('/phone-verification/initiate', ['to' => $to]);
        $response->assertStatus(200);
        $response->assertJson(['ok' => true, 'message' => trans(self::LANG_MESSAGES.'initiation_success')]);

        $response = $this->postJson('/phone-verification/complete', ['to' => $to, 'otp' => $otp]);
        $response->assertStatus(200);
        $response->assertJson(['ok' => true, 'message' => trans(self::LANG_MESSAGES.'completion_success')]);
    }

    /**
     * Test the completion rate limit
     *
     * @return void
     */
    public function test_process_rate_limit_exceeded()
    {
        config(['phone-verification.manager.rate_limits.complete' => ['count' => 0, 'period_secs' => 60]]);
        $response = $this->postJson('/phone-verification/complete', ['to' => '+15417543010', 'otp' => 0]);

        $response->assertStatus(406);

        $response->assertJson(['ok' => false, 'message' => trans(self::LANG_MESSAGES.'completion_rate_limit', ['times' => 0, 'minutes' => 1])]);
    }

    /**
     * Test verification process using the routes when OTP is incorrect
     *
     * @return void
     */
    public function test_completion_otp_incorrect()
    {
        $response = $this->postJson('/phone-verification/initiate', ['to' => '+15417543010']);

        $response->assertStatus(200);

        $response->assertJson(['ok' => true, 'message' => trans(self::LANG_MESSAGES.'initiation_success')]);

        $response = $this->postJson('/phone-verification/complete', ['to' => '+15417543010', 'otp' => 0]);

        $response->assertStatus(406);

        $response->assertJson(['ok' => false, 'message' => trans(self::LANG_MESSAGES.'incorrect')]);
    }

    /**
     * Test verification process using the routes when OTP is expired
     *
     * @return void
     */
    public function test_completion_otp_expired()
    {
        // No initiation has the same behaviour as the initiation expiration - the key ain a storage just doesn't exist
        $expirationPeriodSecs = config('phone-verification.manager.rate_limits.complete.period_secs');

        $response = $this->postJson('/phone-verification/complete', ['to' => '+15417543010', 'otp' => 0]);

        $response->assertStatus(406);

        $response->assertJson(['ok' => false, 'message' => trans(self::LANG_MESSAGES.'expired', ['minutes' => $expirationPeriodSecs / 60])]);
    }
}
