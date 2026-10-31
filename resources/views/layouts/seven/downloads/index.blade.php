@extends('layouts.seven.public_app')
@section('title', trans_choice('common.download', 2))

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
    padding-top: 0 !important;
    overflow-x: hidden;
}

body::before {
    content: '';
    position: fixed;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 50% at 10% 0%,   rgba(16,185,129,.07) 0%, transparent 60%),
        radial-gradient(ellipse 60% 40% at 90% 100%,  rgba(251,191,36,.05) 0%, transparent 60%);
    pointer-events: none;
    z-index: 0;
}

/* ── Layout ── */
.dl-wrap {
    position: relative;
    z-index: 1;
    padding: 90px 0 4rem;
    min-height: 100vh;
}

.dl-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* ── Page header ── */
.dl-header {
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

.dl-header-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: rgba(16,185,129,.15);
    border: 1px solid var(--em2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--em);
    font-size: 1.25rem;
    flex-shrink: 0;
}

.dl-header-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--tx);
    margin: 0 0 .2rem;
    letter-spacing: -.4px;
}

.dl-header-sub {
    font-size: .7rem;
    color: var(--tx2);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* ── Category grid ── */
.dl-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(440px, 1fr));
    gap: 1.5rem;
}

/* ── Category card ── */
.dl-card {
    background: rgba(15,23,42,.85);
    border: 1px solid var(--em2);
    border-radius: 14px;
    backdrop-filter: blur(10px);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.dl-card-head {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--em2);
    background: rgba(16,185,129,.05);
}

.dl-cat-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: rgba(16,185,129,.12);
    border: 1px solid var(--em2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--em);
    font-size: .85rem;
    flex-shrink: 0;
}

/* SmartCARS amber accent */
.dl-card.smartcars .dl-card-head { background: rgba(251,191,36,.05); border-bottom-color: rgba(251,191,36,.15); }
.dl-card.smartcars { border-color: rgba(251,191,36,.15); }
.dl-card.smartcars .dl-cat-icon { background: rgba(251,191,36,.12); border-color: rgba(251,191,36,.2); color: var(--amb); }
.dl-card.smartcars .dl-cat-name { color: var(--amb); }

/* ACARS blue accent */
.dl-card.acars .dl-card-head { background: rgba(59,130,246,.05); border-bottom-color: rgba(59,130,246,.15); }
.dl-card.acars { border-color: rgba(59,130,246,.15); }
.dl-card.acars .dl-cat-icon { background: rgba(59,130,246,.12); border-color: rgba(59,130,246,.2); color: var(--blu); }
.dl-card.acars .dl-cat-name { color: var(--blu); }

.dl-cat-name {
    font-size: .8rem;
    font-weight: 700;
    color: var(--em);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin: 0;
}

.dl-card-body {
    padding: 1rem 1.5rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: .75rem;
    flex: 1;
}

/* ── File row ── */
.dl-file {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: .875rem 1rem;
    background: rgba(255,255,255,.03);
    border: 1px solid rgba(255,255,255,.06);
    border-radius: 10px;
    text-decoration: none !important;
    transition: background .18s, border-color .18s;
    color: var(--tx);
}

.dl-file:hover {
    background: rgba(16,185,129,.06);
    border-color: rgba(16,185,129,.25);
    color: var(--tx);
}

.dl-file-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    flex-shrink: 0;
    background: rgba(16,185,129,.1);
    border: 1px solid var(--em2);
    color: var(--em);
}

.dl-card.smartcars .dl-file-icon { background: rgba(251,191,36,.1); border-color: rgba(251,191,36,.2); color: var(--amb); }
.dl-card.acars .dl-file-icon { background: rgba(59,130,246,.1); border-color: rgba(59,130,246,.2); color: var(--blu); }

.dl-file-meta {
    flex: 1;
    min-width: 0;
}

.dl-file-name {
    font-size: .88rem;
    font-weight: 600;
    color: var(--tx);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: .1rem;
}

.dl-file-desc {
    font-size: .72rem;
    color: var(--tx2);
}

.dl-file-right {
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-shrink: 0;
}

.dl-count {
    font-size: .68rem;
    color: var(--amb);
    background: rgba(251,191,36,.08);
    border: 1px solid rgba(251,191,36,.15);
    border-radius: 20px;
    padding: .15rem .55rem;
    white-space: nowrap;
}

.dl-btn {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-family: var(--mono);
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    text-decoration: none !important;
    padding: .4rem .9rem;
    border-radius: 6px;
    background: rgba(16,185,129,.15);
    border: 1px solid var(--em2);
    color: var(--em) !important;
    transition: background .18s, border-color .18s;
    white-space: nowrap;
}

.dl-btn:hover {
    background: rgba(16,185,129,.25);
    border-color: var(--em);
    color: var(--em) !important;
}

.dl-card.smartcars .dl-btn {
    background: rgba(251,191,36,.1);
    border-color: rgba(251,191,36,.2);
    color: var(--amb) !important;
}
.dl-card.smartcars .dl-btn:hover {
    background: rgba(251,191,36,.2);
    border-color: var(--amb);
    color: var(--amb) !important;
}

