<?php

namespace App\Notifications;

use App\Contracts\Listener;
use App\Events\AwardAwarded;
use App\Events\NewsAdded;
use App\Events\NewsUpdated;
use App\Events\PirepAccepted;
use App\Events\PirepFiled;
use App\Events\PirepPrefiled;
use App\Events\PirepRejected;
use App\Events\PirepStatusChange;
use App\Events\UserStateChanged;
use App\Events\UserStatsChanged;
use App\Models\Enums\PirepStatus;
use App\Models\Enums\UserState;
use App\Models\User;
use App\Notifications\Messages\AdminUserRegistered;
use App\Notifications\Messages\AdminUserRegisteredImmediate;
use App\Notifications\Messages\Broadcast\PirepStatusChanged;
use App\Notifications\Messages\Broadcast\UserRankChanged;
use App\Notifications\Messages\UserApproved;
use App\Notifications\Messages\UserApprovedImmediate;
use App\Notifications\Messages\UserPending;
use App\Notifications\Messages\UserPendingImmediate;
use App\Notifications\Messages\UserRegistered;
use App\Notifications\Messages\UserRejected;
use App\Notifications\Messages\UserRejectedImmediate;
use App\Notifications\Messages\VerifyEmailNotification;
use App\Notifications\Notifiables\Broadcast;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Listen for different events and map them to different notifications
 */
class NotificationEventsHandler extends Listener
{
    private static $broadcastNotifyable;

    public static $callbacks = [
        AwardAwarded::class      => 'onAwardAwarded',
        NewsAdded::class         => 'onNewsAdded',
        NewsUpdated::class       => 'onNewsUpdated',
        PirepPrefiled::class     => 'onPirepPrefile',
        PirepStatusChange::class => 'onPirepStatusChange',
        PirepAccepted::class     => 'onPirepAccepted',
        PirepFiled::class        => 'onPirepFile',
        PirepRejected::class     => 'onPirepRejected',
        Registered::class        => 'onUserRegistered',
        UserStateChanged::class  => 'onUserStateChange',
        UserStatsChanged::class  => 'onUserStatsChanged',
        Verified::class          => 'onEmailVerified',
    ];

    public function __construct()
    {
        self::$broadcastNotifyable = app(Broadcast::class);
    }

    /**
     * Send a notification to all of the admins
     */
    protected function notifyAdmins($notification)
    {
        // Always use the support email for admin notifications
        $admin_email = 'support@airjamaicavirtual.com';

        try {
            // Create a temporary user object for the admin email
            $admin_user = new User();
            $admin_user->email = $admin_email;
            $admin_user->name = 'Air Jamaica Virtual Admin';
            
            // Send notification to admin email
            $admin_user->notify($notification);
            
            Log::info('Admin notification sent to: ' . $admin_email);
            
        } catch (Exception $e) {
            Log::emergency('Error emailing admin (' . $admin_email . '). Error=' . $e->getMessage());
            Log::emergency('Email error stack trace', ['trace' => $e->getTraceAsString()]);
        }
    }

    protected function notifyUser(User $user, $notification)
    {
        if ($user->state === UserState::DELETED) {
            return;
        }

        try {
            $user->notify($notification);
            
            Log::info('User notification sent', [
                'user_id' => $user->id ?? 'temp-user',
                'user_email' => $user->email,
                'user_name' => $user->name,
                'notification_class' => get_class($notification),
                'user_state' => $user->state ? UserState::label($user->state) : 'unknown',
            ]);
            
        } catch (Exception $e) {
            Log::emergency('Error emailing user, '.$user->email.', error='.$e->getMessage());
            Log::emergency('User email error stack trace', ['trace' => $e->getTraceAsString()]);
        }
    }

    /**
     * Send a notification to all users. Also can specify if a particular notification
     * requires an opt-in
     */
    protected function notifyAllUsers(\App\Contracts\Notification $notification)
    {
        $where = [];
        if ($notification->requires_opt_in === true) {  // If the opt-in is required
            $where['opt_in'] = true;
        }

        /** @var Collection $users */
        $users = User::where($where)->whereIn('state', [UserState::ACTIVE, UserState::ON_LEAVE])->get();
        if ($users->count() === 0) {
            return;
        }

        Log::info('Sending notification to '.$users->count().' users');

        foreach ($users as $user) {
            $this->notifyUser($user, $notification);
        }
    }

