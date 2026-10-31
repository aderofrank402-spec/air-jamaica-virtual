@component('mail::message')
# Welcome to {{ config('app.name') }}!

Hello {{ $user->name }},

Thank you for registering with {{ config('app.name') }}. To complete your registration, please verify your email address by clicking the button below.

@component('mail::button', ['url' => $verificationUrl])
Verify Email Address
@endcomponent

**What's Next?**
Once your email is verified, your account will be pending approval from our administration team. You'll receive another email notification once your account has been approved and is ready to use.

**Having trouble clicking the button?** Copy and paste the following URL into your browser:
{{ $verificationUrl }}

This verification link will expire in 60 minutes.

Thanks,<br>
Management, {{ config('app.name') }}

---
*This is an automated message from {{ config('app.name') }}. If you did not create an account, please ignore this email.*
@endcomponent