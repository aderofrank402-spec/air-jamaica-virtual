<?php

namespace App\Filament\Resources\TrainingCourseResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                
                Textarea::make('description')
                    ->required()
                    ->rows(3),
                
                TextInput::make('order_index')
                    ->label('Module Order')
                    ->numeric()
                    ->default(fn () => $this->getOwnerRecord()->modules()->count() + 1)
                    ->helperText('Order in which this module appears in the course'),
                
                TextInput::make('duration_minutes')
                    ->label('Duration (minutes)')
                    ->numeric()
                    ->suffix('min')
                    ->required(),
                
                Textarea::make('content')
                    ->label('Module Content')
                    ->rows(6)
                    ->columnSpanFull(),
                
                Textarea::make('objectives')
                    ->label('Learning Objectives')
                    ->rows(3)
                    ->columnSpanFull(),
                
                Toggle::make('is_active')
                    ->default(true),
                
                Toggle::make('is_mandatory')
                    ->default(true),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('order_index')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->suffix(' min')
                    ->alignCenter(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->alignCenter(),
                
                Tables\Columns\IconColumn::make('is_mandatory')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
                
                Tables\Filters\TernaryFilter::make('is_mandatory')
                    ->label('Mandatory'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order_index');
    }
}