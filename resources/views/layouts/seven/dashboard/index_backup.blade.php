@extends('layouts.seven.public_app')
@section('title', __('common.dashboard'))

@section('css')
    {{-- World-Class Premium Dashboard Styles --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        /* Professional Aviation Dashboard Styles */
        :root {
            --aviation-primary: #1e3a8a;    /* Deep aviation blue */
            --aviation-secondary: #1e40af;   /* Secondary blue */
            --aviation-accent: #3b82f6;      /* Accent blue */
            --aviation-success: #059669;     /* Success green */
            --aviation-warning: #d97706;     /* Warning amber */
            --aviation-danger: #dc2626;      /* Alert red */
            --aviation-white: #ffffff;
            --aviation-light: #f8fafc;
            --aviation-gray: #64748b;
            --aviation-dark: #1e293b;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Override parent layout's body padding */
        body {
            padding-top: 0 !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            background: linear-gradient(135deg, var(--aviation-primary) 0%, var(--aviation-secondary) 100%) !important;
            background-attachment: fixed;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* Dashboard specific container */

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .premium-dashboard {
            padding: 100px 0 2rem 0; /* Account for fixed navigation */
            min-height: calc(100vh - 100px);
        }

        /* Override navigation styling for dashboard */
        .public-nav {
            background: rgba(0, 0, 0, 0.1) !important;
            backdrop-filter: blur(20px) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--border-radius);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-5px);
            box-shadow: var(--shadow-strong);
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: var(--border-radius);
            padding: 2rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            text-align: center;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-strong);
        }

        .stat-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            background: var(--primary-gradient);
            color: white;
            font-size: 1.5rem;
            box-shadow: var(--shadow-soft);
            transition: var(--transition);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 1rem 0;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .stat-label {
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.875rem;
            margin: 0;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .action-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            display: block;
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            animation: rotate 4s linear infinite;
            z-index: 1;
            opacity: 0;
            transition: var(--transition);
        }

        .action-card:hover::before {
            opacity: 1;
        }

        .action-card .content {
            position: relative;
            z-index: 2;
        }

        .action-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: var(--shadow-strong);
            text-decoration: none;
            color: inherit;
        }

        .action-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            background: var(--primary-gradient);
            color: white;
            font-size: 2rem;
            transition: var(--transition);
            box-shadow: var(--shadow-soft);
        }

        .action-card:hover .action-icon {
            transform: scale(1.1) rotate(5deg);
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .premium-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: var(--transition);
            position: relative;
        }

        .premium-section:hover {
            box-shadow: var(--shadow-strong);
        }

        .section-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 2rem;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .section-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-body {
            padding: 2rem;
        }

        .floating-element {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .floating-element:nth-child(2) { animation-delay: 0.5s; }
        .floating-element:nth-child(3) { animation-delay: 1s; }
        .floating-element:nth-child(4) { animation-delay: 1.5s; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.1);
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            border-radius: 4px;
            position: relative;
            transition: width 1s ease-in-out;
        }

        .btn {
            font-weight: 500;
            transition: var(--transition);
            border-radius: 16px;
            padding: 0.75rem 1.5rem;
            position: relative;
            overflow: hidden;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.12);
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
        }

        .btn-warning {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-info {
            background: var(--info-gradient);
            color: white;
        }

        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }

        .badge {
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            body {
                background-attachment: scroll;
            }

            .premium-dashboard {
                padding: 80px 0 1rem 0; /* Adjusted for mobile */
            }

            .stat-card,
            .action-card {
                padding: 1.5rem;
            }

            .section-body {
                padding: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .action-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stat-number {
                font-size: 2rem;
            }

            .action-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }
        }

        @media (max-width: 576px) {
            .premium-dashboard {
                padding: 0.5rem 0;
            }

            .section-header {
                padding: 1rem 1.25rem;
            }

            .section-title {
                font-size: 1.1rem;
            }

            .stat-number {
                font-size: 1.75rem;
            }

            .action-card,
            .stat-card {
                padding: 1rem;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        .btn:focus,
        .glass-card:focus {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
    </style>
@endsection

@section('content')
<div class="premium-dashboard animate__animated animate__fadeIn">
    <div class="container-fluid">

        {{-- Premium Status Alert --}}
        @if (Auth::user()->state === \App\Models\Enums\UserState::ON_LEAVE)
        <div class="row mb-4">
            <div class="col-12">
                <div class="premium-section">
                    <div class="section-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">
                        <h5 class="section-title">
                            <i class="fas fa-exclamation-triangle"></i>
                            Pilot Status Alert
                        </h5>
                    </div>
                    <div class="section-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-danger mb-2">On Leave Status Active</h6>
                                <p class="mb-0 text-muted">You're currently marked as on leave. File a PIREP to return to active duty and resume flight operations.</p>
                            </div>
                            <a href="{{ route('frontend.pireps.create') }}" class="btn btn-danger btn-lg">
                                <i class="fas fa-file-alt me-2"></i>
                                Return to Active
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Premium Welcome Section --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="text-center text-white mb-4">
                    <h1 class="display-4 fw-bold mb-3 animate__animated animate__fadeInUp">
                        Welcome back, {{ Auth::user()->name_private }}
                    </h1>
                    <p class="lead opacity-75 animate__animated animate__fadeInUp animate__delay-1s">
                        {{ Auth::user()->rank ? Auth::user()->rank->name : 'Pilot' }} • {{ Auth::user()->airline ? Auth::user()->airline->name : 'Virtual Airline' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Flight Operations Center --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="premium-section animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="fas fa-tower-control"></i>
                            Flight Operations Center
                        </h5>
                    </div>
                    <div class="section-body">
                        <div class="action-grid">
                            <a href="{{ route('frontend.pireps.create') }}" class="action-card floating-element">
                                <div class="content">
                                    <div class="action-icon" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%)">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">File PIREP</h5>
                                    <p class="text-muted mb-0">Submit your flight report and earn flight hours</p>
                                </div>
                            </a>

                            <a href="{{ route('frontend.flights.index') }}" class="action-card floating-element" style="animation-delay: 0.5s">
                                <div class="content">
                                    <div class="action-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
                                        <i class="fas fa-route"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">Book Flight</h5>
                                    <p class="text-muted mb-0">Browse and bid on available routes</p>
                                </div>
                            </a>

                            <a href="{{ route('frontend.downloads.index') }}" class="action-card floating-element" style="animation-delay: 1s">
                                <div class="content">
                                    <div class="action-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">ACARS Downloads</h5>
                                    <p class="text-muted mb-0">Get flight tracking and simulation tools</p>
                                </div>
                            </a>

                            <a href="{{ route('frontend.flights.bids') }}" class="action-card floating-element" style="animation-delay: 1.5s">
                                <div class="content">
                                    <div class="action-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)">
                                        <i class="fas fa-bookmark"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">My Bids</h5>
                                    <p class="text-muted mb-0">Manage your booked flights
                                        @if(isset($current_bids) && $current_bids->count() > 0)
                                            <span class="badge bg-primary ms-2">{{ $current_bids->count() }}</span>
                                        @endif
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Premium Statistics Dashboard --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="stats-grid animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
                            <i class="fas fa-plane"></i>
                        </div>
                        <h3 class="stat-number">{{ number_format($user->flights) }}</h3>
                        <p class="stat-label">{{ trans_choice('common.flight', $user->flights) }}</p>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar" style="width: {{ min(($user->flights / 100) * 100, 100) }}%; background: var(--primary-gradient);"></div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%)">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="stat-number">@minutestotime($user->flight_time)</h3>
                        <p class="stat-label">@lang('dashboard.totalhours')</p>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar" style="width: {{ min(($user->flight_time / 6000) * 100, 100) }}%; background: var(--success-gradient);"></div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h3 class="stat-number">{{ optional($user->journal)->balance ?? '$0' }}</h3>
                        <p class="stat-label">@lang('dashboard.yourbalance')</p>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-success"><i class="fas fa-arrow-up"></i> Earnings</small>
                            <small class="text-muted">This Month</small>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3 class="stat-number">{{ $current_airport }}</h3>
                        <p class="stat-label">@lang('airports.current')</p>
                        <div class="mt-2">
                            <span class="badge bg-success">Active Hub</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">

                {{-- Premium Current Bids Section --}}
                @if(isset($current_bids) && $current_bids->count() > 0)
                <div class="premium-section mb-4 animate__animated animate__fadeInUp animate__delay-4s">
                    <div class="section-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)">
                        <h5 class="section-title">
                            <i class="fas fa-bookmark"></i>
                            Active Flight Bids ({{ $current_bids->count() }})
                        </h5>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            @foreach($current_bids->take(3) as $bid)
                            <div class="col-md-4">
                                <div class="glass-card p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="stat-icon me-3" style="width: 48px; height: 48px; font-size: 1rem; background: var(--info-gradient)">
                                            <i class="fas fa-plane"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                @if($bid->flight)
                                                    {{ $bid->flight->ident }}
                                                @else
                                                    Flight {{ $bid->flight_id }}
                                                @endif
                                            </h6>
                                            <small class="text-muted">{{ $bid->flight->dpt_airport_id ?? 'Unknown' }} → {{ $bid->flight->arr_airport_id ?? 'Unknown' }}</small>
                                        </div>
                                    </div>
                                    @if($bid->aircraft)
                                        <p class="text-muted mb-3">
                                            <i class="fas fa-plane-departure me-2"></i>{{ $bid->aircraft->name }}
                                        </p>
                                    @endif
                                    <a href="{{ route('frontend.flights.show', $bid->flight_id) }}" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-eye me-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($current_bids->count() > 3)
                            <div class="text-center mt-4">
                                <a href="{{ route('frontend.flights.bids') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-list me-2"></i>View All {{ $current_bids->count() }} Bids
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Premium Last PIREP Section --}}
                <div class="premium-section mb-4 animate__animated animate__fadeInUp animate__delay-5s">
                    <div class="section-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="section-title mb-0">
                                <i class="fas fa-file-alt"></i>
                                @lang('dashboard.yourlastreport')
                            </h5>
                            @if ($last_pirep !== null)
                                <span class="badge bg-white bg-opacity-25">
                                    {{ $last_pirep->created_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="section-body">
                        @if ($last_pirep === null)
                            <div class="text-center py-5">
                                <div class="stat-icon mx-auto mb-4" style="background: var(--primary-gradient)">
                                    <i class="fas fa-file-plus"></i>
                                </div>
                                <h5 class="fw-bold mb-3">@lang('dashboard.noreportsyet')</h5>
                                <p class="text-muted mb-4">Ready to begin your aviation journey? File your first flight report to start building your pilot career.</p>
                                <div class="d-flex gap-3 justify-content-center flex-wrap">
                                    <a href="{{ route('frontend.pireps.create') }}" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-plus me-2"></i>@lang('dashboard.fileonenow')
                                    </a>
                                    <a href="{{ route('frontend.flights.index') }}" class="btn btn-outline-primary btn-lg px-4">
                                        <i class="fas fa-search me-2"></i>Browse Flights
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="glass-card p-4">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon me-3" style="width: 48px; height: 48px; font-size: 1rem; background: var(--success-gradient)">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Flight {{ $last_pirep->flight->ident ?? 'N/A' }}</h6>
                                            <small class="text-muted">{{ $last_pirep->dpt_airport_id }} → {{ $last_pirep->arr_airport_id }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success">Completed</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Flight Time</small>
                                        <strong>@minutestotime($last_pirep->flight_time)</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Distance</small>
                                        <strong>{{ $last_pirep->distance }} nm</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Aircraft</small>
                                        <strong>{{ $last_pirep->aircraft->name ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Score</small>
                                        <strong>{{ $last_pirep->score ?? 'N/A' }}%</strong>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Premium Recent Awards --}}
                @if(isset($recent_awards) && $recent_awards->count() > 0)
                <div class="premium-section mb-4 animate__animated animate__fadeInUp animate__delay-6s">
                    <div class="section-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">
                        <h5 class="section-title">
                            <i class="fas fa-trophy"></i>
                            Recent Achievements
                        </h5>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            @foreach($recent_awards->take(4) as $award)
                            <div class="col-md-6">
                                <div class="glass-card p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($award->image_url)
                                                <img src="{{ $award->image_url }}" alt="{{ $award->name }}" class="rounded" style="width: 48px; height: 48px; object-fit: cover;">
                                            @else
                                                <div class="stat-icon" style="width: 48px; height: 48px; font-size: 1rem; background: var(--warning-gradient)">
                                                    <i class="fas fa-award"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $award->name }}</h6>
                                            @if($award->description)
                                                <small class="text-muted">{{ Str::limit($award->description, 40) }}</small>
                                            @endif
                                        </div>
                                        <span class="badge bg-warning">New</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Premium News Section --}}
                <div class="premium-section animate__animated animate__fadeInUp animate__delay-7s">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="fas fa-newspaper"></i>
                            Latest Updates
                        </h5>
                    </div>
                    <div class="section-body">
                        {{ Widget::latestNews(['count' => 5]) }}
                    </div>
                </div>
            </div>

            {{-- Premium Right Sidebar --}}
            <div class="col-lg-4">
                {{-- Network Operations Center --}}
                @if(isset($network_stats))
                <div class="premium-section mb-4 animate__animated animate__fadeInUp animate__delay-4s">
                    <div class="section-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%)">
                        <h5 class="section-title">
                            <i class="fas fa-satellite-dish"></i>
                            Network Operations
                        </h5>
                    </div>
                    <div class="section-body">
                        <div class="row g-3 text-center">
                            <div class="col-4">
                                <div class="glass-card p-3">
                                    <div class="stat-icon mx-auto mb-2" style="width: 48px; height: 48px; font-size: 1rem; background: var(--success-gradient)">
                                        <i class="fas fa-plane"></i>
                                    </div>
                                    <h4 class="fw-bold mb-1">{{ $network_stats['pilots_flying'] ?? 0 }}</h4>
                                    <small class="text-muted">Flying Now</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="glass-card p-3">
                                    <div class="stat-icon mx-auto mb-2" style="width: 48px; height: 48px; font-size: 1rem; background: var(--primary-gradient)">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h4 class="fw-bold mb-1">{{ $network_stats['users_online'] ?? 0 }}</h4>
                                    <small class="text-muted">Online</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="glass-card p-3">
                                    <div class="stat-icon mx-auto mb-2" style="width: 48px; height: 48px; font-size: 1rem; background: var(--info-gradient)">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <h4 class="fw-bold mb-1">{{ $network_stats['todays_flights'] ?? 0 }}</h4>
                                    <small class="text-muted">Today</small>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <div class="d-inline-flex align-items-center px-3 py-2 bg-success bg-opacity-10 rounded-pill">
                                <div class="bg-success rounded-circle me-2" style="width: 8px; height: 8px; animation: pulse 2s infinite;"></div>
                                <small class="text-success fw-medium">Network Status: Online</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Premium Weather Center --}}
                <div class="premium-section mb-4 animate__animated animate__fadeInUp animate__delay-5s">
                    <div class="section-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)">
                        <h5 class="section-title">
                            <i class="fas fa-cloud-sun"></i>
                            @lang('dashboard.weatherat', ['ICAO' => $current_airport])
                        </h5>
                    </div>
                    <div class="section-body">
                        <div class="glass-card p-3 mb-3">
                            {{ Widget::Weather(['icao' => $current_airport]) }}
                        </div>
                    </div>
                </div>

                {{-- Recent Flight Reports --}}
                <div class="premium-section mb-4 animate__animated animate__fadeInUp animate__delay-6s">
                    <div class="section-header" style="background: var(--dark-gradient)">
                        <h5 class="section-title">
                            <i class="fas fa-history"></i>
                            @lang('dashboard.recentreports')
                        </h5>
                    </div>
                    <div class="section-body">
                        {{ Widget::latestPireps(['count' => 5]) }}
                    </div>
                </div>

                {{-- Welcome New Pilots --}}
                <div class="premium-section animate__animated animate__fadeInUp animate__delay-7s">
                    <div class="section-header" style="background: var(--dark-gradient)">
                        <h5 class="section-title">
                            <i class="fas fa-user-plus"></i>
                            @lang('common.newestpilots')
                        </h5>
                    </div>
                    <div class="section-body">
                        {{ Widget::latestPilots(['count' => 5]) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Premium Footer Section --}}
        <div class="row mt-5">
            <div class="col-12">
                <div class="text-center text-white mb-4">
                    <div class="d-inline-flex align-items-center px-4 py-3 glass-card">
                        <i class="fas fa-shield-alt me-3 text-success"></i>
                        <div class="text-start">
                            <div class="fw-bold">Secure Flight Operations</div>
                            <small class="opacity-75">All systems operational • {{ date('Y-m-d H:i') }} UTC</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/world-class-dashboard.js') }}?v={{ time() }}"></script>
    <script>
        // Add premium tooltips
        document.addEventListener('DOMContentLoaded', function() {
            // Add tooltip data attributes
            document.querySelectorAll('.action-card').forEach(card => {
                const title = card.querySelector('h5')?.textContent;
                const description = card.querySelector('p')?.textContent;
                if (title && description) {
                    card.setAttribute('data-tooltip', `<strong>${title}</strong><br>${description}`);
                }
            });

            // Add keyboard shortcut hints
            const shortcuts = {
                'pireps/create': 'Ctrl+P',
                'flights': 'Ctrl+F',
                'bids': 'Ctrl+B',
                'downloads': 'Ctrl+D'
            };

            Object.entries(shortcuts).forEach(([path, shortcut]) => {
                const link = document.querySelector(`a[href*="${path}"]`);
                if (link) {
                    const existing = link.getAttribute('data-tooltip') || '';
                    link.setAttribute('data-tooltip', `${existing}<br><small>Shortcut: ${shortcut}</small>`);
                }
            });
        });
    </script>
@endsection