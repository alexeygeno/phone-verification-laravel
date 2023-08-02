<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use phpmock\phpunit\PHPMock;

class CommandsTest extends FeatureTestCase
{
    use PHPMock;

    /**
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     *
     * @see https://github.com/php-mock/php-mock-phpunit#restrictions
     * @see https://github.com/orchestral/testbench/issues/371#issuecomment-1649239817
     */
    public function test_process_ok()
    {
        $otp = 1234;
        $to = '+15417543010';

        $rand = $this->getFunctionMock('AlexGeno\PhoneVerification', 'rand');
        $rand->expects($this->once())->willReturn($otp);

        $this->artisan('phone-verification:initiate', ['--to' => $to])->assertOk();

        $this->artisan('phone-verification:complete', ['--to' => $to, '--otp' => $otp])->assertOk();
    }
}