    public function onEmailVerified(Verified $event): void
    {
        /** @var User $user */
        $user = $event->user;
        
        Log::info('NotificationEvents::onEmailVerified: Email verified for user '
            .$user->ident.' ('.$user->email.')');
            
        // Email verification is handled separately from registration
        // No additional notifications needed here as user already received
        // their registration/pending notifications when they first registered
    }

    /**
     * Handle user registration event
     */
    public function onUserRegistered(Registered $event): void
    {
        /** @var User $user */
        $user = $event->user;
        
        Log::info('NotificationEvents::onUserRegistered: '
            .$user->ident.' is '
            .UserState::label($user->state).', sending appropriate email');

        /*
         * Send user notification based on state (controlled by settings)
         */
        if ($user->state === UserState::PENDING) {
            // Check setting before sending pending notification
            if (setting('notifications.mail_user_pending', true)) {
                try {
                    $this->notifyUser($user, new UserPendingImmediate($user));
                    Log::info('Pending user notification sent to: ' . $user->email);
                } catch (Exception $e) {
                    Log::error('Failed to send pending user notification to ' . $user->email . ': ' . $e->getMessage());
                }
            } else {
                Log::info('Pending user notification disabled by setting for: ' . $user->email);
            }
        } elseif ($user->state === UserState::ACTIVE) {
            // Check setting before sending registration notification
            if (setting('notifications.mail_user_registration', true)) {
                try {
                    $this->notifyUser($user, new UserRegistered($user));
                    Log::info('Active user registration notification sent to: ' . $user->email);
                } catch (Exception $e) {
                    Log::error('Failed to send user registration notification to ' . $user->email . ': ' . $e->getMessage());
                }
            } else {
                Log::info('User registration notification disabled by setting for: ' . $user->email);
            }
        }

        /*
         * Send admin notification about new user registration (controlled by setting)
         */
        if (setting('notifications.mail_admin_user_registered', true)) {
            try {
                $this->notifyAdmins(new AdminUserRegisteredImmediate($user));
                Log::info('Admin notification about new user registration sent for user: ' . $user->email);
            } catch (Exception $e) {
                Log::error('Failed to send admin notification for user ' . $user->email . ': ' . $e->getMessage());
            }
        } else {
            Log::info('Admin user registration notification disabled by setting');
        }

        /*
         * Broadcast notifications (optional)
         */
        try {
            Notification::send([$user], new Messages\Broadcast\UserRegistered($user));
        } catch (Exception $e) {
            Log::error('Failed to broadcast user registration for ' . $user->email . ': ' . $e->getMessage());
        }
    }

    /**
     * When a user's state changes, send an email out
     */
    public function onUserStateChange(UserStateChanged $event): void
    {
        Log::info('NotificationEvents::onUserStateChange: User '.$event->user->ident.' state changed from '.
            UserState::label($event->old_state).' to '.UserState::label($event->user->state));

        if ($event->old_state === UserState::PENDING) {
            if ($event->user->state === UserState::ACTIVE) {
                // Send user approved notification (controlled by setting)
                if (setting('notifications.mail_user_approved', true)) {
                    try {
                        $this->notifyUser($event->user, new UserApprovedImmediate($event->user));
                        Log::info('User approved notification sent to: ' . $event->user->email);
                    } catch (Exception $e) {
                        Log::error('Failed to send user approved notification to ' . $event->user->email . ': ' . $e->getMessage());
                    }
                } else {
                    Log::info('User approved notification disabled by setting for: ' . $event->user->email);
                }
            } elseif ($event->user->state === UserState::REJECTED) {
                // Send user rejected notification (controlled by setting)
                if (setting('notifications.mail_user_rejected', true)) {
                    try {
                        $this->notifyUser($event->user, new UserRejectedImmediate($event->user));
                        Log::info('User rejected notification sent to: ' . $event->user->email);
                    } catch (Exception $e) {
                        Log::error('Failed to send user rejected notification to ' . $event->user->email . ': ' . $e->getMessage());
                    }
                } else {
                    Log::info('User rejected notification disabled by setting for: ' . $event->user->email);
                }
            }
        } elseif ($event->old_state === UserState::ACTIVE) {
            Log::info('User state change from active - no email notifications needed');
        }
    }

