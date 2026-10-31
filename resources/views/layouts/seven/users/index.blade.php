@extends('app')
@section('title', trans_choice('common.pilot', 2))

@section('content')
@php
$breadcrumbs = [
    [
        'title' => 'Pilot Center',
        'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
        'icon' => Auth::check() ? 'bi bi-speedometer2' : 'bi bi-house-fill'
    ],
    [
        'title' => trans_choice('common.pilot', 2),
        'icon' => 'bi bi-people-fill'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --em: #10b981;
    --em2: rgba(16,185,129,.12);
    --sl: #020617;
    --sl2: #1e293b;
    --sl3: #0f172a;
    --amb: #fbbf24;
    --blu: #3b82f6;
    --tx: #e2e8f0;
    --tx2: #94a3b8;
}

body, .pl-wrap * {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: 16px;
}

.pl-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.5rem 1rem 3rem;
}

/* ── Page header ── */
.pl-header {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 2rem;
    padding: 1.25rem 1.75rem;
    background: rgba(15,23,42,.85);
    border: 1px solid var(--em2);
    border-radius: 14px;
    backdrop-filter: blur(10px);
}

.pl-header-icon {
    width: 46px;
    height: 46px;
    border-radius: 10px;
    background: rgba(16,185,129,.15);
    border: 1px solid var(--em2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--em);
    font-size: 1.2rem;
    flex-shrink: 0;
}

.pl-header-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--tx);
    margin: 0 0 .15rem;
    letter-spacing: -.4px;
}

