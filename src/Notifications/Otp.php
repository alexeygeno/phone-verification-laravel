<?php

namespace AlexGeno\PhoneVerificationLaravel\Notifications;

use Illuminate\Notifications\Notification;

class Otp extends Notification
{
    protected string $content;

    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(object $notifiable)
    {
        return array_keys($notifiable->routes);
    }

    /**
     * Returns $this->content for the methods: toMessagebird, toTwilio, toVonage, ...
     * If you need custom behaviour just add the "to{Driver}" method to this class
     */
    public function __call(string $name, array $args)
    {
        if (str_starts_with($name, 'to') and count($args) == 1 and is_object(current($args))) {
            return $this->content;
        }
    }
}
