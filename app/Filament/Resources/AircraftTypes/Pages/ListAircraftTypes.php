<?php

namespace App\Filament\Resources\AircraftTypes\Pages;

use App\Filament\Resources\AircraftTypes\AircraftTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAircraftTypes extends ListRecords
{
    protected static string $resource = AircraftTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
