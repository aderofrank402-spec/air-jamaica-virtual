<?php

namespace App\Filament\Resources\Aircraft\Tables;

use App\Models\Enums\AircraftStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class AircraftTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('registration')
                    ->searchable()
                    ->sortable()
                    ->label('Registration'),
                    
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Aircraft Name'),
                    
                TextColumn::make('subfleet.name')
                    ->searchable()
                    ->sortable()
                    ->label('Subfleet'),
                    
                TextColumn::make('icao')
                    ->searchable()
                    ->sortable()
                    ->label('ICAO'),
                    
                TextColumn::make('airport.name')
                    ->searchable()
                    ->sortable()
                    ->label('Current Airport'),
                    
                TextColumn::make('hub.name')
                    ->searchable()
                    ->sortable()
                    ->label('Hub'),
                    
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'A' => 'success',  // Active
                        'S' => 'info',     // In Service
                        'M' => 'warning',  // Maintenance
                        'R' => 'danger',   // Retired
                        default => 'gray',
                    })
                    ->label('Status'),
                    
                TextColumn::make('flight_time')
                    ->numeric(decimalPlaces: 1)
                    ->suffix(' hrs')
                    ->sortable()
                    ->label('Flight Time'),
                    
                TextColumn::make('dow')
                    ->numeric()
                    ->suffix(' kg')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('DOW'),
                    
                TextColumn::make('mtow')
                    ->numeric()
                    ->suffix(' kg')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('MTOW'),
                    
                TextColumn::make('fuel_onboard')
                    ->numeric()
                    ->suffix(' lbs')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Fuel Onboard'),
                    
                TextColumn::make('mlw')
                    ->numeric()
                    ->suffix(' kg')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('MLW'),
                    
                TextColumn::make('zfw')
                    ->numeric()
                    ->suffix(' kg')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('ZFW'),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                
                SelectFilter::make('subfleet_id')
                    ->relationship('subfleet', 'name')
                    ->label('Subfleet'),
                    
                SelectFilter::make('status')
                    ->options([
                        'A' => 'Active',
                        'S' => 'In Service',
                        'M' => 'Maintenance',
                        'R' => 'Retired',
                    ])
                    ->label('Status'),
                    
                SelectFilter::make('airport_id')
                    ->relationship('airport', 'name')
                    ->label('Current Airport'),
                    
                SelectFilter::make('hub_id')
                    ->relationship('hub', 'name')
                    ->label('Hub'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