.dl-card.acars .dl-btn {
    background: rgba(59,130,246,.1);
    border-color: rgba(59,130,246,.2);
    color: var(--blu) !important;
}
.dl-card.acars .dl-btn:hover {
    background: rgba(59,130,246,.2);
    border-color: var(--blu);
    color: var(--blu) !important;
}

/* ── Empty state ── */
.dl-empty {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(15,23,42,.85);
    border: 1px dashed rgba(16,185,129,.2);
    border-radius: 14px;
    color: var(--tx2);
}

.dl-empty i { font-size: 2.5rem; color: var(--em2); margin-bottom: 1rem; display: block; }
.dl-empty h3 { color: var(--tx2); font-size: 1rem; font-weight: 600; }

/* ── Responsive ── */
@media (max-width: 900px) {
    .dl-grid { grid-template-columns: 1fr; }
}

@media (max-width: 540px) {
    .dl-file { flex-wrap: wrap; }
    .dl-file-right { width: 100%; justify-content: flex-end; margin-top: .25rem; }
}
</style>
@endsection

@section('content')
<div class="dl-wrap">
<div class="dl-inner">

    {{-- Breadcrumb --}}
    @php
    $breadcrumbs = [
        [
            'title' => Auth::check() ? 'Pilot Center' : 'Home',
            'url'   => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
            'icon'  => Auth::check() ? 'bi bi-speedometer2' : 'bi bi-house-fill'
        ],
        [
            'title' => trans_choice('common.download', 2),
            'icon'  => 'fas fa-download'
        ]
    ];
    @endphp
    @include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

    @include('flash::message')

    {{-- Page header --}}
    <div class="dl-header">
        <div class="dl-header-icon"><i class="fas fa-download"></i></div>
        <div>
            <p class="dl-header-title">{{ trans_choice('common.download', 2) }}</p>
            <p class="dl-header-sub">Tools &amp; resources for Air Jamaica Virtual pilots</p>
        </div>
    </div>

    {{-- Empty state --}}
    @if(!$grouped_files || \count($grouped_files) === 0)
        <div class="dl-empty">
            <i class="fas fa-inbox"></i>
            <h3>No Downloads Available</h3>
            <p style="font-size:.82rem;">{{ __('downloads.none') }}</p>
        </div>

    {{-- Category grid --}}
    @else
        <div class="dl-grid">
            @foreach($grouped_files as $group => $files)
                @php
                    $slug       = strtolower($group);
                    $isSmartcars = $slug === 'smartcars';
                    $isAcars     = $slug === 'acars';
                    $isAircraft  = str_contains($slug, 'aircraft');
                    $isAirport   = str_contains($slug, 'airport');
                    $isSubfleet  = str_contains($slug, 'subfleet');

                    if ($isSmartcars)     $catIcon = 'fas fa-plane';
                    elseif ($isAcars)     $catIcon = 'fas fa-broadcast-tower';
                    elseif ($isAircraft)  $catIcon = 'fas fa-fighter-jet';
                    elseif ($isAirport)   $catIcon = 'fas fa-map-marker-alt';
                    elseif ($isSubfleet)  $catIcon = 'fas fa-layer-group';
                    else                  $catIcon = 'fas fa-folder';

                    $cardClass = $isSmartcars ? 'smartcars' : ($isAcars ? 'acars' : '');
                @endphp

                <div class="dl-card {{ $cardClass }}">
                    <div class="dl-card-head">
                        <div class="dl-cat-icon"><i class="{{ $catIcon }}"></i></div>
                        <p class="dl-cat-name">{{ $group }}</p>
                    </div>

                    <div class="dl-card-body">
                        @foreach($files as $file)
                            @php
                                $ext = strtolower(pathinfo($file->name ?? '', PATHINFO_EXTENSION));
                                if (in_array($ext, ['pdf']))                $fileIcon = 'fas fa-file-pdf';
                                elseif (in_array($ext, ['zip','rar','7z'])) $fileIcon = 'fas fa-file-archive';
                                elseif (in_array($ext, ['exe','msi']))      $fileIcon = 'fas fa-file-code';
                                elseif ($isSmartcars)                       $fileIcon = 'fas fa-plane';
                                elseif ($isAcars)                           $fileIcon = 'fas fa-broadcast-tower';
                                else                                        $fileIcon = 'fas fa-file-arrow-down';
                            @endphp

                            <a href="{{ route('frontend.downloads.download', [$file->id]) }}"
                               target="_blank"
                               @if($file->isExternalFile) data-external-redirect="{{ $file->url }}" @endif
                               class="dl-file">

                                <div class="dl-file-icon"><i class="{{ $fileIcon }}"></i></div>

                                <div class="dl-file-meta">
                                    <div class="dl-file-name">{{ $file->name }}</div>
                                    @if($file->description)
                                        <div class="dl-file-desc">{{ $file->description }}</div>
                                    @endif
                                </div>

                                <div class="dl-file-right">
                                    @if(isset($file->download_count) && $file->download_count > 0)
                                        <span class="dl-count">
                                            <i class="fas fa-download" style="font-size:.6rem;"></i>
                                            {{ number_format($file->download_count) }}
                                        </span>
                                    @endif
                                    <span class="dl-btn">
                                        <i class="fas fa-download"></i> Get
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
</div>
@endsection
