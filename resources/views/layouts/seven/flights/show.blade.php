@extends('app')
@section('title', trans_choice('common.flight', 1) . ' ' . $flight->ident)

@section('css')
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --em:  #10b981;
    --em2: rgba(16,185,129,.12);
    --sl:  #020617;
    --sl2: #1e293b;
    --sl3: #0f172a;
    --amb: #fbbf24;
    --blu: #3b82f6;
    --tx:  #e2e8f0;
    --tx2: #94a3b8;
    --bdr: rgba(16,185,129,.12);
    --card: rgba(15,23,42,.85);
    --mono: 'JetBrains Mono', monospace;
}

*, *::before, *::after { box-sizing: border-box; }

body {
    font-family: var(--mono) !important;
    font-size: 16px;
    background: var(--sl) !important;
    color: var(--tx) !important;
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
}

body::before {
    content: '';
    position: fixed;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 50% at 10% 0%,   rgba(16,185,129,.07) 0%, transparent 60%),
        radial-gradient(ellipse 60% 40% at 90% 100%,  rgba(59,130,246,.05) 0%, transparent 60%);
    pointer-events: none;
    z-index: 0;
}

/* ── Layout ── */
.fs-wrap {
    position: relative;
    z-index: 1;
    padding: 90px 0 4rem;
    min-height: 100vh;
}

.fs-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* ── Card base ── */
.mc-card {
    background: var(--card);
    border: 1px solid var(--bdr);
    border-radius: 14px;
    backdrop-filter: blur(10px);
    overflow: hidden;
}

.mc-card-head {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .85rem 1.5rem;
    border-bottom: 1px solid var(--bdr);
    background: rgba(16,185,129,.04);
}

.mc-head-icon {
    width: 30px;
    height: 30px;
    border-radius: 7px;
    background: var(--em2);
    border: 1px solid rgba(16,185,129,.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--em);
    font-size: .8rem;
    flex-shrink: 0;
}

.mc-head-label {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--em);
    margin: 0;
}

/* ── Route Hero ── */
.route-hero {
    background: var(--card);
    border: 1px solid var(--bdr);
    border-radius: 14px;
    backdrop-filter: blur(10px);
    padding: 2rem 2.5rem;
    margin-bottom: 1.25rem;
    position: relative;
    overflow: hidden;
}

.route-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 60% 80% at 50% 0%, rgba(16,185,129,.06) 0%, transparent 70%);
    pointer-events: none;
}

.route-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.route-airline {
    display: flex;
    align-items: center;
    gap: .85rem;
}

.airline-logo-wrap img {
    max-height: 36px;
    width: auto;
    opacity: .9;
    filter: brightness(1.1);
}

.airline-name-tag {
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--tx2);
}

.flight-ident {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--em);
    letter-spacing: .04em;
}

.flight-type-badge {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: rgba(251,191,36,.1);
    border: 1px solid rgba(251,191,36,.25);
    border-radius: 20px;
    padding: .3rem .85rem;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--amb);
}

/* ── Airport route strip ── */
.route-strip {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}

.route-airport { display: flex; flex-direction: column; }
.route-airport.arr { text-align: right; align-items: flex-end; }

.airport-iata {
    font-size: 3.2rem;
    font-weight: 700;
    line-height: 1;
    color: var(--tx);
    text-decoration: none !important;
    letter-spacing: -.02em;
    transition: color .18s;
}
.airport-iata:hover { color: var(--em); }

.airport-full-name {
    font-size: .72rem;
    color: var(--tx2);
    margin-top: .2rem;
    margin-bottom: .6rem;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.airport-time-pill {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: rgba(251,191,36,.08);
    border: 1px solid rgba(251,191,36,.2);
    border-radius: 20px;
    padding: .28rem .75rem;
    font-size: .72rem;
    font-weight: 700;
    color: var(--amb);
}

.route-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .5rem;
}

.route-arrow-line {
    display: flex;
    align-items: center;
    gap: 0;
    width: 100%;
}

.route-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, rgba(16,185,129,.15) 0%, rgba(16,185,129,.5) 50%, rgba(16,185,129,.15) 100%);
}

.route-plane-icon {
    color: var(--em);
    font-size: 1.1rem;
    padding: 0 .5rem;
}

.route-flight-label {
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--tx2);
    text-align: center;
}

/* ── Stats bar ── */
.stats-bar {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 1.75rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--bdr);
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
}

.stat-chip {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    background: rgba(255,255,255,.03);
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 8px;
    padding: .45rem .9rem;
    font-size: .72rem;
    color: var(--tx2);
}

.stat-chip i { color: var(--em); font-size: .72rem; }
.stat-chip strong { color: var(--tx); font-weight: 600; }

