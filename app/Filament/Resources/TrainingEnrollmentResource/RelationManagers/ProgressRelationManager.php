<?php

namespace App\Filament\Resources\TrainingEnrollmentResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'progress';

    protected static ?string $recordTitleAttribute = 'module.title';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('module_id')
                    ->label('Module')
                    ->relationship('module', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Select::make('status')
                    ->options([
                        'not_started' => 'Not Started',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->required()
                    ->default('not_started'),
                
                TextInput::make('progress_percentage')
                    ->label('Progress %')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(0)
                    ->suffix('%'),
                
                TextInput::make('score')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),
                
                TextInput::make('time_spent_minutes')
                    ->label('Time Spent (minutes)')
                    ->numeric()
                    ->minValue(0)
                    ->suffix(' min'),
                
                DateTimePicker::make('started_at')
                    ->label('Started At'),
                
                DateTimePicker::make('completed_at')
                    ->label('Completed At'),
                
                Toggle::make('is_passed')
                    ->label('Passed')
                    ->default(false),
                
                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('module.title')
            ->columns([
                Tables\Columns\TextColumn::make('module.title')
                    ->label('Module')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'not_started',
                        'primary' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),
                
                Tables\Columns\TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->suffix('%')
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('score')
                    ->suffix('%')
                    ->alignCenter(),
                
                Tables\Columns\IconColumn::make('is_passed')
                    ->boolean()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('time_spent_minutes')
                    ->label('Time Spent')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        $hours = floor($state / 60);
                        $minutes = $state % 60;
                        return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
                    }),
                
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Started')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'not_started' => 'Not Started',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_passed')
                    ->label('Passed'),
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
            ->defaultSort('started_at', 'desc');
    }
}