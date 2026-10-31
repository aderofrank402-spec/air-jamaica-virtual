<?php

namespace App\Filament\Resources\AircraftDocuments\Pages;

use App\Filament\Resources\AircraftDocuments\AircraftDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAircraftDocuments extends ListRecords
{
    protected static string $resource = AircraftDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
