@extends('layouts.seven.public_app')
@section('title', 'Mission Control')

@section('css')
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --em:   #10b981;
    --em2:  #059669;
    --sl:   #020617;
    --sl1:  #0f172a;
    --sl2:  #1e293b;
    --sl3:  #334155;
    --amb:  #fbbf24;
    --red:  #ef4444;
    --blu:  #3b82f6;
    --pur:  #8b5cf6;
    --txt:  #e2e8f0;
    --mute: #94a3b8;
    --bdr:  rgba(16,185,129,.14);
    --bdr-h:rgba(16,185,129,.4);
    --card: rgba(15,23,42,.88);
}
*, *::before, *::after { box-sizing: border-box; }
body {
    font-family: 'JetBrains Mono', monospace !important;
    background: var(--sl) !important;
    color: var(--txt) !important;
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
    font-size: 15px;
}
body::before {
    content: '';
    position: fixed;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 50% at 10% 0%,   rgba(16,185,129,.08) 0%, transparent 60%),
        radial-gradient(ellipse 60% 40% at 90% 100%, rgba(251,191,36,.06)  0%, transparent 60%);
    pointer-events: none;
    z-index: 0;
}

.mc-wrap { position: relative; z-index: 1; padding: 90px 0 5rem; }

/* ── banner ── */
.mc-banner {
    background: var(--card);
    border: 1px solid var(--bdr);
    border-radius: 14px;
    backdrop-filter: blur(10px);
    padding: 1.75rem 2.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.25rem;
    flex-wrap: wrap;
}
.mc-banner-eyebrow {
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--em);
    margin-bottom: .4rem;
}
.mc-banner-name { font-size: 1.85rem; font-weight: 700; color: var(--txt); margin: 0 0 .35rem; line-height: 1; }
.mc-banner-sub  { font-size: .85rem; color: var(--mute); }
.mc-banner-sub span { color: var(--em); }
.mc-banner-right { text-align: right; font-size: .82rem; color: var(--mute); line-height: 2; }

/* ── on-leave alert ── */
.mc-alert {
    display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
    background: rgba(251,191,36,.08);
    border: 1px solid rgba(251,191,36,.3);
    border-radius: 10px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    font-size: .88rem;
    color: var(--amb);
}
.mc-alert a {
    display: inline-flex; align-items: center; gap: .35rem;
    background: var(--amb); color: #000 !important;
    font-weight: 700; font-size: .78rem; letter-spacing: .07em; text-transform: uppercase;
    text-decoration: none !important;
    padding: .45rem 1rem; border-radius: 6px;
    margin-left: auto; white-space: nowrap;
}
.mc-alert a:hover { opacity: .85; }

/* ── stats bar ── */
.mc-stats {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 1rem;
    margin-bottom: 1.75rem;
}
@media (max-width: 1100px) { .mc-stats { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 560px)  { .mc-stats { grid-template-columns: repeat(2,1fr); } }

.mc-stat {
    background: var(--card);
    border: 1px solid var(--bdr);
    border-radius: 12px;
    padding: 1.25rem 1.1rem;
    backdrop-filter: blur(8px);
    text-align: center;
    transition: border-color .2s, transform .15s;
}
.mc-stat:hover { border-color: var(--bdr-h); transform: translateY(-2px); }
.mc-stat-val { font-size: 1.65rem; font-weight: 700; color: var(--em); line-height: 1; margin-bottom: .35rem; }
.mc-stat-lbl { font-size: .72rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: var(--mute); }

/* ── api key section ── */
.mc-apikey {
    background: var(--card);
    border: 1px solid rgba(139,92,246,.28);
    border-radius: 14px;
    padding: 1.4rem 2rem;
    margin-bottom: 1.75rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.mc-apikey-icon {
    width: 50px; height: 50px;
    background: rgba(139,92,246,.15);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: var(--pur); font-size: 1.3rem;
    flex-shrink: 0;
}
.mc-apikey-label { font-size: .75rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--pur); margin-bottom: .3rem; }
.mc-apikey-hint  { font-size: .8rem; color: var(--mute); margin-top: .25rem; }
.mc-apikey-field { display: flex; align-items: center; gap: .6rem; flex: 1; min-width: 260px; }
.mc-apikey-input {
    background: var(--sl2) !important;
    border: 1px solid rgba(139,92,246,.3) !important;
    border-radius: 8px !important;
    color: var(--txt) !important;
    font-family: 'JetBrains Mono', monospace !important;
    font-size: .88rem !important;
    padding: .55rem 1rem !important;
    flex: 1;
}
.mc-apikey-input:focus { outline: none; border-color: var(--pur) !important; box-shadow: 0 0 0 3px rgba(139,92,246,.18) !important; }
.mc-copy-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    background: rgba(139,92,246,.15);
    border: 1px solid rgba(139,92,246,.3);
    border-radius: 8px;
    color: var(--pur) !important;
    font-family: 'JetBrains Mono', monospace;
    font-size: .78rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    padding: .5rem 1.1rem; cursor: pointer;
    transition: background .2s;
    white-space: nowrap;
}
.mc-copy-btn:hover { background: rgba(139,92,246,.28); }
.mc-copy-btn.copied { background: rgba(16,185,129,.15); border-color: rgba(16,185,129,.3); color: var(--em) !important; }

