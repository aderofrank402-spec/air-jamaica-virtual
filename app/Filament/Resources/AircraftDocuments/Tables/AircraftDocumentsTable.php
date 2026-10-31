<?php

namespace App\Filament\Resources\AircraftDocuments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AircraftDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Title'),
                    
                TextColumn::make('aircraftType.name')
                    ->searchable()
                    ->sortable()
                    ->label('Aircraft Type'),
                    
                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->label('Category'),
                    
                TextColumn::make('version')
                    ->sortable()
                    ->label('Version'),
                    
                TextColumn::make('file_type')
                    ->sortable()
                    ->label('File Type'),
                    
                TextColumn::make('file_size_formatted')
                    ->label('File Size'),
                    
                TextColumn::make('access_level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'public' => 'success',
                        'registered' => 'info',
                        'pilot' => 'warning',
                        'instructor' => 'primary',
                        'admin' => 'danger',
                        default => 'gray',
                    })
                    ->label('Access Level'),
                    
                TextColumn::make('download_count')
                    ->numeric()
                    ->sortable()
                    ->label('Downloads'),
                    
                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),
                    
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                    
                TextColumn::make('effective_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('expiry_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('aircraft_type_id')
                    ->relationship('aircraftType', 'name')
                    ->label('Aircraft Type'),
                    
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
                    
                SelectFilter::make('access_level')
                    ->options([
                        'public' => 'Public',
                        'registered' => 'Registered Users',
                        'pilot' => 'Pilots Only',
                        'instructor' => 'Instructors Only',
                        'admin' => 'Administrators Only',
                    ])
                    ->label('Access Level'),
                    
                TernaryFilter::make('is_featured')
                    ->label('Featured'),
                    
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
