<?php

namespace App\Filament\Resources\NotamResource\Pages;

use App\Filament\Resources\NotamResource;
use Filament\Resources\Pages\ListRecords;

class ListNotams extends ListRecords
{
    protected static string $resource = NotamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}