@php
    $record = $getRecord();
    $state = $record->state;
    $status = $record->status;
    $source = $record->source;
    $sourceName = $record->source_name;
    $ident = $record->ident;
    $airline = $record->airline;
    $aircraft = $record->aircraft;
    $dptAirport = $record->dpt_airport;
    $arrAirport = $record->arr_airport;
    $dptIcao = $dptAirport?->icao ?? $record->dpt_airport_id ?? '???';
    $arrIcao = $arrAirport?->icao ?? $record->arr_airport_id ?? '???';
    $dptName = $dptAirport?->name ?? 'Departure';
    $arrName = $arrAirport?->name ?? 'Arrival';
    $stateColor = match ($state) { \App\Models\Enums\PirepState::PENDING => '#ffcc00', \App\Models\Enums\PirepState::ACCEPTED => '#00ff88', \App\Models\Enums\PirepState::REJECTED => '#ff4444', default => '#00d4ff'};
    $stateLabel = \App\Models\Enums\PirepState::label($state);
    $statusLabel = \App\Models\Enums\PirepStatus::label($status);
    $flightTimeStr = $record->flight_time ? \App\Support\Units\Time::minutesToTimeString($record->flight_time) : '00:00';
    $plannedTimeStr = $record->planned_flight_time ? \App\Support\Units\Time::minutesToTimeString($record->planned_flight_time) : '00:00';
    $landingRateStr = $record->landing_rate ? number_format($record->landing_rate, 0) . ' fpm' : 'N/A';
    $fuelUsedStr = $record->fuel_used ? number_format($record->fuel_used->local(0)) . ' ' . setting('units.fuel', 'lbs') : 'N/A';
    $distanceVal = $record->distance ? $record->distance->local(0) : 0;
    $scoreValue = $record->score ?? 100;
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div id="pirep-hero-root-{{ $record->id }}" {{ $getExtraAttributeBag() }}>
        <style>
            .pirep-hero {
                position: relative;
                width: 100%;
                background: #0a0a0a;
                border-radius: 1rem;
                overflow: hidden;
                border: 1px solid rgba(255, 255, 255, 0.05);
                color: white;
                font-family: sans-serif;
                min-height: 500px;
            }

            .hero-glow {
                position: absolute;
                inset: -50%;
                background: radial-gradient(circle at 30% 20%, rgba(6, 126, 193, 0.15) 0%, transparent 40%);
                pointer-events: none;
            }

            .hero-content {
                position: relative;
                z-index: 2;
                padding: 2.5rem;
                display: flex;
                flex-direction: column;
                gap: 2rem;
            }

            .tactical-badge {
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                padding: 0.4rem 0.8rem;
                border-radius: 2rem;
                font-size: 0.7rem;
                font-weight: 700;
                text-transform: uppercase;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .icao {
                font-size: 2.5rem;
                font-weight: 800;
                color: white;
            }

            .path-line {
                flex-grow: 1;
                height: 2px;
                background: linear-gradient(90deg, rgba(6, 126, 193, 0.5), rgba(0, 166, 81, 0.5));
                position: relative;
                min-width: 100px;
            }

            .path-line::after {
                content: '✈';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(90deg);
                color: #00d4ff;
            }

            .quick-metrics {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                gap: 1rem;
                background: rgba(255, 255, 255, 0.03);
                padding: 1.5rem;
                border-radius: 0.75rem;
            }
        </style>
        <div class="pirep-hero">
            <div class="hero-glow"></div>
            <div class="hero-content">
                <div style="display: flex; gap: 0.75rem;">
                    <div class="tactical-badge" style="color: #60a5fa; border-color: rgba(96, 165, 250, 0.3);">
                        {{ \App\Models\Enums\PirepSource::label($source) }}
                    </div>
                    <div class="tactical-badge" style="color: {{ $stateColor }}; border-color: {{ $stateColor }}44;">
                        {{ $stateLabel }} / {{ $statusLabel }}
                    </div>
                    <div class="tactical-badge" style="color: #4ade80;">SCORE: {{ $scoreValue }}%</div>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                    <div class="callsign-box">
                        <h1 style="font-size: 4rem; font-weight: 900; margin: 0; color: #fff;">{{ $ident }}</h1>
                        <p style="font-size: 0.9rem; color: #64748b; margin: 0.5rem 0 0 0; letter-spacing: 0.2em;">
                            {{ $airline->name ?? 'AIR JAMAICA' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div style="font-size: 1.5rem; font-weight: 900;">{{ $aircraft->registration ?? 'N/A' }}</div>
                        <div style="font-size: 0.7rem; color: #60a5fa; font-weight: bold;">
                            {{ $aircraft->name ?? 'UNKNOWN ASSET' }}
                        </div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 2rem; padding: 1rem 0;">
                    <div style="display: flex; flex-direction: column;">
                        <span class="icao">{{ $dptIcao }}</span>
                        <span style="font-size: 0.75rem; color: #64748b;">{{ $dptName }}</span>
                    </div>
                    <div class="path-line"></div>
                    <div style="display: flex; flex-direction: column; text-align: right;">
                        <span class="icao">{{ $arrIcao }}</span>
                        <span style="font-size: 0.75rem; color: #64748b;">{{ $arrName }}</span>
                    </div>
                </div>
                <div class="quick-metrics">
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase;">Duration</span>
                        <span style="font-weight: bold;">{{ $flightTimeStr }}</span>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase;">Landing</span>
                        <span style="font-weight: bold; color: #4ade80;">{{ $landingRateStr }}</span>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase;">Fuel</span>
                        <span style="font-weight: bold;">{{ $fuelUsedStr }}</span>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase;">Distance</span>
                        <span style="font-weight: bold;">{{ number_format($distanceVal) }} NM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>