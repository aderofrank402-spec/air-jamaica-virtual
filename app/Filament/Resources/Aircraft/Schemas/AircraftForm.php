<?php

namespace App\Filament\Resources\Aircraft\Schemas;

use App\Models\Airport;
use App\Models\Subfleet;
use App\Models\Enums\AircraftStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AircraftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('subfleet_id')
                    ->label('Subfleet')
                    ->options(Subfleet::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                    
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Aircraft Name'),
                    
                TextInput::make('registration')
                    ->required()
                    ->maxLength(10)
                    ->label('Registration'),
                    
                TextInput::make('icao')
                    ->maxLength(4)
                    ->label('ICAO Code'),
                    
                TextInput::make('iata')
                    ->maxLength(3)
                    ->label('IATA Code'),
                    
                TextInput::make('fin')
                    ->maxLength(10)
                    ->label('Fleet Number'),
                    
                TextInput::make('hex_code')
                    ->maxLength(6)
                    ->label('Hex Code'),
                    
                TextInput::make('selcal')
                    ->maxLength(4)
                    ->label('SELCAL Code'),
                    
                Select::make('airport_id')
                    ->label('Current Airport')
                    ->options(Airport::pluck('name', 'id'))
                    ->searchable(),
                    
                Select::make('hub_id')
                    ->label('Hub Airport')
                    ->options(Airport::pluck('name', 'id'))
                    ->searchable(),
                    
                TextInput::make('dow')
                    ->numeric()
                    ->label('Dry Operating Weight (kg)'),

                TextInput::make('mtow')
                    ->numeric()
                    ->label('Maximum Takeoff Weight (kg)'),

                TextInput::make('mlw')
                    ->numeric()
                    ->label('Maximum Landing Weight (kg)'),

                TextInput::make('zfw')
                    ->numeric()
                    ->label('Zero Fuel Weight (kg)'),

                TextInput::make('fuel_onboard')
                    ->numeric()
                    ->label('Fuel Onboard (lbs)'),

                TextInput::make('simbrief_type')
                    ->maxLength(50)
                    ->label('SimBrief Aircraft Type'),
                    
                Select::make('status')
                    ->label('Status')
                    ->options(AircraftStatus::class)
                    ->default(AircraftStatus::ACTIVE)
                    ->required(),
                    
                TextInput::make('flight_time')
                    ->numeric()
                    ->label('Total Flight Time (hours)')
                    ->step(0.1),
            ]);
    }
}
