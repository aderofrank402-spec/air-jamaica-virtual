<?php

namespace App\Filament\Resources\AircraftDocumentCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AircraftDocumentCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Category Name'),
                    
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->label('Slug'),
                    
                TextColumn::make('description')
                    ->limit(50)
                    ->label('Description'),
                    
                TextColumn::make('icon')
                    ->label('Icon')
                    ->badge(),
                    
                ColorColumn::make('color')
                    ->label('Color'),
                    
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->label('Sort Order'),
                    
                TextColumn::make('documents_count')
                    ->counts('documents')
                    ->label('Documents'),
                    
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                    
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
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}
