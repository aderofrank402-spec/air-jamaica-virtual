<?php

namespace App\Widgets;

use App\Contracts\Widget;
use App\Repositories\AcarsRepository;
use App\Repositories\NotamRepository;
use App\Services\AirportService;
use App\Models\Enums\PirepStatus;
use App\Models\Flight;
use App\Models\User;
use Carbon\Carbon;

/**
 * Dispatch Center Widget - Centralized flight operations monitoring
 */
class DispatchCenter extends Widget
{
    protected $config = [
        'show_active_flights' => true,
        'show_weather' => true,
        'show_notams' => true,
        'show_pending_departures' => true,
        'hub_icao' => null, // Main hub airport
        'refresh_interval' => 30, // seconds
    ];

    /**
     * Get dispatch center data
     */
    public function run()
    {
        $data = [
            'config' => $this->config,
            'updated_at' => Carbon::now(),
        ];

        // Active flights monitoring
        if ($this->config['show_active_flights']) {
            $data['active_flights'] = $this->getActiveFlights();
        }

        // Pending departures
        if ($this->config['show_pending_departures']) {
            $data['pending_departures'] = $this->getPendingDepartures();
        }

        // Weather information for hub
        if ($this->config['show_weather'] && $this->config['hub_icao']) {
            $data['weather'] = $this->getHubWeather();
        }

        // Critical NOTAMs
        if ($this->config['show_notams']) {
            $data['critical_notams'] = $this->getCriticalNotams();
        }

        // Dispatch statistics
        $data['stats'] = $this->getDispatchStats();

        return view('widgets.dispatch_center', $data);
    }

    /**
     * Get currently active flights
     */
    private function getActiveFlights()
    {
        /** @var AcarsRepository $acarsRepo */
        $acarsRepo = app(AcarsRepository::class);
        
        $activeFlights = $acarsRepo->getPositions(setting('acars.live_time', 15));
        
        return $activeFlights->map(function ($pirep) {
            return [
                'id' => $pirep->id,
                'flight_number' => $pirep->flight->flight_number ?? 'N/A',
                'pilot' => $pirep->user->name ?? 'Unknown',
                'aircraft' => $pirep->aircraft->registration ?? 'N/A',
                'departure' => $pirep->dpt_airport_id ?? 'N/A',
                'arrival' => $pirep->arr_airport_id ?? 'N/A',
                'status' => $pirep->status,
                'altitude' => $pirep->position->altitude ?? 0,
                'phase' => $this->getFlightPhase($pirep),
                'eta' => $this->calculateETA($pirep),
                'updated_at' => $pirep->updated_at,
            ];
        });
    }

    /**
     * Get pending departures (scheduled but not yet departed)
     */
    private function getPendingDepartures()
    {
        return \App\Models\Pirep::where('status', PirepStatus::SCHEDULED)
            ->orWhere('status', PirepStatus::BOARDING)
            ->orWhere('status', PirepStatus::RDY_START)
            ->with(['flight', 'user', 'aircraft'])
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($pirep) {
                return [
                    'id' => $pirep->id,
                    'flight_number' => $pirep->flight->flight_number ?? 'N/A',
                    'pilot' => $pirep->user->name ?? 'Unknown',
                    'aircraft' => $pirep->aircraft->registration ?? 'N/A',
                    'departure' => $pirep->dpt_airport_id ?? 'N/A',
                    'arrival' => $pirep->arr_airport_id ?? 'N/A',
                    'scheduled_departure' => $pirep->flight->dep_time ?? null,
                    'status' => $pirep->status,
                    'delay' => $this->calculateDelay($pirep),
                ];
            });
    }

    /**
     * Get weather for hub airport
     */
    private function getHubWeather()
    {
        if (!$this->config['hub_icao']) {
            return null;
        }

        /** @var AirportService $airportSvc */
        $airportSvc = app(AirportService::class);
        
        return [
            'icao' => $this->config['hub_icao'],
            'metar' => $airportSvc->getMetar($this->config['hub_icao']),
            'taf' => $airportSvc->getTaf($this->config['hub_icao']),
        ];
    }

    /**
     * Get critical NOTAMs
     */
    private function getCriticalNotams()
    {
        /** @var NotamRepository $notamRepo */
        $notamRepo = app(NotamRepository::class);
        
        return $notamRepo->getCritical()->take(5);
    }

    /**
     * Get dispatch center statistics
     */
    private function getDispatchStats()
    {
        $activeFlights = \App\Models\Pirep::whereIn('status', [
            PirepStatus::BOARDING,
            PirepStatus::RDY_START,
            PirepStatus::TAXI,
            PirepStatus::TAKEOFF,
            PirepStatus::ENROUTE,
            PirepStatus::APPROACH,
            PirepStatus::LANDED,
        ])->count();

        $pendingDepartures = \App\Models\Pirep::whereIn('status', [
            PirepStatus::SCHEDULED,
            PirepStatus::BOARDING,
            PirepStatus::RDY_START,
        ])->count();

        $todayFlights = \App\Models\Pirep::whereDate('created_at', Carbon::today())->count();

        /** @var NotamRepository $notamRepo */
        $notamRepo = app(NotamRepository::class);
        $criticalNotams = $notamRepo->getCritical()->count();

        return [
            'active_flights' => $activeFlights,
            'pending_departures' => $pendingDepartures,
            'todays_flights' => $todayFlights,
            'critical_notams' => $criticalNotams,
            'online_pilots' => User::where('last_login_at', '>=', Carbon::now()->subMinutes(30))->count(),
        ];
    }

    /**
     * Determine flight phase from PIREP status
     */
    private function getFlightPhase($pirep): string
    {
        return match ($pirep->status) {
            PirepStatus::BOARDING => 'Boarding',
            PirepStatus::RDY_START => 'Ready for Start',
            PirepStatus::TAXI => 'Taxiing',
            PirepStatus::TAKEOFF => 'Takeoff',
            PirepStatus::ENROUTE => 'En Route',
            PirepStatus::APPROACH => 'Approach',
            PirepStatus::LANDED => 'Landed',
            default => 'Unknown',
        };
    }

    /**
     * Calculate estimated time of arrival
     */
    private function calculateETA($pirep): ?string
    {
        if (!$pirep->flight || !$pirep->flight->flight_time) {
            return null;
        }

        // Simple ETA calculation based on flight time and current progress
        $flightTime = $pirep->flight->flight_time; // in minutes
        $elapsed = $pirep->created_at->diffInMinutes(Carbon::now());
        $remaining = max(0, $flightTime - $elapsed);
        
        return Carbon::now()->addMinutes($remaining)->format('H:i');
    }

    /**
     * Calculate departure delay
     */
    private function calculateDelay($pirep): ?int
    {
        if (!$pirep->flight->dep_time) {
            return null;
        }

        $scheduledDep = Carbon::createFromFormat('H:i:s', $pirep->flight->dep_time);
        $now = Carbon::now();
        
        if ($now->greaterThan($scheduledDep)) {
            return $now->diffInMinutes($scheduledDep);
        }
        
        return 0;
    }
}