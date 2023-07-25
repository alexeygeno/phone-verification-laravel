<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;
use phpmock\phpunit\PHPMock;


class PhoneVerificationTest extends FeatureTestCase
{
    use PHPMock;

    protected const LANG_MESSAGES = 'phone-verification::messages.';

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_initiation_ok()
    {
        $response = $this->postJson('/phone-verification/initiate', ['to' =>'+380935258272']);

        $response->assertStatus(200);

        $response->assertJson(["ok"=>true, "message"=>trans(self::LANG_MESSAGES."initiation_success")]);
    }


    public function test_initiation_rate_limit_exceeded()
    {
        config()->set('phone-verification.manager.rate_limits.initiate', ['count' => 0, 'period_secs' => 3600]);
        $response = $this->postJson('/phone-verification/initiate', ['to' =>'+380935258272']);

        $response->assertStatus(406);

        $response->assertJson(["ok"=>false, "message"=> trans(self::LANG_MESSAGES."initiation_rate_limit", ['sms' => 0, 'hours'=> 1])]);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @see https://github.com/php-mock/php-mock-phpunit#restrictions
     * @see https://github.com/orchestral/testbench/issues/371#issuecomment-1649239817
     */
    public function test_completion_ok()
    {
        $rand = $this->getFunctionMock('AlexGeno\PhoneVerification', 'rand');

        $rand->expects($this->once())->willReturn(1234);

        $response = $this->postJson('/phone-verification/initiate', ['to' =>'+380935258272']);

        $response->assertStatus(200);

        $response->assertJson(["ok"=>true, "message"=>trans(self::LANG_MESSAGES."initiation_success")]);

        $response = $this->postJson('/phone-verification/complete', ['to' =>'+380935258272', 'otp' => 1234]);

        $response->assertStatus(200);

        $response->assertJson(["ok"=>true, "message"=> trans(self::LANG_MESSAGES."completion_success")]);
    }

    public function test_completion_rate_limit_exceeded()
    {
        config()->set('phone-verification.manager.rate_limits.complete', ['count' => 0, 'period_secs' => 60]);
        $response = $this->postJson('/phone-verification/complete', ['to' =>'+380935258272', 'otp' => 0]);

        $response->assertStatus(406);

        $response->assertJson(["ok"=>false, "message"=> trans(self::LANG_MESSAGES."completion_rate_limit", ['times' => 0, 'minutes'=> 1])]);
    }

    public function test_completion_otp_incorrect()
    {
        $response = $this->postJson('/phone-verification/initiate', ['to' =>'+380935258272']);

        $response->assertStatus(200);

        $response->assertJson(["ok"=>true, "message"=>trans(self::LANG_MESSAGES."initiation_success")]);

        $response = $this->postJson('/phone-verification/complete', ['to' =>'+380935258272', 'otp' => 0]);

        $response->assertStatus(406);

        $response->assertJson(["ok"=>false, "message"=> trans(self::LANG_MESSAGES."incorrect")]);
    }

    public function test_completion_otp_expired()
    {
        $expirationPeriodSecs = config('phone-verification.manager.rate_limits.complete.period_secs');

        // No initiation has the same behaviour as the initiation expiration - the key ain a storage just doesn't exist

        $response = $this->postJson('/phone-verification/complete', ['to' =>'+380935258272', 'otp' => 0]);

        $response->assertStatus(406);

        $response->assertJson(["ok"=>false, "message"=> trans(self::LANG_MESSAGES."expired", ['minutes' => $expirationPeriodSecs/60])]);
    }
}
