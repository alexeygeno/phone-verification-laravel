<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use AlexGeno\PhoneVerificationLaravel\Facades\PhoneVerification;
use phpmock\phpunit\PHPMock;

class FacadeTest extends FeatureTestCase
{
    use PHPMock;

    /**
     * Test verification process using the facade
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

        $this->assertNotEmpty(PhoneVerification::initiate($to));
        $this->assertNotEmpty(PhoneVerification::complete($to, $otp));
    }
}
