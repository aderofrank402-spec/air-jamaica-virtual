<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroup implements HasLabel
{
    case Config;
    case Operations;
    case Modules;
    case Developers;
    case AircraftManagement;
    case Shield;

    public function getLabel(): string
    {
        return match ($this) {
            self::Config             => __('filament.config'),
            self::Operations         => __('filament.operations'),
            self::Modules            => __('filament.modules'),
            self::Developers         => __('filament.developers'),
            self::AircraftManagement => __('filament.aircraft_management'),
            self::Shield             => __('filament.filament_shield'),
        };
    }
}
