<?php

namespace App\Filament\Resources;

use App\Models\TrainingModule;
use App\Models\Enums\NavigationGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\TrainingModuleResource\Pages;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class TrainingModuleResource extends Resource
{
    protected static ?string $model = TrainingModule::class;
    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::Operations;
    protected static ?string $navigationLabel = 'Training Modules';
    protected static ?int $navigationSort = 7;
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Module Information')
                    ->schema([
                        Select::make('course_id')
                            ->label('Training Course')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->helperText('Auto-generated from title if left empty'),
                        
                        Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Select::make('type')
                            ->options([
                                'reading' => 'Reading Material',
                                'video' => 'Video Content',
                                'interactive' => 'Interactive Content',
                                'assessment' => 'Assessment',
                                'simulation' => 'Simulation',
                            ])
                            ->required(),
                        
                        TextInput::make('estimated_minutes')
                            ->numeric()
                            ->suffix('minutes')
                            ->label('Estimated Duration'),
                        
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(1)
                            ->label('Sort Order'),
                    ])
                    ->columns(2),
                
                Section::make('Module Content')
                    ->schema([
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                        
                        Textarea::make('resources')
                            ->label('Additional Resources')
                            ->helperText('JSON format for additional resources like videos, links, etc.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                
                Section::make('Module Settings')
                    ->schema([
                        Toggle::make('is_required')
                            ->default(true)
                            ->helperText('Mark as required for course completion'),
                        
                        Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Enable this module for students'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                
                \Filament\Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                
                \Filament\Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'reading',
                        'success' => 'video',
                        'warning' => 'interactive',
                        'danger' => 'assessment',
                        'secondary' => 'simulation',
                    ]),
                
                \Filament\Tables\Columns\TextColumn::make('estimated_minutes')
                    ->suffix(' min')
                    ->alignCenter()
                    ->sortable(),
                
                \Filament\Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->alignCenter()
                    ->sortable(),
                
                \Filament\Tables\Columns\IconColumn::make('is_required')
                    ->boolean()
                    ->alignCenter(),
                
                \Filament\Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->alignCenter(),
                
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title'),
                
                \Filament\Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'reading' => 'Reading Material',
                        'video' => 'Video Content',
                        'interactive' => 'Interactive Content',
                        'assessment' => 'Assessment',
                        'simulation' => 'Simulation',
                    ]),
                
                \Filament\Tables\Filters\TernaryFilter::make('is_required')
                    ->label('Required'),
                
                \Filament\Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
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
            ->defaultSort('course_id', 'asc')
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingModules::route('/'),
            'create' => Pages\CreateTrainingModule::route('/create'),
            'edit' => Pages\EditTrainingModule::route('/{record}/edit'),
            'view' => Pages\ViewTrainingModule::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
}