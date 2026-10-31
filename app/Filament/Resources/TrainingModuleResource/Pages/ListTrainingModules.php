<?php

namespace App\Filament\Resources\TrainingModuleResource\Pages;

use App\Filament\Resources\TrainingModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingModules extends ListRecords
{
    protected static string $resource = TrainingModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}