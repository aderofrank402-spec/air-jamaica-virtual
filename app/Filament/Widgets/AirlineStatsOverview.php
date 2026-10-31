<?php

namespace App\Filament\Widgets;

use App\Models\Aircraft;
use App\Models\Airline;
use App\Models\Enums\PirepState;
use App\Models\Enums\UserState;
use App\Models\Pirep;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AirlineStatsOverview extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 1;
    
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Global counts for Super Admin perspective
        $totalAircraft = Aircraft::count();
        $activePilots = User::where('state', UserState::ACTIVE)->count();
        $totalFlights = Pirep::where('state', PirepState::ACCEPTED)->count();
        
        // Calculate fleet utilization (aircraft with recent flights)
        $activeAircraftCount = Aircraft::whereHas('pireps', function($query) {
                $query->where('created_at', '>=', now()->subDays(30))
                      ->where('state', PirepState::ACCEPTED);
            })
            ->count();
        
        $utilization = $totalAircraft > 0 ? round(($activeAircraftCount / $totalAircraft) * 100) : 0;

        return [
            Stat::make('Total Aircraft', number_format($totalAircraft))
                ->description('Fleet size')
                ->descriptionIcon('heroicon-s-paper-airplane')
                ->color('primary')
                ->chart([5, 10, 8, 12, 15, 10, $totalAircraft]),
                
            Stat::make('Active Pilots', number_format($activePilots))
                ->description('Certified personnel')
                ->descriptionIcon('heroicon-s-users')
                ->color('success')
                ->chart([2, 4, 3, 5, 4, 6, $activePilots]),
                
            Stat::make('Total Flights', number_format($totalFlights))
                ->description('Completed operations')
                ->descriptionIcon('heroicon-s-globe-americas')
                ->color('info')
                ->chart([10, 20, 15, 25, 30, 20, $totalFlights]),
                
            Stat::make('Fleet Utilization', $utilization . '%')
                ->description('30-day activity')
                ->descriptionIcon('heroicon-s-bolt')
                ->color($utilization >= 70 ? 'success' : ($utilization >= 40 ? 'warning' : 'danger'))
                ->chart([0, 20, 40, 60, 80, 100]),
        ];
    }
}