<?php

namespace App\Filament\Resources\TrainingEnrollmentResource\Pages;

use App\Filament\Resources\TrainingEnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTrainingEnrollment extends ViewRecord
{
    protected static string $resource = TrainingEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}