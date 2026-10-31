<?php

namespace App\Notifications\Messages;

use App\Models\User;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class UserPendingImmediate extends BaseNotification
{
    public function __construct(
        private readonly User $user
    ) {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->replyTo(config('mail.from.address'), config('mail.from.name'))
            ->subject('Your registration is pending')
            ->markdown('notifications.mail.user.pending', ['user' => $this->user]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'user_id' => $this->user->id,
        ];
    }
}