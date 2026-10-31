<?php

namespace App\Filament\Resources\AircraftDocuments\Schemas;

use App\Models\AircraftType;
use App\Models\AircraftDocumentCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AircraftDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('aircraft_type_id')
                    ->label('Aircraft Type')
                    ->options(AircraftType::where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                    
                Select::make('category_id')
                    ->label('Document Category')
                    ->options(AircraftDocumentCategory::where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                    
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Document Title'),
                    
                TextInput::make('slug')
                    ->maxLength(255)
                    ->label('Slug'),
                    
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
                    
                FileUpload::make('file_path')
                    ->label('Document File')
                    ->directory('aircraft-documents')
                    ->acceptedFileTypes(['pdf', 'doc', 'docx', 'txt'])
                    ->maxSize(10240), // 10MB
                    
                TextInput::make('version')
                    ->label('Version')
                    ->maxLength(50),
                    
                DatePicker::make('effective_date')
                    ->label('Effective Date'),
                    
                DatePicker::make('expiry_date')
                    ->label('Expiry Date'),
                    
                Select::make('access_level')
                    ->label('Access Level')
                    ->options([
                        'public' => 'Public',
                        'registered' => 'Registered Users',
                        'pilot' => 'Pilots Only',
                        'instructor' => 'Instructors Only',
                        'admin' => 'Administrators Only',
                    ])
                    ->default('public'),
                    
                Toggle::make('is_featured')
                    ->label('Featured Document'),
                    
                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),
            ]);
    }
}
