<?php

namespace App\Widgets;

use App\Contracts\Widget;
use App\Models\Pirep;
use App\Models\Acars;
use App\Models\Enums\PirepState;
use Illuminate\Support\Facades\Auth;

/**
 * Active Flight Status Widget - Real-time flight monitoring
 */
class ActiveFlightStatus extends Widget
{
    protected $config = [
        'show_acars_details' => true,
        'show_route_info' => true,
        'refresh_interval' => 30000, // 30 seconds
    ];

    /**
     * Helper method to safely extract numeric values from Unit objects
     */
    private function getNumericValue($value, $default = 0)
    {
        if (is_object($value) && method_exists($value, 'getInternalValue')) {
            return $value->getInternalValue();
        }
        return $value ?? $default;
    }

    public function run()
    {
        $user = Auth::user();

        // Get the last approved flight for this user
        $approved_pirep = Pirep::where('user_id', $user->id)
            ->where('state', PirepState::ACCEPTED)
            ->with(['dpt_airport', 'arr_airport', 'aircraft', 'flight'])
            ->orderByDesc('submitted_at')
            ->orderByDesc('created_at')
            ->first();

        $flight_data = null;
        $latest_acars = null;
        $flight_phase = 'NO FLIGHT';

        if ($approved_pirep) {
            // Get latest ACARS position for that approved flight
            $latest_acars = Acars::where('pirep_id', $approved_pirep->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $flight_phase = $latest_acars ? $this->determineFlightPhase($latest_acars) : 'COMPLETED';

            $flight_data = [
                'pirep' => $approved_pirep,
                'flight_time' => $latest_acars ? $latest_acars->time ?? 0 : ($approved_pirep->flight_time ?? 0),
                'estimated_remaining' => $latest_acars ? $this->calculateEstimatedRemaining($approved_pirep, $latest_acars) : 0,
                'progress_percentage' => $latest_acars ? $this->calculateProgressPercentage($approved_pirep, $latest_acars) : 100,
            ];
        }

        return view('widgets.active_flight_status', [
            'flight_data' => $flight_data,
            'latest_acars' => $latest_acars,
            'flight_phase' => $flight_phase,
            'config' => $this->config,
        ]);
    }

    /**
     * Determine flight phase based on ACARS data
     */
    private function determineFlightPhase($acars): string
    {
        if (!$acars) {
            return 'PREFLIGHT';
        }

        $altitude = $acars->altitude ?? 0;
        $groundspeed = $acars->gs ?? 0;

        if ($altitude < 1000 && $groundspeed < 50) {
            return 'TAXI';
        } elseif ($altitude < 1000 && $groundspeed > 50) {
            return 'TAKEOFF';
        } elseif ($altitude > 10000) {
            return 'CRUISE';
        } elseif ($altitude < 10000 && $altitude > 1000) {
            return 'DESCENT';
        }

        return 'EN-ROUTE';
    }

    /**
     * Calculate estimated remaining time
     */
    private function calculateEstimatedRemaining($pirep, $acars): int
    {
        $distance = $this->getNumericValue($pirep->distance, 0);
        $acarsDistance = $this->getNumericValue($acars->distance ?? 0, 0);
        
        if (!$acars || $distance == 0 || $acars->gs <= 0) {
            return 0;
        }

        $remaining_distance = $distance - $acarsDistance;
        $groundspeed = $acars->gs;

        return max(0, ($remaining_distance / $groundspeed) * 60); // Minutes
    }

    /**
     * Calculate flight progress percentage
     */
    private function calculateProgressPercentage($pirep, $acars): int
    {
        $totalDistance = $this->getNumericValue($pirep->distance, 0);
        $flownDistance = $this->getNumericValue($acars->distance ?? 0, 0);
        
        if (!$acars || $totalDistance == 0) {
            return 0;
        }

        return min(100, max(0, ($flownDistance / $totalDistance) * 100));
    }
}