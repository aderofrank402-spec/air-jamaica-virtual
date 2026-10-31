<?php

namespace App\Filament\Resources\NotamResource\Pages;

use App\Filament\Resources\NotamResource;
use Filament\Resources\Pages\ViewRecord;

class ViewNotam extends ViewRecord
{
    protected static string $resource = NotamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}