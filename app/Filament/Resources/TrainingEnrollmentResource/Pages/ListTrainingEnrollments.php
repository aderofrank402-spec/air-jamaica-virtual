<?php

namespace App\Filament\Resources\TrainingEnrollmentResource\Pages;

use App\Filament\Resources\TrainingEnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingEnrollments extends ListRecords
{
    protected static string $resource = TrainingEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}