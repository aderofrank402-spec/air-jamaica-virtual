@extends('app')
@section('title', 'SimBrief — Flight Planning')

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
        background-attachment: fixed;
        color: #e2e8f0;
        font-family: 'JetBrains Mono', monospace !important;
    }

    .sb-page { max-width: 1100px; margin: 0 auto; padding: 2rem 1rem 4rem; }

    /* ── Mission Header ── */
    .sb-header {
        background: linear-gradient(135deg, rgba(16,185,129,0.07) 0%, rgba(2,6,23,0.95) 70%);
        border: 1px solid rgba(16,185,129,0.15);
        border-radius: 16px; padding: 2rem 2.25rem; margin-bottom: 1.75rem;
        position: relative; overflow: hidden;
    }
    .sb-header::before {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, var(--em), transparent);
    }
    .sb-badge {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .6rem; font-weight: 800; letter-spacing: .14em; text-transform: uppercase;
        color: var(--em); border: 1px solid var(--em3); background: var(--em2);
        padding: .25rem .65rem; border-radius: 5px; margin-bottom: .85rem;
    }
    .sb-route {
        font-family: var(--mono); font-size: 2.2rem; font-weight: 900;
        color: #fff; letter-spacing: .04em; display: flex; align-items: center; gap: 1rem;
        line-height: 1; margin-bottom: .5rem;
    }
    .sb-route .arrow { color: var(--em); font-size: 1.6rem; }
    .sb-meta { font-size: .72rem; color: #cbd5e1; font-family: var(--mono); display: flex; gap: 1.5rem; flex-wrap: wrap; }
    .sb-meta span { display: flex; align-items: center; gap: .35rem; }
    .sb-meta i { color: var(--em); }

    /* ── Layout grid ── */
    .sb-grid { display: grid; grid-template-columns: 1fr 320px; gap: 1.25rem; }
    @media (max-width: 900px) { .sb-grid { grid-template-columns: 1fr; } }

    /* ── Panel ── */
    .sb-panel { background: var(--pan); border: 1px solid var(--bd); border-radius: 12px; overflow: hidden; margin-bottom: 1.25rem; }
    .sb-panel-hd {
        padding: .85rem 1.25rem; border-bottom: 1px solid var(--bd);
        font-size: .68rem; font-weight: 800; color: var(--em);
        letter-spacing: .14em; text-transform: uppercase;
        display: flex; align-items: center; gap: .5rem;
    }
    .sb-panel-bd { padding: 1.25rem; }

    /* ── Form controls ── */
    .sb-field-row { display: grid; gap: 1rem; margin-bottom: 1rem; }
    .sb-field-row.cols-2 { grid-template-columns: 1fr 1fr; }
    .sb-field-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
    .sb-field-row.cols-2-1 { grid-template-columns: 2fr 1fr; }
    .sb-field { display: flex; flex-direction: column; gap: .35rem; }
    .sb-label {
        font-size: .6rem; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: .1em;
    }
    .sb-input, .sb-select {
        background: rgba(15,23,42,0.9); border: 1px solid rgba(255,255,255,0.08);
        border-radius: 7px; color: #f1f5f9; padding: .55rem .85rem;
        font-size: .8rem; font-family: var(--mono); width: 100%;
        transition: border-color .18s;
    }
    .sb-input:focus, .sb-select:focus { outline: none; border-color: var(--em); }
    .sb-input:disabled { color: #64748b; background: rgba(15,23,42,0.5); cursor: default; }
    .sb-select { appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2310b981'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right .75rem center; padding-right: 2.25rem; }
    .sb-select option { background: #0f172a; }

    /* ── Load grid ── */
    .sb-load-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: .75rem; }
    .sb-load-item {}

    /* ── Options table ── */
    .sb-opts { width: 100%; border-collapse: collapse; }
    .sb-opts tr { border-bottom: 1px solid rgba(255,255,255,0.04); }
    .sb-opts tr:last-child { border-bottom: none; }
    .sb-opts td { padding: .55rem 0; vertical-align: middle; }
    .sb-opts td:first-child { font-size: .72rem; color: #e2e8f0; padding-right: 1rem; white-space: nowrap; }
    .sb-opts td:last-child { width: 55%; }
    .sb-opts .sb-select { font-size: .72rem; padding: .4rem .7rem; }

    /* ── Generate button ── */
    .sb-generate {
        width: 100%; padding: .9rem; border-radius: 10px;
        background: var(--em); color: #fff; border: none; cursor: pointer;
        font-size: .85rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
        font-family: var(--mono); transition: all .18s; display: flex; align-items: center; justify-content: center; gap: .5rem;
    }
    .sb-generate:hover { background: #059669; transform: translateY(-1px); box-shadow: 0 4px 20px rgba(16,185,129,0.35); }

    /* ── Alert ── */
    .sb-alert {
        background: rgba(251,191,36,0.06); border: 1px solid rgba(251,191,36,0.25);
        border-radius: 8px; padding: .75rem 1rem;
        font-size: .75rem; color: #fbbf24; display: flex; align-items: center; gap: .5rem;
    }

    /* ── Divider ── */
    .sb-divider { border: none; border-top: 1px solid var(--bd); margin: 1rem 0; }
</style>
@endsection

@section('content')
@php
$breadcrumbs = [
    ['title' => 'Pilot Center', 'url' => route('frontend.dashboard.index'), 'icon' => 'bi bi-speedometer2'],
    ['title' => 'Flights', 'url' => route('frontend.flights.index'), 'icon' => 'bi bi-compass'],
    ['title' => 'SimBrief Planning', 'icon' => 'bi bi-map'],
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

<div class="sb-page">

    {{-- Mission Header --}}
    <div class="sb-header">
        <div class="sb-badge"><i class="fas fa-satellite-dish"></i> SimBrief — Flight Plan Generation</div>
        <div class="sb-route">
            <span>{{ $flight->dpt_airport_id }}</span>
            <span class="arrow">→</span>
            <span>{{ $flight->arr_airport_id }}</span>
        </div>
        <div class="sb-meta">
            <span><i class="fas fa-hashtag"></i> {{ $flight->airline->code ?? '' }}{{ $flight->flight_number }}</span>
            <span><i class="fas fa-plane"></i> {{ $aircraft->registration }} ({{ $aircraft->icao }})</span>
            @if($flight->dpt_airport)<span><i class="fas fa-plane-departure"></i> {{ $flight->dpt_airport->name }}</span>@endif
            @if($flight->arr_airport)<span><i class="fas fa-plane-arrival"></i> {{ $flight->arr_airport->name }}</span>@endif
        </div>
    </div>

    <form id="sbapiform">
    <div class="sb-grid">

        {{-- ── LEFT COLUMN ── --}}
        <div>

            {{-- Aircraft Details --}}
            <div class="sb-panel">
                <div class="sb-panel-hd"><i class="fas fa-plane"></i> Aircraft Configuration — {{ $aircraft->registration }}</div>
                <div class="sb-panel-bd">
                    <div class="sb-field-row cols-2">
                        <div class="sb-field">
                            <label class="sb-label">ICAO Type</label>
                            <input type="text" class="sb-input" value="{{ $aircraft->icao }}" maxlength="4" disabled>
                            @if(filled($aircraft->simbrief_type))
                                <input type="hidden" name="type" value="{{ $aircraft->simbrief_type }}">
                            @elseif(filled($aircraft->subfleet->simbrief_type))
                                <input type="hidden" name="type" value="{{ $aircraft->subfleet->simbrief_type }}">
                            @else
                                <input type="hidden" name="type" value="{{ $aircraft->icao }}">
                            @endif
                        </div>
                        <div class="sb-field">
                            <label class="sb-label">Registration</label>
                            <input type="text" class="sb-input" value="{{ $aircraft->registration }}" maxlength="6" disabled>
                            <input type="hidden" name="reg" value="{{ $aircraft->registration }}">
                        </div>
                    </div>

                    @if(empty($pax_load_sheet) && empty($cargo_load_sheet) && empty($tpayload) && empty($tpaxload))
                        <div class="sb-alert"><i class="fas fa-exclamation-triangle"></i> No load configuration available for this aircraft.</div>
                    @else
                        @if($pax_load_sheet || $cargo_load_sheet)
                            <hr class="sb-divider">
                            <div style="font-size:.6rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.75rem;">Passenger &amp; Cargo Loading</div>
                            <div class="sb-load-grid">
                                @foreach($pax_load_sheet as $fare)
                                    <div class="sb-field">
                                        <label class="sb-label">{{ $fare['name'] }} <span style="color:#475569;">[Max: {{ $fare['capacity'] }}]</span></label>
                                        <input type="text" class="sb-input" value="{{ $fare['count'] }}" disabled>
                                    </div>
                                @endforeach
                                @foreach($cargo_load_sheet as $fare)
                                    <div class="sb-field">
                                        <label class="sb-label">{{ $fare['name'] }} <span style="color:#475569;">[Max: {{ number_format($fare['capacity'] - $tbagload) }} {{ setting('units.weight') }}]</span></label>
                                        <input type="text" class="sb-input" value="{{ number_format($fare['count']) }}" disabled>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(isset($tpayload) && $tpayload > 0)
                            <hr class="sb-divider">
                            <div style="font-size:.6rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.75rem;">Weight Summary</div>
                            <div class="sb-load-grid">
                                @if($tpaxload)
                                    <div class="sb-field">
                                        <label class="sb-label">Pax Weight</label>
                                        <input type="text" class="sb-input" value="{{ number_format($tpaxload) }} {{ setting('units.weight') }}" disabled>
                                    </div>
                                    <div class="sb-field">
                                        <label class="sb-label">Baggage</label>
                                        <input type="text" class="sb-input" value="{{ number_format($tbagload) }} {{ setting('units.weight') }}" disabled>
                                    </div>
                                @endif
                                @if($tpaxload && $tcargoload)
                                    <div class="sb-field">
                                        <label class="sb-label">Cargo</label>
                                        <input type="text" class="sb-input" value="{{ number_format($tcargoload) }} {{ setting('units.weight') }}" disabled>
                                    </div>
                                @endif
                                <div class="sb-field">
                                    <label class="sb-label">Total Payload</label>
                                    <input type="text" class="sb-input" value="{{ number_format($tpayload) }} {{ setting('units.weight') }}" disabled style="color:var(--em);border-color:rgba(16,185,129,0.25);">
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Flight Information --}}
            <div class="sb-panel">
                <div class="sb-panel-hd"><i class="fas fa-route"></i> Flight Information — {{ $flight->airline->code ?? '' }}{{ $flight->flight_number }}</div>
                <div class="sb-panel-bd">
                    <div class="sb-field-row cols-3">
                        <div class="sb-field">
                            <label class="sb-label">Departure</label>
                            <input id="dorig" type="text" class="sb-input" maxlength="4" value="{{ $flight->dpt_airport_id }}" disabled>
                            <input name="orig" type="hidden" maxlength="4" value="{{ $flight->dpt_airport_id }}">
                        </div>
                        <div class="sb-field">
                            <label class="sb-label">Arrival</label>
                            <input id="ddest" type="text" class="sb-input" maxlength="4" value="{{ $flight->arr_airport_id }}" disabled>
                            <input name="dest" type="hidden" maxlength="4" value="{{ $flight->arr_airport_id }}">
                        </div>
                        <div class="sb-field">
                            <label class="sb-label">Alternate</label>
                            <input name="altn" type="text" class="sb-input" maxlength="4" value="{{ $flight->alt_airport_id ?? 'AUTO' }}">
                        </div>
                    </div>

                    <div class="sb-field-row cols-2-1">
                        <div class="sb-field">
                            <label class="sb-label">Preferred Route</label>
                            <input name="route" type="text" class="sb-input" value="{{ $flight->route }}" placeholder="Route string or leave blank for auto">
                        </div>
                        <div class="sb-field">
                            <label class="sb-label">Flight Level</label>
                            <input id="fl" name="fl" type="text" class="sb-input" maxlength="5" value="{{ $flight->level }}" placeholder="e.g. 350">
                        </div>
                    </div>

                    <div class="sb-field-row cols-3">
                        <div class="sb-field">
                            <label class="sb-label">Scheduled Dep (UTC)</label>
                            <input id="std" type="text" class="sb-input" maxlength="4"
                                value="{{ $flight->dpt_time ?? '' }}"
                                @if($flight->dpt_time) disabled @endif
                                placeholder="--:--">
                        </div>
                        <div class="sb-field">
                            <label class="sb-label">Est. Dep (UTC)</label>
                            <input id="etd" type="text" class="sb-input" maxlength="4" disabled placeholder="Auto">
                        </div>
                        <div class="sb-field">
                            <label class="sb-label">Date of Flight (UTC)</label>
                            <input id="dof" type="text" class="sb-input" maxlength="9" disabled placeholder="Auto">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── RIGHT COLUMN ── --}}
        <div>

            {{-- Planning Options --}}
            <div class="sb-panel">
                <div class="sb-panel-hd"><i class="fas fa-sliders-h"></i> Planning Options</div>
                <div class="sb-panel-bd" style="padding: .75rem 1.25rem;">
                    <table class="sb-opts">
                        <tr>
                            <td>ATC Callsign</td>
                            <td>
                                @if(setting('simbrief.callsign', true))
                                    <span style="font-family:var(--mono);font-size:.75rem;color:var(--em);">{{ $user->ident }}</span>
                                    <input type="hidden" name="callsign" value="{{ $user->ident }}">
                                @else
                                    <select name="callsign" class="sb-select">
                                        @if(filled($flight->callsign))
                                            <option value="{{ optional($flight->airline)->icao . $flight->callsign }}" selected>{{ optional($flight->airline)->icao . $flight->callsign }}</option>
                                        @endif
                                        <option value="{{ optional($flight->airline)->icao . $flight->flight_number }}">{{ optional($flight->airline)->icao . $flight->flight_number }}</option>
                                        @if(filled($user->callsign))
                                            <option value="{{ optional($flight->airline)->icao . $user->callsign }}">{{ optional($flight->airline)->icao . $user->callsign }}</option>
                                        @endif
                                        <option value="{{ $user->ident }}">{{ $user->ident }}</option>
                                    </select>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Cont Fuel</td>
                            <td>
                                <select name="contpct" class="sb-select">
                                    <option value="0">None</option>
                                    <option value="auto">AUTO</option>
                                    <option value="easa">EASA</option>
                                    <option value="0.03/5">3% or 05 MIN</option>
                                    <option value="0.03/10">3% or 10 MIN</option>
                                    <option value="0.03/15">3% or 15 MIN</option>
                                    <option value="0.05/5" selected>5% or 05 MIN</option>
                                    <option value="0.05/10">5% or 10 MIN</option>
                                    <option value="0.05/15">5% or 15 MIN</option>
                                    <option value="0.03">3%</option>
                                    <option value="0.05">5%</option>
                                    <option value="0.1">10%</option>
                                    <option value="0.15">15%</option>
                                    <option value="3">03 MIN</option>
                                    <option value="5">05 MIN</option>
                                    <option value="10">10 MIN</option>
                                    <option value="15">15 MIN</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Reserve Fuel</td>
                            <td>
                                <select name="resvrule" class="sb-select">
                                    <option value="auto">AUTO</option>
                                    <option value="0">0 MIN</option>
                                    <option value="15">15 MIN</option>
                                    <option value="30" selected>30 MIN</option>
                                    <option value="45">45 MIN</option>
                                    <option value="60">60 MIN</option>
                                    <option value="75">75 MIN</option>
                                    <option value="90">90 MIN</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>SID/STAR Type</td>
                            <td>
                                <select name="find_sidstar" class="sb-select">
                                    <option value="C">Conventional</option>
                                    <option value="R" selected>RNAV</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Step Climbs</td>
                            <td>
                                <select id="stepclimbs" name="stepclimbs" class="sb-select" onchange="DisableFL()">
                                    <option value="0" selected>Disabled</option>
                                    <option value="1">Enabled</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>ETOPS</td>
                            <td>
                                <select name="etops" class="sb-select">
                                    <option value="0" selected>Disabled</option>
                                    <option value="1">Enabled</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Briefing Options --}}
            <div class="sb-panel">
                <div class="sb-panel-hd"><i class="fas fa-file-alt"></i> Briefing Options</div>
                <div class="sb-panel-bd" style="padding: .75rem 1.25rem;">
                    <table class="sb-opts">
                        <tr>
                            <td>OFP Layout</td>
                            <td>
                                <select name="planformat" class="sb-select">
                                    <option selected value="lido">LIDO</option>
                                    <option value="aal">American Airlines</option>
                                    <option value="aca">Air Canada</option>
                                    <option value="afr">Air France (2012)</option>
                                    <option value="afr2017">Air France (2017)</option>
                                    <option value="awe">US Airways</option>
                                    <option value="baw">British Airways</option>
                                    <option value="ber">Air Berlin</option>
                                    <option value="dal">Delta Air Lines</option>
                                    <option value="dlh">Lufthansa</option>
                                    <option value="ein">Aer Lingus</option>
                                    <option value="etd">Etihad Airways</option>
                                    <option value="ezy">easyJet</option>
                                    <option value="gwi">Germanwings</option>
                                    <option value="jbu">JetBlue Airways</option>
                                    <option value="jza">Jazz Aviation</option>
                                    <option value="klm">KLM</option>
                                    <option value="qfa">Qantas</option>
                                    <option value="ryr">Ryanair</option>
                                    <option value="swa">Southwest</option>
                                    <option value="thy">Turkish Airlines</option>
                                    <option value="uae">Emirates</option>
                                    <option value="ual">United (2012)</option>
                                    <option value="ual f:wz">United (2018)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Units</td>
                            <td>
                                <select id="kgslbs" name="units" class="sb-select">
                                    @if(setting('units.weight') === 'kg')
                                        <option value="KGS" selected>KGS</option>
                                        <option value="LBS">LBS</option>
                                    @else
                                        <option value="KGS">KGS</option>
                                        <option value="LBS" selected>LBS</option>
                                    @endif
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Detailed Navlog</td>
                            <td>
                                <select name="navlog" class="sb-select">
                                    <option value="0">Disabled</option>
                                    <option value="1" selected>Enabled</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Runway Analysis</td>
                            <td>
                                <select name="tlr" class="sb-select">
                                    <option value="0">Disabled</option>
                                    <option value="1" selected>Enabled</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>NOTAMS</td>
                            <td>
                                <select name="notams" class="sb-select">
                                    <option value="0">Disabled</option>
                                    <option value="1" selected>Enabled</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>FIR NOTAMS</td>
                            <td>
                                <select name="firnot" class="sb-select">
                                    <option value="0" selected>Disabled</option>
                                    <option value="1">Enabled</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Flight Maps</td>
                            <td>
                                <select name="maps" class="sb-select">
                                    <option value="detail" selected>Detailed</option>
                                    <option value="simple">Simple</option>
                                    <option value="none">None</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Generate button --}}
            <button type="button"
                onclick="simbriefsubmit('{{ url('/simbrief/callback') }}?flight_id={{ $flight->id }}&aircraft_id={{ $aircraft->id }}');"
                class="sb-generate">
                <i class="fas fa-paper-plane"></i> Generate SimBrief Briefing
            </button>

        </div>
    </div>

    {{-- Hidden form fields (SimBrief API) --}}
    <input type="hidden" name="acdata" value="{'paxwgt':{{ round($pax_weight) }}, 'bagwgt': {{ round($bag_weight) }}}">
    @if($tpaxfig)
        <input type="hidden" name="pax" value="{{ $tpaxfig }}">
    @elseif(!$tpaxfig && $tcargoload)
        <input type="hidden" name="pax" value="0">
    @endif
    @if($tcargoload)
        <input type='hidden' name='cargo' value="{{ number_format(($tcargoload / 1000), 1) }}">
    @endif
    @if(isset($tpayload) && $tpayload > 0)
        <input type="hidden" name="manualrmk" value="Load Distribution {{ $loaddist }}">
    @endif
    <input type="hidden" name="airline" value="{{ $flight->airline->icao }}">
    <input type="hidden" name="fltnum" value="{{ $flight->flight_number }}">
    @if(setting('simbrief.name_private', true))
        <input type="hidden" name="cpt" value="{{ $user->name_private }}">
    @endif
    <input type="hidden" id="steh" name="steh" maxlength="2">
    <input type="hidden" id="stem" name="stem" maxlength="2">
    <input type="hidden" id="date" name="date" maxlength="9">
    <input type="hidden" id="deph" name="deph" maxlength="2">
    <input type="hidden" id="depm" name="depm" maxlength="2">
    <input type="hidden" name="selcal" value="{{ $aircraft->selcal ?? 'FK-NS' }}">
    <input type="hidden" name="planformat" value="lido">
    <input type="hidden" name="omit_sids" value="0">
    <input type="hidden" name="omit_stars" value="0">
    <input type="hidden" name="cruise" value="CI">
    <input type="hidden" name="civalue" value="AUTO">
    <input type="hidden" name="static_id" value="{{ $static_id }}">

    </form>
