<?php

namespace App\Filament\Resources\AircraftDocuments\Pages;

use App\Filament\Resources\AircraftDocuments\AircraftDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAircraftDocument extends EditRecord
{
    protected static string $resource = AircraftDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
