@extends('layouts.seven.public_app')
@section('title', 'PIREP '.$pirep->ident)

@php
use App\Models\Acars;
use App\Models\Enums\AcarsType;
use App\Models\Enums\PirepState;

// Pull FLIGHT_PATH ACARS for telemetry chart
$telemetry = Acars::where('pirep_id', $pirep->id)
    ->where('type', AcarsType::FLIGHT_PATH)
    ->orderBy('created_at', 'asc')
    ->get();

$chartData = $telemetry->map(function($p) {
    $alt = $p->altitude_msl ?? 0;
    return [
        'alt'  => (int)$alt,
        'gs'   => (int)($p->gs ?? 0),
        'vs'   => (int)($p->vs ?? 0),
        'time' => $p->created_at ? $p->created_at->format('H:i') : '',
    ];
})->values();

$stateMap = [
    PirepState::IN_PROGRESS => ['label' => 'IN PROGRESS', 'color' => '#fbbf24'],
    PirepState::PENDING     => ['label' => 'PENDING',     'color' => '#f97316'],
    PirepState::ACCEPTED    => ['label' => 'ACCEPTED',    'color' => '#10b981'],
    PirepState::CANCELLED   => ['label' => 'CANCELLED',   'color' => '#6b7280'],
    PirepState::DELETED     => ['label' => 'DELETED',     'color' => '#6b7280'],
    PirepState::DRAFT       => ['label' => 'DRAFT',       'color' => '#94a3b8'],
    PirepState::REJECTED    => ['label' => 'REJECTED',    'color' => '#ef4444'],
    PirepState::PAUSED      => ['label' => 'PAUSED',      'color' => '#fbbf24'],
];
$stInfo = $stateMap[$pirep->state] ?? ['label' => 'UNKNOWN', 'color' => '#6b7280'];

$flightMins  = $pirep->flight_time ?? 0;
$flightDur   = floor($flightMins/60).'h '.str_pad($flightMins%60,2,'0',STR_PAD_LEFT).'m';

// Distance — could be a Distance object or a plain number
$distNmi = $pirep->distance instanceof \App\Support\Units\Distance
    ? round($pirep->distance['nmi'])
    : (int)$pirep->distance;

// Fuel — same unit-wrapper pattern
$fuelUsed  = $pirep->fuel_used instanceof \App\Support\Units\Fuel
    ? round($pirep->fuel_used->local()) : (int)$pirep->fuel_used;
$fuelUnit  = $pirep->fuel_used instanceof \App\Support\Units\Fuel
    ? setting('units.fuel') : 'lbs';
$blockFuel = $pirep->block_fuel instanceof \App\Support\Units\Fuel
    ? round($pirep->block_fuel->local()) : (int)$pirep->block_fuel;

$landRate = $pirep->landing_rate ?? null;
$lrColor  = '#94a3b8';
if ($landRate !== null) {
    $absLR = abs($landRate);
    $lrColor = ($absLR >= 50 && $absLR <= 200) ? '#10b981' : ($absLR > 350 ? '#ef4444' : '#fbbf24');
}

$scoreColor = '#94a3b8';
if ($pirep->score > 0) {
    $scoreColor = $pirep->score >= 80 ? '#10b981' : ($pirep->score >= 60 ? '#fbbf24' : '#ef4444');
}
@endphp

