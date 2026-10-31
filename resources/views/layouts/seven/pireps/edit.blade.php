@extends('layouts.seven.public_app')
@section('title', __('pireps.editflightreport'))

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
    --txt:  #e2e8f0;
    --mute: #64748b;
    --bdr:  rgba(16,185,129,.12);
    --bdr-h: rgba(16,185,129,.35);
    --card: rgba(15,23,42,.85);
}

*, *::before, *::after { box-sizing: border-box; }

body {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: 16px;
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
.pe-wrap {
    position: relative;
    z-index: 1;
    padding: 90px 0 4rem;
    min-height: 100vh;
}

/* ── breadcrumb ── */
.bc { display: flex; align-items: center; gap: .4rem; margin-bottom: 1.5rem; }
.bc a, .bc span { font-size: .65rem; color: var(--mute); text-decoration: none !important; }
.bc a:hover { color: var(--em); }
.bc-sep { color: var(--sl3); }
.bc .active { color: var(--em); }

/* ── page header ── */
.pe-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.pe-title-group { display: flex; align-items: baseline; gap: .75rem; }
.pe-label {
    font-size: .65rem;
    font-weight: 600;
    letter-spacing: .2em;
    color: var(--em);
    text-transform: uppercase;
}
.pe-title-group h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--txt);
    margin: 0;
    line-height: 1;
}

.pe-ident-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(16,185,129,.1);
    border: 1px solid rgba(16,185,129,.25);
    border-radius: 8px;
    padding: .35rem .9rem;
    font-size: .82rem;
    font-weight: 700;
    color: var(--em);
    letter-spacing: .05em;
}

.pe-route {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .82rem;
    color: var(--mute);
    font-weight: 600;
}
.pe-route-code { color: var(--txt); font-size: 1rem; font-weight: 700; }
.pe-route-arrow { color: var(--em); font-size: .75rem; }

/* ── form wrapper card ── */
.pe-form-card {
    background: var(--card);
    border: 1px solid var(--bdr);
    border-radius: 14px;
    backdrop-filter: blur(10px);
    overflow: hidden;
}

/* ── section blocks (used by pireps.fields) ── */
.section-unified {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--bdr);
}
.section-unified:last-of-type {
    border-bottom: none;
}

.section-header-sleek {
    display: flex;
    align-items: center;
    gap: .75rem;
    margin-bottom: 1.25rem;
}

.section-icon-sleek {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(16,185,129,.12);
    border: 1px solid rgba(16,185,129,.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--em);
    font-size: .82rem;
    flex-shrink: 0;
}

.section-title-sleek {
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--txt);
    margin: 0;
}

/* ── form controls ── */
.form-label {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: .72rem !important;
    font-weight: 600 !important;
    letter-spacing: .08em !important;
    text-transform: uppercase !important;
    color: var(--em) !important;
    margin-bottom: .5rem !important;
    display: block !important;
}

.form-control,
.form-select {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: .88rem !important;
    background: rgba(2,6,23,.6) !important;
    border: 1px solid var(--sl3) !important;
    border-radius: 8px !important;
    color: var(--txt) !important;
    padding: .6rem .9rem !important;
    transition: border-color .2s, box-shadow .2s !important;
}

.form-control:focus,
.form-select:focus {
    background: rgba(2,6,23,.8) !important;
    border-color: var(--em) !important;
    box-shadow: 0 0 0 3px rgba(16,185,129,.15) !important;
    color: var(--txt) !important;
    outline: none !important;
}

.form-control::placeholder { color: var(--mute) !important; }

textarea.form-control {
    min-height: 110px !important;
    resize: vertical !important;
    line-height: 1.6 !important;
}

/* ── input group ── */
.input-group-text,
.bg-deep {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: .72rem !important;
    font-weight: 700 !important;
    letter-spacing: .08em !important;
    background: rgba(30,41,59,.8) !important;
    border-color: var(--sl3) !important;
    color: var(--mute) !important;
}

.input-group .form-control {
    border-radius: 0 !important;
}
.input-group .form-control:first-child { border-radius: 8px 0 0 8px !important; }
.input-group .form-control:last-child  { border-radius: 0 8px 8px 0 !important; }
.input-group .input-group-text:first-child { border-radius: 8px 0 0 8px !important; }
.input-group .input-group-text:last-child  { border-radius: 0 8px 8px 0 !important; }

