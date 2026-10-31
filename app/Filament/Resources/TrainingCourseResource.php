<?php

namespace App\Filament\Resources;

use App\Models\TrainingCourse;
use App\Models\Enums\NavigationGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\TrainingCourseResource\Pages;
use App\Filament\Resources\TrainingCourseResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class TrainingCourseResource extends Resource
{
    protected static ?string $model = TrainingCourse::class;
    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::Operations;
    protected static ?string $navigationLabel = 'Training Courses';
    protected static ?int $navigationSort = 5;
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Course Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Select::make('category')
                            ->options([
                                'basic' => 'Basic Training',
                                'type_rating' => 'Type Rating',
                                'recurrent' => 'Recurrent Training',
                                'emergency' => 'Emergency Procedures',
                                'ground_school' => 'Ground School',
                            ])
                            ->required(),
                        
                        Select::make('difficulty_level')
                            ->options([
                                'beginner' => 'Beginner',
                                'intermediate' => 'Intermediate', 
                                'advanced' => 'Advanced',
                                'expert' => 'Expert',
                            ])
                            ->required(),
                        
                        TextInput::make('duration_hours')
                            ->numeric()
                            ->suffix('hours')
                            ->required(),
                        
                        TextInput::make('pass_score')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(80),
                    ])
                    ->columns(2),
                
                Section::make('Course Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Enable this course for enrollment'),
                        
                        Toggle::make('is_mandatory')
                            ->default(false)
                            ->helperText('Mark as mandatory for all pilots'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                
                \Filament\Tables\Columns\BadgeColumn::make('category')
                    ->colors([
                        'primary' => 'basic',
                        'success' => 'type_rating',
                        'warning' => 'recurrent',
                        'danger' => 'emergency',
                        'secondary' => 'ground_school',
                    ]),
                
                \Filament\Tables\Columns\BadgeColumn::make('difficulty_level')
                    ->colors([
                        'success' => 'beginner',
                        'primary' => 'intermediate',
                        'warning' => 'advanced',
                        'danger' => 'expert',
                    ]),
                
                \Filament\Tables\Columns\TextColumn::make('duration_hours')
                    ->suffix(' hrs')
                    ->alignCenter()
                    ->sortable(),
                
                \Filament\Tables\Columns\TextColumn::make('pass_score')
                    ->suffix('%')
                    ->alignCenter()
                    ->sortable(),
                
                \Filament\Tables\Columns\TextColumn::make('modules_count')
                    ->label('Modules')
                    ->counts('modules')
                    ->alignCenter()
                    ->sortable(),
                
                \Filament\Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->alignCenter(),
                
                \Filament\Tables\Columns\IconColumn::make('is_mandatory')
                    ->boolean()
                    ->alignCenter(),
                
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                \Filament\Tables\Columns\TextColumn::make('enrollments_count')
                    ->label('Enrolled')
                    ->counts('enrollments')
                    ->alignCenter(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'basic' => 'Basic Training',
                        'type_rating' => 'Type Rating',
                        'recurrent' => 'Recurrent Training',
                        'emergency' => 'Emergency Procedures',
                        'ground_school' => 'Ground School',
                    ]),
                
                \Filament\Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                
                \Filament\Tables\Filters\TernaryFilter::make('is_mandatory')
                    ->label('Mandatory'),
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
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingCourses::route('/'),
            'create' => Pages\CreateTrainingCourse::route('/create'),
            'edit' => Pages\EditTrainingCourse::route('/{record}/edit'),
            'view' => Pages\ViewTrainingCourse::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ModulesRelationManager::class,
            RelationManagers\EnrollmentsRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
}