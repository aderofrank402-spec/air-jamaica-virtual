<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Models\User;
use App\Models\Pirep;
use App\Models\Airport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Enums\PirepState;

class LiveMapController extends Controller
{
    public function index(Request $request): View
    {
        // Get active pilots (users who have flown recently or are currently flying)
        $activePilots = User::whereHas('pireps', function($query) {
            $query->where('created_at', '>=', now()->subDays(7))
                  ->orWhere('state', PirepState::IN_PROGRESS);
        })->count();

        // Get live flights (PIREPs currently in progress)
        $liveFlights = Pirep::where('state', PirepState::IN_PROGRESS)->count();
        
        // Get flights today
        $flightsToday = Pirep::whereDate('created_at', today())->count();
        
        // Get total registered pilots (all users)
        $totalRegisteredPilots = User::count();
        
        // Get recently active pilots (last 24 hours)
        $recentlyActivePilots = User::whereHas('pireps', function($query) {
            $query->where('created_at', '>=', now()->subDay());
        })->count();
        
        // Get active airports (airports that have had flights recently)
        $recentAirportIds = Pirep::where('created_at', '>=', now()->subDays(30))
            ->whereNotNull('dpt_airport_id')
            ->pluck('dpt_airport_id')
            ->merge(
                Pirep::where('created_at', '>=', now()->subDays(30))
                    ->whereNotNull('arr_airport_id')
                    ->pluck('arr_airport_id')
            )
            ->unique();
            
        $activeAirports = $recentAirportIds->count();

        // If no active airports from pireps, just get total airports
        if ($activeAirports == 0) {
            $activeAirports = Airport::count();
        }

        return view('livemap.index', [
            'activePilots' => $activePilots,
            'liveFlights' => $liveFlights,
            'activeAirports' => $activeAirports,
            'totalRegisteredPilots' => $totalRegisteredPilots,
            'recentlyActivePilots' => $recentlyActivePilots,
            'flightsToday' => $flightsToday,
        ]);
    }
}
