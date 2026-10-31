<?php

namespace App\Filament\Resources\Aircraft;

use App\Filament\Resources\Aircraft\Pages\CreateAircraft;
use App\Filament\Resources\Aircraft\Pages\EditAircraft;
use App\Filament\Resources\Aircraft\Pages\ListAircraft;
use App\Filament\Resources\Aircraft\Tables\AircraftTable;
use App\Models\Aircraft;
use App\Models\Enums\NavigationGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class AircraftResource extends Resource
{
    protected static ?string $model = Aircraft::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::AircraftManagement;
    
    protected static ?string $navigationLabel = 'Aircraft';

    public static function form(Schema $schema): Schema
    {
        return AircraftForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AircraftTable::configure($table);
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
            'index' => ListAircraft::route('/'),
            'create' => CreateAircraft::route('/create'),
            'edit' => EditAircraft::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