    /**
     * Prefile notification. Disabled intentionally, No need to send it to Discord
     */
    public function onPirepPrefile(PirepPrefiled $event): void
    {
        Log::info('NotificationEvents::onPirepPrefile: '.$event->pirep->id.' prefiled');

        /*
         * Broadcast notifications
         */
        // Notification::send([$event->pirep], new Messages\Broadcast\PirepPrefiled($event->pirep));
    }

    /**
     * Status Change notification.
     * Reduced the messages (Boarding, Pushback, TakeOff, Landing and non-normals only)
     * If needed array can be tied to a setting at admin side for further customization
     */
    public function onPirepStatusChange(PirepStatusChange $event): void
    {
        Log::info('NotificationEvents::onPirepStatusChange: '.$event->pirep->id.' status changed');

        $message_types = [
            PirepStatus::BOARDING,
            PirepStatus::PUSHBACK_TOW,
            PirepStatus::GRND_RTRN,
            PirepStatus::TAKEOFF,
            PirepStatus::LANDED,
            PirepStatus::DIVERTED,
            PirepStatus::CANCELLED,
            PirepStatus::PAUSED,
            PirepStatus::EMERG_DESCENT,
        ];

        if (setting('notifications.discord_pirep_status', true) && in_array($event->pirep->status, $message_types, true)) {
            Notification::send([$event->pirep], new PirepStatusChanged($event->pirep));
        }
    }

    /**
     * Notify the admins that a new PIREP has been filed
     */
    public function onPirepFile(PirepFiled $event): void
    {
        Log::info('NotificationEvents::onPirepFile: '.$event->pirep->id.' filed');
        if (setting('notifications.mail_pirep_admin', true)) {
            $this->notifyAdmins(new Messages\PirepFiled($event->pirep->withoutRelations()));
        }

        /*
         * Broadcast notifications
         */
        if (setting('notifications.discord_pirep_filed', true)) {
            Notification::send([$event->pirep], new Messages\Broadcast\PirepFiled($event->pirep));
        }
    }

    /**
     * Notify the user that their PIREP has been accepted
     */
    public function onPirepAccepted(PirepAccepted $event): void
    {
        if (setting('notifications.mail_pirep_user_ack', true)) {
            Log::info('NotificationEvents::onPirepAccepted: '.$event->pirep->id.' accepted');
            $this->notifyUser($event->pirep->user, new Messages\PirepAccepted($event->pirep->withoutRelations()));
        }
    }

    /**
     * Notify the user that their PIREP has been rejected
     */
    public function onPirepRejected(PirepRejected $event): void
    {
        if (setting('notifications.mail_pirep_user_rej', true)) {
            Log::info('NotificationEvents::onPirepRejected: '.$event->pirep->id.' rejected');
            $this->notifyUser($event->pirep->user, new Messages\PirepRejected($event->pirep->withoutRelations()));
        }
    }

    /**
     * Notify all users of a news event, but only the users which have opted in
     */
    public function onNewsAdded(NewsAdded $event): void
    {
        Log::info('NotificationEvents::onNewsAdded');
        if (setting('notifications.mail_news', true)) {
            $this->notifyAllUsers(new Messages\NewsAdded($event->news));
        }

        /*
         * Broadcast notifications
         */
        Notification::send([$event->news], new Messages\Broadcast\NewsAdded($event->news));
    }

    /**
     * Notify all users of a news event, but only the users which have opted in
     */
    public function onNewsUpdated(NewsUpdated $event): void
    {
        Log::info('NotificationEvents::onNewsAdded');
        if (setting('notifications.mail_news', true)) {
            $this->notifyAllUsers(new Messages\NewsAdded($event->news));
        }

        /*
         * Broadcast notifications
         */
        Notification::send([$event->news], new Messages\Broadcast\NewsAdded($event->news));
    }

    /**
     * Notify all users that user has awarded a new award
     */
    public function onAwardAwarded(AwardAwarded $event): void
    {
        /*
         * Broadcast notifications
         */
        if (setting('notifications.discord_award_awarded', true)) {
            Notification::send([$event->userAward], new Messages\Broadcast\AwardAwarded($event->userAward));
        }
    }

    /**
     * Notify all users of a user rank change
     */
    public function onUserStatsChanged(UserStatsChanged $event): void
    {
        /*
         * Broadcast notifications
         */
        if (setting('notifications.discord_user_rank_changed', true) && $event->stat_name === 'rank') {
            Notification::send([$event->user], new UserRankChanged($event->user));
        }
    }
}
