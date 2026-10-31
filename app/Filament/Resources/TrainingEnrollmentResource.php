<?php

namespace App\Filament\Resources;

use App\Models\TrainingEnrollment;
use App\Models\Enums\NavigationGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\TrainingEnrollmentResource\Pages;
use App\Filament\Resources\TrainingEnrollmentResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class TrainingEnrollmentResource extends Resource
{
    protected static ?string $model = TrainingEnrollment::class;
    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::Operations;
    protected static ?string $navigationLabel = 'Training Enrollments';
    protected static ?int $navigationSort = 6;
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Enrollment Information')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pilot')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Select::make('course_id')
                            ->label('Training Course')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Select::make('status')
                            ->options([
                                'enrolled' => 'Enrolled',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'withdrawn' => 'Withdrawn',
                            ])
                            ->required()
                            ->default('enrolled'),
                        
                        TextInput::make('progress_percentage')
                            ->label('Progress %')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->suffix('%'),
                    ])
                    ->columns(2),
                
                Section::make('Assessment Results')
                    ->schema([
                        TextInput::make('final_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%'),
                        
                        DateTimePicker::make('completed_at')
                            ->label('Completion Date'),
                        
                        Textarea::make('instructor_notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('user.name')
                    ->label('Pilot')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                
                \Filament\Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                \Filament\Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'enrolled',
                        'primary' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'warning' => 'withdrawn',
                    ]),
                
                \Filament\Tables\Columns\TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->suffix('%')
                    ->alignCenter()
                    ->sortable(),
                
                \Filament\Tables\Columns\TextColumn::make('final_score')
                    ->label('Final Score')
                    ->suffix('%')
                    ->alignCenter()
                    ->sortable(),
                
                \Filament\Tables\Columns\TextColumn::make('enrolled_at')
                    ->label('Enrolled')
                    ->dateTime()
                    ->sortable(),
                
                \Filament\Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'enrolled' => 'Enrolled',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'withdrawn' => 'Withdrawn',
                    ]),
                
                \Filament\Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('enrolled_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProgressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingEnrollments::route('/'),
            'create' => Pages\CreateTrainingEnrollment::route('/create'),
            'edit' => Pages\EditTrainingEnrollment::route('/{record}/edit'),
            'view' => Pages\ViewTrainingEnrollment::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'in_progress')->count();
    }
}