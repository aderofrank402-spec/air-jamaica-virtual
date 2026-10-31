@component('mail::message')
# New Pilot Registration - Action Required

A new pilot has registered and is awaiting approval.

## Applicant Details
- **Name**: {{ $user->name }}
- **Email**: {{ $user->email }}
- **Home Airport**: {{ $user->home_airport->name ?? 'Not specified' }} ({{ $user->home_airport->icao ?? 'N/A' }})
- **Airline**: {{ $user->airline->name ?? 'Not specified' }}
- **Country**: {{ $user->country ?? 'Not specified' }}
- **Registration Date**: {{ $user->created_at ? $user->created_at->format('F j, Y \a\t g:i A') : date('F j, Y \a\t g:i A') }}
- **Current Status**: {{ $user->state == \App\Models\Enums\UserState::PENDING ? 'Pending Review' : 'Unknown' }}

## Required Action
Please review this application in the admin dashboard and either approve or reject the registration.

@component('mail::button', ['url' => url('/admin/users/' . $user->id)])
Review Application
@endcomponent

**Air Jamaica Virtual Airlines**<br>
Administrative System

---
*This is an automated notification from the registration system.*
@endcomponent
