<?php

namespace App\Filament\Resources\AircraftDocumentCategories;

use App\Filament\Resources\AircraftDocumentCategories\Pages\CreateAircraftDocumentCategory;
use App\Filament\Resources\AircraftDocumentCategories\Pages\EditAircraftDocumentCategory;
use App\Filament\Resources\AircraftDocumentCategories\Pages\ListAircraftDocumentCategories;
use App\Filament\Resources\AircraftDocumentCategories\Schemas\AircraftDocumentCategoryForm;
use App\Filament\Resources\AircraftDocumentCategories\Tables\AircraftDocumentCategoriesTable;
use App\Models\AircraftDocumentCategory;
use App\Models\Enums\NavigationGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AircraftDocumentCategoryResource extends Resource
{
    protected static ?string $model = AircraftDocumentCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::AircraftManagement;
    
    protected static ?string $navigationLabel = 'Document Categories';

    public static function form(Schema $schema): Schema
    {
        return AircraftDocumentCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AircraftDocumentCategoriesTable::configure($table);
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
            'index' => ListAircraftDocumentCategories::route('/'),
            'create' => CreateAircraftDocumentCategory::route('/create'),
            'edit' => EditAircraftDocumentCategory::route('/{record}/edit'),
        ];
    }
}
