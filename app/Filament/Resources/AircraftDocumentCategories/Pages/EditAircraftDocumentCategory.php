<?php

namespace App\Filament\Resources\AircraftDocumentCategories\Pages;

use App\Filament\Resources\AircraftDocumentCategories\AircraftDocumentCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAircraftDocumentCategory extends EditRecord
{
    protected static string $resource = AircraftDocumentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
