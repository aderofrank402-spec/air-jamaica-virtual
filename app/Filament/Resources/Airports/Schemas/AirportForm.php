<?php

namespace App\Filament\Resources\Airports\Schemas;

use App\Filament\Resources\Airports\Actions\LookupAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use League\ISO3166\ISO3166;

class AirportForm
{
    /**
     * Get all timezones as a flat array
     */
    protected static function getAllTimezones(): array
    {
        // Get all timezones directly from all continents to ensure complete list
        $continents = [
            'Africa'     => \DateTimeZone::AFRICA,
            'America'    => \DateTimeZone::AMERICA,
            'Antarctica' => \DateTimeZone::ANTARCTICA,
            'Arctic'     => \DateTimeZone::ARCTIC,
            'Asia'       => \DateTimeZone::ASIA,
            'Atlantic'   => \DateTimeZone::ATLANTIC,
            'Australia'  => \DateTimeZone::AUSTRALIA,
            'Europe'     => \DateTimeZone::EUROPE,
            'Indian'     => \DateTimeZone::INDIAN,
            'Pacific'    => \DateTimeZone::PACIFIC,
        ];
        
        $flattened = [];
        
        // Add GMT and UTC first
        $flattened['GMT'] = 'GMT timezone';
        $flattened['UTC'] = 'UTC timezone';
        
        // Process all continents - ensure all are included
        foreach ($continents as $continent => $mask) {
            $timezones = \DateTimeZone::listIdentifiers($mask);
            if (is_array($timezones)) {
                foreach ($timezones as $timezone) {
                    try {
                        $time = new \DateTimeImmutable('now', new \DateTimeZone($timezone));
                        $offset = $time->format('P');
                        $offset = str_replace(['-', '+'], [' &minus; ', ' &plus; '], $offset);
                        $tzName = substr($timezone, strlen($continent) + 1);
                        $tzName = str_replace(['St_', '_'], ['St. ', ' '], $tzName);
                        $label = '(GMT/UTC' . $offset . ')&nbsp;&nbsp;&nbsp;&nbsp;' . $tzName;
                        $flattened[$timezone] = '[' . $continent . '] ' . $label;
                    } catch (\Exception $e) {
                        // Skip invalid timezones
                        continue;
                    }
                }
            }
        }
        
        // Sort by display label (continent + timezone name) for better distribution
        uasort($flattened, function($a, $b) {
            return strcmp($a, $b);
        });
        
        // Return flat array with all timezones from all continents
        return $flattened;
    }

    public static function configure(Schema $schema): Schema
    {

        return $schema
            ->components([
                Section::make(__('filament.airport_informations'))
                    ->schema([
                        TextInput::make('icao')
                            ->label('ICAO')
                            ->required()
                            ->string()
                            ->length(4)
                            ->columnSpan(2)
                            ->hintAction(LookupAction::make()),

                        TextInput::make('iata')
                            ->label('IATA')
                            ->string()
                            ->length(3)
                            ->columnSpan(2),

                        TextInput::make('name')
                            ->label(__('common.name'))
                            ->required()
                            ->string(),

                        TextInput::make('lat')
                            ->label(__('airports.latitude'))
                            ->required()
                            ->minValue(-90)
                            ->maxValue(90)
                            ->numeric(),

                        TextInput::make('lon')
                            ->label(__('airports.longitude'))
                            ->required()
                            ->minValue(-180)
                            ->maxValue(180)
                            ->numeric(),

                        TextInput::make('elevation')
                            ->hint(__('common.in_feet'))
                            ->label(__('airports.elevation'))
                            ->numeric(),

                        Select::make('country')
                            ->label(label: __('common.country'))
                            ->options(collect((new ISO3166())->all())->mapWithKeys(fn (array $item, string $key) => [strtolower($item['alpha2']) => str_replace('&bnsp;', ' ', $item['name'])]))
                            ->searchable()
                            ->native(false),

                        TextInput::make('location')
                            ->label(__('user.location'))
                            ->string(),

                        TextInput::make('region')
                            ->label(__('airports.region'))
                            ->string(),

                        Select::make('timezone')
                            ->label(__(key: 'common.timezone'))
                            ->options(self::getAllTimezones())
                            ->searchable()
                            ->allowHtml()
                            ->native(false),

                        TextInput::make('ground_handling_cost')
                            ->label(__('airports.ground_handling_cost'))
                            ->helperText(__('airports.ground_handling_cost_helper'))
                            ->numeric(),

                        TextInput::make('fuel_jeta_cost')
                            ->label(__('airports.fuel_jeta_cost'))
                            ->helperText(__('airports.cost_per_lbs'))
                            ->numeric(),

                        TextInput::make('fuel_100ll_cost')
                            ->label(__('airports.fuel_100ll_cost'))
                            ->helperText(__('airports.cost_per_lbs'))
                            ->numeric(),

                        TextInput::make('fuel_mogas_cost')
                            ->label(__('airports.fuel_mogas_cost'))
                            ->helperText(__('airports.cost_per_lbs'))
                            ->numeric(),

                        RichEditor::make('notes')
                            ->label(trans_choice('common.notes', 2))
                            ->columnSpan(4),

                        Toggle::make('hub')
                            ->label(__('airports.hub'))
                            ->offIcon(Heroicon::XCircle)
                            ->offColor('danger')
                            ->onIcon(Heroicon::CheckCircle)
                            ->onColor('success'),
                    ])
                    ->columnSpanFull()
                    ->columns(4),
            ]);
    }
}
