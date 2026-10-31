<?php

namespace App\Filament\Resources\AircraftDocumentCategories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AircraftDocumentCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Category Name'),
                    
                TextInput::make('slug')
                    ->maxLength(255)
                    ->label('Slug'),
                    
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
                    
                TextInput::make('icon')
                    ->label('Icon Class')
                    ->maxLength(100)
                    ->helperText('CSS icon class (e.g., fa-file-pdf, heroicon-o-document)'),
                    
                ColorPicker::make('color')
                    ->label('Color')
                    ->helperText('Category color for UI display'),
                    
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->label('Sort Order')
                    ->helperText('Lower numbers appear first'),
                    
                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),
            ]);
    }
}