/* ── Main grid ── */
.fs-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.25rem;
    align-items: start;
}

/* ── Map ── */
.map-wrap {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid rgba(16,185,129,.15);
}

.map-wrap #map {
    border-radius: 0 !important;
    border: none !important;
}

/* ── Notes ── */
.notes-body {
    padding: 1.25rem 1.5rem;
    font-size: .88rem;
    color: var(--tx2);
    line-height: 1.7;
}

/* ── Weather sidebar ── */
.weather-tabs {
    display: flex;
    gap: .4rem;
    padding: .85rem 1rem .5rem;
    border-bottom: 1px solid var(--bdr);
    background: rgba(16,185,129,.03);
}

.wtab {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-family: var(--mono);
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--tx2);
    background: transparent;
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 6px;
    padding: .3rem .65rem;
    cursor: pointer;
    text-decoration: none !important;
    transition: all .18s;
}

.wtab:hover { border-color: rgba(16,185,129,.3); color: var(--tx); }

.wtab.active {
    background: rgba(16,185,129,.12);
    border-color: rgba(16,185,129,.35);
    color: var(--em);
}

.weather-pane {
    padding: 1rem 1.25rem 1.25rem;
}

.tab-content-mc { padding: 0; }

/* Override default weather widget colors */
.weather-pane table { font-family: var(--mono) !important; font-size: .8rem !important; }
.weather-pane td, .weather-pane th { color: var(--tx) !important; border-color: var(--bdr) !important; }

/* ── Large monitor cap ── */
@media (min-width: 1400px) {
    .fs-inner { max-width: 1400px; }
}

/* ── Responsive ── */
@media (max-width: 1024px) {
    .fs-grid { grid-template-columns: 1fr; }
}

@media (max-width: 640px) {
    .fs-wrap { padding: 80px 0 3rem; }
    .route-hero { padding: 1.5rem 1.25rem; }
    .route-strip { grid-template-columns: 1fr; gap: .75rem; }
    .route-airport.arr { text-align: left; align-items: flex-start; }
    .route-center { flex-direction: row; }
    .airport-iata { font-size: 2rem; }
    .route-arrow-line { width: auto; }
}
</style>
@endsection

@section('content')
@php
$breadcrumbs = [
    [
        'title' => 'Pilot Center',
        'url'   => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
        'icon'  => Auth::check() ? 'bi bi-speedometer2' : 'bi bi-house-fill'
    ],
    [
        'title' => trans_choice('common.flight', 2),
        'url'   => route('frontend.flights.index'),
        'icon'  => 'bi bi-airplane-engines-fill'
    ],
    [
        'title' => $flight->ident . ' — ' . $flight->dpt_airport_id . ' › ' . $flight->arr_airport_id,
        'icon'  => 'bi bi-airplane-fill'
    ]
];
@endphp

