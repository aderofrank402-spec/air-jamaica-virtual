@component('mail::message')
# Congratulations {{ $user->name }}! Your Application Has Been Approved

Welcome to the **Air Jamaica Virtual Airlines** family! We're excited to inform you that your pilot application has been approved.

## Your Account is Now Active
You can now access all features of our virtual airline platform, including:

- ✈️ **Flight Booking**: Browse and book available flights
- 📊 **Pilot Dashboard**: Track your statistics and progress
- 🏆 **Awards System**: Earn achievements as you fly
- 👥 **Community Features**: Connect with fellow virtual pilots
- 📱 **ACARS Integration**: Use supported flight tracking software

## Getting Started
1. Log in to your pilot dashboard using the button below
2. Complete your pilot profile if needed
3. Browse available flights and book your first route
4. Download and configure ACARS software (optional)
5. Start flying and building your virtual aviation career!

@component('mail::button', ['url' => url('/login')])
Access Your Pilot Dashboard
@endcomponent

## Need Help?
- Check our documentation and tutorials in the pilot resources section
- Join our community forums to connect with other pilots
- Contact support if you have any technical issues

We're thrilled to have you aboard and look forward to seeing you in the virtual skies!

Blue skies and tailwinds,<br>
**Air Jamaica Virtual Airlines**<br>
Management Team

---
*Welcome to the crew! This is an automated message confirming your account approval.*
@endcomponent