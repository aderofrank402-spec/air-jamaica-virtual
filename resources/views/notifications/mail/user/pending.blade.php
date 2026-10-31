@component('mail::message')
# Welcome to Air Jamaica Virtual, {{ $user->name }}!

Thank you for registering with **Air Jamaica Virtual Airlines**. Your application has been received and is currently under review by our administrative team.

## What happens next?

1. **Review Process**: Our administrators will carefully review your application
2. **Email Notification**: You will receive an email notification with our decision within 24-48 hours
3. **Account Access**: If approved, you'll be able to log in and start your virtual aviation journey with us

## Application Details
- **Name**: {{ $user->name }}
- **Email**: {{ $user->email }}
- **Registration Date**: {{ $user->created_at ? $user->created_at->format('F j, Y \a\t g:i A') : date('F j, Y \a\t g:i A') }}
- **Status**: Pending Review

We appreciate your interest in joining our virtual airline community. If you have any questions while waiting for approval, please don't hesitate to contact us.

@component('mail::button', ['url' => url('/login')])
Check Login Status
@endcomponent

Safe skies,<br>
**Air Jamaica Virtual Airlines**<br>
Management Team

---
*This is an automated message. Please do not reply directly to this email.*
@endcomponent
