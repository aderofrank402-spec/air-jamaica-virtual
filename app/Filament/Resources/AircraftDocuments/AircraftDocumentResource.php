<?php

namespace App\Filament\Resources\AircraftDocuments;

use App\Filament\Resources\AircraftDocuments\Pages\CreateAircraftDocument;
use App\Filament\Resources\AircraftDocuments\Pages\EditAircraftDocument;
use App\Filament\Resources\AircraftDocuments\Pages\ListAircraftDocuments;
use App\Filament\Resources\AircraftDocuments\Schemas\AircraftDocumentForm;
use App\Filament\Resources\AircraftDocuments\Tables\AircraftDocumentsTable;
use App\Models\AircraftDocument;
use App\Models\Enums\NavigationGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AircraftDocumentResource extends Resource
{
    protected static ?string $model = AircraftDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::AircraftManagement;
    
    protected static ?string $navigationLabel = 'Aircraft Documents';

    public static function form(Schema $schema): Schema
    {
        return AircraftDocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AircraftDocumentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAircraftDocuments::route('/'),
            'create' => CreateAircraftDocument::route('/create'),
            'edit' => EditAircraftDocument::route('/{record}/edit'),
        ];
    }
}
