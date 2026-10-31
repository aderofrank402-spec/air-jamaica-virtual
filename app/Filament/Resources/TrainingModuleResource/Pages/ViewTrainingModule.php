<?php

namespace App\Filament\Resources\TrainingModuleResource\Pages;

use App\Filament\Resources\TrainingModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTrainingModule extends ViewRecord
{
    protected static string $resource = TrainingModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}