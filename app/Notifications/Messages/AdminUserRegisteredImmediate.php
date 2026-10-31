<?php

namespace App\Notifications\Messages;

use App\Models\User;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminUserRegisteredImmediate extends BaseNotification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly User $user
    ) {
        //
    }

    /**
     * @return string[]
     */
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
            ->subject('New User Registration - ' . $this->user->name)
            ->markdown('notifications.mail.admin.user.registered', ['user' => $this->user]);
    }

    /**
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'user_id' => $this->user->id,
        ];
    }
}