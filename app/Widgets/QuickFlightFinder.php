<?php

namespace App\Widgets;

use App\Contracts\Widget;
use App\Models\Flight;
use App\Models\Airport;
use App\Models\Aircraft;
use App\Models\Subfleet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Quick Flight Finder Widget - Find available flights quickly
 */
class QuickFlightFinder extends Widget
{
    protected $config = [
        'max_results' => 6,
        'show_distance' => true,
        'show_aircraft' => true,
        'filter_by_pilot_rank' => true,
    ];

    public function run()
    {
        try {
            $user = Auth::user();
            
            $quick_flights = [
                'nearby_flights' => $this->getNearbyFlights($user),
                'popular_routes' => $this->getPopularRoutes(),
                'quick_hops' => $this->getQuickHops(),
                'long_hauls' => $this->getLongHaulFlights($user),
                'aircraft_types' => $this->getAvailableAircraft(),
                'airports' => $this->getPopularAirports(),
            ];

            return view('widgets.quick_flight_finder', [
                'user' => $user,
                'quick_flights' => $quick_flights,
                'config' => $this->config,
            ]);
        } catch (\Exception $e) {
            Log::error('QuickFlightFinder widget error: ' . $e->getMessage());
            
            return view('widgets.quick_flight_finder', [
                'error' => 'Unable to load flight data',
                'user' => Auth::user(),
            ]);
        }
    }

    /**
     * Get flights from user's current location or nearby
     */
    private function getNearbyFlights($user): array
    {
        // Get user's last arrival airport or home base
        $current_airport = $user->curr_airport_id ?? $user->home_airport_id;
        
        if (!$current_airport) {
            return [];
        }

        return Flight::where('dpt_airport_id', $current_airport)
            ->where('active', true)
            ->with(['dpt_airport', 'arr_airport', 'airline', 'aircraft'])
            ->take($this->config['max_results'])
            ->get()
            ->map(function ($flight) {
                return $this->formatFlightData($flight);
            })
            ->toArray();
    }

    /**
     * Get popular/frequently flown routes
     */
    private function getPopularRoutes(): array
    {
        // Get flights with high activity or manually marked as popular
        return Flight::where('active', true)
            ->whereHas('pireps', function($query) {
                $query->where('created_at', '>=', Carbon::now()->subMonth());
            })
            ->with(['dpt_airport', 'arr_airport', 'airline', 'aircraft'])
            ->withCount('pireps')
            ->orderBy('pireps_count', 'desc')
            ->take(4)
            ->get()
            ->map(function ($flight) {
                return $this->formatFlightData($flight);
            })
            ->toArray();
    }

    /**
     * Get quick short-haul flights (under 2 hours)
     */
    private function getQuickHops(): array
    {
        return Flight::where('active', true)
            ->where('flight_time', '<=', 120) // 2 hours in minutes
            ->with(['dpt_airport', 'arr_airport', 'airline', 'aircraft'])
            ->take(4)
            ->get()
            ->map(function ($flight) {
                return $this->formatFlightData($flight);
            })
            ->toArray();
    }

    /**
     * Get long-haul flights (4+ hours) for experienced pilots
     */
    private function getLongHaulFlights($user): array
    {
        $query = Flight::where('active', true)
            ->where('flight_time', '>=', 240) // 4+ hours
            ->with(['dpt_airport', 'arr_airport', 'airline', 'aircraft']);

        // Filter by pilot rank if enabled
        if ($this->config['filter_by_pilot_rank'] && $user->rank) {
            // Assuming higher rank IDs = more experienced
            if ($user->rank->id < 3) {
                // Lower ranks get easier long hauls
                $query->where('flight_time', '<=', 480); // Max 8 hours
            }
        }

        return $query->take(4)
            ->get()
            ->map(function ($flight) {
                return $this->formatFlightData($flight);
            })
            ->toArray();
    }

    /**
     * Get available aircraft types
     */
    private function getAvailableAircraft(): array
    {
        return Aircraft::whereHas('subfleet', function($query) {
                $query->where('type', Aircraft::class);
            })
            ->with('subfleet')
            ->distinct('icao')
            ->take(6)
            ->get()
            ->map(function ($aircraft) {
                return [
                    'icao' => $aircraft->icao,
                    'name' => $aircraft->name,
                    'type' => $aircraft->subfleet->type ?? 'Unknown',
                    'available_count' => Aircraft::where('icao', $aircraft->icao)->count(),
                ];
            })
            ->toArray();
    }

    /**
     * Get popular airports
     */
    private function getPopularAirports(): array
    {
        return Airport::whereHas('dep_flights', function($query) {
                $query->where('active', true);
            })
            ->withCount(['dep_flights as departure_count', 'arr_flights as arrival_count'])
            ->orderByRaw('departure_count + arrival_count DESC')
            ->take(8)
            ->get()
            ->map(function ($airport) {
                return [
                    'icao' => $airport->icao,
                    'name' => $airport->name,
                    'city' => $airport->city ?? 'Unknown',
                    'country' => $airport->country ?? 'Unknown',
                    'total_flights' => $airport->departure_count + $airport->arrival_count,
                ];
            })
            ->toArray();
    }

    /**
     * Format flight data for display
     */
    private function formatFlightData($flight): array
    {
        $formatted = [
            'id' => $flight->id,
            'flight_number' => $flight->airline ? $flight->airline->icao . $flight->flight_number : $flight->flight_number,
            'departure' => [
                'icao' => $flight->dpt_airport->icao ?? 'N/A',
                'name' => $flight->dpt_airport->name ?? 'Unknown',
                'city' => $flight->dpt_airport->city ?? '',
            ],
            'arrival' => [
                'icao' => $flight->arr_airport->icao ?? 'N/A',
                'name' => $flight->arr_airport->name ?? 'Unknown', 
                'city' => $flight->arr_airport->city ?? '',
            ],
            'flight_time' => $flight->flight_time ?? 0,
            'flight_time_formatted' => $this->formatFlightTime($flight->flight_time ?? 0),
            'distance' => $flight->distance ?? 0,
            'route' => $flight->route ?? '',
        ];

        // Add aircraft info if available and enabled
        if ($this->config['show_aircraft'] && $flight->aircraft) {
            $formatted['aircraft'] = [
                'icao' => $flight->aircraft->icao ?? 'N/A',
                'name' => $flight->aircraft->name ?? 'Unknown',
            ];
        }

        return $formatted;
    }

    /**
     * Format flight time to hours and minutes
     */
    private function formatFlightTime($minutes): string
    {
        if ($minutes < 60) {
            return $minutes . 'm';
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($remainingMinutes == 0) {
            return $hours . 'h';
        }
        
        return $hours . 'h ' . $remainingMinutes . 'm';
    }
}