<?php

namespace AlexGeno\PhoneVerificationLaravel\Notifications;
use Illuminate\Bus\Queueable;
use NotificationChannels\Twilio\TwilioChannel;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioSmsMessage;
use NotificationChannels\Log\LogChannel;
use NotificationChannels\Log\LogMessage;

abstract class Otp extends Notification
{
    use Queueable;

    protected string $content;

    protected abstract function channel():string;

    protected function content(string $content):self{
        $this->content = $content;
        return $this;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(object $notifiable)
    {
        return app()->environment('production')
        //return true
                        ? [$this->channel()]
            : [LogChannel::class];
    }


    /**
     * Get the log message representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return LogMessage
     */
    public function toLog(object $notifiable):LogMessage
    {
        $route = strtolower((new \ReflectionClass($this))->getShortName());
        return new LogMessage("Pretended sms send to {$route}:. ".$notifiable->routeNotificationFor($route)." with message: {$this->content} ");
    }
}