</div>
@endsection

@section('scripts')
<script src="{{ public_asset('/assets/global/js/simbrief.apiv1.js') }}"></script>
<script>
    function DisableFL() {
        const climb = document.getElementById("stepclimbs").value;
        document.getElementById("fl").disabled = climb === "1";
    }

    // UTC departure time + 45 min, rounded to nearest 5 min
    const months = ["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
    const d = new Date();
    d.setMinutes(d.getMinutes() + 45);
    let deph = ("0" + d.getUTCHours()).slice(-2);
    let depm = d.getUTCMinutes();
    depm = depm < 55 ? Math.ceil(depm / 5) * 5 : Math.floor(depm / 5) * 5;
    depm = ("0" + depm).slice(-2);
    const dof = ("0" + d.getUTCDate()).slice(-2) + months[d.getUTCMonth()] + d.getUTCFullYear();

    document.getElementById("dof").value  = dof;
    document.getElementById("etd").value  = deph + ":" + depm;
    document.getElementById("date").value = dof;
    document.getElementById("deph").value = deph;
    document.getElementById("depm").value = depm;

    // Scheduled enroute time
    const num = {{ $flight->flight_time }};
    const rhours   = Math.floor(num / 60);
    const rminutes = Math.round((num / 60 - rhours) * 60);
    document.getElementById("steh").value = rhours.toString();
    document.getElementById("stem").value = rminutes.toString();
</script>
@endsection
