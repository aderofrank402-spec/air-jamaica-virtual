@extends('app')
@section('title', __('common.profile'))

@section('content')
@php
$breadcrumbs = [
    ['title' => 'Pilot Center', 'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'), 'icon' => 'bi bi-speedometer2'],
    ['title' => __('common.profile'), 'icon' => 'bi bi-person-circle'],
];
$totalMinutes = $user->pireps()->where('state', \App\Models\Enums\PirepState::ACCEPTED)->sum('flight_time') ?? 0;
$totalHours   = floor($totalMinutes / 60);
$totalMins    = $totalMinutes % 60;

$lastAccepted = $user->pireps()
    ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
    ->orderByDesc('submitted_at')
    ->first();
$currentAirport = $lastAccepted?->arr_airport ?? $user->current_airport ?? null;

$stateMap = [
    0 => ['label' => 'IN PROGRESS', 'color' => '#fbbf24'],
    1 => ['label' => 'PENDING',     'color' => '#f97316'],
    2 => ['label' => 'ACCEPTED',    'color' => '#10b981'],
    3 => ['label' => 'CANCELLED',   'color' => '#6b7280'],
    4 => ['label' => 'DELETED',     'color' => '#6b7280'],
    5 => ['label' => 'DRAFT',       'color' => '#94a3b8'],
    6 => ['label' => 'REJECTED',    'color' => '#ef4444'],
    7 => ['label' => 'PAUSED',      'color' => '#fbbf24'],
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
:root {
    --em:  #10b981;
    --em2: rgba(16,185,129,0.12);
    --em3: rgba(16,185,129,0.28);
    --sl:  #020617;
    --pan: rgba(2,6,23,0.82);
    --bd:  rgba(255,255,255,0.07);
    --amb: #fbbf24;
    --red: #ef4444;
    --mono: 'JetBrains Mono','Courier New',monospace;
}
body {
    background: linear-gradient(160deg,#020617 0%,#0a1628 55%,#010b1a 100%) !important;
    background-attachment: fixed;
    min-height: 100vh;
    color: #e2e8f0;
    font-family: 'JetBrains Mono', monospace !important;
    font-size: 16px;
}

/* PAGE WRAPPER */
.profile-page { max-width: 1280px; margin: 0 auto; padding: 1.5rem 1rem 4rem; }

/* PILOT ID CARD */
.pilot-id-card {
    background: linear-gradient(135deg, rgba(16,185,129,0.07) 0%, rgba(2,6,23,0.92) 70%);
    border: 1px solid var(--em3);
    border-radius: 16px;
    padding: 2rem 2.5rem;
    margin-bottom: 1.5rem;
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 2rem;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.pilot-id-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent 0%, var(--em) 50%, transparent 100%);
}
.pilot-avatar {
    width: 88px; height: 88px; border-radius: 50%;
    border: 2px solid var(--em3); object-fit: cover;
}
.pilot-name {
    font-size: 1.75rem; font-weight: 900; color: #fff;
    margin: 0 0 0.25rem; letter-spacing: -0.02em;
}
.pilot-ident {
    font-family: var(--mono); font-size: 0.78rem;
    color: var(--em); letter-spacing: 0.15em;
    text-transform: uppercase; margin-bottom: 0.75rem;
}
.pilot-meta { display: flex; flex-wrap: wrap; gap: 1.25rem; }
.pilot-meta-item {
    font-size: 0.78rem; color: rgba(255,255,255,0.5);
    display: flex; align-items: center; gap: 0.4rem;
}
.pilot-meta-item i { color: var(--em); font-size: 0.7rem; }
.rank-badge {
    background: var(--em2); border: 1px solid var(--em3);
    border-radius: 10px; padding: 1rem 1.5rem;
    text-align: center; min-width: 148px;
}
.rank-badge .rank-label {
    font-family: var(--mono); font-size: 0.62rem;
    color: var(--em); letter-spacing: 0.12em;
    text-transform: uppercase; margin-bottom: 0.3rem;
}
.rank-badge .rank-name { font-size: 0.95rem; font-weight: 700; color: #fff; }
.rank-progress-bar {
    height: 3px; background: rgba(255,255,255,0.1);
    border-radius: 2px; margin-top: 0.55rem; overflow: hidden;
}

/* STATS BAR */
.stats-bar {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 1rem; margin-bottom: 1.5rem;
}
.stat-cell {
    background: var(--pan); border: 1px solid var(--bd);
    border-radius: 12px; padding: 1.25rem; text-align: center;
    transition: border-color .2s;
}
.stat-cell:hover { border-color: var(--em3); }
.stat-cell .val {
    font-family: var(--mono); font-size: 1.55rem; font-weight: 900;
    color: var(--amb); line-height: 1; margin-bottom: 0.3rem;
}
.stat-cell .lbl {
    font-size: 0.62rem; font-weight: 700;
    color: rgba(255,255,255,0.38); text-transform: uppercase; letter-spacing: 0.12em;
}

/* LAYOUT GRID */
.profile-grid {
    display: grid; grid-template-columns: 260px 1fr;
    gap: 1.25rem; margin-bottom: 1.25rem;
}

/* PANEL */
.panel {
    background: var(--pan); border: 1px solid var(--bd);
    border-radius: 12px; overflow: hidden; margin-bottom: 1.25rem;
}
.panel-hd {
    padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--bd);
    display: flex; align-items: center; justify-content: space-between;
    font-size: 0.68rem; font-weight: 800;
    color: var(--em); letter-spacing: 0.14em; text-transform: uppercase;
}
.panel-hd-left { display: flex; align-items: center; gap: 0.55rem; }
.panel-bd { padding: 1.25rem; }

/* QUICK ACTIONS */
.qa-btn {
    display: flex; align-items: center; gap: 0.6rem;
    padding: 0.6rem 0.9rem; margin-bottom: 0.45rem;
    border-radius: 8px; border: 1px solid var(--bd);
    color: rgba(255,255,255,0.72); font-size: 0.78rem; font-weight: 600;
    text-decoration: none; transition: all .18s;
    background: rgba(255,255,255,0.02);
}
.qa-btn:hover {
    border-color: var(--em3); background: var(--em2);
    color: #fff; text-decoration: none;
}
.qa-btn i { color: var(--em); width: 14px; text-align: center; font-size: 0.72rem; }

/* HUD ROWS */
.hud-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 0.77rem; padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.04);
}
.hud-row:last-child { border-bottom: none; }
.hud-row .lbl { color: rgba(255,255,255,0.38); font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.08em; }
.hud-row .val { font-family: var(--mono); color: #e2e8f0; font-weight: 600; }

/* FLIGHT LOG CARDS */
.flight-card {
    display: block; border: 1px solid var(--bd); border-radius: 10px;
    padding: 1rem 1.25rem; margin-bottom: 0.65rem;
    text-decoration: none; transition: all .18s;
    background: rgba(255,255,255,0.015); position: relative; overflow: hidden;
}
.flight-card::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
    background: var(--em); opacity: 0; transition: opacity .18s;
}
.flight-card:hover { border-color: var(--em3); background: var(--em2); text-decoration: none; }
.flight-card:hover::before { opacity: 1; }
.fc-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.45rem; }
.fc-ident { font-size: 1.05rem; font-weight: 900; color: #fff; }
.fc-status {
    font-family: var(--mono); font-size: 0.58rem; font-weight: 800;
    padding: 0.18rem 0.5rem; border-radius: 4px;
    letter-spacing: 0.08em; border: 1px solid currentColor;
}
.fc-route {
    font-family: var(--mono); font-size: 0.82rem; color: var(--em);
    margin-bottom: 0.45rem; letter-spacing: 0.04em;
}
.fc-route .fa-chevron-right { font-size: 0.6rem; opacity: .5; margin: 0 0.35rem; }
.fc-meta { display: flex; flex-wrap: wrap; gap: 1rem; }
.fc-meta span {
    font-size: 0.68rem; color: rgba(255,255,255,0.38);
    display: flex; align-items: center; gap: 0.3rem;
}
.fc-meta i { color: rgba(16,185,129,0.5); font-size: 0.62rem; }

/* PERFORMANCE GRID */
.perf-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 0.7rem;
}
.perf-cell {
    background: rgba(255,255,255,0.025); border: 1px solid var(--bd);
    border-radius: 8px; padding: 0.85rem; text-align: center;
}
.perf-cell .val {
    font-family: var(--mono); font-size: 1.15rem; font-weight: 900;
    color: var(--amb); line-height: 1; margin-bottom: 0.28rem;
}
.perf-cell .lbl {
    font-size: 0.6rem; font-weight: 700;
    color: rgba(255,255,255,0.32); text-transform: uppercase; letter-spacing: 0.1em;
}

