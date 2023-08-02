<?php

namespace AlexGeno\PhoneVerificationLaravel\Commands;

use AlexGeno\PhoneVerification\Manager\Completer;
use Illuminate\Console\Command;

class Complete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phone-verification:complete {--to= : A recipient ID such as phone number, email, etc} {--otp= : A one-time password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifies a recipient';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Completer $phoneVerification)
    {
        $to = $this->option('to') ?? $this->ask('to:');
        $otp = $this->option('otp') ?? $this->ask('otp:');
        $phoneVerification->complete((string) $to, (int) $otp);

        return self::SUCCESS;
    }
}
