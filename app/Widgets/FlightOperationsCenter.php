<?php

namespace App\Widgets;

use App\Contracts\Widget;
use Illuminate\Support\Facades\Auth;

/**
 * Flight Operations Center Widget - Professional Quick Actions Hub
 */
class FlightOperationsCenter extends Widget
{
    protected $config = [
        'show_downloads' => true,
        'show_planning_tools' => true,
        'show_logbook' => true,
    ];

    public function run()
    {
        $user = Auth::user();

        // Check if user has active flight
        $has_active_flight = \App\Models\Pirep::where('user_id', $user->id)
            ->whereIn('state', [\App\Models\Enums\PirepState::IN_PROGRESS, \App\Models\Enums\PirepState::PAUSED])
            ->exists();

        // Get user's current bids count
        $bids_count = \App\Models\Bid::where('user_id', $user->id)->count();

        // Check SmartCARS module availability
        $smartcars_available = class_exists('\Modules\SmartCARS3phpVMS7Api\Providers\SmartCARS3phpVMS7ApiServiceProvider');

        return view('widgets.flight_operations_center', [
            'user' => $user,
            'has_active_flight' => $has_active_flight,
            'bids_count' => $bids_count,
            'smartcars_available' => $smartcars_available,
            'config' => $this->config,
        ]);
    }
}