.pl-header-sub {
    font-size: .72rem;
    color: var(--tx2);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* ── Stat cards ── */
.pl-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.pl-stat {
    background: rgba(15,23,42,.85);
    border: 1px solid var(--em2);
    border-radius: 12px;
    padding: 1.25rem 1.25rem 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    backdrop-filter: blur(10px);
}

.pl-stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.pl-stat-icon.gold  { background: rgba(251,191,36,.12); color: var(--amb); border: 1px solid rgba(251,191,36,.2); }
.pl-stat-icon.green { background: rgba(16,185,129,.12); color: var(--em); border: 1px solid var(--em2); }
.pl-stat-icon.blue  { background: rgba(59,130,246,.12); color: var(--blu); border: 1px solid rgba(59,130,246,.2); }
.pl-stat-icon.red   { background: rgba(239,68,68,.1); color: #f87171; border: 1px solid rgba(239,68,68,.18); }

.pl-stat-val {
    font-size: 1.65rem;
    font-weight: 700;
    color: var(--tx);
    line-height: 1;
    margin-bottom: .25rem;
}

.pl-stat-lbl {
    font-size: .68rem;
    color: var(--tx2);
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* ── Filters ── */
.pl-filters {
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: .875rem;
    margin-bottom: 1.5rem;
    align-items: center;
}

.pl-search-wrap {
    position: relative;
}

.pl-search-wrap i {
    position: absolute;
    left: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--tx2);
    font-size: .82rem;
    pointer-events: none;
}

.pl-search-wrap input {
    width: 100%;
    background: rgba(15,23,42,.7);
    border: 1px solid rgba(16,185,129,.2);
    border-radius: 8px;
    color: var(--tx);
    font-family: 'JetBrains Mono', monospace;
    font-size: .85rem;
    padding: .6rem .9rem .6rem 2.2rem;
    outline: none;
    transition: border-color .2s;
}

.pl-search-wrap input::placeholder { color: var(--tx2); opacity: .7; }
.pl-search-wrap input:focus { border-color: var(--em); }

.pl-filter-select {
    background: rgba(15,23,42,.7);
    border: 1px solid rgba(16,185,129,.2);
    border-radius: 8px;
    color: var(--tx);
    font-family: 'JetBrains Mono', monospace;
    font-size: .85rem;
    padding: .6rem .9rem;
    outline: none;
    min-width: 140px;
    cursor: pointer;
    transition: border-color .2s;
}

.pl-filter-select:focus { border-color: var(--em); }
.pl-filter-select option { background: #1e293b; color: #e2e8f0; }

/* ── Main card ── */
.pl-card {
    background: rgba(15,23,42,.85);
    border: 1px solid var(--em2);
    border-radius: 14px;
    backdrop-filter: blur(10px);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.pl-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--em2);
}

.pl-card-title {
    font-size: .82rem;
    font-weight: 700;
    color: var(--tx);
    text-transform: uppercase;
    letter-spacing: .6px;
    display: flex;
    align-items: center;
    gap: .5rem;
}

.pl-card-title i { color: var(--em); }

.pl-count-badge {
    background: rgba(16,185,129,.12);
    border: 1px solid var(--em2);
    color: var(--em);
    font-size: .72rem;
    font-weight: 700;
    padding: .2rem .65rem;
    border-radius: 20px;
    font-family: 'JetBrains Mono', monospace;
}

/* ── Pagination ── */
.pl-pagination {
    display: flex;
    justify-content: center;
    margin-top: 1.5rem;
}

.pagination .page-link {
    background: rgba(15,23,42,.7);
    border: 1px solid rgba(16,185,129,.15);
    color: var(--tx2);
    font-family: 'JetBrains Mono', monospace;
    font-size: .8rem;
    padding: .45rem .85rem;
    margin: 0 .2rem;
    border-radius: 7px;
    transition: background .15s, color .15s;
}

.pagination .page-link:hover {
    background: rgba(16,185,129,.12);
    color: var(--em);
    border-color: var(--em2);
}

.pagination .page-item.active .page-link {
    background: var(--em);
    color: #020617;
    border-color: var(--em);
    font-weight: 700;
}

.pagination .page-item.disabled .page-link {
    opacity: .4;
}

/* text helpers */
.text-muted { color: var(--tx2) !important; }

@media (max-width: 992px) {
    .pl-stats { grid-template-columns: repeat(2, 1fr); }
    .pl-filters { grid-template-columns: 1fr 1fr; }
    .pl-search-wrap { grid-column: 1 / -1; }
}

@media (max-width: 576px) {
    .pl-stats { grid-template-columns: repeat(2, 1fr); }
    .pl-filters { grid-template-columns: 1fr; }
}
</style>

<div class="pl-wrap">

    {{-- Header --}}
    <div class="pl-header">
        <div class="pl-header-icon"><i class="fas fa-users"></i></div>
        <div>
            <p class="pl-header-title">{{ trans_choice('common.pilot', 2) }}</p>
            <p class="pl-header-sub">Pilot Directory &mdash; Air Jamaica Virtual</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="pl-stats">
        <div class="pl-stat">
            <div class="pl-stat-icon gold"><i class="fas fa-users"></i></div>
            <div>
                <div class="pl-stat-val">{{ $users->total() }}</div>
                <div class="pl-stat-lbl">Total Pilots</div>
            </div>
        </div>
        <div class="pl-stat">
            <div class="pl-stat-icon green"><i class="fas fa-user-check"></i></div>
            <div>
                <div class="pl-stat-val">{{ $users->where('state', \App\Models\Enums\UserState::ACTIVE)->count() }}</div>
                <div class="pl-stat-lbl">Active Pilots</div>
            </div>
        </div>
        <div class="pl-stat">
            <div class="pl-stat-icon blue"><i class="fas fa-plane-departure"></i></div>
            <div>
                <div class="pl-stat-val">{{ number_format($users->sum('flights')) }}</div>
                <div class="pl-stat-lbl">Total Flights</div>
            </div>
        </div>
        <div class="pl-stat">
            <div class="pl-stat-icon red"><i class="fas fa-clock"></i></div>
            <div>
                <div class="pl-stat-val">{{ number_format($users->sum('flight_time') / 60, 0) }}</div>
                <div class="pl-stat-lbl">Flight Hours</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="pl-filters">
        <div class="pl-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="pilotSearch" placeholder="Search pilots...">
        </div>
        <select class="pl-filter-select" id="airlineFilter">
            <option value="">All Airlines</option>
            @foreach($users->groupBy('airline.icao') as $airline => $group)
                @if($airline)
                    <option value="{{ $airline }}">{{ $airline }}</option>
                @endif
            @endforeach
        </select>
        <select class="pl-filter-select" id="statusFilter">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    {{-- Table card --}}
    <div class="pl-card">
        <div class="pl-card-header">
            <span class="pl-card-title"><i class="fas fa-list"></i> Pilot Directory</span>
            <span class="pl-count-badge">{{ $users->total() }} Total</span>
        </div>
        <div style="padding:0;">
            @include('layouts.seven.users.table')
        </div>
    </div>

    {{-- Pagination --}}
    <div class="pl-pagination">
        {{ $users->withQueryString()->links('pagination.bootstrap-5') }}
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput  = document.getElementById('pilotSearch');
    const airlineFilter = document.getElementById('airlineFilter');
    const statusFilter  = document.getElementById('statusFilter');
    const tableRows     = document.querySelectorAll('#users-table tbody tr');

    function filterTable() {
        const searchTerm     = searchInput.value.toLowerCase();
        const selectedAirline = airlineFilter.value.toLowerCase();

        tableRows.forEach(row => {
            const name    = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const airline = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const matchesSearch  = name.includes(searchTerm);
            const matchesAirline = !selectedAirline || airline.includes(selectedAirline);
            row.style.display = (matchesSearch && matchesAirline) ? '' : 'none';
        });
    }

    if (searchInput)   searchInput.addEventListener('input', filterTable);
    if (airlineFilter) airlineFilter.addEventListener('change', filterTable);
    if (statusFilter)  statusFilter.addEventListener('change', filterTable);
});
</script>
@endsection
