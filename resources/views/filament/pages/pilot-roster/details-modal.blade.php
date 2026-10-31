<div class="space-y-6">
    {{-- Basic Information --}}
    <div>
        <h3 class="text-lg font-semibold mb-3">Basic Information</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Pilot ID</p>
                <p class="font-medium">{{ $record->ident }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium">{{ $record->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Callsign</p>
                <p class="font-medium">{{ $record->callsign ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Country</p>
                <p class="font-medium">{{ $record->country ? strtoupper($record->country) : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Home Airport</p>
                <p class="font-medium">{{ $record->home_airport->icao ?? 'N/A' }} - {{ $record->home_airport->name ?? '' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Current Airport</p>
                <p class="font-medium">{{ $record->current_airport->icao ?? 'N/A' }} - {{ $record->current_airport->name ?? '' }}</p>
            </div>
        </div>
    </div>

    {{-- Flight Statistics --}}
    <div>
        <h3 class="text-lg font-semibold mb-3">Flight Statistics</h3>
        <div class="grid grid-cols-3 gap-4">
            @foreach($stats as $label => $value)
                <div class="bg-gray-50 p-3 rounded">
                    <p class="text-sm text-gray-500">{{ $label }}</p>
                    <p class="text-lg font-semibold">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Performance Metrics --}}
    <div>
        <h3 class="text-lg font-semibold mb-3">Performance Metrics</h3>
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-gray-50 p-3 rounded">
                <p class="text-sm text-gray-500">Average Score</p>
                <p class="text-lg font-semibold">{{ $avgScore }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded">
                <p class="text-sm text-gray-500">Average Landing Rate</p>
                <p class="text-lg font-semibold">{{ $avgLanding }} fpm</p>
            </div>
            <div class="bg-gray-50 p-3 rounded">
                <p class="text-sm text-gray-500">Total Distance</p>
                <p class="text-lg font-semibold">{{ $totalDistance }}</p>
            </div>
        </div>
    </div>

    {{-- Financial Information --}}
    @if($record->journal && $record->journal->balance)
        <div>
            <h3 class="text-lg font-semibold mb-3">Financial Information</h3>
            <div class="bg-gray-50 p-3 rounded">
                <p class="text-sm text-gray-500">Current Balance</p>
                <p class="text-lg font-semibold text-green-600">${{ number_format($record->journal->balance->getValue(), 2) }}</p>
            </div>
        </div>
    @endif

    {{-- Additional Information --}}
    <div>
        <h3 class="text-lg font-semibold mb-3">Additional Information</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">VATSIM ID</p>
                <p class="font-medium">{{ $record->vatsim_id ?: 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">IVAO ID</p>
                <p class="font-medium">{{ $record->ivao_id ?: 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Email Verified</p>
                <p class="font-medium">
                    @if($record->email_verified_at)
                        <span class="text-green-600">Yes</span> ({{ $record->email_verified_at->format('M d, Y') }})
                    @else
                        <span class="text-red-600">No</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Last Login</p>
                <p class="font-medium">{{ $record->lastlogin_at ? $record->lastlogin_at->format('M d, Y H:i') . ' (' . $record->lastlogin_at->diffForHumans() . ')' : 'Never' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Registered</p>
                <p class="font-medium">{{ $record->created_at->format('M d, Y') }} ({{ $record->created_at->diffForHumans() }})</p>
            </div>
            @if($record->notes)
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Notes</p>
                    <p class="font-medium">{{ $record->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

