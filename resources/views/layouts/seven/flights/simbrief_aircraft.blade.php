@extends('app')
@section('title', 'SimBrief — Select Aircraft')

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

    .sb-page { max-width: 860px; margin: 0 auto; padding: 2rem 1rem 4rem; }

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
    .sb-meta { font-size: .72rem; color: #94a3b8; font-family: var(--mono); display: flex; gap: 1.5rem; flex-wrap: wrap; }
    .sb-meta span { display: flex; align-items: center; gap: .35rem; }

    /* ── Panel ── */
    .sb-panel {
        background: var(--pan); border: 1px solid var(--bd);
        border-radius: 12px; overflow: hidden; margin-bottom: 1.25rem;
    }
    .sb-panel-hd {
        padding: .85rem 1.25rem; border-bottom: 1px solid var(--bd);
        font-size: .68rem; font-weight: 800; color: var(--em);
        letter-spacing: .14em; text-transform: uppercase;
        display: flex; align-items: center; gap: .5rem;
    }
    .sb-panel-bd { padding: 1.5rem 1.25rem; }

    /* ── Select ── */
    .sb-label {
        display: block; font-size: .6rem; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: .1em; margin-bottom: .5rem;
    }
    .sb-select-wrap { position: relative; }
    .sb-select {
        width: 100%; background: rgba(15,23,42,0.9); border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px; color: #f1f5f9; padding: .7rem 2.5rem .7rem 1rem;
        font-size: .82rem; font-family: var(--mono); appearance: none; -webkit-appearance: none;
        transition: border-color .18s;
    }
    .sb-select:focus { outline: none; border-color: var(--em); }
    .sb-select option { background: #0f172a; }
    .sb-chevron {
        position: absolute; right: .85rem; top: 50%; transform: translateY(-50%);
        pointer-events: none; color: var(--em); font-size: .7rem;
    }

    /* ── Actions ── */
    .sb-actions {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1rem 1.25rem; border-top: 1px solid var(--bd);
        background: rgba(255,255,255,0.01);
    }
    .sb-btn {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .6rem 1.35rem; border-radius: 8px;
        font-size: .73rem; font-weight: 700; letter-spacing: .06em;
        text-decoration: none; transition: all .18s; cursor: pointer; border: none;
    }
    .sb-btn-ghost {
        background: transparent; border: 1px solid rgba(255,255,255,0.1); color: #94a3b8;
    }
    .sb-btn-ghost:hover { border-color: rgba(255,255,255,0.25); color: #e2e8f0; text-decoration: none; }
    .sb-btn-primary {
        background: var(--em); color: #fff;
    }
    .sb-btn-primary:hover { background: #059669; color: #fff; text-decoration: none; transform: translateY(-1px); }
    .sb-btn-primary[style*="hidden"] { visibility: hidden; }

    /* ── Empty state ── */
    .sb-empty {
        text-align: center; padding: 2.5rem 1rem; color: #475569;
    }
    .sb-empty i { font-size: 2rem; margin-bottom: .75rem; display: block; color: #1e293b; }
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
        <div class="sb-badge"><i class="fas fa-satellite-dish"></i> SimBrief Flight Planning</div>
        <div class="sb-route">
            <span>{{ $flight->dpt_airport_id }}</span>
            <span class="arrow">→</span>
            <span>{{ $flight->arr_airport_id }}</span>
        </div>
        <div class="sb-meta">
            <span><i class="fas fa-hashtag" style="color:var(--em)"></i> {{ $flight->airline->code ?? '' }}{{ $flight->flight_number }}</span>
            @if($flight->dpt_airport)<span><i class="fas fa-plane-departure" style="color:var(--em)"></i> {{ $flight->dpt_airport->name }}</span>@endif
            @if($flight->arr_airport)<span><i class="fas fa-plane-arrival" style="color:var(--em)"></i> {{ $flight->arr_airport->name }}</span>@endif
        </div>
    </div>

    {{-- Aircraft Selection --}}
    <div class="sb-panel">
        <div class="sb-panel-hd"><i class="fas fa-plane"></i> Select Aircraft</div>
        <div class="sb-panel-bd">
            @if($aircrafts->isEmpty())
                <div class="sb-empty">
                    <i class="fas fa-ban"></i>
                    <p style="font-size:.8rem;font-weight:600;color:#475569;margin:0 0 .25rem;">No aircraft available</p>
                    <p style="font-size:.72rem;color:#334155;margin:0;">No aircraft at the departure airport match your type ratings.</p>
                </div>
            @else
                <label class="sb-label">Available Aircraft ({{ $aircrafts->count() }} found)</label>
                <div class="sb-select-wrap">
                    <select id="aircraftselection" class="sb-select" onchange="checkacselection()">
                        <option value="ZZZZZ">— Select an aircraft to continue —</option>
                        @foreach($aircrafts as $ac)
                            <option value="{{ $ac->id }}">[{{ $ac->icao }}] {{ $ac->registration }}@if($ac->registration != $ac->name) — {{ $ac->name }}@endif</option>
                        @endforeach
                    </select>
                    <span class="sb-chevron"><i class="fas fa-chevron-down"></i></span>
                </div>
            @endif
        </div>
        <div class="sb-actions">
            <a href="{{ route('frontend.flights.index') }}" class="sb-btn sb-btn-ghost">
                <i class="fas fa-arrow-left"></i> Back to Flights
            </a>
            <a id="generate_link"
               style="visibility:hidden"
               href="{{ route('frontend.simbrief.generate') }}?flight_id={{ $flight->id }}"
               class="sb-btn sb-btn-primary">
                Continue to Planning <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    const $oldlink = document.getElementById("generate_link").href;
    function checkacselection() {
        const sel = document.getElementById("aircraftselection");
        const btn = document.getElementById("generate_link");
        if (sel.value === "ZZZZZ") {
            btn.style.visibility = "hidden";
        } else {
            btn.style.visibility = "visible";
            btn.href = $oldlink + "&aircraft_id=" + sel.value;
        }
    }
</script>
@endsection
