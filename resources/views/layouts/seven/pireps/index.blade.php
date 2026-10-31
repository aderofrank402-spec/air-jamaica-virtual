@extends('layouts.seven.public_app')
@section('title', 'Flight Log')

@section('css')
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --em:  #10b981;
    --em2: #059669;
    --sl:  #020617;
    --sl1: #0f172a;
    --sl2: #1e293b;
    --sl3: #334155;
    --amb: #fbbf24;
    --red: #ef4444;
    --blu: #3b82f6;
    --txt: #e2e8f0;
    --mute: #64748b;
    --bdr: rgba(16,185,129,.12);
    --bdr-h: rgba(16,185,129,.35);
    --card: rgba(15,23,42,.85);
}

*, *::before, *::after { box-sizing: border-box; }

body {
    font-family: 'JetBrains Mono', monospace !important;
    background: var(--sl) !important;
    color: var(--txt) !important;
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
}

body::before {
    content: '';
    position: fixed;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 50% at 10% 0%, rgba(16,185,129,.07) 0%, transparent 60%),
        radial-gradient(ellipse 60% 40% at 90% 100%, rgba(251,191,36,.05) 0%, transparent 60%);
    pointer-events: none;
    z-index: 0;
}

/* ── layout ── */
.fl-wrap { position: relative; z-index: 1; padding: 90px 0 4rem; min-height: 100vh; }

/* ── top bar ── */
.fl-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.fl-headline {
    display: flex;
    align-items: baseline;
    gap: .75rem;
}
.fl-headline-label {
    font-size: .65rem;
    font-weight: 600;
    letter-spacing: .2em;
    color: var(--em);
    text-transform: uppercase;
}
.fl-headline h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--txt);
    margin: 0;
    line-height: 1;
}

.btn-new-pirep {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: var(--em);
    color: #000 !important;
    font-family: 'JetBrains Mono', monospace;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    text-decoration: none !important;
    padding: .55rem 1.25rem;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: background .2s, transform .15s;
}
.btn-new-pirep:hover { background: #34d399; transform: translateY(-1px); }

/* ── stats row ── */
.fl-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}
@media (max-width: 640px) { .fl-stats-row { grid-template-columns: repeat(2, 1fr); } }

.fl-stat {
    background: var(--card);
    border: 1px solid var(--bdr);
    border-radius: 10px;
    padding: 1rem 1.25rem;
    backdrop-filter: blur(10px);
}
.fl-stat-val {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--em);
    line-height: 1;
    margin-bottom: .25rem;
}
.fl-stat-lbl {
    font-size: .6rem;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--mute);
}

/* ── card list ── */
.fl-list { display: flex; flex-direction: column; gap: .75rem; }

/* ── single flight card ── */
.fl-card {
    background: var(--card);
    border: 1px solid var(--bdr);
    border-radius: 12px;
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
    transition: border-color .2s, box-shadow .2s, transform .15s;
}
.fl-card:hover {
    border-color: var(--bdr-h);
    box-shadow: 0 4px 24px rgba(16,185,129,.12);
    transform: translateY(-1px);
}
.fl-card-link {
    display: block;
    padding: 1rem 1.25rem;
    text-decoration: none !important;
    color: inherit !important;
}
.fl-card-link:hover { color: inherit !important; }

/* status strip */
.fl-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
}
.fl-card.s-accepted::before  { background: var(--em); }
.fl-card.s-pending::before   { background: var(--amb); }
.fl-card.s-progress::before  { background: var(--blu); }
.fl-card.s-rejected::before  { background: var(--red); }
.fl-card.s-paused::before    { background: var(--mute); }
.fl-card.s-draft::before     { background: var(--sl3); }

/* ── row 1: meta ── */
.fl-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: .75rem;
    flex-wrap: wrap;
}
.fl-meta-left { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }

