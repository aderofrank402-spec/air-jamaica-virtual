<?php

namespace App\Filament\Resources\NotamResource\Pages;

use App\Filament\Resources\NotamResource;
use Filament\Resources\Pages\EditRecord;

class EditNotam extends EditRecord
{
    protected static string $resource = NotamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ViewAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}