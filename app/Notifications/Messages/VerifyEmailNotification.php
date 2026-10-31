<?php

namespace App\Notifications\Messages;

use App\Contracts\Notification;
use App\Models\User;
use App\Notifications\Channels\MailChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{
    use MailChannel;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly User $user
    ) {
        parent::__construct();

        $verificationUrl = $this->verificationUrl($user);

        $this->setMailable(
            'Welcome to ' . config('app.name') . ' - Verify Your Email',
            'notifications.mail.user.verify-email',
            [
                'user' => $this->user,
                'verificationUrl' => $verificationUrl
            ]
        );
    }

    /**
     * Generate the email verification URL for this user
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->id, 'hash' => sha1($notifiable->email)]
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