<div class="fs-wrap">
<div class="fs-inner">

    @include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

    {{-- Route Hero --}}
    <div class="route-hero mb-4">

        {{-- Top bar: airline + type badge --}}
        <div class="route-topbar">
            <div class="route-airline">
                @if (optional($flight->airline)->logo)
                    <div class="airline-logo-wrap">
                        <img src="{{ $flight->airline->logo }}" alt="{{ $flight->airline->name }}">
                    </div>
                @endif
                <div>
                    <div class="airline-name-tag">{{ $flight->airline->name }}</div>
                    <div class="flight-ident">
                        @if ($flight->airline->iata)
                            {{ $flight->airline->icao }}{{ $flight->flight_number }} /
                        @endif
                        {{ $flight->ident }}
                        @if (filled($flight->callsign) && !setting('simbrief.callsign', true))
                            / {{ $flight->atc }}
                        @endif
                    </div>
                </div>
            </div>
            <span class="flight-type-badge">
                <i class="fas fa-tag"></i>
                {{ $flight->flight_type }}&nbsp;
                <span class="d-none d-sm-inline">— {{ \App\Models\Enums\FlightType::label($flight->flight_type) }}</span>
            </span>
        </div>

        {{-- Airport route strip --}}
        <div class="route-strip">
            {{-- Departure --}}
            <div class="route-airport">
                <a href="{{ route('frontend.airports.show', [$flight->dpt_airport_id]) }}" class="airport-iata">
                    {{ $flight->dpt_airport_id }}
                </a>
                <div class="airport-full-name d-none d-md-block">{{ $flight->dpt_airport->name }}</div>
                <span class="airport-time-pill">
                    <i class="fas fa-plane-departure" style="font-size:.6rem"></i>
                    {{ $flight->dpt_time }}
                </span>
            </div>

            {{-- Center arrow --}}
            <div class="route-center">
                <div class="route-arrow-line">
                    <div class="route-line"></div>
                    <i class="fas fa-plane route-plane-icon"></i>
                    <div class="route-line"></div>
                </div>
                <div class="route-flight-label">{{ $flight->ident }}</div>
            </div>

            {{-- Arrival --}}
            <div class="route-airport arr">
                <a href="{{ route('frontend.airports.show', [$flight->arr_airport_id]) }}" class="airport-iata">
                    {{ $flight->arr_airport_id }}
                </a>
                <div class="airport-full-name d-none d-md-block">{{ $flight->arr_airport->name }}</div>
                <span class="airport-time-pill">
                    <i class="fas fa-plane-arrival" style="font-size:.6rem"></i>
                    {{ $flight->arr_time }}
                </span>
            </div>
        </div>

        {{-- Stats bar --}}
        <div class="stats-bar">
            @if ($flight->flight_time)
                <span class="stat-chip">
                    <i class="fas fa-clock"></i>
                    <strong>@minutestotime($flight->flight_time)</strong>
                    <span>block time</span>
                </span>
            @endif
            @if ($flight->distance)
                <span class="stat-chip">
                    <i class="fas fa-route"></i>
                    <strong>{{ $flight->distance }} nm</strong>
                    <span>distance</span>
                </span>
            @endif
            @if (count($flight->subfleets) !== 0)
                @php
                    $arr = [];
                    foreach ($flight->subfleets as $sf) { $arr[] = $sf->type; }
                @endphp
                <span class="stat-chip">
                    <i class="fas fa-plane"></i>
                    <strong>{{ implode(', ', $arr) }}</strong>
                </span>
            @else
                <span class="stat-chip">
                    <i class="fas fa-plane"></i>
                    <span>Any Subfleet</span>
                </span>
            @endif
        </div>

    </div>{{-- /.route-hero --}}

    {{-- Main grid: map + notes | weather --}}
    <div class="fs-grid">

        {{-- Left column --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Map --}}
            <div class="mc-card">
                <div class="mc-card-head">
                    <div class="mc-head-icon"><i class="fas fa-map"></i></div>
                    <p class="mc-head-label">Route Map</p>
                </div>
                <div class="map-wrap">
                    @include('flights.map')
                </div>
            </div>

            {{-- Notes --}}
            @if (filled($flight->notes))
                <div class="mc-card">
                    <div class="mc-card-head">
                        <div class="mc-head-icon"><i class="fas fa-file-alt"></i></div>
                        <p class="mc-head-label">{{ __('common.notes') }}</p>
                    </div>
                    <div class="notes-body">
                        {!! $flight->notes !!}
                    </div>
                </div>
            @endif

        </div>

        {{-- Right column: Weather --}}
        <div class="mc-card">
            <div class="mc-card-head">
                <div class="mc-head-icon"><i class="fas fa-cloud-sun"></i></div>
                <p class="mc-head-label">{{ __('flights.weather') }}</p>
            </div>

            {{-- Custom tabs --}}
            <div class="weather-tabs">
                <a class="wtab active" id="wtab-dpt" href="#" onclick="switchWTab(event,'w-dpt')">
                    <i class="fas fa-plane-departure"></i> {{ $flight->dpt_airport_id }}
                </a>
                <a class="wtab" id="wtab-arr" href="#" onclick="switchWTab(event,'w-arr')">
                    <i class="fas fa-plane-arrival"></i> {{ $flight->arr_airport_id }}
                </a>
                @if ($flight->alt_airport_id)
                    <a class="wtab" id="wtab-alt" href="#" onclick="switchWTab(event,'w-alt')">
                        <i class="fas fa-map-pin"></i> {{ $flight->alt_airport_id }}
                    </a>
                @endif
            </div>

            <div class="weather-pane">
                <div id="w-dpt">
                    {{ Widget::Weather(['icao' => $flight->dpt_airport_id]) }}
                </div>
                <div id="w-arr" style="display:none">
                    {{ Widget::Weather(['icao' => $flight->arr_airport_id]) }}
                </div>
                @if ($flight->alt_airport_id)
                    <div id="w-alt" style="display:none">
                        {{ Widget::Weather(['icao' => $flight->alt_airport_id]) }}
                    </div>
                @endif
            </div>
        </div>

    </div>{{-- /.fs-grid --}}

</div>
</div>

<script>
function switchWTab(e, paneId) {
    e.preventDefault();
    document.querySelectorAll('.wtab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.weather-pane > div').forEach(p => p.style.display = 'none');
    e.currentTarget.classList.add('active');
    const pane = document.getElementById(paneId);
    if (pane) pane.style.display = '';
}
</script>
@endsection
