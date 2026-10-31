<?php

namespace App\Filament\Resources\AircraftTypes\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AircraftTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Aircraft Type Name'),
                    
                TextInput::make('slug')
                    ->maxLength(255)
                    ->label('Slug'),
                    
                TextInput::make('manufacturer')
                    ->required()
                    ->maxLength(255)
                    ->label('Manufacturer'),
                    
                TextInput::make('model')
                    ->required()
                    ->maxLength(255)
                    ->label('Model'),
                    
                TextInput::make('variant')
                    ->maxLength(255)
                    ->label('Variant'),
                    
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
                    
                TextInput::make('image_url')
                    ->url()
                    ->maxLength(255)
                    ->label('Image URL'),
                    
                Textarea::make('specifications')
                    ->label('Specifications (JSON)')
                    ->rows(4)
                    ->helperText('Enter specifications in JSON format'),
                    
                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),
            ]);
    }
}