/* AWARDS */
.awards-grid {
    display: grid; grid-template-columns: repeat(auto-fill,minmax(172px,1fr)); gap: 1rem;
}
.award-cell {
    background: rgba(255,255,255,0.02); border: 1px solid var(--bd);
    border-radius: 10px; padding: 1.2rem; text-align: center; transition: all .18s;
}
.award-cell:hover { border-color: var(--em3); background: var(--em2); }
.award-img {
    width: 60px; height: 60px; border-radius: 50%;
    background: var(--em2); border: 1px solid var(--em3);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 0.7rem; overflow: hidden;
}
.award-img img { width: 44px; height: 44px; object-fit: contain; border-radius: 5px; }
.award-img i { color: var(--amb); font-size: 1.4rem; }
.award-name { font-size: 0.82rem; font-weight: 700; color: var(--amb); margin-bottom: 0.35rem; }
.award-desc { font-size: 0.72rem; color: rgba(255,255,255,0.45); line-height: 1.5; }
.award-desc p { margin: 0; }

/* TYPE RATINGS */
.tr-grid {
    display: grid; grid-template-columns: repeat(auto-fill,minmax(130px,1fr)); gap: 0.7rem;
}
.tr-cell {
    background: rgba(255,255,255,0.02); border: 1px solid var(--bd);
    border-radius: 8px; padding: 0.9rem; text-align: center; transition: border-color .18s;
}
.tr-cell:hover { border-color: var(--em3); }
.tr-cell .tr-name { font-size: 0.78rem; font-weight: 700; color: var(--em); margin-bottom: 0.2rem; }
.tr-cell .tr-type { font-size: 0.62rem; color: rgba(255,255,255,0.32); text-transform: uppercase; letter-spacing: 0.08em; }