/* ── active flight banner ── */
.mc-active-flight {
    background: rgba(59,130,246,.08);
    border: 1px solid rgba(59,130,246,.28);
    border-radius: 14px;
    padding: 1.25rem 1.75rem;
    margin-bottom: 1.75rem;
    display: flex; align-items: center; gap: 1.75rem; flex-wrap: wrap;
}
.mc-active-pill {
    display: inline-flex; align-items: center; gap: .4rem;
    background: rgba(59,130,246,.15); border: 1px solid rgba(59,130,246,.3);
    border-radius: 20px; padding: .35rem .9rem;
    font-size: .75rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    color: #93c5fd;
}
.mc-active-pill .blink { animation: blink 1.2s infinite; }
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
.mc-active-route { display: flex; align-items: center; gap: .9rem; flex: 1; }
.mc-active-iata { font-size: 1.6rem; font-weight: 700; color: var(--txt); letter-spacing: .06em; }
.mc-active-arrow { color: var(--em); font-size: 1rem; }
.mc-active-meta { font-size: .82rem; color: var(--mute); }
.mc-active-meta span { color: var(--txt); font-weight: 600; }
.mc-active-progress { flex: 1; min-width: 140px; }
.mc-active-progress-bar { height: 5px; background: var(--sl2); border-radius: 3px; overflow: hidden; margin-bottom: .4rem; }
.mc-active-progress-fill { height: 100%; background: linear-gradient(90deg,var(--em2),var(--em)); border-radius: 3px; }
.mc-active-pct { font-size: .75rem; color: var(--mute); text-align: right; }
.mc-active-link {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .8rem; font-weight: 600; color: #93c5fd !important;
    text-decoration: none !important; white-space: nowrap;
    transition: color .2s;
}
.mc-active-link:hover { color: var(--em) !important; }

/* ── three ops cards ── */
.mc-ops-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 1.75rem;
}
@media (max-width: 960px) { .mc-ops-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 620px) { .mc-ops-grid { grid-template-columns: 1fr; } }

.mc-op-card {
    background: var(--card);
    border: 1px solid var(--bdr);
    border-radius: 14px;
    backdrop-filter: blur(10px);
    overflow: hidden;
    display: flex; flex-direction: column;
    transition: border-color .2s, box-shadow .2s, transform .15s;
}
.mc-op-card:hover { border-color: var(--bdr-h); box-shadow: 0 6px 30px rgba(16,185,129,.12); transform: translateY(-2px); }

