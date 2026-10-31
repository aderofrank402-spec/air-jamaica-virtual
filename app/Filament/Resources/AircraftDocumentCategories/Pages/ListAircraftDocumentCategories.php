<?php

namespace App\Filament\Resources\AircraftDocumentCategories\Pages;

use App\Filament\Resources\AircraftDocumentCategories\AircraftDocumentCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAircraftDocumentCategories extends ListRecords
{
    protected static string $resource = AircraftDocumentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
