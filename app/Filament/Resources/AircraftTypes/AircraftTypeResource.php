<?php

namespace App\Filament\Resources\AircraftTypes;

use App\Filament\Resources\AircraftTypes\Pages\CreateAircraftType;
use App\Filament\Resources\AircraftTypes\Pages\EditAircraftType;
use App\Filament\Resources\AircraftTypes\Pages\ListAircraftTypes;
use App\Filament\Resources\AircraftTypes\Schemas\AircraftTypeForm;
use App\Filament\Resources\AircraftTypes\Tables\AircraftTypesTable;
use App\Models\AircraftType;
use App\Models\Enums\NavigationGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AircraftTypeResource extends Resource
{
    protected static ?string $model = AircraftType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::AircraftManagement;
    
    protected static ?string $navigationLabel = 'Aircraft Types';

    public static function form(Schema $schema): Schema
    {
        return AircraftTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AircraftTypesTable::configure($table);
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
            'index' => ListAircraftTypes::route('/'),
            'create' => CreateAircraftType::route('/create'),
            'edit' => EditAircraftType::route('/{record}/edit'),
        ];
    }
}