.mc-op-hdr {
    display: flex; align-items: center; gap: 1rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--bdr);
}
.mc-op-hdr-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.mc-op-hdr-icon.em  { background: rgba(16,185,129,.15); color: var(--em); }
.mc-op-hdr-icon.amb { background: rgba(251,191,36,.15);  color: var(--amb); }
.mc-op-hdr-icon.blu { background: rgba(59,130,246,.15);  color: #93c5fd; }
.mc-op-hdr-title {
    font-size: .88rem; font-weight: 700;
    letter-spacing: .06em; text-transform: uppercase;
    color: var(--txt);
}

.mc-op-body { padding: 1rem; flex: 1; display: flex; flex-direction: column; gap: .45rem; }

/* operation item rows */
.mc-op-item {
    display: flex; align-items: center; gap: 1rem;
    padding: .85rem 1rem;
    border-radius: 10px;
    border: 1px solid transparent;
    text-decoration: none !important;
    color: var(--txt) !important;
    transition: background .2s, border-color .2s;
    position: relative;
}
.mc-op-item:hover { background: rgba(16,185,129,.08); border-color: var(--bdr); color: var(--txt) !important; }
.mc-op-item.static { cursor: default; background: rgba(255,255,255,.025); }
.mc-op-item-icon {
    width: 40px; height: 40px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.mc-op-item-icon.em  { background: rgba(16,185,129,.13); color: var(--em); }
.mc-op-item-icon.amb { background: rgba(251,191,36,.13);  color: var(--amb); }
.mc-op-item-icon.blu { background: rgba(59,130,246,.13);  color: #93c5fd; }
.mc-op-item-icon.pur { background: rgba(139,92,246,.13);  color: var(--pur); }
.mc-op-item-icon.mute{ background: rgba(100,116,139,.12); color: var(--mute); }
.mc-op-item-icon.red { background: rgba(239,68,68,.13);   color: var(--red); }
.mc-op-item-content { flex: 1; min-width: 0; }
.mc-op-item-title { font-size: .9rem; font-weight: 600; color: var(--txt); line-height: 1.25; }
.mc-op-item-sub   { font-size: .76rem; color: var(--mute); margin-top: .15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.mc-op-badge {
    background: var(--amb); color: #000;
    font-size: .68rem; font-weight: 800;
    padding: .15rem .55rem; border-radius: 10px; min-width: 22px; text-align: center;
}
.mc-op-btn {
    display: inline-flex; align-items: center; gap: .3rem;
    font-family: 'JetBrains Mono', monospace;
    font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    background: transparent;
    border: 1px solid var(--bdr);
    border-radius: 6px;
    color: var(--mute) !important;
    text-decoration: none !important;
    padding: .3rem .7rem;
    transition: border-color .2s, color .2s;
}
.mc-op-btn:hover { border-color: var(--em); color: var(--em) !important; }

/* bid sub-row */
.mc-bid-row {
    display: flex; align-items: center; gap: .85rem;
    padding: .65rem 1rem;
    border-radius: 8px;
    background: rgba(16,185,129,.04);
    border: 1px solid rgba(16,185,129,.12);
    font-size: .82rem; color: var(--mute);
}
.mc-bid-route { flex: 1; font-size: .85rem; color: var(--txt); font-weight: 600; }

/* button row at bottom of card */
.mc-op-btn-row {
    display: grid; grid-template-columns: 1fr 1fr; gap: .5rem;
    padding: 1rem;
    border-top: 1px solid var(--bdr);
    margin-top: auto;
}
.mc-op-btn-full { grid-column: 1/-1; }

/* ── bottom widgets grid ── */
.mc-widgets-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 1.75rem;
}
@media (max-width: 900px) { .mc-widgets-grid { grid-template-columns: 1fr; } }

.mc-wcard {
    background: var(--card);
    border: 1px solid var(--bdr);
    border-radius: 14px;
    backdrop-filter: blur(10px);
    overflow: hidden;
}
.mc-wcard-hdr {
    display: flex; align-items: center; gap: .7rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--bdr);
    font-size: .8rem; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
    color: var(--em);
}
.mc-wcard-body { padding: 1.25rem 1.5rem; }

/* ── recent pirep rows ── */
.mc-pr-row {
    display: flex; align-items: center; gap: .75rem;
    padding: .7rem 0;
    border-bottom: 1px solid var(--bdr);
    text-decoration: none !important;
    color: var(--txt) !important;
    font-size: .85rem;
    transition: color .2s;
}
.mc-pr-row:last-child { border-bottom: none; }
.mc-pr-row:hover .mc-pr-ident { color: var(--em); }
.mc-pr-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.mc-pr-ident { font-weight: 700; min-width: 56px; font-size: .85rem; transition: color .2s; }
.mc-pr-route { flex: 1; color: var(--mute); font-size: .82rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.mc-pr-route b { color: var(--txt); }
.mc-pr-badge {
    font-size: .66rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
    padding: .18rem .5rem; border-radius: 4px;
}
.mc-pr-badge.s-accepted { background: rgba(16,185,129,.13); color: var(--em); }
.mc-pr-badge.s-pending  { background: rgba(251,191,36,.13);  color: var(--amb); }
.mc-pr-badge.s-progress { background: rgba(59,130,246,.13);  color: #93c5fd; }
.mc-pr-badge.s-other    { background: rgba(100,116,139,.1);  color: var(--mute); }

/* ── news rows ── */
.mc-news-row { padding: .8rem 0; border-bottom: 1px solid var(--bdr); }
.mc-news-row:last-child { border-bottom: none; }
.mc-news-title { font-size: .88rem; font-weight: 600; color: var(--txt); margin-bottom: .2rem;
    display: -webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.mc-news-meta  { font-size: .74rem; color: var(--mute); }

/* ── network row ── */
.mc-net-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: .65rem 0; border-bottom: 1px solid var(--bdr); font-size: .85rem;
}
.mc-net-row:last-child { border-bottom: none; }
.mc-net-lbl { color: var(--mute); display: flex; align-items: center; gap: .4rem; }
.mc-net-val { font-weight: 700; }

/* widget output theming */
.mc-wcard-body .table         { color: var(--txt) !important; font-size: .82rem !important; }
.mc-wcard-body .table th      { background: rgba(16,185,129,.08) !important; color: var(--mute) !important; border-color: var(--bdr) !important; font-size: .72rem !important; }
.mc-wcard-body .table td      { border-color: var(--bdr) !important; }
.mc-wcard-body .table-striped>tbody>tr:nth-of-type(odd)>* { background: rgba(255,255,255,.02) !important; }
.mc-wcard-body .badge         { font-family: 'JetBrains Mono', monospace !important; }
.mc-wcard-body .card          { background: transparent !important; border: none !important; box-shadow: none !important; }
.mc-wcard-body .card-header   { background: transparent !important; border-color: var(--bdr) !important; padding: 0 0 .75rem !important; }
.mc-wcard-body a              { color: var(--em) !important; }

/* section-label inline */
.mc-section-lbl {
    padding: .6rem 1rem .25rem;
    font-size: .68rem; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase; color: var(--mute);
}

/* empty */
.mc-empty { text-align: center; padding: 2rem; color: var(--mute); font-size: .85rem; }
.mc-empty i { font-size: 2rem; display: block; margin-bottom: .5rem; opacity: .35; }
</style>
@endsection

@section('content_header')@endsection

@section('content')
@php
/* recent pireps */
$dash_pireps = \App\Models\Pirep::where('user_id', $user->id)
    ->whereNotIn('state', [
        \App\Models\Enums\PirepState::CANCELLED,
        \App\Models\Enums\PirepState::DELETED,
    ])
    ->with(['dpt_airport', 'arr_airport'])
    ->orderBy('submitted_at', 'desc')
    ->take(5)->get();

/* hours */
$totalMins = (int)$user->flight_time;
$fh = floor($totalMins / 60);
$fm = $totalMins % 60;

/* balance */
$balanceVal = 0;
if ($user->journal && $user->journal->balance) {
    $b = $user->journal->balance;
    $balanceVal = is_object($b) && method_exists($b, 'getValue') ? (float)$b->getValue() : (float)$b;
}

/* state helpers */
$stateInfo = [
    \App\Models\Enums\PirepState::ACCEPTED    => ['cls'=>'s-accepted','dot'=>'#10b981','lbl'=>'Accepted'],
    \App\Models\Enums\PirepState::PENDING     => ['cls'=>'s-pending', 'dot'=>'#fbbf24','lbl'=>'Pending'],
    \App\Models\Enums\PirepState::IN_PROGRESS => ['cls'=>'s-progress','dot'=>'#3b82f6','lbl'=>'Active'],
    \App\Models\Enums\PirepState::PAUSED      => ['cls'=>'s-other',   'dot'=>'#64748b','lbl'=>'Paused'],
    \App\Models\Enums\PirepState::REJECTED    => ['cls'=>'s-other',   'dot'=>'#ef4444','lbl'=>'Rejected'],
    \App\Models\Enums\PirepState::DRAFT       => ['cls'=>'s-other',   'dot'=>'#475569','lbl'=>'Draft'],
];
@endphp

<div class="mc-wrap">
<div class="container-fluid px-4">

    {{-- On Leave --}}
    @if(Auth::user()->state === \App\Models\Enums\UserState::ON_LEAVE)
    <div class="mc-alert">
        <i class="fas fa-exclamation-triangle"></i>
        <div><strong>On Leave</strong> — You are currently marked as on leave. File a PIREP to return to active status.</div>
        <a href="{{ route('frontend.pireps.create') }}"><i class="fas fa-plus"></i> Return to Active</a>
    </div>
    @endif

    {{-- Welcome Banner --}}
    <div class="mc-banner">
        <div>
            <div class="mc-banner-eyebrow"><i class="fas fa-fighter-jet"></i> Mission Control · Air Jamaica Virtual</div>
            <h1 class="mc-banner-name">Welcome back, {{ $user->name_private }}</h1>
            <div class="mc-banner-sub">
                <span>{{ $user->rank ? $user->rank->name : 'Pilot' }}</span>
                &nbsp;·&nbsp; {{ $user->airline ? $user->airline->name : 'Air Jamaica Virtual' }}
                &nbsp;·&nbsp; <i class="fas fa-map-marker-alt"></i> {{ $current_airport }}
            </div>
        </div>
        <div class="mc-banner-right">
            <div><i class="far fa-calendar-alt"></i> {{ now()->format('d M Y') }}</div>
            <div><i class="far fa-clock"></i> {{ now()->format('H:i') }} UTC</div>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="mc-stats">
        <div class="mc-stat">
            <div class="mc-stat-val">{{ number_format($user->flights) }}</div>
            <div class="mc-stat-lbl">Flights</div>
        </div>
        <div class="mc-stat">
            <div class="mc-stat-val">{{ $fh }}<span style="font-size:.8rem;color:var(--mute)">h</span>{{ str_pad($fm,2,'0',STR_PAD_LEFT) }}<span style="font-size:.8rem;color:var(--mute)">m</span></div>
            <div class="mc-stat-lbl">Hours Logged</div>
        </div>
        <div class="mc-stat">
            <div class="mc-stat-val" style="color:var(--amb)">{{ number_format($avg_score ?? 0, 1) }}</div>
            <div class="mc-stat-lbl">Avg Score</div>
        </div>
        <div class="mc-stat">
            <div class="mc-stat-val">${{ number_format($balanceVal, 0) }}</div>
            <div class="mc-stat-lbl">Balance</div>
        </div>
        <div class="mc-stat">
            <div class="mc-stat-val" style="{{ ($pending_pireps_count ?? 0) > 0 ? 'color:var(--amb)' : '' }}">{{ $pending_pireps_count ?? 0 }}</div>
            <div class="mc-stat-lbl">Pending</div>
        </div>
        <div class="mc-stat">
            <div class="mc-stat-val" style="color:var(--amb)">{{ $awards_count ?? 0 }}</div>
            <div class="mc-stat-lbl">Awards</div>
        </div>
    </div>

    {{-- SmartCARS API Key --}}
    @if(Auth::check() && Auth::user()->api_key)
    <div class="mc-apikey">
        <div class="mc-apikey-icon"><i class="fas fa-key"></i></div>
        <div>
            <div class="mc-apikey-label">SmartCARS API Key</div>
            <div class="mc-apikey-hint">Use this as your password when logging into SmartCARS</div>
        </div>
        <div class="mc-apikey-field">
            <input type="text" class="form-control mc-apikey-input" id="apiKeyInput"
                   readonly value="{{ Auth::user()->api_key }}">
            <button class="mc-copy-btn" id="copyBtn"
                    onclick="navigator.clipboard.writeText(document.getElementById('apiKeyInput').value).then(()=>{
                        var b=document.getElementById('copyBtn');
                        b.classList.add('copied');b.innerHTML='<i class=\'fas fa-check\'></i> Copied';
                        setTimeout(()=>{b.classList.remove('copied');b.innerHTML='<i class=\'fas fa-copy\'></i> Copy';},2000)
                    })">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
    </div>
    @endif

    {{-- Active Flight Banner --
    @if($flight_status)
    <div class="mc-active-flight">
        <div class="mc-active-pill">
            <span class="blink" style="color:#3b82f6;font-size:.5rem">●</span>
            {{ $flight_status['phase'] }}
        </div>
        <div class="mc-active-route">
            <span class="mc-active-iata">{{ $flight_status['pirep']->dpt_airport_id }}</span>
            <span class="mc-active-arrow"><i class="fas fa-plane"></i></span>
            <span class="mc-active-iata">{{ $flight_status['pirep']->arr_airport_id }}</span>
            @if($flight_status['acars'])
            <span class="mc-active-meta" style="margin-left:.5rem">
                Alt <span>{{ number_format($flight_status['acars']->altitude_msl ?? $flight_status['acars']->altitude ?? 0) }}ft</span>
                &nbsp; GS <span>{{ (int)($flight_status['acars']->gs ?? 0) }}kt</span>
            </span>
            @endif
        </div>
        <div class="mc-active-progress">
            <div class="mc-active-progress-bar">
                <div class="mc-active-progress-fill" style="width:{{ $flight_status['progress'] }}%"></div>
            </div>
            <div class="mc-active-pct">{{ $flight_status['progress'] }}% complete</div>
        </div>
        <a href="{{ route('frontend.pireps.show', $flight_status['pirep']->id) }}" class="mc-active-link">
            <i class="fas fa-external-link-alt"></i> View PIREP
        </a>
    </div>
    @endif
    --}}

    {{-- ═══ THREE OPERATIONS CARDS ═══ --}}
    <div class="mc-ops-grid">

        {{-- CARD 1: Flight Operations --}}
        <div class="mc-op-card">
            <div class="mc-op-hdr">
                <div class="mc-op-hdr-icon em"><i class="fas fa-plane-departure"></i></div>
                <span class="mc-op-hdr-title">Flight Operations</span>
            </div>
            <div class="mc-op-body">

                <a href="{{ route('frontend.pireps.create') }}" class="mc-op-item">
                    <div class="mc-op-item-icon em"><i class="fas fa-file-alt"></i></div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">File PIREP</div>
                        <div class="mc-op-item-sub">Submit your flight report and log hours</div>
                    </div>
                </a>

                <a href="{{ route('frontend.flights.index') }}" class="mc-op-item">
                    <div class="mc-op-item-icon em"><i class="fas fa-route"></i></div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">Browse Flights</div>
                        <div class="mc-op-item-sub">Find and book available routes</div>
                    </div>
                </a>

                <a href="{{ route('frontend.pireps.index') }}" class="mc-op-item">
                    <div class="mc-op-item-icon em"><i class="fas fa-history"></i></div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">Flight History</div>
                        <div class="mc-op-item-sub">View all your completed flights</div>
                    </div>
                </a>

                <a href="{{ route('frontend.livemap.index') }}" class="mc-op-item">
                    <div class="mc-op-item-icon em"><i class="fas fa-globe-americas"></i></div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">Live Map</div>
                        <div class="mc-op-item-sub">Track real-time flights on the network</div>
                    </div>
                </a>

                @if($last_pirep !== null)
                <div class="mc-op-item static">
                    <div class="mc-op-item-icon mute"><i class="fas fa-check-circle"></i></div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">Last: {{ $last_pirep->flight->ident ?? $last_pirep->ident ?? 'N/A' }}</div>
                        <div class="mc-op-item-sub">{{ $last_pirep->dpt_airport_id }} → {{ $last_pirep->arr_airport_id }} · {{ $last_pirep->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @else
                <div style="padding:1rem;text-align:center">
                    <a href="{{ route('frontend.pireps.create') }}" style="font-size:.85rem;color:var(--em);text-decoration:none">
                        <i class="fas fa-plus-circle"></i> File your first PIREP
                    </a>
                </div>
                @endif

            </div>
        </div>

        {{-- CARD 2: Flight Management --}}
        <div class="mc-op-card">
            <div class="mc-op-hdr">
                <div class="mc-op-hdr-icon amb"><i class="fas fa-clipboard-list"></i></div>
                <span class="mc-op-hdr-title">Flight Management</span>
            </div>
            <div class="mc-op-body">

                <a href="{{ route('frontend.flights.bids') }}" class="mc-op-item">
                    <div class="mc-op-item-icon amb"><i class="fas fa-bookmark"></i></div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">My Bids</div>
                        <div class="mc-op-item-sub">Manage your booked flights</div>
                    </div>
                    @if(isset($current_bids) && $current_bids->count() > 0)
                        <span class="mc-op-badge">{{ $current_bids->count() }}</span>
                    @endif
                </a>

                @if(config('ajv.simbrief.api_key'))
                <a href="{{ route('frontend.simbrief.generate') }}" class="mc-op-item">
                    <div class="mc-op-item-icon amb"><i class="fas fa-map"></i></div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">SimBrief Planning</div>
                        <div class="mc-op-item-sub">Professional flight planning tool</div>
                    </div>
                </a>
                @endif

                <a href="{{ route('frontend.profile.index') }}" class="mc-op-item">
                    <div class="mc-op-item-icon amb"><i class="fas fa-user-astronaut"></i></div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">My Profile</div>
                        <div class="mc-op-item-sub">View stats, awards and pilot card</div>
                    </div>
                </a>

                {{-- current bids list --}}
                @if(isset($current_bids) && $current_bids->count() > 0)
                    <div class="mc-section-lbl">Active Bids</div>
                    @foreach($current_bids->take(3) as $bid)
                    <div class="mc-bid-row">
                        <i class="fas fa-plane" style="color:var(--amb);font-size:.7rem"></i>
                        <span class="mc-bid-route">
                            {{ $bid->flight->ident ?? 'Flight '.$bid->flight_id }}
                            <span style="color:var(--mute);font-weight:400"> · {{ $bid->flight->dpt_airport_id ?? '?' }} → {{ $bid->flight->arr_airport_id ?? '?' }}</span>
                        </span>
                        <a href="{{ route('frontend.flights.show', $bid->flight_id) }}" class="mc-op-btn">View</a>
                    </div>
                    @endforeach
                @endif

                {{-- recent pirep rows --}}
                @if($dash_pireps->isNotEmpty())
                    <div class="mc-section-lbl">Recent Flights</div>
                    @foreach($dash_pireps->take(4) as $dp)
                    @php
                        $si = $stateInfo[$dp->state] ?? ['cls'=>'s-other','dot'=>'#475569','lbl'=>'Unknown'];
                        $dm = (int)$dp->flight_time;
                    @endphp
                    <a href="{{ route('frontend.pireps.show', $dp->id) }}" class="mc-pr-row">
                        <span class="mc-pr-dot" style="background:{{ $si['dot'] }}"></span>
                        <span class="mc-pr-ident">{{ $dp->ident }}</span>
                        <span class="mc-pr-route"><b>{{ $dp->dpt_airport_id }}</b> → <b>{{ $dp->arr_airport_id }}</b></span>
                        @if($dm > 0)<span style="font-size:.78rem;color:var(--mute);white-space:nowrap">{{ floor($dm/60) }}h{{ str_pad($dm%60,2,'0',STR_PAD_LEFT) }}m</span>@endif
                        <span class="mc-pr-badge {{ $si['cls'] }}">{{ $si['lbl'] }}</span>
                    </a>
                    @endforeach
                @endif

            </div>
        </div>

        {{-- CARD 3: Tools & Information --}}
        <div class="mc-op-card">
            <div class="mc-op-hdr">
                <div class="mc-op-hdr-icon blu"><i class="fas fa-tools"></i></div>
                <span class="mc-op-hdr-title">Tools & Information</span>
            </div>
            <div class="mc-op-body">

                <a href="{{ route('frontend.downloads.index') }}" class="mc-op-item">
                    <div class="mc-op-item-icon blu"><i class="fas fa-download"></i></div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">ACARS Downloads</div>
                        <div class="mc-op-item-sub">SmartCARS, xACARS, and other clients</div>
                    </div>
                </a>

                <div class="mc-op-item static">
                    <div class="mc-op-item-icon blu"><i class="fas fa-cloud-sun"></i></div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">Current Hub: {{ $current_airport }}</div>
                        <div class="mc-op-item-sub">Weather and conditions at your base</div>
                    </div>
                </div>

                @if(isset($network_stats))
                <div class="mc-op-item static">
                    <div class="mc-op-item-icon blu"><i class="fas fa-network-wired"></i></div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">Network Status</div>
                        <div class="mc-op-item-sub">{{ $network_stats['pilots_online'] }} online · {{ $network_stats['active_flights'] }} active flights · {{ $network_stats['total_pilots'] }} total pilots</div>
                    </div>
                </div>
                @endif

                @if($recent_awards->isNotEmpty())
                <div class="mc-section-lbl">Recent Awards</div>
                @foreach($recent_awards->take(3) as $ua)
                <div class="mc-op-item static">
                    <div class="mc-op-item-icon" style="background:rgba(251,191,36,.12);color:var(--amb)">
                        @if($ua->award && $ua->award->image)
                            <img src="{{ $ua->award->image }}" alt="" style="width:18px;height:18px;object-fit:contain;border-radius:3px">
                        @else
                            <i class="fas fa-medal"></i>
                        @endif
                    </div>
                    <div class="mc-op-item-content">
                        <div class="mc-op-item-title">{{ $ua->award ? $ua->award->name : 'Award' }}</div>
                        <div class="mc-op-item-sub">{{ $ua->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @endforeach
                @endif

            </div>
            <div class="mc-op-btn-row">
                <a href="{{ route('frontend.pireps.index') }}" class="mc-op-btn" style="justify-content:center">
                    <i class="fas fa-history"></i> History
                </a>
                <a href="{{ route('frontend.profile.index') }}" class="mc-op-btn" style="justify-content:center">
                    <i class="fas fa-user"></i> Profile
                </a>
            </div>
        </div>

    </div>{{-- /mc-ops-grid --}}

    {{-- ═══ BOTTOM WIDGETS: NOTAMs / News / Activity ═══ --}}
    <div class="mc-widgets-grid">

        {{-- NOTAMs --}}
        <div class="mc-wcard">
            <div class="mc-wcard-hdr"><i class="fas fa-exclamation-triangle"></i> Active NOTAMs</div>
            <div class="mc-wcard-body">
                {{ Widget::activeNotams(['count' => 5, 'icao' => null, 'show_critical_only' => false, 'show_priority_badges' => true, 'show_airports' => true]) }}
            </div>
        </div>

        {{-- Latest News --}}
        <div class="mc-wcard">
            <div class="mc-wcard-hdr"><i class="fas fa-broadcast-tower"></i> Latest News</div>
            <div class="mc-wcard-body">
                @if($latest_news->isNotEmpty())
                    @foreach($latest_news as $news)
                    <div class="mc-news-row">
                        <div class="mc-news-title">{{ $news->title }}</div>
                        <div class="mc-news-meta">
                            <i class="far fa-clock"></i> {{ $news->created_at->diffForHumans() }}
                            @if($news->user) · {{ $news->user->name_private }} @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="mc-empty"><i class="fas fa-newspaper"></i> No news at this time.</div>
                @endif
            </div>
        </div>

        {{-- Weather + Recent Network PIREPs --}}
        <div class="mc-wcard">
            <div class="mc-wcard-hdr"><i class="fas fa-cloud-sun"></i> Weather · {{ $current_airport }}</div>
            <div class="mc-wcard-body">
                {{ Widget::Weather(['icao' => $current_airport]) }}
            </div>
        </div>

    </div>

</div>
</div>

<style>
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
/* suppress global link colour in mc context */
.mc-op-item, .mc-pr-row, .mc-active-link { color: var(--txt) !important; }
</style>

@endsection
