<?php

namespace AlexGeno\PhoneVerificationLaravel\Commands;

use AlexGeno\PhoneVerification\Manager\Initiator;
use Illuminate\Console\Command;

class Initiate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phone-verification:initiate {--to= : A recipient phone number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a notification with A one-time password to a recipient';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Initiator $phoneVerification)
    {
        $to = $this->option('to') ?? $this->ask('to:');
        $phoneVerification->initiate((string) $to);

        return self::SUCCESS;
    }
}
