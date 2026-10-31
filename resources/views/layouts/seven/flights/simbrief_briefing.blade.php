@extends('app')
@section('title', 'SimBrief Briefing — ' . $simbrief->xml->general->icao_airline . $simbrief->xml->general->flight_number)

@section('css')
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    :root {
        --em: #10b981; --em2: rgba(16,185,129,0.08); --em3: rgba(16,185,129,0.22);
        --amb: #fbbf24; --pan: rgba(2,6,23,0.88); --bd: rgba(255,255,255,0.07);
        --mono: 'JetBrains Mono', monospace;
    }
    body {
        background: linear-gradient(160deg,#020617 0%,#091528 55%,#010b1a 100%) !important;
        background-attachment: fixed; color: #e2e8f0;
        font-family: 'JetBrains Mono', monospace !important;
    }

    .sb-page { max-width: 1200px; margin: 0 auto; padding: 2rem 1rem 4rem; }

    /* ── Mission Header ── */
    .sb-header {
        background: linear-gradient(135deg, rgba(16,185,129,0.07) 0%, rgba(2,6,23,0.95) 70%);
        border: 1px solid rgba(16,185,129,0.15); border-radius: 16px;
        padding: 1.75rem 2rem; margin-bottom: 1.75rem; position: relative; overflow: hidden;
        display: flex; justify-content: space-between; align-items: flex-start; gap: 1.5rem; flex-wrap: wrap;
    }
    .sb-header::before {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, var(--em), transparent);
    }
    .sb-badge {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .6rem; font-weight: 800; letter-spacing: .14em; text-transform: uppercase;
        color: var(--em); border: 1px solid var(--em3); background: var(--em2);
        padding: .25rem .65rem; border-radius: 5px; margin-bottom: .75rem;
    }
    .sb-route {
        font-family: var(--mono); font-size: 2rem; font-weight: 900;
        color: #fff; letter-spacing: .04em; display: flex; align-items: center; gap: .85rem;
        line-height: 1; margin-bottom: .45rem;
    }
    .sb-route .arrow { color: var(--em); font-size: 1.5rem; }
    .sb-meta { font-size: .7rem; color: #cbd5e1; font-family: var(--mono); display: flex; gap: 1.25rem; flex-wrap: wrap; }
    .sb-meta i { color: var(--em); }

    /* ── Action buttons ── */
    .sb-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: flex-start; }
    .sb-btn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem 1rem; border-radius: 7px;
        font-size: .7rem; font-weight: 700; letter-spacing: .06em; font-family: var(--mono);
        text-decoration: none; transition: all .18s; cursor: pointer; border: none; white-space: nowrap;
    }
    .sb-btn-primary { background: var(--em); color: #fff; }
    .sb-btn-primary:hover { background: #059669; color: #fff; text-decoration: none; }
    .sb-btn-ghost { background: rgba(255,255,255,0.04); border: 1px solid var(--bd); color: #e2e8f0; }
    .sb-btn-ghost:hover { background: rgba(255,255,255,0.09); color: #fff; text-decoration: none; }
    .sb-btn-amber { background: rgba(251,191,36,0.1); border: 1px solid rgba(251,191,36,0.3); color: var(--amb); }
    .sb-btn-amber:hover { background: rgba(251,191,36,0.2); color: var(--amb); text-decoration: none; }

    /* dropdown */
    .sb-dropdown { position: relative; }
    .sb-dropdown-menu {
        display: none; position: absolute; top: calc(100% + 6px); right: 0;
        background: #0f172a; border: 1px solid var(--bd); border-radius: 8px;
        min-width: 180px; z-index: 200; overflow: hidden;
    }
    .sb-dropdown:hover .sb-dropdown-menu,
    .sb-dropdown.open .sb-dropdown-menu { display: block; }
    .sb-dropdown-menu a {
        display: flex; align-items: center; gap: .5rem;
        padding: .55rem 1rem; font-size: .7rem; color: #e2e8f0;
        text-decoration: none; transition: background .15s;
        font-family: var(--mono);
    }
    .sb-dropdown-menu a:hover { background: rgba(255,255,255,0.05); color: #fff; }

    /* ── Layout ── */
    .sb-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    @media (max-width: 900px) { .sb-grid { grid-template-columns: 1fr; } }

    /* ── Panel ── */
    .sb-panel { background: var(--pan); border: 1px solid var(--bd); border-radius: 12px; overflow: hidden; margin-bottom: 1.25rem; }
    .sb-panel-hd {
        padding: .8rem 1.25rem; border-bottom: 1px solid var(--bd);
        font-size: .65rem; font-weight: 800; color: var(--em);
        letter-spacing: .14em; text-transform: uppercase;
        display: flex; align-items: center; gap: .5rem;
    }
    .sb-panel-bd { padding: 1.25rem; }

    /* ── Stat chips ── */
    .sb-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; }
    .sb-stat {
        background: rgba(255,255,255,0.02); border: 1px solid var(--bd);
        border-radius: 8px; padding: .65rem .75rem; text-align: center;
    }
    .sb-stat-lbl { font-size: .55rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .1em; margin-bottom: .35rem; }
    .sb-stat-val { font-size: .85rem; font-weight: 800; color: #f1f5f9; font-family: var(--mono); }
    .sb-stat-val.em { color: var(--em); }

    /* ── Divider ── */
    .sb-divider { border: none; border-top: 1px solid var(--bd); margin: 1rem 0; }

    /* ── Remarks ── */
    .sb-remark {
        background: rgba(255,255,255,0.02); border: 1px solid var(--bd);
        border-radius: 8px; padding: .65rem .85rem; margin-bottom: .65rem;
    }
    .sb-remark-lbl { font-size: .55rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .1em; margin-bottom: .3rem; }
    .sb-remark-val { font-size: .72rem; color: #e2e8f0; font-family: var(--mono); line-height: 1.5; }

    /* ── Flight plan text ── */
    .sb-flightplan {
        font-family: var(--mono); font-size: .72rem; color: #e2e8f0;
        line-height: 1.7; word-break: break-all;
        background: rgba(255,255,255,0.02); border: 1px solid var(--bd);
        border-radius: 8px; padding: .85rem 1rem;
    }

    /* ── Weather ── */
    .sb-wx-block { margin-bottom: 1rem; }
    .sb-wx-lbl { font-size: .55rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .1em; margin-bottom: .3rem; }
    .sb-wx-val {
        font-family: var(--mono); font-size: .7rem; color: #e2e8f0;
        background: rgba(255,255,255,0.02); border: 1px solid var(--bd);
        border-radius: 6px; padding: .55rem .75rem; line-height: 1.5;
    }
    .sb-wx-section-lbl {
        font-size: .6rem; font-weight: 800; color: var(--em); text-transform: uppercase;
        letter-spacing: .12em; margin: .85rem 0 .5rem; display: flex; align-items: center; gap: .4rem;
    }

    /* ── Download / Prefile ── */
    .sb-select {
        flex: 1; background: rgba(15,23,42,0.9); border: 1px solid var(--bd);
        border-radius: 7px; color: #f1f5f9; padding: .55rem .85rem;
        font-size: .72rem; font-family: var(--mono); appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2310b981'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right .75rem center; padding-right: 2rem;
    }
    .sb-select:focus { outline: none; border-color: var(--em); }
    .sb-select option { background: #0f172a; }
    .sb-dl-row { display: flex; gap: .6rem; align-items: stretch; }
    .sb-dl-btn {
        background: var(--em); color: #fff; border: none; border-radius: 7px;
        padding: .55rem .9rem; cursor: pointer; font-size: .8rem; transition: background .18s; flex-shrink: 0;
    }
    .sb-dl-btn:hover { background: #059669; }
    .sb-dl-btn:disabled { opacity: .5; cursor: not-allowed; }

    /* Prefile network buttons */
    .sb-prefile { display: grid; grid-template-columns: repeat(4,1fr); gap: .5rem; }
    .sb-net-btn {
        display: flex; align-items: center; justify-content: center; gap: .35rem;
        padding: .55rem .5rem; border-radius: 7px; font-size: .68rem; font-weight: 700;
        text-decoration: none; transition: all .18s; font-family: var(--mono); letter-spacing: .04em;
        border: 1px solid transparent;
    }
    .sb-net-btn:hover { transform: translateY(-1px); text-decoration: none; }
    .net-ivao   { background: rgba(13,44,153,0.3); border-color: rgba(13,44,153,0.6); color: #93c5fd; }
    .net-ivao:hover { background: rgba(13,44,153,0.5); color: #fff; }
    .net-vatsim { background: rgba(41,180,115,0.15); border-color: rgba(41,180,115,0.4); color: #6ee7b7; }
    .net-vatsim:hover { background: rgba(41,180,115,0.3); color: #fff; }
    .net-poscon { background: rgba(64,58,96,0.4); border-color: rgba(64,58,96,0.8); color: #c4b5fd; }
    .net-poscon:hover { background: rgba(64,58,96,0.6); color: #fff; }
    .net-sky    { background: rgba(14,165,233,0.1); border-color: rgba(14,165,233,0.3); color: #7dd3fc; }
    .net-sky:hover { background: rgba(14,165,233,0.25); color: #fff; }

    /* ── OFP ── */
    .sb-ofp-wrap {
        height: 700px; overflow-y: auto; border-radius: 8px;
        background: #020817; padding: .85rem 1rem;
        scrollbar-width: thin; scrollbar-color: var(--em) rgba(255,255,255,0.05);
        font-family: 'JetBrains Mono', monospace;
    }
    .sb-ofp-wrap::-webkit-scrollbar { width: 4px; }
    .sb-ofp-wrap::-webkit-scrollbar-thumb { background: var(--em); border-radius: 2px; }

    /* Force all SimBrief OFP content to be clearly readable */
    .sb-ofp-wrap,
    .sb-ofp-wrap * {
        color: #f1f5f9 !important;
        background: transparent !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: .72rem !important;
        line-height: 1.65 !important;
    }
    .sb-ofp-wrap pre {
        white-space: pre-wrap !important;
        word-break: break-word !important;
        margin: 0 !important;
    }

    /* ── Maps ── */
    .sb-maps { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 700px) { .sb-maps { grid-template-columns: 1fr; } }
    .sb-map-card { text-align: center; }
    .sb-map-card img { width: 100%; border-radius: 8px; border: 1px solid var(--bd); }
    .sb-map-lbl { font-size: .6rem; color: #64748b; text-transform: uppercase; letter-spacing: .08em; margin-top: .4rem; }

    /* ── Modal ── */
    .sb-modal-overlay {
        display: none; position: fixed; inset: 0; z-index: 1050;
        background: rgba(0,0,0,0.75); backdrop-filter: blur(4px);
        align-items: center; justify-content: center;
    }
    .sb-modal-overlay.active { display: flex; }
    .sb-modal { background: #0f172a; border: 1px solid var(--bd); border-radius: 14px; width: 90vw; max-width: 1020px; overflow: hidden; }
    .sb-modal-hd {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1rem 1.25rem; border-bottom: 1px solid var(--bd);
        font-size: .75rem; font-weight: 700; color: var(--em); letter-spacing: .1em;
    }
    .sb-modal-close { background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.2rem; }
    .sb-modal-close:hover { color: #f1f5f9; }
    .sb-modal-ft {
        display: flex; justify-content: flex-end; gap: .6rem;
        padding: .85rem 1.25rem; border-top: 1px solid var(--bd);
    }
</style>
@endsection

@section('content')
@php
$breadcrumbs = [
    ['title' => 'Pilot Center', 'url' => route('frontend.dashboard.index'), 'icon' => 'bi bi-speedometer2'],
    ['title' => 'Flights', 'url' => route('frontend.flights.index'), 'icon' => 'bi bi-compass'],
    ['title' => 'SimBrief Briefing', 'icon' => 'bi bi-file-text'],
];
$xml = $simbrief->xml;
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

<div class="sb-page">

    {{-- ── Mission Header ── --}}
    <div class="sb-header">
        <div>
            <div class="sb-badge"><i class="fas fa-satellite-dish"></i> SimBrief OFP</div>
            <div class="sb-route">
                <span>{{ $xml->origin->icao_code }}</span>
                <span class="arrow">→</span>
                <span>{{ $xml->destination->icao_code }}</span>
            </div>
            <div class="sb-meta">
                <span><i class="fas fa-hashtag"></i> {{ $xml->general->icao_airline }}{{ $xml->general->flight_number }}</span>
                <span><i class="fas fa-plane"></i> {{ $xml->aircraft->name }}</span>
                <span><i class="fas fa-clock"></i> @minutestotime($xml->times->est_time_enroute / 60) enroute</span>
                <span><i class="fas fa-mountain"></i> FL{{ $xml->general->initial_altitude }}</span>
            </div>
        </div>
        <div class="sb-actions">
            {{-- SimBrief dropdown --}}
            <div class="sb-dropdown" id="sbDrop">
                <button class="sb-btn sb-btn-ghost" onclick="toggleDrop('sbDrop')">
                    <i class="fas fa-map"></i> SimBrief <i class="fas fa-chevron-down" style="font-size:.55rem;"></i>
                </button>
                <div class="sb-dropdown-menu">
                    @if(!empty($xml->params->static_id) && $user->id === $simbrief->user_id)
                        <a href="#" onclick="openModal(); return false;"><i class="fas fa-edit"></i> Edit OFP</a>
                    @endif
                    <a href="{{ url(route('frontend.simbrief.generate_new', [$simbrief->id])) }}">
                        <i class="fas fa-redo"></i> Generate New OFP
                    </a>
                </div>
            </div>
            {{-- Fly Now dropdown --}}
            <div class="sb-dropdown" id="flyDrop">
                <button class="sb-btn sb-btn-primary" onclick="toggleDrop('flyDrop')">
                    <i class="fas fa-plane-departure"></i> Fly Now <i class="fas fa-chevron-down" style="font-size:.55rem;"></i>
                </button>
                <div class="sb-dropdown-menu">
                    @if(empty($simbrief->pirep_id))
                        <a href="{{ url(route('frontend.simbrief.prefile', [$simbrief->id])) }}">
                            <i class="fas fa-file-alt"></i> Prefile PIREP
                        </a>
                    @endif
                    @if($acars_plugin)
                        @if($bid)
                            <a href="vmsacars:bid/{{ $bid->id }}"><i class="fas fa-plug"></i> Load in vmsACARS</a>
                        @else
                            <a href="vmsacars:flight/{{ $flight->id }}"><i class="fas fa-plug"></i> Load in vmsACARS</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main Grid ── --}}
    <div class="sb-grid">

        {{-- LEFT: Dispatch + Flight Plan + Weather --}}
        <div>

            {{-- Dispatch Information --}}
            <div class="sb-panel">
                <div class="sb-panel-hd"><i class="fas fa-broadcast-tower"></i> Dispatch Information</div>
                <div class="sb-panel-bd">
                    <div class="sb-stats">
                        <div class="sb-stat">
                            <div class="sb-stat-lbl">Flight</div>
                            <div class="sb-stat-val em">{{ $xml->general->icao_airline }}{{ $xml->general->flight_number }}</div>
                        </div>
                        <div class="sb-stat">
                            <div class="sb-stat-lbl">Departure</div>
                            <div class="sb-stat-val">{{ $xml->origin->icao_code }}@if(!empty($xml->origin->plan_rwy)) <span style="color:#94a3b8;font-size:.7rem;">/{{ $xml->origin->plan_rwy }}</span>@endif</div>
                        </div>
                        <div class="sb-stat">
                            <div class="sb-stat-lbl">Arrival</div>
                            <div class="sb-stat-val">{{ $xml->destination->icao_code }}@if(!empty($xml->destination->plan_rwy)) <span style="color:#94a3b8;font-size:.7rem;">/{{ $xml->destination->plan_rwy }}</span>@endif</div>
                        </div>
                        <div class="sb-stat">
                            <div class="sb-stat-lbl">Aircraft</div>
                            <div class="sb-stat-val" style="font-size:.72rem;">{{ $xml->aircraft->name }}</div>
                        </div>
                        <div class="sb-stat">
                            <div class="sb-stat-lbl">Est. Enroute</div>
                            <div class="sb-stat-val">@minutestotime($xml->times->est_time_enroute / 60)</div>
                        </div>
                        <div class="sb-stat">
                            <div class="sb-stat-lbl">Cruise Alt</div>
                            <div class="sb-stat-val">FL{{ $xml->general->initial_altitude }}</div>
                        </div>
                    </div>

                    @if(!empty($xml->general->dx_rmk) || !empty($xml->general->sys_rmk))
                        <hr class="sb-divider">
                        @if(!empty($xml->general->dx_rmk))
                            <div class="sb-remark">
                                <div class="sb-remark-lbl">Dispatcher Remarks</div>
                                <div class="sb-remark-val">{{ $xml->general->dx_rmk }}</div>
                            </div>
                        @endif
                        @if(!empty($xml->general->sys_rmk))
                            <div class="sb-remark">
                                <div class="sb-remark-lbl">System Remarks</div>
                                <div class="sb-remark-val">{{ $xml->general->sys_rmk }}</div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- ATC Flight Plan --}}
            <div class="sb-panel">
                <div class="sb-panel-hd"><i class="fas fa-route"></i> ATC Flight Plan</div>
                <div class="sb-panel-bd">
                    <div class="sb-flightplan">{!! str_replace("\n", "<br>", $xml->atc->flightplan_text) !!}</div>
                </div>
            </div>

            {{-- Weather --}}
            <div class="sb-panel">
                <div class="sb-panel-hd"><i class="fas fa-cloud-sun"></i> Weather</div>
                <div class="sb-panel-bd">
                    <div class="sb-wx-section-lbl"><i class="fas fa-plane-departure"></i> {{ $xml->origin->icao_code }} — Departure</div>
                    @if(!empty($xml->weather->orig_metar))
                        <div class="sb-wx-block">
                            <div class="sb-wx-lbl">METAR</div>
                            <div class="sb-wx-val">{{ $xml->weather->orig_metar }}</div>
                        </div>
                    @endif
                    @if(!empty($xml->weather->orig_taf))
                        <div class="sb-wx-block">
                            <div class="sb-wx-lbl">TAF</div>
                            <div class="sb-wx-val">{{ $xml->weather->orig_taf }}</div>
                        </div>
                    @endif

                    <div class="sb-wx-section-lbl"><i class="fas fa-plane-arrival"></i> {{ $xml->destination->icao_code }} — Arrival</div>
                    @if(!empty($xml->weather->dest_metar))
                        <div class="sb-wx-block">
                            <div class="sb-wx-lbl">METAR</div>
                            <div class="sb-wx-val">{{ $xml->weather->dest_metar }}</div>
                        </div>
                    @endif
                    @if(!empty($xml->weather->dest_taf))
                        <div class="sb-wx-block">
                            <div class="sb-wx-lbl">TAF</div>
                            <div class="sb-wx-val">{{ $xml->weather->dest_taf }}</div>
                        </div>
                    @endif

                    @if(!empty($xml->weather->altn_metar) || !empty($xml->weather->altn_taf))
                        <div class="sb-wx-section-lbl"><i class="fas fa-exchange-alt"></i> Alternate</div>
                        @if(!empty($xml->weather->altn_metar))
                            <div class="sb-wx-block">
                                <div class="sb-wx-lbl">METAR</div>
                                <div class="sb-wx-val">{{ $xml->weather->altn_metar }}</div>
                            </div>
                        @endif
                        @if(!empty($xml->weather->altn_taf))
                            <div class="sb-wx-block">
                                <div class="sb-wx-lbl">TAF</div>
                                <div class="sb-wx-val">{{ $xml->weather->altn_taf }}</div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT: Download + Prefile + OFP --}}
        <div>

            {{-- Download FMS --}}
            <div class="sb-panel">
                <div class="sb-panel-hd"><i class="fas fa-download"></i> Download Flight Plan</div>
                <div class="sb-panel-bd">
                    <div class="sb-dl-row">
                        <select id="download_fms_select" class="sb-select">
                            @foreach($simbrief->files as $fms)
                                <option value="{{ $fms['url'] }}">{{ $fms['name'] }}</option>
                            @endforeach
                        </select>
                        <button id="download_fms" class="sb-dl-btn" type="button">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Pre-file ATC --}}
            <div class="sb-panel">
                <div class="sb-panel-hd"><i class="fas fa-broadcast-tower"></i> Pre-File ATC Flight Plan</div>
                <div class="sb-panel-bd">
                    <div class="sb-prefile">
                        <a href="{{ $xml->prefile->ivao->link }}" target="_blank" class="sb-net-btn net-ivao">
                            <i class="fas fa-tower-broadcast"></i> IVAO
                        </a>
                        <a href="{{ $xml->prefile->vatsim->link }}" target="_blank" class="sb-net-btn net-vatsim">
                            <i class="fas fa-tower-broadcast"></i> VATSIM
                        </a>
                        <a href="{{ $xml->prefile->poscon->link }}" target="_blank" class="sb-net-btn net-poscon">
                            <i class="fas fa-tower-broadcast"></i> POSCON
                        </a>
                        <a href="http://skyvector.com/?chart=304&fpl={{ $xml->origin->icao_code }} {{ $xml->general->route }} {{ $xml->destination->icao_code }}" target="_blank" class="sb-net-btn net-sky">
                            <i class="fas fa-globe"></i> SkyVector
                        </a>
                    </div>
                </div>
            </div>

            {{-- OFP --}}
            <div class="sb-panel">
                <div class="sb-panel-hd"><i class="fas fa-file-alt"></i> Operational Flight Plan</div>
                <div class="sb-panel-bd" style="padding:.75rem;">
                    <div class="sb-ofp-wrap">
                        {!! $xml->text->plan_html !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Flight Maps ── --}}
    @php
        $routeMap = $simbrief->images->first(fn($i) => stripos($i['url'], 'ROUTE') !== false || stripos($i['name'], 'route') !== false);
        $windMap  = $simbrief->images->first(fn($i) => stripos($i['url'], 'WINDS34000') !== false || stripos($i['name'], 'wind') !== false)
                    ?? $simbrief->images->first(fn($i) => stripos($i['url'], 'WINDS') !== false || stripos($i['name'], 'wind') !== false);
    @endphp
    @if($routeMap || $windMap)
    <div class="sb-panel">
        <div class="sb-panel-hd"><i class="fas fa-map"></i> Flight Maps</div>
        <div class="sb-panel-bd">
            <div class="sb-maps">
                @if($routeMap)
                    <div class="sb-map-card">
                        <img src="{{ $routeMap['url'] }}" alt="{{ $routeMap['name'] }}">
                        <div class="sb-map-lbl">{{ $routeMap['name'] }}</div>
                    </div>
                @endif
                @if($windMap)
                    <div class="sb-map-card">
                        <img src="{{ $windMap['url'] }}" alt="{{ $windMap['name'] }}">
                        <div class="sb-map-lbl">{{ $windMap['name'] }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ── Edit OFP Modal ── --}}
@if(!empty($xml->params->static_id) && $user->id === $simbrief->user_id)
<div class="sb-modal-overlay" id="sbModal">
    <div class="sb-modal">
        <div class="sb-modal-hd">
            <span><i class="fas fa-edit"></i> Edit OFP — {{ $xml->general->icao_airline }}{{ $xml->general->flight_number }}</span>
            <button class="sb-modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <iframe src="https://dispatch.simbrief.com/briefing/{{ $simbrief->id }}"
                style="width:100%;height:75vh;display:block;" frameborder="0" title="SimBrief"></iframe>
        <div class="sb-modal-ft">
            <a href="{{ route('frontend.simbrief.update_ofp') }}?ofp_id={{ $simbrief->id }}&flight_id={{ $simbrief->flight_id }}&aircraft_id={{ $simbrief->aircraft_id }}&sb_userid={{ $xml->params->user_id }}&sb_static_id={{ $xml->params->static_id }}"
               class="sb-btn sb-btn-primary"><i class="fas fa-download"></i> Download Updated OFP</a>
            <button onclick="closeModal()" class="sb-btn sb-btn-ghost"><i class="fas fa-times"></i> Close</button>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    // FMS download
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('download_fms_select');
        const btn    = document.getElementById('download_fms');
        if (!btn) return;
        btn.addEventListener('click', function () {
            if (!select) return;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
            const a = document.createElement('a');
            a.href = select.options[select.selectedIndex].value;
            a.download = '';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            setTimeout(() => { btn.disabled = false; btn.innerHTML = '<i class="bi bi-download"></i>'; }, 3000);
        });
    });

    // Dropdowns
    function toggleDrop(id) {
        const el = document.getElementById(id);
        const isOpen = el.classList.contains('open');
        document.querySelectorAll('.sb-dropdown.open').forEach(d => d.classList.remove('open'));
        if (!isOpen) el.classList.add('open');
    }
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.sb-dropdown')) {
            document.querySelectorAll('.sb-dropdown.open').forEach(d => d.classList.remove('open'));
        }
    });

    // Modal
    function openModal()  { document.getElementById('sbModal').classList.add('active'); }
    function closeModal() { document.getElementById('sbModal').classList.remove('active'); }
    document.getElementById('sbModal')?.addEventListener('click', function (e) {
        if (e.target === this) closeModal();
    });
</script>
@endsection
