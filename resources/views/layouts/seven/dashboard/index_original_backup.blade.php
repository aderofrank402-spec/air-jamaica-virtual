@extends('layouts.seven.public_app')
@section('title', __('common.dashboard'))

@section('css')
    {{-- Professional Aviation Dashboard Styles --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

        /* Professional Dashboard Container */
        .aviation-dashboard {
            padding: 100px 0 2rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Professional Navigation Integration */
        .public-nav {
            background: rgba(30, 58, 138, 0.95) !important;
            backdrop-filter: blur(10px) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        /* Professional Cards */
        .aviation-card {
            background: var(--aviation-white);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.2s ease-in-out;
            overflow: hidden;
        }

        .aviation-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-2px);
        }

        .aviation-card-header {
            background: var(--aviation-primary);
            color: var(--aviation-white);
            padding: 1rem 1.5rem;
            border-bottom: 3px solid var(--aviation-accent);
        }

        .aviation-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .aviation-card-body {
            padding: 1.5rem;
        }

        /* Operation Action Items */
        .operation-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 8px;
            text-decoration: none;
            color: var(--aviation-dark);
            transition: all 0.15s ease;
            border: 1px solid transparent;
            margin-bottom: 0.5rem;
        }

        .operation-item:hover {
            background: var(--aviation-light);
            color: var(--aviation-primary);
            border-color: var(--aviation-accent);
            text-decoration: none;
        }

        .operation-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.1rem;
            color: var(--aviation-white);
        }

        .operation-icon.primary { background: var(--aviation-primary); }
        .operation-icon.success { background: var(--aviation-success); }
        .operation-icon.warning { background: var(--aviation-warning); }
        .operation-icon.accent { background: var(--aviation-accent); }

        /* Statistics Cards */
        .stat-metric {
            text-align: center;
            padding: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--aviation-primary);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--aviation-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-progress {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .stat-progress-bar {
            height: 100%;
            background: var(--aviation-success);
            transition: width 1s ease;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            body {
                background-attachment: scroll;
            }

            .aviation-dashboard {
                padding: 80px 0 1rem 0;
            }

            .aviation-card-body {
                padding: 1rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }
        }

        /* Button Enhancements */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background: var(--aviation-primary);
            border-color: var(--aviation-primary);
        }

        .btn-primary:hover {
            background: var(--aviation-secondary);
            border-color: var(--aviation-secondary);
        }

        .btn-outline-primary {
            color: var(--aviation-primary);
            border-color: var(--aviation-primary);
        }

        .btn-outline-primary:hover {
            background: var(--aviation-primary);
            border-color: var(--aviation-primary);
        }
    </style>
@endsection

