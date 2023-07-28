<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Unit;

use Illuminate\Support\Facades\Notification;


class SendersTest extends UnitTestCase
{

    public function meta():array
    {
        return [
            'messagebird' => [\AlexGeno\PhoneVerificationLaravel\Notifications\Messagebird::class,
                              \AlexGeno\PhoneVerificationLaravel\Senders\Messagebird::class,
                              \NotificationChannels\Messagebird\MessagebirdMessage::class,
                              'setBody'],
            'vonage' => [\AlexGeno\PhoneVerificationLaravel\Notifications\Vonage::class,
                         \AlexGeno\PhoneVerificationLaravel\Senders\Vonage::class,
                         \Illuminate\Notifications\Messages\VonageMessage::class,
                        'content'],
            'twilio' => [\AlexGeno\PhoneVerificationLaravel\Notifications\Twilio::class,
                         \AlexGeno\PhoneVerificationLaravel\Senders\Twilio::class,
                        \NotificationChannels\Twilio\TwilioSmsMessage::class,
                        'content']
        ];
    }

    /**
     * @param $notification
     * @param $sender
     * @dataProvider meta
     */
    public function test_message_invoked($notification, $sender, $message, $messageContentMethod)
    {

        $text = 'Test text';
        $to = '+380935258272';

        $messageMock = $this->mock($message);

        $messageMock->shouldReceive($messageContentMethod)
            ->once()
            ->with($text);

       $notificationObj = new $notification($messageMock);

        $sender = new $sender($notificationObj);

        Notification::shouldReceive('send')->once()->withArgs(fn($notifiables, $notification) => $notificationObj === $notification);

        $sender->invoke($to, $text);

    }

    /**
     * @param $notification
     * @param $sender
     * @dataProvider meta
     */
    public function test_notification_invoked($notification, $sender, $message)
    {

        $text = 'Test text';
        $to = '+380935258272';

        $notificationMock = \Mockery::mock($notification, [$this->mock($message)]);

        $notificationMock
            ->shouldReceive('content')
                ->once()
                ->with($text)
                ->andReturnSelf()
            ->shouldReceive('via')
        ;

        $sender = new $sender($notificationMock);

        Notification::shouldReceive('send')->once()->withArgs(fn($notifiables, $notification) => $notificationMock === $notification);

        $sender->invoke($to, $text);

    }
}
