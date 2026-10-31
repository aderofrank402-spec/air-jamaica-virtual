<?php

namespace App\Filament\Resources\Pireps\Pages;

use App\Filament\Resources\Pireps\Actions\AcceptAction;
use App\Filament\Resources\Pireps\Actions\RejectAction;
use App\Filament\Resources\Pireps\PirepResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPirep extends ViewRecord
{
    protected static string $resource = PirepResource::class;

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Flight ' . $this->record->ident;
    }


    protected function getHeaderActions(): array
    {
        return [
            // Reuse the same PirepService::changeState()-backed actions as the PIREPs table so
            // accepting/rejecting from this page runs the full side-effect chain (flight time,
            // rank, aircraft state, curr_airport_id, PirepAccepted event) instead of a raw state write.
            AcceptAction::make()->requiresConfirmation(),
            RejectAction::make()->requiresConfirmation(),

            Actions\EditAction::make(),
        ];
    }

    protected function resolveRecord(int|string $record): \App\Models\Pirep
    {
        /** @var \App\Models\Pirep $pirep */
        $pirep = parent::resolveRecord($record);

        $pirep->load([
            'position',
            'acars',
            'acars_logs',
            'acars_route',
            'dpt_airport',
            'arr_airport',
            'alt_airport',
            'user',
            'airline',
            'aircraft',
        ]);

        return $pirep;
    }
}
