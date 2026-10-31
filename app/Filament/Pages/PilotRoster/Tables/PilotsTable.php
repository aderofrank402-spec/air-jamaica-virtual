<?php

namespace App\Filament\Pages\PilotRoster\Tables;

use App\Models\Enums\PirepState;
use App\Models\Enums\UserState;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class PilotsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name ?? '') . '&background=random')
                    ->size(40),

                TextColumn::make('name')
                    ->label('Pilot')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->ident . ($record->callsign ? ' • ' . $record->callsign : ''))
                    ->weight('medium'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope')
                    ->iconPosition('before')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('location')
                    ->label('Location')
                    ->getStateUsing(function ($record) {
                        if ($record->current_airport) {
                            return $record->current_airport->icao;
                        }
                        if ($record->home_airport) {
                            return $record->home_airport->icao;
                        }
                        return 'N/A';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('current_airport', fn ($q) => $q->where('icao', 'like', "%{$search}%"))
                            ->orWhereHas('home_airport', fn ($q) => $q->where('icao', 'like', "%{$search}%"));
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('curr_airport_id', $direction);
                    })
                    ->description(function ($record) {
                        if ($record->current_airport) {
                            return $record->current_airport->name;
                        }
                        if ($record->home_airport) {
                            return $record->home_airport->name . ' (Home)';
                        }
                        return null;
                    })
                    ->icon(fn ($record) => $record->current_airport ? 'heroicon-o-map-pin' : ($record->home_airport ? 'heroicon-o-home' : null))
                    ->iconPosition('before')
                    ->color(fn ($record) => $record->current_airport ? 'primary' : ($record->home_airport ? 'info' : 'gray')),

                TextColumn::make('rank.name')
                    ->label('Rank')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('state')
                    ->label('State')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        UserState::PENDING => 'warning',
                        UserState::ACTIVE => 'success',
                        UserState::REJECTED => 'danger',
                        UserState::ON_LEAVE => 'info',
                        UserState::SUSPENDED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => UserState::label($state))
                    ->sortable(),

                TextColumn::make('flights')
                    ->label('Flights')
                    ->description(fn ($record) => ($record->accepted_pireps_count ?? 0) . ' accepted')
                    ->sortable(),

                TextColumn::make('flight_hours')
                    ->label('Hours')
                    ->getStateUsing(fn ($record) => round(($record->flight_time ?? 0) / 60, 1) . 'h')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('flight_time', $direction);
                    }),

                TextColumn::make('last_pirep.route')
                    ->label('Last Flight')
                    ->getStateUsing(function ($record) {
                        $pirep = $record->latestPirep ?? $record->last_pirep;
                        if (!$pirep) {
                            return 'No flights';
                        }
                        $dpt = $pirep->dpt_airport?->icao ?? $pirep->dpt_airport_id ?? 'N/A';
                        $arr = $pirep->arr_airport?->icao ?? $pirep->arr_airport_id ?? 'N/A';
                        return $dpt . ' → ' . $arr;
                    })
                    ->description(fn ($record) => ($record->latestPirep ?? $record->last_pirep)?->submitted_at?->diffForHumans())
                    ->icon('heroicon-o-paper-airplane')
                    ->iconPosition('before')
                    ->color('gray'),

                TextColumn::make('lastlogin_at')
                    ->label('Last Login')
                    ->formatStateUsing(function ($state) {
                        if (empty($state) || strtolower($state) === 'never') {
                            return 'Never';
                        }
                        try {
                            return Carbon::parse($state)->format('M d, Y');
                        } catch (\Exception $e) {
                            return 'Never';
                        }
                    })
                    ->description(function ($record) {
                        if (empty($record->lastlogin_at) || strtolower($record->lastlogin_at) === 'never') {
                            return 'Never';
                        }
                        try {
                            return Carbon::parse($record->lastlogin_at)->diffForHumans();
                        } catch (\Exception $e) {
                            return 'Never';
                        }
                    })
                    ->sortable()
                    ->toggleable()
                    ->default('Never'),

                TextColumn::make('awards_count')
                    ->label('Awards')
                    ->description(fn ($record) => ($record->typeratings_count ?? 0) > 0 ? $record->typeratings_count . ' type ratings' : null)
                    ->badge()
                    ->color('warning'),

                TextColumn::make('active_bids_count')
                    ->label('Active Bids')
                    ->getStateUsing(function ($record) {
                        return $record->active_bids_count ?? 0;
                    })
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('journal_balance')
                    ->label('Balance')
                    ->getStateUsing(function ($record) {
                        if (!$record->journal || !$record->journal->balance) {
                            return 0;
                        }
                        // Money object has getValue() method that returns float
                        return $record->journal->balance->getValue();
                    })
                    ->money('USD', divideBy: 1)
                    ->icon('heroicon-o-banknotes')
                    ->iconPosition('before')
                    ->color(function ($record) {
                        if (!$record->journal || !$record->journal->balance) {
                            return 'gray';
                        }
                        return $record->journal->balance->getValue() >= 0 ? 'success' : 'danger';
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->join('journals', 'users.id', '=', 'journals.morphed_id')
                            ->where('journals.morphed_type', 'App\Models\User')
                            ->orderBy('journals.balance', $direction);
                    })
                    ->toggleable(),

                TextColumn::make('airline.icao')
                    ->label('Airline')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->toggleable(),

                TextColumn::make('country')
                    ->label('Country')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (?string $state) => $state ? strtoupper($state) : 'N/A')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('M d, Y')
                    ->description(fn ($record) => $record->created_at->diffForHumans())
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('avg_score')
                    ->label('Avg Score')
                    ->getStateUsing(function ($record) {
                        $avg = $record->pireps()
                            ->where('state', PirepState::ACCEPTED)
                            ->where('score', '>', 0)
                            ->avg('score');
                        return $avg ? round($avg, 1) : 'N/A';
                    })
                    ->badge()
                    ->color(function ($state) {
                        if ($state === 'N/A' || !is_numeric($state)) {
                            return 'gray';
                        }
                        $score = (float) $state;
                        return match (true) {
                            $score >= 90 => 'success',
                            $score >= 80 => 'warning',
                            $score >= 70 => 'info',
                            default => 'gray',
                        };
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->withAvg(['pireps as avg_score' => fn ($q) => $q->where('state', PirepState::ACCEPTED)->where('score', '>', 0)], 'score')
                            ->orderBy('avg_score', $direction);
                    })
                    ->toggleable(),

                TextColumn::make('avg_landing_rate')
                    ->label('Avg Landing')
                    ->getStateUsing(function ($record) {
                        $avg = $record->pireps()
                            ->where('state', PirepState::ACCEPTED)
                            ->whereNotNull('landing_rate')
                            ->where('landing_rate', '>', -1000)
                            ->avg('landing_rate');
                        return $avg ? round($avg) . ' fpm' : 'N/A';
                    })
                    ->badge()
                    ->color(function ($state) {
                        if ($state === 'N/A') {
                            return 'gray';
                        }
                        $rate = (int) str_replace(' fpm', '', $state);
                        return match (true) {
                            abs($rate) <= 100 => 'success',
                            abs($rate) <= 200 => 'warning',
                            default => 'danger',
                        };
                    })
                    ->toggleable(),

                TextColumn::make('total_distance')
                    ->label('Distance')
                    ->getStateUsing(function ($record) {
                        $distance = $record->pireps()
                            ->where('state', PirepState::ACCEPTED)
                            ->sum('distance');
                        return $distance ? number_format($distance, 0) . ' nm' : '0 nm';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('activity_status')
                    ->label('Activity')
                    ->getStateUsing(function ($record) {
                        // Handle null or 'Never' values safely
                        if (empty($record->lastlogin_at) || $record->lastlogin_at === 'Never') {
                            return 'Never';
                        }
                        // Try to parse, catch exceptions
                        try {
                            $daysSinceLogin = (int) floor(Carbon::parse($record->lastlogin_at)->diffInDays(now()));
                        } catch (\Exception $e) {
                            return 'Never';
                        }
                        return match (true) {
                            $daysSinceLogin === 0 => 'Today',
                            $daysSinceLogin === 1 => 'Yesterday',
                            $daysSinceLogin <= 7 => $daysSinceLogin . ' days ago',
                            $daysSinceLogin <= 30 => $daysSinceLogin . ' days ago',
                            default => $daysSinceLogin . ' days ago',
                        };
                    })
                    ->badge()
                    ->color(function ($record) {
                        if (empty($record->lastlogin_at) || $record->lastlogin_at === 'Never') {
                            return 'gray';
                        }
                        try {
                            $daysSinceLogin = (int) floor(Carbon::parse($record->lastlogin_at)->diffInDays(now()));
                        } catch (\Exception $e) {
                            return 'gray';
                        }
                        return match (true) {
                            $daysSinceLogin <= 1 => 'success',
                            $daysSinceLogin <= 7 => 'warning',
                            $daysSinceLogin <= 30 => 'info',
                            default => 'danger',
                        };
                    })
                    ->icon(function ($record) {
                        if (empty($record->lastlogin_at) || $record->lastlogin_at === 'Never') {
                            return 'heroicon-o-x-circle';
                        }
                        try {
                            $daysSinceLogin = (int) floor(Carbon::parse($record->lastlogin_at)->diffInDays(now()));
                        } catch (\Exception $e) {
                            return 'heroicon-o-x-circle';
                        }
                        return $daysSinceLogin <= 7 ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle';
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('lastlogin_at', $direction);
                    })
                    ->toggleable(),

                TextColumn::make('vatsim_id')
                    ->label('VATSIM')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (?string $state) => $state ?: 'N/A'),

                TextColumn::make('ivao_id')
                    ->label('IVAO')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (?string $state) => $state ?: 'N/A'),

                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->notes)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('state')
                    ->label('State')
                    ->options(UserState::labels())
                    ->multiple(),

                SelectFilter::make('current_airport_id')
                    ->label('Current Location')
                    ->relationship('current_airport', 'icao')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->icao . ' - ' . $record->name)
                    ->searchable()
                    ->preload(),

                SelectFilter::make('home_airport_id')
                    ->label('Home Airport')
                    ->relationship('home_airport', 'icao')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->icao . ' - ' . $record->name)
                    ->searchable()
                    ->preload(),

                SelectFilter::make('rank_id')
                    ->label('Rank')
                    ->relationship('rank', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('airline_id')
                    ->label('Airline')
                    ->relationship('airline', 'icao')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('email_verified_at')
                    ->label('Email Verified')
                    ->placeholder('All')
                    ->trueLabel('Verified')
                    ->falseLabel('Not Verified')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('email_verified_at'),
                        false: fn (Builder $query) => $query->whereNull('email_verified_at'),
                        blank: fn (Builder $query) => $query,
                    ),

                Filter::make('lastlogin_at')
                    ->label('Last Login')
                    ->form([
                        Select::make('period')
                            ->options([
                                'today' => 'Today',
                                'week' => 'Last 7 days',
                                'month' => 'Last 30 days',
                                'quarter' => 'Last 90 days',
                                'never' => 'Never logged in',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['period'] === 'today',
                                fn (Builder $query) => $query->whereDate('lastlogin_at', today()),
                            )
                            ->when(
                                $data['period'] === 'week',
                                fn (Builder $query) => $query->where('lastlogin_at', '>=', now()->subDays(7)),
                            )
                            ->when(
                                $data['period'] === 'month',
                                fn (Builder $query) => $query->where('lastlogin_at', '>=', now()->subDays(30)),
                            )
                            ->when(
                                $data['period'] === 'quarter',
                                fn (Builder $query) => $query->where('lastlogin_at', '>=', now()->subDays(90)),
                            )
                            ->when(
                                $data['period'] === 'never',
                                fn (Builder $query) => $query->whereNull('lastlogin_at'),
                            );
                    }),

                Filter::make('flights')
                    ->label('Flight Count')
                    ->form([
                        Select::make('range')
                            ->options([
                                'none' => 'No flights',
                                'low' => '1-10 flights',
                                'medium' => '11-50 flights',
                                'high' => '51-100 flights',
                                'very_high' => '100+ flights',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['range'] === 'none',
                                fn (Builder $query) => $query->where('flights', 0),
                            )
                            ->when(
                                $data['range'] === 'low',
                                fn (Builder $query) => $query->whereBetween('flights', [1, 10]),
                            )
                            ->when(
                                $data['range'] === 'medium',
                                fn (Builder $query) => $query->whereBetween('flights', [11, 50]),
                            )
                            ->when(
                                $data['range'] === 'high',
                                fn (Builder $query) => $query->whereBetween('flights', [51, 100]),
                            )
                            ->when(
                                $data['range'] === 'very_high',
                                fn (Builder $query) => $query->where('flights', '>', 100),
                            );
                    }),
            ])
            ->defaultSort('name')
            ->defaultPaginationPageOption(25)
            ->paginationPageOptions([10, 25, 50, 100])
            ->striped()
            ->recordActions([
                ActionGroup::make([
                    Action::make('view_profile')
                        ->label('View Profile')
                        ->icon('heroicon-o-user-circle')
                        ->url(fn ($record) => route('frontend.users.show.public', $record->id))
                        ->openUrlInNewTab(),
                    Action::make('edit')
                        ->label('Edit')
                        ->icon('heroicon-o-pencil')
                        ->url(fn ($record) => \App\Filament\Resources\Users\UserResource::getUrl('edit', ['record' => $record])),
                    Action::make('view_details')
                        ->label('View Details')
                        ->icon('heroicon-o-information-circle')
                        ->modalHeading(fn ($record) => 'Pilot Details: ' . $record->name)
                        ->modalContent(function ($record) {
                            $stats = [
                                'Total Flights' => $record->pireps_count ?? 0,
                                'Accepted Flights' => $record->accepted_pireps_count ?? 0,
                                'Total Hours' => round(($record->flight_time ?? 0) / 60, 1) . 'h',
                                'Transfer Hours' => round(($record->transfer_time ?? 0) / 60, 1) . 'h',
                                'Awards' => $record->awards_count ?? 0,
                                'Type Ratings' => $record->typeratings_count ?? 0,
                                'Active Bids' => $record->active_bids_count ?? 0,
                            ];
                            
                            $avgScore = $record->pireps()
                                ->where('state', PirepState::ACCEPTED)
                                ->where('score', '>', 0)
                                ->avg('score');
                            
                            $avgLanding = $record->pireps()
                                ->where('state', PirepState::ACCEPTED)
                                ->whereNotNull('landing_rate')
                                ->where('landing_rate', '>', -1000)
                                ->avg('landing_rate');
                            
                            $totalDistance = $record->pireps()
                                ->where('state', PirepState::ACCEPTED)
                                ->sum('distance');
                            
                            return view('filament.pages.pilot-roster.details-modal', [
                                'record' => $record,
                                'stats' => $stats,
                                'avgScore' => $avgScore ? round($avgScore, 1) : 'N/A',
                                'avgLanding' => $avgLanding ? round($avgLanding) : 'N/A',
                                'totalDistance' => $totalDistance ? number_format($totalDistance, 0) . ' nm' : '0 nm',
                            ]);
                        })
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Close'),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No pilots found')
            ->emptyStateDescription('There are no pilots in the system yet.')
            ->emptyStateIcon('heroicon-o-users')
            ->deferLoading()
            ->persistSearchInSession()
            ->persistColumnSearchesInSession()
            ->persistSortInSession()
            ->persistFiltersInSession();
    }
}

