<?php

namespace App\Filament\Resources\TrainingModuleResource\Pages;

use App\Filament\Resources\TrainingModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingModule extends EditRecord
{
    protected static string $resource = TrainingModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}