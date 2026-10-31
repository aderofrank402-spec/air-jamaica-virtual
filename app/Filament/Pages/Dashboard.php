<?php

namespace App\Filament\Pages;

use App\Http\Middleware\UpdatePending;
use Filament\Pages\Dashboard as FilamentDashboard;

class Dashboard extends FilamentDashboard
{
    protected static string|array $routeMiddleware = [UpdatePending::class];

    protected string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\News::class,
            \App\Filament\Widgets\LatestPirepsChart::class,
        ];
    }

    protected function getViewData(): array
    {
        return \Illuminate\Support\Facades\Cache::remember('admin_dashboard_metrics_v3', 600, function () {
            // 1. Recently Completed Flights (most recent 5)
            $liveFlights = \App\Models\Pirep::where('state', \App\Models\Enums\PirepState::ACCEPTED)
                ->with(['user', 'aircraft', 'dpt_airport', 'arr_airport', 'position'])
                ->orderByDesc('submitted_at')
                ->limit(5)
                ->get()
                ->map(fn($p) => [
                    'callsign' => optional($p->airline)->code . $p->flight_number,
                    'pilot' => $p->user->name,
                    'aircraft' => optional($p->aircraft)->registration ?? 'N/A',
                    'dep' => $p->dpt_airport_id,
                    'arr' => $p->arr_airport_id,
                    'altitude' => optional($p->position)->altitude_msl ?? 0,
                    'gs' => optional($p->position)->gs ?? 0,
                ]);

            // 2. Hub Activity Monitor
            $hubs = \App\Models\Airport::where('hub', true)
                ->take(5)
                ->get()
                ->map(fn($h) => [
                    'icao' => $h->icao,
                    'name' => $h->name,
                    'activity' => \App\Models\Pirep::where('dpt_airport_id', $h->id)
                        ->where('created_at', '>=', now()->startOfDay())
                        ->count()
                ]);

            // 3. Training & Recruitment Pipeline
            $pendingApplicants = \App\Models\User::where('state', \App\Models\Enums\UserState::PENDING)->count();
            $activeTrainees = \App\Models\TrainingEnrollment::whereIn('status', ['enrolled', 'in_progress'])->count();
            $recentCerts = \App\Models\TrainingCertification::where('issued_at', '>=', now()->subDays(7))->count();

            // 4. Fleet Health
            $maintenanceCount = \App\Models\Aircraft::where('status', \App\Models\Enums\AircraftStatus::MAINTENANCE)->count();
            $highHoursAircraft = \App\Models\Aircraft::orderBy('flight_time', 'desc')->take(3)->get()->map(fn($a) => [
                'reg' => $a->registration,
                'hours' => round($a->flight_time / 3600, 1)
            ]);

            // Existing Strategic Metrics
            $completedFlights = \App\Models\Pirep::where('state', \App\Models\Enums\PirepState::ACCEPTED)->count();
            $totalAirports = \App\Models\Airport::count();
            $activeAircraft = \App\Models\Aircraft::where('status', \App\Models\Enums\AircraftStatus::ACTIVE)->count();
            $activePilots = \App\Models\User::where('state', \App\Models\Enums\UserState::ACTIVE)->count();

            return [
                'metrics' => [
                    'operations' => [
                        'completed' => $completedFlights,
                        'active_count' => $liveFlights->count(),
                        'live' => $liveFlights,
                    ],
                    'fleet' => [
                        'active' => $activeAircraft,
                        'maintenance' => $maintenanceCount,
                        'high_hours' => $highHoursAircraft,
                        'total' => \App\Models\Aircraft::count(),
                    ],
                    'personnel' => [
                        'active' => $activePilots,
                        'total' => \App\Models\User::count(),
                        'pending' => $pendingApplicants,
                    ],
                    'destinations' => [
                        'total' => $totalAirports,
                    ],
                    'hubs' => $hubs,
                    'training' => [
                        'active' => $activeTrainees,
                        'certs' => $recentCerts,
                    ]
                ]
            ];
        });
    }
}