.fl-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-size: .58rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    padding: .2rem .55rem;
    border-radius: 4px;
    white-space: nowrap;
}
.fl-badge.s-accepted { background: rgba(16,185,129,.15); color: var(--em); }
.fl-badge.s-pending  { background: rgba(251,191,36,.15);  color: var(--amb); }
.fl-badge.s-progress { background: rgba(59,130,246,.15);  color: #93c5fd; }
.fl-badge.s-rejected { background: rgba(239,68,68,.15);   color: #fca5a5; }
.fl-badge.s-paused   { background: rgba(100,116,139,.15); color: var(--mute); }
.fl-badge.s-draft    { background: rgba(100,116,139,.1);  color: var(--mute); }

.fl-ident {
    font-size: .82rem;
    font-weight: 700;
    color: var(--txt);
    letter-spacing: .04em;
}
.fl-sep { color: var(--sl3); }
.fl-aircraft {
    font-size: .72rem;
    color: var(--mute);
}

.fl-date {
    font-size: .65rem;
    color: var(--mute);
    white-space: nowrap;
}

/* ── row 2: route ── */
.fl-route {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: .75rem;
    margin-bottom: .75rem;
}
.fl-apt { min-width: 0; }
.fl-apt-code {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--txt);
    line-height: 1;
    letter-spacing: .06em;
}
.fl-apt-name {
    font-size: .6rem;
    color: var(--mute);
    margin-top: .1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.fl-arr .fl-apt-code,
.fl-arr .fl-apt-name { text-align: right; }

.fl-arrow {
    display: flex;
    align-items: center;
    gap: .3rem;
    color: var(--em);
    font-size: .65rem;
    white-space: nowrap;
}
.fl-arrow-line {
    height: 1px;
    background: linear-gradient(90deg, var(--sl3), var(--em), var(--sl3));
    flex: 1;
    min-width: 40px;
}
.fl-arrow-icon { font-size: .7rem; }

/* ── row 3: hud stats ── */
.fl-hud {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex-wrap: wrap;
    padding-top: .6rem;
    border-top: 1px solid var(--bdr);
}
.fl-hud-item {
    display: flex;
    align-items: center;
    gap: .3rem;
    font-size: .65rem;
    color: var(--mute);
    white-space: nowrap;
}
.fl-hud-item i { font-size: .6rem; }
.fl-hud-val { color: var(--txt); font-weight: 600; }

.fl-hud-spacer { flex: 1; }

/* ── action buttons ── */
.fl-actions {
    display: flex;
    gap: .4rem;
    align-items: center;
}
.fl-btn {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-family: 'JetBrains Mono', monospace;
    font-size: .6rem;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    text-decoration: none !important;
    padding: .3rem .65rem;
    border-radius: 5px;
    border: 1px solid transparent;
    transition: all .2s;
    white-space: nowrap;
}
.fl-btn-view {
    background: rgba(16,185,129,.1);
    border-color: rgba(16,185,129,.25);
    color: var(--em) !important;
}
.fl-btn-view:hover {
    background: rgba(16,185,129,.2);
    border-color: var(--em);
    color: var(--em) !important;
}
.fl-btn-edit {
    background: rgba(59,130,246,.1);
    border-color: rgba(59,130,246,.25);
    color: #93c5fd !important;
}
.fl-btn-edit:hover {
    background: rgba(59,130,246,.2);
    border-color: #3b82f6;
    color: #93c5fd !important;
}

/* ── empty state ── */
.fl-empty {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--mute);
}
.fl-empty-icon { font-size: 2.5rem; margin-bottom: 1rem; opacity: .4; }
.fl-empty h3 { font-size: 1rem; color: var(--txt); margin-bottom: .5rem; }
.fl-empty p  { font-size: .75rem; }

/* ── pagination ── */
.fl-pages {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}
.fl-pages .pagination {
    display: flex;
    gap: .3rem;
    list-style: none;
    padding: 0; margin: 0;
}
.fl-pages .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2rem;
    height: 2rem;
    padding: 0 .6rem;
    font-family: 'JetBrains Mono', monospace;
    font-size: .65rem;
    font-weight: 600;
    background: var(--card);
    border: 1px solid var(--bdr);
    border-radius: 5px;
    color: var(--mute) !important;
    text-decoration: none !important;
    transition: all .2s;
}
.fl-pages .page-link:hover {
    background: rgba(16,185,129,.12);
    border-color: var(--em);
    color: var(--em) !important;
}
.fl-pages .page-item.active .page-link {
    background: rgba(16,185,129,.2);
    border-color: var(--em);
    color: var(--em) !important;
}
.fl-pages .page-item.disabled .page-link {
    opacity: .35;
    cursor: not-allowed;
}

