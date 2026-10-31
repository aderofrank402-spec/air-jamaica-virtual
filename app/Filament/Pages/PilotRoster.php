<?php

namespace App\Filament\Pages;

use App\Filament\Pages\PilotRoster\Tables\PilotsTable;
use App\Models\Bid;
use App\Models\Enums\NavigationGroup;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PilotRoster extends Page implements Tables\Contracts\HasTable
{
    use HasPageShield;
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationLabel = 'Pilot Roster';

    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::Operations;

    protected static ?int $navigationSort = 4;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public function table(Table $table): Table
    {
        return PilotsTable::configure($table);
    }

    public function getTableQuery(): Builder
    {
        return \App\Models\User::query()
            ->with([
                'airline',
                'rank',
                'home_airport',
                'current_airport',
                'last_pirep.arr_airport',
                'last_pirep.dpt_airport',
                'latestPirep.arr_airport',
                'latestPirep.dpt_airport',
                'journal',
            ])
            ->withCount([
                'pireps',
                'pireps as accepted_pireps_count' => fn ($query) => $query->where('state', \App\Models\Enums\PirepState::ACCEPTED),
                'awards',
                'typeratings',
                'bids as active_bids_count' => fn ($query) => $query->whereNull('deleted_at'),
            ]);
    }

    public function getStats(): array
    {
        $totalPilots = \App\Models\User::count();
        $activePilots = \App\Models\User::where('state', \App\Models\Enums\UserState::ACTIVE)->count();
        $pendingPilots = \App\Models\User::where('state', \App\Models\Enums\UserState::PENDING)->count();
        $totalFlights = \App\Models\User::sum('flights');
        $totalHours = round(\App\Models\User::sum('flight_time') / 60, 1);
        
        // Additional stats
        $verifiedPilots = \App\Models\User::whereNotNull('email_verified_at')->count();
        $recentlyActive = \App\Models\User::where('lastlogin_at', '>=', now()->subDays(7))->count();
        $totalAwards = \App\Models\User::withCount('awards')->get()->sum('awards_count');
        $totalBids = \App\Models\Bid::whereNull('deleted_at')->count();

        return [
            'total' => $totalPilots,
            'active' => $activePilots,
            'pending' => $pendingPilots,
            'total_flights' => $totalFlights,
            'total_hours' => $totalHours,
            'verified' => $verifiedPilots,
            'recently_active' => $recentlyActive,
            'total_awards' => $totalAwards,
            'total_bids' => $totalBids,
        ];
    }

    public function getStatsComponent(): Component
    {
        $stats = $this->getStats();
        
        return Grid::make()
            ->columns([
                'default' => 2,
                'sm' => 2,
                'md' => 4,
                'lg' => 4,
            ])
            ->columnSpanFull()
            ->schema([
                Section::make()
                    ->heading('Total Pilots')
                    ->description((string) $stats['total'])
                    ->icon('heroicon-o-users')
                    ->iconColor('primary')
                    ->compact(),
                Section::make()
                    ->heading('Active Pilots')
                    ->description((string) $stats['active'])
                    ->icon('heroicon-o-check-circle')
                    ->iconColor('success')
                    ->compact(),
                Section::make()
                    ->heading('Pending')
                    ->description((string) $stats['pending'])
                    ->icon('heroicon-o-clock')
                    ->iconColor('warning')
                    ->compact(),
                Section::make()
                    ->heading('Total Flights')
                    ->description(number_format($stats['total_flights']))
                    ->icon('heroicon-o-paper-airplane')
                    ->iconColor('info')
                    ->compact(),
                Section::make()
                    ->heading('Total Hours')
                    ->description(number_format($stats['total_hours'], 1) . 'h')
                    ->icon('heroicon-o-clock')
                    ->iconColor('gray')
                    ->compact(),
                Section::make()
                    ->heading('Verified')
                    ->description((string) $stats['verified'])
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('success')
                    ->compact(),
                Section::make()
                    ->heading('Active (7d)')
                    ->description((string) $stats['recently_active'])
                    ->icon('heroicon-o-fire')
                    ->iconColor('warning')
                    ->compact(),
                Section::make()
                    ->heading('Total Awards')
                    ->description(number_format($stats['total_awards']))
                    ->icon('heroicon-o-trophy')
                    ->iconColor('warning')
                    ->compact(),
            ]);
    }

    public function getTableContentComponent(): Component
    {
        return EmbeddedTable::make();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getStatsComponent(),
                $this->getTableContentComponent(),
            ]);
    }
}