/* ── alerts ── */
.alert {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: .82rem !important;
    border-radius: 10px !important;
    border: none !important;
}
.alert-info    { background: rgba(59,130,246,.12) !important; border-left: 3px solid var(--blu) !important; color: #93c5fd !important; }
.alert-success { background: rgba(16,185,129,.12) !important; border-left: 3px solid var(--em) !important;  color: #6ee7b7 !important; }
.alert-danger  { background: rgba(239,68,68,.12)  !important; border-left: 3px solid var(--red) !important; color: #fca5a5 !important; }
.alert-warning { background: rgba(251,191,36,.12) !important; border-left: 3px solid var(--amb) !important; color: #fcd34d !important; }

/* ── validation ── */
.is-invalid { border-color: var(--red) !important; box-shadow: 0 0 0 3px rgba(239,68,68,.15) !important; }
.invalid-feedback { font-size: .72rem !important; color: #fca5a5 !important; }

/* ── custom fields table ── */
.table {
    color: var(--txt) !important;
    font-size: .85rem !important;
}
.table td, .table th {
    border-color: var(--bdr) !important;
    padding: .65rem .75rem !important;
}

/* ── Select2 dark theme ── */
.select2-container--default .select2-selection--single {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: .88rem !important;
    background: rgba(2,6,23,.6) !important;
    border: 1px solid var(--sl3) !important;
    border-radius: 8px !important;
    height: calc(2.25rem + 2px) !important;
    color: var(--txt) !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--txt) !important;
    line-height: calc(2.25rem + 2px) !important;
    font-size: .88rem !important;
    padding-left: .9rem !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: calc(2.25rem + 2px) !important;
}
.select2-container--focus .select2-selection--single,
.select2-container--open .select2-selection--single {
    border-color: var(--em) !important;
    box-shadow: 0 0 0 3px rgba(16,185,129,.15) !important;
}
.select2-dropdown {
    font-family: 'JetBrains Mono', monospace !important;
    background: rgba(15,23,42,.98) !important;
    border: 1px solid var(--sl3) !important;
    border-radius: 10px !important;
}
.select2-container--default .select2-results__option {
    font-size: .85rem !important;
    color: var(--txt) !important;
    padding: .5rem .9rem !important;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: rgba(16,185,129,.2) !important;
    color: var(--em) !important;
}
.select2-container--default .select2-search--dropdown .select2-search__field {
    font-family: 'JetBrains Mono', monospace !important;
    background: rgba(2,6,23,.7) !important;
    border: 1px solid var(--sl3) !important;
    border-radius: 6px !important;
    color: var(--txt) !important;
    font-size: .82rem !important;
}

/* ── action bar ── */
.pe-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--bdr);
    flex-wrap: wrap;
}

.pe-btn {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    font-family: 'JetBrains Mono', monospace;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    text-decoration: none !important;
    padding: .6rem 1.4rem;
    border-radius: 7px;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
}

.pe-btn-back {
    background: transparent;
    border-color: var(--sl3);
    color: var(--mute) !important;
}
.pe-btn-back:hover {
    border-color: var(--em);
    color: var(--em) !important;
}

.pe-btn-save {
    background: var(--em);
    border-color: var(--em);
    color: #000 !important;
}
.pe-btn-save:hover {
    background: #34d399;
    border-color: #34d399;
    color: #000 !important;
    transform: translateY(-1px);
}

/* ── read-only strip ── */
.pe-readonly-notice {
    display: flex;
    align-items: center;
    gap: .5rem;
    background: rgba(59,130,246,.1);
    border: 1px solid rgba(59,130,246,.2);
    border-radius: 8px;
    padding: .65rem 1rem;
    font-size: .78rem;
    color: #93c5fd;
    margin-bottom: 1.5rem;
}

/* Large monitor: cap form width so content stays readable at 1440-1920px */
.pe-wrap > .container-fluid {
    max-width: 980px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 640px) {
    .pe-wrap { padding: 80px 0 3rem; }
    .pe-actions { flex-direction: column; }
    .pe-btn { width: 100%; justify-content: center; }
    .section-unified { padding: 1.25rem 1rem; }
}
</style>
@endsection

@section('content_header')@endsection

@section('content')
<div class="pe-wrap">
<div class="container-fluid px-4">

    {{-- breadcrumb --}}
    <nav class="bc">
        <a href="{{ Auth::check() ? route('frontend.dashboard.index') : route('frontend.home') }}">
            <i class="fas fa-th-large"></i> Pilot Center
        </a>
        <span class="bc-sep">/</span>
        <a href="{{ route('frontend.pireps.index') }}">Flight Log</a>
        <span class="bc-sep">/</span>
        <a href="{{ route('frontend.pireps.show', $pirep->id) }}">{{ $pirep->ident }}</a>
        <span class="bc-sep">/</span>
        <span class="active">Edit</span>
    </nav>

    {{-- page header --}}
    <div class="pe-header">
        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
            <div class="pe-title-group">
                <span class="pe-label"><i class="fas fa-edit"></i></span>
                <h1>Edit Report</h1>
            </div>
            <span class="pe-ident-badge">
                <i class="fas fa-hashtag" style="font-size:.65rem"></i>
                {{ $pirep->ident }}
            </span>
            <div class="pe-route">
                <span class="pe-route-code">{{ $pirep->dpt_airport_id }}</span>
                <i class="fas fa-long-arrow-alt-right pe-route-arrow"></i>
                <span class="pe-route-code">{{ $pirep->arr_airport_id }}</span>
            </div>
        </div>
        <a href="{{ route('frontend.pireps.show', $pirep->id) }}" class="pe-btn pe-btn-back">
            <i class="fas fa-arrow-left"></i> Back to Report
        </a>
    </div>

    @include('flash::message')

    {{-- form card --}}
    <form method="post" action="{{ route('frontend.pireps.update', $pirep->id) }}">
        @method('PATCH')
        @csrf

        <div class="pe-form-card">

            @include('pireps.fields')

            {{-- action bar --}}
            <div class="pe-actions">
                <a href="{{ route('frontend.pireps.show', $pirep->id) }}" class="pe-btn pe-btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Report
                </a>
                <button type="submit" class="pe-btn pe-btn-save">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>

        </div>{{-- /.pe-form-card --}}

    </form>

</div>
</div>
@endsection

@include('pireps.scripts')