@section('css')
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
:root {
    --em:  #10b981;
    --em2: rgba(16,185,129,0.12);
    --em3: rgba(16,185,129,0.28);
    --pan: rgba(2,6,23,0.88);
    --bd:  rgba(255,255,255,0.07);
    --amb: #fbbf24;
    --red: #ef4444;
    --mono: 'JetBrains Mono','Courier New',monospace;
}
body {
    background: linear-gradient(160deg,#020617 0%,#091528 55%,#010b1a 100%) !important;
    background-attachment: fixed;
    min-height: 100vh;
    color: #e2e8f0;
    padding-top: 0 !important;
    font-family: var(--mono) !important;
    font-size: 16px;
}

/* PAGE */
.pirep-page { max-width: 1280px; margin: 0 auto; padding: 1.5rem 1rem 4rem; }

/* MISSION HEADER */
.mission-header {
    background: linear-gradient(135deg, rgba(16,185,129,0.07) 0%, rgba(2,6,23,0.95) 70%);
    border: 1px solid var(--em3);
    border-radius: 16px;
    padding: 2rem 2.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.mission-header::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent 0%, var(--em) 50%, transparent 100%);
}
.mh-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; }
.mh-ident { font-size: 2rem; font-weight: 900; color: #fff; line-height: 1; margin-bottom: 0.2rem; }
.mh-route {
    font-family: var(--mono); font-size: 1.35rem; font-weight: 800;
    color: var(--em); letter-spacing: 0.04em;
}
.mh-route .fa-chevron-right { font-size: 0.85rem; opacity: .5; margin: 0 0.5rem; }
.mh-date { font-size: 0.75rem; color: #e2e8f0; margin-top: 0.35rem; font-family: var(--mono); }
.status-badge {
    font-family: var(--mono); font-size: 0.68rem; font-weight: 800;
    padding: 0.4rem 0.9rem; border-radius: 6px; border: 1px solid currentColor;
    letter-spacing: 0.1em;
    align-self: flex-start;
}
.mh-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }
.mh-btn {
    font-size: 0.72rem; font-weight: 700; padding: 0.5rem 1rem;
    border-radius: 7px; text-decoration: none; letter-spacing: 0.06em;
    transition: all .18s; display: inline-flex; align-items: center; gap: 0.4rem;
    border: 1px solid var(--em3); color: var(--em); background: var(--em2);
}
.mh-btn:hover { background: rgba(16,185,129,0.22); color: #fff; text-decoration: none; border-color: var(--em); }
.mh-btn-submit { border-color: rgba(251,191,36,0.4); color: var(--amb); background: rgba(251,191,36,0.08); }
.mh-btn-submit:hover { background: rgba(251,191,36,0.18); color: #fff; border-color: var(--amb); }

/* STATS ROW */
.stats-row {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 1rem; margin-bottom: 1.5rem;
}
.stat-cell {
    background: var(--pan); border: 1px solid var(--bd);
    border-radius: 12px; padding: 1.1rem; text-align: center;
}
.stat-cell .val {
    font-family: var(--mono); font-size: 1.45rem; font-weight: 900;
    line-height: 1; margin-bottom: 0.28rem;
}
.stat-cell .lbl {
    font-size: 0.6rem; font-weight: 700;
    color: #e2e8f0; text-transform: uppercase; letter-spacing: 0.12em;
}

/* LAYOUT */
.content-grid {
    display: grid; grid-template-columns: 1fr 340px; gap: 1.25rem;
}

/* PANEL */
.panel {
    background: var(--pan); border: 1px solid var(--bd);
    border-radius: 12px; overflow: hidden; margin-bottom: 1.25rem;
}
.panel-hd {
    padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--bd);
    display: flex; align-items: center; gap: 0.55rem;
    font-size: 0.68rem; font-weight: 800;
    color: var(--em); letter-spacing: 0.14em; text-transform: uppercase;
}
.panel-bd { padding: 1.25rem; }

/* HUD ROWS */
.hud-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 0.77rem; padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.04);
}
.hud-row:last-child { border-bottom: none; }
.hud-row .lbl { color: #e2e8f0; font-size: 0.67rem; text-transform: uppercase; letter-spacing: 0.08em; }
.hud-row .val { font-family: var(--mono); color: #f8fafc; font-weight: 600; max-width: 60%; text-align: right; }

/* MAP */
#pirep-map { width: 100%; height: 420px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(255,255,255,0.08); }

/* TELEMETRY CHART */
.chart-wrap { position: relative; height: 240px; }

/* ACARS LOG */
.acars-log { list-style: none; padding: 0; margin: 0; }
.acars-entry {
    display: flex; gap: 0.9rem; align-items: flex-start;
    padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.04);
    font-size: 0.78rem;
}
.acars-entry:last-child { border-bottom: none; }
.acars-time {
    font-family: var(--mono); font-size: 0.65rem; color: var(--em);
    white-space: nowrap; padding-top: 0.05rem; min-width: 48px;
}
.acars-msg { color: #f1f5f9; line-height: 1.4; }

/* COMMENTS */
.comment-item {
    padding: 1rem; border-radius: 8px;
    background: rgba(255,255,255,0.02); margin-bottom: 0.75rem;
    border: 1px solid var(--bd);
}
.comment-author { font-size: 0.78rem; font-weight: 700; color: var(--em); margin-bottom: 0.2rem; }
.comment-meta { font-size: 0.65rem; color: #cbd5e1; margin-bottom: 0.5rem; font-family: var(--mono); }
.comment-body { font-size: 0.8rem; color: #f1f5f9; line-height: 1.5; }

/* ROUTE STRING */
.route-str {
    font-family: var(--mono); font-size: 0.72rem;
    color: #e2e8f0; line-height: 1.6; word-break: break-all;
}

/* FARES TABLE */
.fares-table { width: 100%; border-collapse: collapse; }
.fares-table th, .fares-table td {
    padding: 0.5rem 0.75rem; font-size: 0.75rem;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    text-align: left;
}
.fares-table th { color: var(--em); font-size: 0.65rem; text-transform: uppercase; letter-spacing: .1em; font-weight: 700; }
.fares-table td { color: #f1f5f9; }

/* RESPONSIVE */
@media (max-width: 960px) {
    .content-grid { grid-template-columns: 1fr; }
    .stats-row { grid-template-columns: repeat(2,1fr); }
    .mission-header { padding: 1.5rem; }
    .mh-ident { font-size: 1.5rem; }
}
</style>
@endsection

@section('content_header')@endsection

@section('content')
@php
$breadcrumbs = [
    ['title' => 'Pilot Center', 'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'), 'icon' => 'bi bi-speedometer2'],
    ['title' => 'Flight Logs',  'url' => route('frontend.pireps.index'), 'icon' => 'bi bi-file-earmark-text-fill'],
    ['title' => 'PIREP '.$pirep->ident, 'icon' => 'bi bi-file-earmark-check-fill'],
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

<div class="pirep-page">

    {{-- MISSION HEADER --}}
    <div class="mission-header">
        <div class="mh-top">
            <div>
                <div class="mh-ident">{{ $pirep->ident }}</div>
                <div class="mh-route">
                    {{ $pirep->dpt_airport_id }}
                    <i class="fas fa-chevron-right"></i>
                    {{ $pirep->arr_airport_id }}
                </div>
                <div class="mh-date">
                    {{ $pirep->submitted_at ? $pirep->submitted_at->format('d M Y · H:i \U\T\C') : 'Draft' }}
                    @if($pirep->user) &nbsp;&middot;&nbsp; {{ $pirep->user->name_private }} @endif
                </div>
            </div>
            <span class="status-badge" style="color:{{ $stInfo['color'] }};border-color:{{ $stInfo['color'] }}">
                {{ $stInfo['label'] }}
            </span>
        </div>
        <div class="mh-actions">
            <a href="{{ route('frontend.pireps.index') }}" class="mh-btn"><i class="fas fa-arrow-left"></i> All Logs</a>
            @if(!empty($pirep->simbrief))
                <a href="{{ url(route('frontend.simbrief.briefing', [$pirep->simbrief->id])) }}" class="mh-btn">
                    <i class="fas fa-file-alt"></i> SimBrief
                </a>
            @endif
            @if(!$pirep->read_only && $user && $pirep->user_id === $user->id)
                <a href="{{ route('frontend.pireps.edit', $pirep->id) }}" class="mh-btn">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @if($pirep->state !== \App\Models\Enums\PirepState::PENDING)
                    <form method="post" action="{{ route('frontend.pireps.submit', $pirep->id) }}" style="display:inline;">
                        @csrf
                        <button class="mh-btn mh-btn-submit" type="submit">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    {{-- STATS ROW --}}
    <div class="stats-row">
        <div class="stat-cell">
            <div class="val" style="color:#e2e8f0;">{{ $flightDur }}</div>
            <div class="lbl">Flight Time</div>
        </div>
        <div class="stat-cell">
            <div class="val" style="color:var(--amb);">{{ number_format($distNmi) }}</div>
            <div class="lbl">NM</div>
        </div>
        <div class="stat-cell">
            <div class="val" style="color:{{ $lrColor }};">
                {{ $landRate !== null ? $landRate.' fpm' : 'N/A' }}
            </div>
            <div class="lbl">Landing Rate</div>
        </div>
        <div class="stat-cell">
            <div class="val" style="color:{{ $scoreColor }};">
                {{ $pirep->score > 0 ? $pirep->score : 'N/A' }}
            </div>
            <div class="lbl">Score</div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="content-grid">

        {{-- LEFT: Map + Telemetry + ACARS Log --}}
        <div>
            {{-- MAP --}}
            <div class="panel">
                <div class="panel-hd"><i class="fas fa-map"></i> Flight Route</div>
                <div class="panel-bd" style="padding:0.75rem;">
                    <div id="pirep-map"></div>
                </div>
            </div>

            {{-- TELEMETRY CHART --}}
            @if($chartData->count() > 1)
            <div class="panel">
                <div class="panel-hd"><i class="fas fa-chart-area"></i> Telemetry</div>
                <div class="panel-bd">
                    <div class="chart-wrap">
                        <canvas id="telemetry-chart"></canvas>
                    </div>
                    <div style="display:flex;gap:1.5rem;margin-top:0.75rem;font-size:0.65rem;color:#e2e8f0;font-family:var(--mono);">
                        <span style="display:flex;align-items:center;gap:0.4rem;"><span style="width:12px;height:2px;background:#10b981;display:inline-block;border-radius:1px;"></span>ALTITUDE (FT)</span>
                        <span style="display:flex;align-items:center;gap:0.4rem;"><span style="width:12px;height:2px;background:#fbbf24;display:inline-block;border-radius:1px;"></span>GROUND SPEED (KT)</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- ACARS EVENT LOG --}}
            @if($pirep->acars_logs && $pirep->acars_logs->count() > 0)
            <div class="panel">
                <div class="panel-hd">
                    <i class="fas fa-list-alt"></i> ACARS Event Log
                    <span style="margin-left:auto;font-size:0.65rem;color:#e2e8f0;">
                        {{ $pirep->acars_logs->count() }} entries
                    </span>
                </div>
                <div class="panel-bd">
                    <ul class="acars-log">
                        @foreach($pirep->acars_logs as $log)
                        <li class="acars-entry">
                            <span class="acars-time">{{ $log->created_at ? $log->created_at->format('H:i') : '--:--' }}</span>
                            <span class="acars-msg">{{ $log->log }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- COMMENTS --}}
            @if($pirep->comments && $pirep->comments->count() > 0)
            <div class="panel">
                <div class="panel-hd"><i class="fas fa-comments"></i> Comments ({{ $pirep->comments->count() }})</div>
                <div class="panel-bd">
                    @foreach($pirep->comments->unique('id') as $comment)
                    <div class="comment-item">
                        <div class="comment-author">{{ $comment->user->name ?? 'Unknown' }}</div>
                        <div class="comment-meta">
                            {{ $comment->created_at->format('d M Y · H:i') }}
                            @if($comment->user?->rank) &nbsp;&middot;&nbsp; {{ $comment->user->rank->name }} @endif
                        </div>
                        <div class="comment-body">{{ $comment->comment }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT: HUD Details --}}
        <div>
            {{-- FLIGHT DATA --}}
            <div class="panel">
                <div class="panel-hd"><i class="fas fa-satellite-dish"></i> Flight Data</div>
                <div class="panel-bd">
                    <div class="hud-row">
                        <span class="lbl">Flight No.</span>
                        <span class="val">{{ $pirep->ident }}</span>
                    </div>
                    <div class="hud-row">
                        <span class="lbl">Status</span>
                        <span class="val" style="color:{{ $stInfo['color'] }}">{{ $stInfo['label'] }}</span>
                    </div>
                    @if($pirep->aircraft)
                    <div class="hud-row">
                        <span class="lbl">Aircraft</span>
                        <span class="val">{{ $pirep->aircraft->registration ?? $pirep->aircraft->name }}</span>
                    </div>
                    <div class="hud-row">
                        <span class="lbl">Type</span>
                        <span class="val">{{ $pirep->aircraft->name ?? 'N/A' }}</span>
                    </div>
                    @endif
                    <div class="hud-row">
                        <span class="lbl">Flight Time</span>
                        <span class="val">{{ $flightDur }}</span>
                    </div>
                    <div class="hud-row">
                        <span class="lbl">Distance</span>
                        <span class="val">{{ number_format($distNmi) }} NM</span>
                    </div>
                    <div class="hud-row">
                        <span class="lbl">Landing Rate</span>
                        <span class="val" style="color:{{ $lrColor }}">{{ $landRate !== null ? $landRate.' fpm' : 'N/A' }}</span>
                    </div>
                    <div class="hud-row">
                        <span class="lbl">Score</span>
                        <span class="val" style="color:{{ $scoreColor }}">{{ $pirep->score > 0 ? $pirep->score : 'N/A' }}</span>
                    </div>
                    @if($fuelUsed > 0)
                    <div class="hud-row">
                        <span class="lbl">Fuel Used</span>
                        <span class="val">{{ number_format($fuelUsed) }} {{ strtoupper($fuelUnit) }}</span>
                    </div>
                    @endif
                    @if($pirep->level)
                    <div class="hud-row">
                        <span class="lbl">Cruise Level</span>
                        <span class="val">FL{{ floor($pirep->level / 100) }}</span>
                    </div>
                    @endif
                    <div class="hud-row">
                        <span class="lbl">Source</span>
                        <span class="val">{{ $pirep->source === \App\Models\Enums\PirepSource::ACARS ? 'ACARS' : 'Manual' }}</span>
                    </div>
                    @if($pirep->submitted_at)
                    <div class="hud-row">
                        <span class="lbl">Filed</span>
                        <span class="val">{{ $pirep->submitted_at->format('d M Y · H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- AIRPORTS --}}
            <div class="panel">
                <div class="panel-hd"><i class="fas fa-map-marker-alt"></i> Route</div>
                <div class="panel-bd">
                    <div class="hud-row">
                        <span class="lbl">Departure</span>
                        <span class="val" style="color:var(--em)">{{ $pirep->dpt_airport_id }}</span>
                    </div>
                    @if($pirep->dpt_airport)
                    <div class="hud-row">
                        <span class="lbl"></span>
                        <span class="val" style="font-size:.65rem;color:rgba(255,255,255,.4)">{{ $pirep->dpt_airport->name }}</span>
                    </div>
                    @endif
                    <div class="hud-row">
                        <span class="lbl">Arrival</span>
                        <span class="val" style="color:var(--amb)">{{ $pirep->arr_airport_id }}</span>
                    </div>
                    @if($pirep->arr_airport)
                    <div class="hud-row">
                        <span class="lbl"></span>
                        <span class="val" style="font-size:.65rem;color:rgba(255,255,255,.4)">{{ $pirep->arr_airport->name }}</span>
                    </div>
                    @endif
                    @if(filled($pirep->route))
                    <div style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid rgba(255,255,255,0.06);">
                        <div style="font-size:0.62rem;color:#cbd5e1;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.4rem;">Route String</div>
                        <div class="route-str">{{ $pirep->route }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- CUSTOM FIELDS --}}
            @if($pirep->fields && $pirep->fields->count() > 0)
            <div class="panel">
                <div class="panel-hd"><i class="fas fa-list"></i> Additional Info</div>
                <div class="panel-bd">
                    @foreach($pirep->fields as $field)
                    <div class="hud-row">
                        <span class="lbl">{{ $field->name }}</span>
                        <span class="val">{{ $field->value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- FARES --}}
            @if($pirep->fares && $pirep->fares->count() > 0)
            <div class="panel">
                <div class="panel-hd"><i class="fas fa-users"></i> Passenger Load</div>
                <div class="panel-bd" style="padding:0.75rem 1rem;">
                    <table class="fares-table">
                        <thead>
                            <tr><th>Class</th><th>Pax</th><th>Capacity</th></tr>
                        </thead>
                        <tbody>
                            @foreach($pirep->fares as $fare)
                            <tr>
                                <td>{{ $fare->name }}</td>
                                <td>{{ $fare->count }}</td>
                                <td>{{ $fare->capacity ?? '–' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- NOTES --}}
            @if(filled($pirep->notes))
            <div class="panel">
                <div class="panel-hd"><i class="fas fa-sticky-note"></i> Notes</div>
                <div class="panel-bd">
                    <p style="font-size:0.8rem;color:rgba(255,255,255,0.65);line-height:1.6;margin:0;">{{ $pirep->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@section('scripts')
@php
// GeoJSON objects implement JsonSerializable — encode them here so Blade can't array-access them
$plannedLineJson = json_encode($map_features['planned_rte_line'] ?? null);
$actualLineJson  = json_encode($map_features['actual_route_line'] ?? null);
// A valid non-empty LineString encodes to more than 30 chars
$hasPlanned = strlen($plannedLineJson) > 30;
$hasActual  = strlen($actualLineJson)  > 30;
@endphp
{{-- Map rendering --}}
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('pirep-map', { scrollWheelZoom: false });
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; CartoDB',
        subdomains: 'abcd', maxZoom: 19,
    }).addTo(map);

    var bounds = [];

    @if($hasPlanned)
    var plannedLine = {!! $plannedLineJson !!};
    L.geoJSON(plannedLine, {
        style: { color: '#2563eb', weight: 2, dashArray: '5 10', opacity: .7 }
    }).addTo(map);
    @endif

    @if($hasActual)
    var actualLine = {!! $actualLineJson !!};
    L.geoJSON(actualLine, {
        style: { color: '#059669', weight: 2.5, opacity: .9 }
    }).addTo(map);
    // Collect coords for bounds from the coordinates array
    var coords = (actualLine.coordinates || []);
    coords.forEach(function(c) { bounds.push([c[1], c[0]]); });
    @endif

    // Departure marker
    @if($pirep->dpt_airport)
    var dptIcon = L.divIcon({
        html: '<div style="background:#10b981;width:10px;height:10px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 6px rgba(16,185,129,.8);"></div>',
        className: '', iconSize: [10,10], iconAnchor: [5,5]
    });
    var dptMarker = L.marker([{{ $pirep->dpt_airport->lat }}, {{ $pirep->dpt_airport->lon }}], { icon: dptIcon })
        .addTo(map)
        .bindPopup('<b style="color:#10b981">{{ $pirep->dpt_airport_id }}</b><br>{{ addslashes($pirep->dpt_airport->name) }}');
    bounds.push([{{ $pirep->dpt_airport->lat }}, {{ $pirep->dpt_airport->lon }}]);
    @endif

    // Arrival marker
    @if($pirep->arr_airport)
    var arrIcon = L.divIcon({
        html: '<div style="background:#fbbf24;width:10px;height:10px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 6px rgba(251,191,36,.8);"></div>',
        className: '', iconSize: [10,10], iconAnchor: [5,5]
    });
    var arrMarker = L.marker([{{ $pirep->arr_airport->lat }}, {{ $pirep->arr_airport->lon }}], { icon: arrIcon })
        .addTo(map)
        .bindPopup('<b style="color:#fbbf24">{{ $pirep->arr_airport_id }}</b><br>{{ addslashes($pirep->arr_airport->name) }}');
    bounds.push([{{ $pirep->arr_airport->lat }}, {{ $pirep->arr_airport->lon }}]);
    @endif

    if (bounds.length > 0) {
        map.fitBounds(L.latLngBounds(bounds), { padding: [30, 30] });
    } else {
        map.setView([18, -77], 5);
    }
});
</script>

{{-- Telemetry Chart --}}
@if($chartData->count() > 1)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var raw = {!! $chartData->toJson() !!};
    if (!raw || raw.length < 2) return;

    var labels  = raw.map(function(p) { return p.time; });
    var altData = raw.map(function(p) { return p.alt; });
    var gsData  = raw.map(function(p) { return p.gs; });

    var ctx = document.getElementById('telemetry-chart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Altitude (ft)',
                    data: altData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.08)',
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: 0.35,
                    fill: true,
                    yAxisID: 'yAlt',
                },
                {
                    label: 'Ground Speed (kt)',
                    data: gsData,
                    borderColor: '#fbbf24',
                    backgroundColor: 'rgba(251,191,36,0.06)',
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: 0.35,
                    fill: false,
                    yAxisID: 'yGs',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(2,6,23,0.92)',
                    borderColor: 'rgba(16,185,129,0.3)',
                    borderWidth: 1,
                    titleColor: '#10b981',
                    bodyColor: '#e2e8f0',
                    titleFont: { family: "'JetBrains Mono', monospace", size: 11 },
                    bodyFont:  { family: "'JetBrains Mono', monospace", size: 11 },
                    callbacks: {
                        title: function(items) { return items[0].label + ' UTC'; },
                        label: function(item) {
                            if (item.datasetIndex === 0) return '  ALT: ' + item.raw.toLocaleString() + ' ft';
                            return '  GS:  ' + item.raw + ' kt';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: {
                        color: 'rgba(255,255,255,0.3)',
                        font: { family: "'JetBrains Mono', monospace", size: 10 },
                        maxTicksLimit: 8,
                    }
                },
                yAlt: {
                    position: 'left',
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: {
                        color: '#10b981',
                        font: { family: "'JetBrains Mono', monospace", size: 10 },
                        callback: function(v) { return (v/1000).toFixed(0) + 'k'; }
                    },
                    title: { display: false }
                },
                yGs: {
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: {
                        color: '#fbbf24',
                        font: { family: "'JetBrains Mono', monospace", size: 10 },
                        callback: function(v) { return v + ' kt'; }
                    }
                }
            }
        }
    });
});
</script>
@endif
@endsection
