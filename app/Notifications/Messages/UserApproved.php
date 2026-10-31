<?php

namespace App\Notifications\Messages;

use App\Contracts\Notification;
use App\Models\User;
use App\Notifications\Channels\MailChannel;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserApproved extends Notification
{
    use MailChannel;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly User $user
    ) {
        parent::__construct();

        $this->setMailable(
            'Your account has been approved',
            'notifications.mail.user.approved',
            ['user' => $user]
        );
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
        ];
    }
}