/* RESPONSIVE */
@media (max-width: 960px) {
    .pilot-id-card { grid-template-columns: auto 1fr; }
    .rank-badge { display: none; }
    .profile-grid { grid-template-columns: 1fr; }
    .stats-bar { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 560px) {
    .perf-grid { grid-template-columns: 1fr; }
    .stats-bar { grid-template-columns: 1fr 1fr; }
}
</style>

<div class="profile-page">

    {{-- PILOT ID CARD --}}
    <div class="pilot-id-card">
        @php
            try { $avatarUrl = $user->resolveAvatarUrl(); }
            catch (\Exception $e) { $avatarUrl = $user->gravatar(512); }
        @endphp
        <img src="{{ $avatarUrl }}" class="pilot-avatar" alt="Avatar"
             onerror="this.src='{{ $user->gravatar(256) }}'">

        <div>
            <div class="pilot-name">{{ $user->name_private }}</div>
            <div class="pilot-ident">
                <i class="fas fa-id-badge"></i> {{ $user->ident }}
                @if(filled($user->callsign)) &middot; {{ $user->callsign }} @endif
                @if($user->country) <span class="fi fi-{{ $user->country }} ms-1"></span> @endif
            </div>
            <div class="pilot-meta">
                <span class="pilot-meta-item"><i class="fas fa-building"></i> {{ $user->airline->name }}</span>
                @if($user->home_airport)
                    <span class="pilot-meta-item"><i class="fas fa-home"></i> Home: {{ $user->home_airport->icao }}</span>
                @endif
                @if($currentAirport)
                    <span class="pilot-meta-item"><i class="fas fa-map-marker-alt"></i> Current: {{ $currentAirport->icao }}</span>
                @endif
                @if($user->last_pirep)
                    <span class="pilot-meta-item"><i class="fas fa-clock"></i> Last flight {{ $user->last_pirep->submitted_at?->diffForHumans() ?? 'N/A' }}</span>
                @endif
            </div>
        </div>

        <div class="rank-badge">
            <div class="rank-label">Current Rank</div>
            @if(!empty($user->rank->image_url))
                <img src="{{ $user->rank->image_url }}"
                     style="width:34px;height:34px;object-fit:contain;display:block;margin:0 auto 0.35rem;"
                     alt="{{ $user->rank->name }}">
            @endif
            <div class="rank-name">{{ $user->rank->name }}</div>
            @if(!empty($career_progress['next_rank']))
                <div class="rank-progress-bar">
                    <div style="height:100%;background:var(--em);border-radius:2px;width:{{ $career_progress['rank_progress'] }}%;"></div>
                </div>
                <div style="font-size:0.58rem;color:rgba(255,255,255,0.28);margin-top:0.28rem;">
                    {{ $career_progress['rank_progress'] }}% &rarr; {{ $career_progress['next_rank']->name }}
                </div>
            @endif
        </div>
    </div>

    {{-- STATS BAR --}}
    <div class="stats-bar">
        <div class="stat-cell">
            <div class="val">{{ number_format($user->flights) }}</div>
            <div class="lbl">Flights</div>
        </div>
        <div class="stat-cell">
            <div class="val">{{ $totalHours }}h {{ str_pad($totalMins,2,'0',STR_PAD_LEFT) }}m</div>
            <div class="lbl">Flight Hours</div>
        </div>
        <div class="stat-cell">
            <div class="val">{{ number_format($flight_stats['total_distance']) }}</div>
            <div class="lbl">NM Flown</div>
        </div>
        <div class="stat-cell">
            <div class="val">{{ $flight_stats['countries_visited'] }}</div>
            <div class="lbl">Countries</div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="profile-grid">

        {{-- LEFT COLUMN --}}
        <div>
            @if(Auth::check() && $user->id === Auth::user()->id)
            <div class="panel">
                <div class="panel-hd">
                    <span class="panel-hd-left"><i class="fas fa-terminal"></i> Quick Access</span>
                </div>
                <div class="panel-bd">
                    <a href="{{ route('frontend.flights.index') }}"  class="qa-btn"><i class="fas fa-search"></i> Browse Flights</a>
                    <a href="{{ route('frontend.pireps.create') }}"  class="qa-btn"><i class="fas fa-file-alt"></i> File PIREP</a>
                    <a href="{{ route('frontend.flights.bids') }}"   class="qa-btn"><i class="fas fa-bookmark"></i> My Bids</a>
                    <a href="{{ route('frontend.pireps.index') }}"   class="qa-btn"><i class="fas fa-history"></i> All Flight Logs</a>
                    @if(isset($acars) && $acars)
                        <a href="{{ route('frontend.profile.acars') }}" class="qa-btn"
                           onclick="alert('Save to My Documents/vmsacars/profiles')">
                            <i class="fas fa-download"></i> ACARS Config
                        </a>
                    @endif
                    <a href="{{ route('frontend.profile.edit', [$user->id]) }}" class="qa-btn"><i class="fas fa-edit"></i> Edit Profile</a>
                </div>
            </div>
            @endif

            <div class="panel">
                <div class="panel-hd"><span class="panel-hd-left"><i class="fas fa-tachometer-alt"></i> Pilot Stats</span></div>
                <div class="panel-bd">
                    @php
                        $avgScore = $flight_stats['avg_score'];
                        $avgLR    = round($flight_stats['avg_landing_rate']);
                        $absLR    = abs($avgLR);
                        $lrColor  = ($absLR >= 50 && $absLR <= 200) ? '#10b981' : ($absLR > 350 ? '#ef4444' : '#fbbf24');
                        $scColor  = $avgScore >= 80 ? '#10b981' : ($avgScore >= 60 ? '#fbbf24' : '#ef4444');
                    @endphp
                    <div class="hud-row">
                        <span class="lbl">Avg Score</span>
                        <span class="val" style="color:{{ $scColor }}">{{ number_format($avgScore,1) }}</span>
                    </div>
                    <div class="hud-row">
                        <span class="lbl">Avg Landing</span>
                        <span class="val" style="color:{{ $lrColor }}">{{ $avgLR }} fpm</span>
                    </div>
                    <div class="hud-row">
                        <span class="lbl">Total Distance</span>
                        <span class="val">{{ number_format($flight_stats['total_distance']) }} NM</span>
                    </div>
                    @if(setting('pilots.allow_transfer_hours') === true && $user->transfer_time > 0)
                    <div class="hud-row">
                        <span class="lbl">Transfer Hrs</span>
                        <span class="val">@minutestohours($user->transfer_time)</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($userFields && $userFields->count() > 0)
            <div class="panel">
                <div class="panel-hd"><span class="panel-hd-left"><i class="fas fa-info-circle"></i> Pilot Data</span></div>
                <div class="panel-bd">
                    @foreach($userFields as $field)
                    <div class="hud-row">
                        <span class="lbl">{{ $field->field->name }}</span>
                        <span class="val">{{ $field->value ?? 'N/A' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT COLUMN --}}
        <div>
            {{-- MISSION LOG --}}
            <div class="panel">
                <div class="panel-hd">
                    <span class="panel-hd-left"><i class="fas fa-database"></i> Mission Log</span>
                    @if(Auth::check() && $user->id === Auth::user()->id)
                        <a href="{{ route('frontend.pireps.index') }}"
                           style="font-size:0.62rem;color:var(--em);text-decoration:none;letter-spacing:.08em;font-weight:700;">
                            VIEW ALL &rsaquo;
                        </a>
                    @endif
                </div>
                <div class="panel-bd">
                    @forelse($recent_pireps as $pirep)
                        @php
                            $stInfo = $stateMap[$pirep->state] ?? ['label' => 'UNKNOWN', 'color' => '#6b7280'];
                            $mins   = $pirep->flight_time;
                            $dur    = floor($mins/60).'h '.str_pad($mins%60,2,'0',STR_PAD_LEFT).'m';
                        @endphp
                        <a href="{{ route('frontend.pireps.show', $pirep->id) }}" class="flight-card">
                            <div class="fc-header">
                                <span class="fc-ident">{{ $pirep->ident }}</span>
                                <span class="fc-status" style="color:{{ $stInfo['color'] }};border-color:{{ $stInfo['color'] }}">{{ $stInfo['label'] }}</span>
                            </div>
                            <div class="fc-route">
                                {{ $pirep->dpt_airport_id }}
                                <i class="fas fa-chevron-right"></i>
                                {{ $pirep->arr_airport_id }}
                            </div>
                            <div class="fc-meta">
                                @if($pirep->aircraft)
                                    <span><i class="fas fa-plane"></i> {{ $pirep->aircraft->registration ?? $pirep->aircraft->name }}</span>
                                @endif
                                @if($pirep->flight_time > 0)
                                    <span><i class="fas fa-clock"></i> {{ $dur }}</span>
                                @endif
                                @php $distNmi = $pirep->distance instanceof \App\Support\Units\Distance ? round($pirep->distance['nmi']) : (int)$pirep->distance; @endphp
                                @if($distNmi > 0)
                                    <span><i class="fas fa-route"></i> {{ number_format($distNmi) }} NM</span>
                                @endif
                                @if($pirep->submitted_at)
                                    <span><i class="fas fa-calendar-alt"></i> {{ $pirep->submitted_at->format('d M Y') }}</span>
                                @endif
                                @if($pirep->landing_rate && $pirep->landing_rate < 0)
                                    <span><i class="fas fa-plane-arrival"></i> {{ $pirep->landing_rate }} fpm</span>
                                @endif
                                @if($pirep->score > 0)
                                    <span><i class="fas fa-star"></i> {{ $pirep->score }}</span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div style="text-align:center;padding:2.5rem;color:rgba(255,255,255,0.22);">
                            <i class="fas fa-plane-slash d-block mb-2" style="font-size:2rem;opacity:.3;"></i>
                            <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;">No flights recorded yet</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- PERFORMANCE OVERVIEW --}}
            @if($flight_stats['avg_score'] > 0 || $flight_stats['total_distance'] > 0)
            <div class="panel">
                <div class="panel-hd"><span class="panel-hd-left"><i class="fas fa-chart-line"></i> Performance Overview</span></div>
                <div class="panel-bd">
                    @php
                        $absLR2  = abs(round($flight_stats['avg_landing_rate']));
                        $lrCol2  = ($absLR2 >= 50 && $absLR2 <= 200) ? '#10b981' : ($absLR2 > 350 ? '#ef4444' : '#fbbf24');
                        $scCol2  = $flight_stats['avg_score'] >= 80 ? '#10b981' : ($flight_stats['avg_score'] >= 60 ? '#fbbf24' : '#ef4444');
                    @endphp
                    <div class="perf-grid">
                        <div class="perf-cell">
                            <div class="val" style="color:{{ $scCol2 }}">{{ number_format($flight_stats['avg_score'],1) }}</div>
                            <div class="lbl">Avg Score</div>
                        </div>
                        <div class="perf-cell">
                            <div class="val" style="color:{{ $lrCol2 }}">{{ round($flight_stats['avg_landing_rate']) }}</div>
                            <div class="lbl">Avg Landing fpm</div>
                        </div>
                        <div class="perf-cell">
                            <div class="val">{{ number_format($flight_stats['total_distance']) }}</div>
                            <div class="lbl">Total NM</div>
                        </div>
                        <div class="perf-cell">
                            <div class="val">{{ $flight_stats['countries_visited'] }}</div>
                            <div class="lbl">Countries</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- TYPE RATINGS --}}
    @if($user->typeratings && $user->typeratings->count() > 0)
    <div class="panel">
        <div class="panel-hd"><span class="panel-hd-left"><i class="fas fa-certificate"></i> Type Ratings</span></div>
        <div class="panel-bd">
            <div class="tr-grid">
                @foreach($user->typeratings as $tr)
                <div class="tr-cell">
                    @if($tr->image_url)
                        <img src="{{ $tr->image_url }}" style="width:38px;height:38px;object-fit:contain;display:block;margin:0 auto .5rem;" alt="{{ $tr->name }}">
                    @else
                        <i class="fas fa-certificate" style="font-size:1.4rem;color:var(--em);display:block;margin-bottom:.5rem;"></i>
                    @endif
                    <div class="tr-name">{{ $tr->name }}</div>
                    @if($tr->type)<div class="tr-type">{{ $tr->type }}</div>@endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- AWARDS (HTML bug fixed: {!! instead of {{ for description) --}}
    @if($user->awards && $user->awards->count() > 0)
    <div class="panel">
        <div class="panel-hd"><span class="panel-hd-left"><i class="fas fa-trophy"></i> @lang('profile.your-awards')</span></div>
        <div class="panel-bd">
            <div class="awards-grid">
                @foreach($user->awards as $award)
                <div class="award-cell">
                    @if($award->image && trim($award->image))
                        <div class="award-img">
                            <img src="{{ $award->image }}" alt="{{ $award->name }}"
                                 onerror="this.style.display='none';this.parentElement.querySelector('i').style.display='block';">
                            <i class="fas fa-medal" style="display:none;"></i>
                        </div>
                    @else
                        <div class="award-img"><i class="fas fa-medal"></i></div>
                    @endif
                    <div class="award-name">{{ $award->name }}</div>
                    <div class="award-desc">{!! $award->description !!}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
