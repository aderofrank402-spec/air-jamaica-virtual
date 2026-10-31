<?php

namespace App\Filament\Widgets;

use App\Models\Enums\UserState;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PilotRosterStats extends BaseWidget
{
    use HasWidgetShield;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public static function canAccess(): bool
    {
        return true;
    }

    protected function getStats(): array
    {
        $totalPilots = User::count();
        $activePilots = User::where('state', UserState::ACTIVE)->count();
        $pendingPilots = User::where('state', UserState::PENDING)->count();
        $totalFlights = User::sum('flights');
        $totalHours = round(User::sum('flight_time') / 60, 1);

        return [
            Stat::make('Pilot Roster', number_format($totalPilots))
                ->description('Total registrations')
                ->descriptionIcon('heroicon-s-identification')
                ->color('primary'),

            Stat::make('Applications', number_format($pendingPilots))
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-s-clock')
                ->color($pendingPilots > 0 ? 'warning' : 'gray'),

            Stat::make('Experience', number_format($totalHours, 1) . 'h')
                ->description('Fleet flight hours')
                ->descriptionIcon('heroicon-s-academic-cap')
                ->color('info'),
        ];
    }
}

