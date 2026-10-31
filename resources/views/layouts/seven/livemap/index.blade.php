@extends('layouts.seven.public_app')
@section('title', __('common.livemap'))

@section('content')
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    /* --- MISSION CONTROL SYSTEM STYLES --- */
    :root {
        --emerald-glow: #10b981;
        --emerald-dim: rgba(16, 185, 129, 0.2);
        --slate-dark: #020617;
        --panel-bg: rgba(2, 6, 23, 0.9);
        --border-color: rgba(255, 255, 255, 0.1);
        --font-mono: 'JetBrains Mono', 'Courier New', monospace;
    }

    body, .navbar, .footer, .nav-link, button, select, input, label {
        font-family: 'JetBrains Mono', monospace !important;
    }

    /* Hide Footer & Remove Bottom Gaps for Mission Control */
    .footer,
    .footer-bottom,
    .global-footer-wrap {
        display: none !important;
    }

    body,
    .wrapper,
    .main-content-wrapper,
    .body.container {
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
        overflow: hidden !important;
    }

    /* Adjust main content container padding for this full-screen view */
    /* Scoped to main content only — never the navbar's .container */
    .main-content-wrapper .container,
    .main-content-wrapper {
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Mission Control Tactical Interface */
    .fec-wrapper {
        display: flex;
        height: calc(100vh - 76px);
        position: relative;
        overflow: hidden;
        background: #000;
        width: 100%;
    }
    
    @media (max-width: 991px) {
        .fec-wrapper {
            flex-direction: column;
            height: auto !important;
            overflow-y: auto !important;
        }
        body, .wrapper, .main-content-wrapper {
            overflow: auto !important;
        }
    }
</style>

<script>
    window.liveMapStats = {
        liveFlights: {{ $liveFlights ?? 0 }},
        flightsToday: {{ $flightsToday ?? 0 }},
        activeAirports: {{ $activeAirports ?? 0 }},
        activePilots: {{ $activePilots ?? 0 }},
    };
</script>

<div class="fec-wrapper">
    {{ Widget::liveMap() }}
</div>
@endsection