@section('content')
<div class="aviation-dashboard">
    <div class="container-fluid">

        {{-- Pilot Status Alert --}}
        @if (Auth::user()->state === \App\Models\Enums\UserState::ON_LEAVE)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-3"></i>
                    <div>
                        <strong>On Leave Status Active</strong><br>
                        You're currently marked as on leave. File a PIREP to return to active duty.
                        <a href="{{ route('frontend.pireps.create') }}" class="alert-link ms-2">Return to Active</a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Welcome Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="text-center text-white mb-4">
                    <h1 class="h2 fw-bold mb-2">Flight Operations Center</h1>
                    <p class="mb-0 opacity-90">
                        Welcome back, <strong>{{ Auth::user()->name_private }}</strong> • 
                        {{ Auth::user()->rank ? Auth::user()->rank->name : 'Pilot' }} • 
                        {{ Auth::user()->airline ? Auth::user()->airline->name : 'Virtual Airline' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Column 1: Flight Operations --}}
            <div class="col-lg-4 mb-4">
                <div class="aviation-card">
                    <div class="aviation-card-header">
                        <h5 class="aviation-card-title">
                            <i class="fas fa-plane-departure"></i>
                            Flight Operations
                        </h5>
                    </div>
                    <div class="aviation-card-body">
                        <a href="{{ route('frontend.pireps.create') }}" class="operation-item">
                            <div class="operation-icon primary">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">File PIREP</div>
                                <small class="text-muted">Submit flight report</small>
                            </div>
                        </a>

                        <a href="{{ route('frontend.flights.index') }}" class="operation-item">
                            <div class="operation-icon success">
                                <i class="fas fa-route"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Browse Flights</div>
                                <small class="text-muted">Find available routes</small>
                            </div>
                        </a>

                        <a href="{{ route('frontend.flights.bids') }}" class="operation-item">
                            <div class="operation-icon accent">
                                <i class="fas fa-bookmark"></i>
                            </div>
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="fw-semibold">My Bids</div>
                                    <small class="text-muted">Manage booked flights</small>
                                </div>
                                @if(isset($current_bids) && $current_bids->count() > 0)
                                    <span class="badge bg-primary ms-auto">{{ $current_bids->count() }}</span>
                                @endif
                            </div>
                        </a>

                        <a href="{{ route('frontend.downloads.index') }}" class="operation-item">
                            <div class="operation-icon warning">
                                <i class="fas fa-download"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">ACARS Downloads</div>
                                <small class="text-muted">Flight tracking tools</small>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Pilot Statistics --}}
                <div class="aviation-card mt-4">
                    <div class="aviation-card-header">
                        <h5 class="aviation-card-title">
                            <i class="fas fa-chart-line"></i>
                            Pilot Statistics
                        </h5>
                    </div>
                    <div class="aviation-card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="stat-metric">
                                    <div class="stat-value">{{ number_format($user->flights) }}</div>
                                    <div class="stat-label">{{ trans_choice('common.flight', $user->flights) }}</div>
                                    <div class="stat-progress">
                                        <div class="stat-progress-bar" style="width: {{ min(($user->flights / 50) * 100, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-metric">
                                    <div class="stat-value">@minutestotime($user->flight_time)</div>
                                    <div class="stat-label">Flight Hours</div>
                                    <div class="stat-progress">
                                        <div class="stat-progress-bar" style="width: {{ min(($user->flight_time / 3000) * 100, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="stat-metric">
                                    <div class="stat-value">{{ optional($user->journal)->balance ?? '$0' }}</div>
                                    <div class="stat-label">Balance</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-metric">
                                    <div class="stat-value">{{ $current_airport }}</div>
                                    <div class="stat-label">Current Hub</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 2: Active Flights & Recent Activity --}}
            <div class="col-lg-4 mb-4">
                {{-- Current Bids --}}
                @if(isset($current_bids) && $current_bids->count() > 0)
                <div class="aviation-card mb-4">
                    <div class="aviation-card-header">
                        <h5 class="aviation-card-title">
                            <i class="fas fa-clock"></i>
                            Active Bids ({{ $current_bids->count() }})
                        </h5>
                    </div>
                    <div class="aviation-card-body">
                        @foreach($current_bids->take(3) as $bid)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="operation-icon accent me-3" style="width: 36px; height: 36px; font-size: 0.9rem;">
                                <i class="fas fa-plane"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">
                                    @if($bid->flight)
                                        {{ $bid->flight->ident }}
                                    @else
                                        Flight {{ $bid->flight_id }}
                                    @endif
                                </div>
                                <small class="text-muted">{{ $bid->flight->dpt_airport_id ?? 'Unknown' }} → {{ $bid->flight->arr_airport_id ?? 'Unknown' }}</small>
                            </div>
                            <a href="{{ route('frontend.flights.show', $bid->flight_id) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                        @endforeach
                        @if($current_bids->count() > 3)
                            <div class="text-center">
                                <a href="{{ route('frontend.flights.bids') }}" class="btn btn-primary btn-sm">View All {{ $current_bids->count() }} Bids</a>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Last PIREP --}}
                <div class="aviation-card">
                    <div class="aviation-card-header">
                        <h5 class="aviation-card-title">
                            <i class="fas fa-history"></i>
                            @lang('dashboard.yourlastreport')
                        </h5>
                    </div>
                    <div class="aviation-card-body">
                        @if ($last_pirep === null)
                            <div class="text-center py-4">
                                <div class="operation-icon primary mx-auto mb-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-file-plus"></i>
                                </div>
                                <h6 class="fw-semibold mb-2">@lang('dashboard.noreportsyet')</h6>
                                <p class="text-muted mb-3 small">Ready to begin your aviation journey?</p>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('frontend.pireps.create') }}" class="btn btn-primary">
                                        @lang('dashboard.fileonenow')
                                    </a>
                                    <a href="{{ route('frontend.flights.index') }}" class="btn btn-outline-primary btn-sm">
                                        Browse Flights
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-start mb-3">
                                <div class="operation-icon success me-3" style="width: 36px; height: 36px; font-size: 0.9rem;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Flight {{ $last_pirep->flight->ident ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $last_pirep->dpt_airport_id }} → {{ $last_pirep->arr_airport_id }}</small>
                                    <div class="text-muted small mt-1">{{ $last_pirep->created_at->diffForHumans() }}</div>
                                </div>
                                <span class="badge bg-success">Completed</span>
                            </div>
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <strong>Flight Time:</strong><br>
                                    @minutestotime($last_pirep->flight_time)
                                </div>
                                <div class="col-6">
                                    <strong>Distance:</strong><br>
                                    {{ $last_pirep->distance }} nm
                                </div>
                                <div class="col-6">
                                    <strong>Aircraft:</strong><br>
                                    {{ $last_pirep->aircraft->name ?? 'N/A' }}
                                </div>
                                <div class="col-6">
                                    <strong>Score:</strong><br>
                                    {{ $last_pirep->score ?? 'N/A' }}%
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Column 3: Weather & Information --}}
            <div class="col-lg-4 mb-4">
                {{-- Weather Center --}}
                <div class="aviation-card mb-4">
                    <div class="aviation-card-header">
                        <h5 class="aviation-card-title">
                            <i class="fas fa-cloud-sun"></i>
                            Weather - {{ $current_airport }}
                        </h5>
                    </div>
                    <div class="aviation-card-body">
                        {{ Widget::Weather(['icao' => $current_airport]) }}
                    </div>
                </div>

                {{-- Recent Flight Reports --}}
                <div class="aviation-card mb-4">
                    <div class="aviation-card-header">
                        <h5 class="aviation-card-title">
                            <i class="fas fa-file-alt"></i>
                            @lang('dashboard.recentreports')
                        </h5>
                    </div>
                    <div class="aviation-card-body">
                        {{ Widget::latestPireps(['count' => 5]) }}
                    </div>
                </div>

                {{-- Latest News --}}
                <div class="aviation-card">
                    <div class="aviation-card-header">
                        <h5 class="aviation-card-title">
                            <i class="fas fa-newspaper"></i>
                            Latest Updates
                        </h5>
                    </div>
                    <div class="aviation-card-body">
                        {{ Widget::latestNews(['count' => 3]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- Minimal JavaScript for professional functionality --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth hover effects
            const cards = document.querySelectorAll('.aviation-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Animate progress bars
            const progressBars = document.querySelectorAll('.stat-progress-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
            });
        });
    </script>
@endsection