/* ── breadcrumb ── */
.bc { display: flex; align-items: center; gap: .4rem; margin-bottom: 1.5rem; }
.bc a, .bc span { font-size: .65rem; color: var(--mute); text-decoration: none !important; }
.bc a:hover { color: var(--em); }
.bc-sep { color: var(--sl3); }
.bc .active { color: var(--em); }

/* override global link color inside cards */
.fl-card a { color: inherit !important; }
</style>
@endsection

@section('content_header')@endsection

@section('content')
@php
/* ── stats ── */
$allPireps = \App\Models\Pirep::where('user_id', $user->id)
    ->whereNotIn('state', [
        \App\Models\Enums\PirepState::CANCELLED,
        \App\Models\Enums\PirepState::DELETED,
    ])
    ->select(['state', 'flight_time'])
    ->get();

$totalFlights    = $allPireps->count();
$acceptedFlights = $allPireps->where('state', \App\Models\Enums\PirepState::ACCEPTED)->count();
$inProgress      = $allPireps->whereIn('state', [
    \App\Models\Enums\PirepState::IN_PROGRESS,
    \App\Models\Enums\PirepState::PAUSED,
])->count();

$totalMins  = $allPireps->where('state', \App\Models\Enums\PirepState::ACCEPTED)->sum('flight_time');
$loggedHrs  = floor($totalMins / 60);
$loggedMins = $totalMins % 60;
@endphp

<div class="fl-wrap">
<div class="container-fluid px-4">

    {{-- breadcrumb --}}
    <nav class="bc">
        <a href="{{ Auth::check() ? route('frontend.dashboard.index') : route('frontend.home') }}">
            <i class="fas fa-th-large"></i> Pilot Center
        </a>
        <span class="bc-sep">/</span>
        <span class="active">Flight Log</span>
    </nav>

    {{-- header --}}
    <div class="fl-header">
        <div class="fl-headline">
            <span class="fl-headline-label"><i class="fas fa-list"></i></span>
            <h1>Flight Log</h1>
        </div>
        <a href="{{ route('frontend.pireps.create') }}" class="btn-new-pirep">
            <i class="fas fa-plus"></i> File New Report
        </a>
    </div>

    {{-- stats row --}}
    <div class="fl-stats-row">
        <div class="fl-stat">
            <div class="fl-stat-val">{{ number_format($totalFlights) }}</div>
            <div class="fl-stat-lbl">Total Flights</div>
        </div>
        <div class="fl-stat">
            <div class="fl-stat-val" style="color:var(--em)">{{ number_format($acceptedFlights) }}</div>
            <div class="fl-stat-lbl">Accepted</div>
        </div>
        <div class="fl-stat">
            <div class="fl-stat-val">{{ $loggedHrs }}<span style="font-size:.9rem;color:var(--mute)">h</span>{{ str_pad($loggedMins,2,'0',STR_PAD_LEFT) }}<span style="font-size:.9rem;color:var(--mute)">m</span></div>
            <div class="fl-stat-lbl">Hours Logged</div>
        </div>
        <div class="fl-stat">
            <div class="fl-stat-val" style="color:var(--blu)">{{ $inProgress }}</div>
            <div class="fl-stat-lbl">Active / Paused</div>
        </div>
    </div>

    @include('flash::message')

    {{-- flight cards --}}
    @include('pireps.table')

</div>
</div>
@endsection
