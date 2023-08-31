<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use AlexGeno\PhoneVerification\Sender\I as ISender;
use AlexGeno\PhoneVerificationLaravel\Notifications\Otp;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class SendersTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        config(['phone-verification.sender.to_log' => false]);
    }

    /**
     * Senders data provider.
     *
     * @return array<int, array<int, string>>
     */
    public function senders()
    {
        return [
            ['messagebird', \NotificationChannels\Messagebird\MessagebirdChannel::class],
            ['vonage', \Illuminate\Notifications\Channels\VonageSmsChannel::class],
            ['twilio', \NotificationChannels\Twilio\TwilioChannel::class],
        ];
    }

    /**
     * If the notification facade was called properly for different channels.
     *
     * @see https://laravel.com/docs/9.x/mocking#on-demand-notifications
     *
     * @dataProvider senders
     *
     * @return void
     */
    public function test_notification_invocation_ok(string $driver, string $channel)
    {
        $text = 'Test text';
        $to = '+15417543010';
        config(['phone-verification.sender.channel' => $channel,
            'phone-verification.sender.driver' => $driver]);

        Notification::fake();

        app(ISender::class)->invoke($to, $text);

        Notification::assertSentOnDemand(Otp::class,
            function (Otp $notification, array $channels, AnonymousNotifiable $notifiable) use ($text, $to, $driver, $channel) {
                $toMethod = 'to'.Str::ucfirst($driver);

                return count($channels) == 1 && current($channels) === $channel &&
                    $notification->$toMethod($notifiable) == $text &&
                    $notification->via($notifiable) == [$channel] &&
                    $notifiable->routeNotificationFor($driver) == $to;
            }
        );
    }
}
