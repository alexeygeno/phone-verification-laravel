<?php

namespace AlexGeno\PhoneVerificationLaravel\Notifications;

use Illuminate\Notifications\Notification;

class Otp extends Notification
{
    protected string $content;

    /**
     * Set content for the OTP notification.
     *
     * @return $this
     */
    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<string>
     */
    public function via(object $notifiable)
    {
        return array_keys($notifiable->routes);
    }

    /**
     * Return content for the methods: toMessagebird, toTwilio, toVonage, ...
     * If you need custom behaviour just add the "to{Driver}" method.
     *
     * @param  array<mixed>  $args
     * @return string|mixed
     */
    public function __call(string $name, array $args)
    {
        if (str_starts_with($name, 'to') and count($args) == 1 and is_object(current($args))) {
            return $this->content;
        }
    }
}
