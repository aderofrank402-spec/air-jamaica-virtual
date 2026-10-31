<?php

namespace App\Filament\Resources\Pireps\Infolists;

use App\Filament\Resources\Pireps\Infolists\Components\DashboardStat;
use App\Filament\Resources\Pireps\Infolists\Components\FlightLogEntry;
use App\Filament\Resources\Pireps\Infolists\Components\MapEntry;
use App\Filament\Resources\Pireps\Infolists\Components\PirepHero;
use App\Filament\Resources\Pireps\Infolists\Components\RouteWaypointsEntry;
use App\Filament\Resources\Pireps\Infolists\Components\TelemetryInstrument;
use App\Models\Enums\PirepState;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PirepInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Top Row: Identity & Real-time Tracking
                Grid::make(2)->schema([
                    PirepHero::make('hero')
                        ->hiddenLabel(),
                    MapEntry::make('map')
                        ->hiddenLabel(),
                ])->columnSpanFull(),

                // Second Row: Tactical Telemetry & Navigational Waypoints
                Grid::make(2)->schema([
                    Section::make('Tactical Telemetry')
                        ->description('Latest ACARS position data.')
                        ->collapsible()
                        ->schema([
                            Grid::make(['default' => 1, 'sm' => 2])->schema([
                                TelemetryInstrument::make('altitude')
                                    ->labelText('Altitude')
                                    ->icon(Heroicon::OutlinedCloud)
                                    ->chartType('altitude')
                                    ->metrics(fn($record) => [
                                        ['value' => number_format($record->position?->altitude_msl ?? 0), 'unit' => 'MSL'],
                                        ['value' => number_format($record->position?->altitude_agl ?? 0), 'unit' => 'AGL'],
                                    ]),
                                TelemetryInstrument::make('airspeed')
                                    ->labelText('Airspeed')
                                    ->icon(Heroicon::OutlinedBolt)
                                    ->chartType('airspeed')
                                    ->metrics(fn($record) => [
                                        ['value' => $record->position?->ias ?? 0, 'unit' => 'IAS'],
                                        ['value' => $record->position?->gs ?? 0, 'unit' => 'GS'],
                                    ]),
                                TelemetryInstrument::make('heading')
                                    ->labelText('Navigation')
                                    ->icon(Heroicon::OutlinedMap)
                                    ->chartType('navigation')
                                    ->metrics(fn($record) => [
                                        ['value' => str_pad($record->position?->heading ?? 0, 3, '0', STR_PAD_LEFT), 'unit' => 'HDG'],
                                        ['value' => $record->position?->vs ?? 0, 'unit' => 'VS'],
                                    ]),
                                TelemetryInstrument::make('fuel')
                                    ->labelText('Fuel & Flow')
                                    ->icon(Heroicon::OutlinedBeaker)
                                    ->chartType('fuel')
                                    ->metrics(fn($record) => [
                                        ['value' => number_format($record->position?->fuel->local(0) ?? 0), 'unit' => setting('units.fuel', 'lbs')],
                                        ['value' => number_format($record->position?->fuel_flow ?? 0), 'unit' => 'PPH'],
                                    ]),
                            ]),
                        ]),

                    Section::make('Navigational Waypoints')
                        ->icon(Heroicon::OutlinedMapPin)
                        ->description('Filed route details.')
                        ->schema([
                            RouteWaypointsEntry::make('waypoints')
                                ->hiddenLabel(),
                        ]),
                ])->columnSpanFull(),

                // Third Row: Flight Operations (Boxed Stats)
                Section::make('Flight Operations')
                    ->icon(Heroicon::OutlinedChartBar)
                    ->description('Performance metrics and timing.')
                    ->schema([
                        Grid::make(['default' => 2, 'md' => 3, 'xl' => 6])->schema([
                            DashboardStat::make('flight_time')
                                ->label('Elapsed Time')
                                ->icon(Heroicon::OutlinedClock)
                                ->value(fn ($record) => \App\Support\Units\Time::minutesToTimeString($record->flight_time)),
                            DashboardStat::make('planned_flight_time')
                                ->label('Planned Time')
                                ->icon(Heroicon::OutlinedClock)
                                ->value(fn ($record) => $record->planned_flight_time ? \App\Support\Units\Time::minutesToTimeString($record->planned_flight_time) : 'N/A'),
                            DashboardStat::make('distance')
                                ->label('Distance')
                                ->icon(Heroicon::OutlinedArrowTrendingUp)
                                ->value(fn ($record) => number_format($record->distance->local(0)))
                                ->unit('nm'),
                            DashboardStat::make('planned_distance')
                                ->label('Planned Dist')
                                ->icon(Heroicon::OutlinedFlag)
                                ->value(fn ($record) => number_format($record->planned_distance->local(0)))
                                ->unit('nm'),
                            DashboardStat::make('fuel_used')
                                ->label('Fuel Consumed')
                                ->icon(Heroicon::OutlinedBeaker)
                                ->value(fn ($record) => number_format($record->fuel_used->local(0)))
                                ->unit(setting('units.fuel', 'lbs')),
                            DashboardStat::make('landing_rate')
                                ->label('Landing Rate')
                                ->icon(Heroicon::OutlinedArrowPath)
                                ->value(fn ($record) => number_format($record->landing_rate))
                                ->unit('fpm'),
                        ]),

                        Grid::make(3)->schema([
                            DashboardStat::make('block_off_time')
                                ->label('Block Off')
                                ->icon(Heroicon::OutlinedPlay)
                                ->value(fn ($record) => $record->block_off_time?->format('H:i:s') ?? '--:--:--'),
                            DashboardStat::make('block_on_time')
                                ->label('Block On')
                                ->icon(Heroicon::OutlinedStop)
                                ->value(fn ($record) => $record->block_on_time?->format('H:i:s') ?? '--:--:--'),
                            DashboardStat::make('submitted_at')
                                ->label('Submitted')
                                ->icon(Heroicon::OutlinedCheckCircle)
                                ->value(fn ($record) => $record->submitted_at?->diffForHumans() ?? 'N/A'),
                        ])->extraAttributes(['class' => 'mt-4 pt-4 border-t border-gray-100 dark:border-white/10']),

                        TextEntry::make('route')
                            ->label('Filed Route')
                            ->fontFamily('mono')
                            ->extraAttributes(['class' => 'mt-4 pt-4 border-t border-gray-100 dark:border-white/10']),
                    ])->columnSpanFull(),

                // Bottom Row: Mission Chronology (Full Width)
                Section::make('Mission Chronology')
                    ->icon(Heroicon::OutlinedListBullet)
                    ->description('Operational flight logs and events.')
                    ->schema([
                        FlightLogEntry::make('logs')
                            ->hiddenLabel(),
                    ])->columnSpanFull(),
            ]);
    }
}
