@extends('layouts.seven.public_app')
@section('title', __('common.dashboard'))

@section('css')
    {{-- Air Jamaica Virtual Dashboard Styles --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Air Jamaica Virtual Branding Colors */
        :root {
            --jamaica-green: #00A651;
            --jamaica-yellow: #FED100;
            --jamaica-black: #000000;
            --jamaica-white: #FFFFFF;
            --jamaica-blue: #0056b3;
            --jamaica-light: #f8fafc;
            --jamaica-gray: #6c757d;
            --jamaica-dark: #212529;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Body Styling */
        body {
            padding-top: 0 !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            background: var(--jamaica-black) !important;
            background-attachment: fixed;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* Dashboard Container */
        .jamaica-dashboard {
            padding: 100px 0 3rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Navigation Integration */
        .public-nav {
            background: linear-gradient(135deg, var(--jamaica-green) 0%, var(--jamaica-blue) 100%) !important;
            backdrop-filter: blur(10px) !important;
            border-bottom: 3px solid var(--jamaica-yellow) !important;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--jamaica-green), var(--jamaica-blue));
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 3rem;
            text-align: center;
            color: var(--jamaica-white);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="plane" patternUnits="userSpaceOnUse" width="100" height="100"><path d="M20 50l60 0M50 20l0 60" stroke="rgba(255,255,255,0.1)" stroke-width="2"/></pattern></defs><rect width="100" height="100" fill="url(%23plane)"/></svg>') center/cover;
            opacity: 0.1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
            margin-bottom: 0;
        }

        /* Statistics Section */
        .stats-container {
            background: var(--jamaica-white);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: var(--card-shadow);
            border: 3px solid var(--jamaica-yellow);
        }

        .stats-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .stats-title {
            color: var(--jamaica-green);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-subtitle {
            color: var(--jamaica-gray);
            font-size: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            border: 2px solid var(--jamaica-green);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
            border-color: var(--jamaica-yellow);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            background: var(--jamaica-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--jamaica-white);
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--jamaica-green);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--jamaica-gray);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .stat-progress {
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            margin-top: 1rem;
            overflow: hidden;
        }

        .stat-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--jamaica-green), var(--jamaica-yellow));
            transition: width 1.5s ease;
        }

        /* Operation Cards */
        .operations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .operation-card {
            background: var(--jamaica-white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            border: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .operation-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow-hover);
            border-color: var(--jamaica-yellow);
        }

        .operation-header {
            background: linear-gradient(135deg, var(--jamaica-green), var(--jamaica-blue));
            color: var(--jamaica-white);
            padding: 1.5rem 2rem;
            position: relative;
        }

        .operation-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--jamaica-yellow);
        }

        .operation-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .operation-body {
            padding: 2rem;
        }

        .operation-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 12px;
            text-decoration: none;
            color: var(--jamaica-dark);
            transition: all 0.2s ease;
            border: 2px solid transparent;
            margin-bottom: 1rem;
            background: #f8f9fa;
        }

        .operation-item:hover {
            background: var(--jamaica-light);
            color: var(--jamaica-green);
            border-color: var(--jamaica-green);
            text-decoration: none;
            transform: translateX(5px);
        }

        .operation-item:last-child {
            margin-bottom: 0;
        }

        .operation-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.3rem;
            color: var(--jamaica-white);
            transition: all 0.2s ease;
        }

        .operation-icon.flight { background: var(--jamaica-green); }
        .operation-icon.manage { background: var(--jamaica-blue); }
        .operation-icon.tools { background: linear-gradient(135deg, var(--jamaica-yellow), #ffa500); color: var(--jamaica-dark); }

        .operation-content h5 {
            margin: 0 0 0.25rem 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .operation-content small {
            color: var(--jamaica-gray);
        }

        .operation-badge {
            margin-left: auto;
            background: var(--jamaica-green);
            color: var(--jamaica-white);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .jamaica-dashboard {
                padding: 80px 1rem 2rem 1rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .operations-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .operation-body {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 1.8rem;
            }
        }

        /* Button Styling */
        .btn-jamaica {
            background: linear-gradient(135deg, var(--jamaica-green), var(--jamaica-blue));
            border: none;
            color: var(--jamaica-white);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-jamaica:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 166, 81, 0.3);
            color: var(--jamaica-white);
        }

        .btn-outline-jamaica {
            border: 2px solid var(--jamaica-green);
            color: var(--jamaica-green);
            background: transparent;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-outline-jamaica:hover {
            background: var(--jamaica-green);
            color: var(--jamaica-white);
        }

        /* Alert Styling */
        .alert-jamaica {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 2px solid var(--jamaica-yellow);
            border-radius: 15px;
            margin-bottom: 2rem;
        }
    </style>
@endsection

@section('content')
<div class="jamaica-dashboard">
    <div class="container-fluid">

        {{-- Pilot Status Alert --}}
        @if (Auth::user()->state === \App\Models\Enums\UserState::ON_LEAVE)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-jamaica d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-3" style="color: var(--jamaica-yellow); font-size: 1.5rem;"></i>
                    <div>
                        <strong>On Leave Status Active</strong><br>
                        You're currently marked as on leave. File a PIREP to return to active duty.
                        <a href="{{ route('frontend.pireps.create') }}" class="btn btn-jamaica btn-sm ms-3">Return to Active</a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Hero Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-section">
                    <div class="hero-content">
                        <h1 class="hero-title">Welcome back, {{ Auth::user()->name_private }}</h1>
                        <p class="hero-subtitle">
                            {{ Auth::user()->rank ? Auth::user()->rank->name : 'Pilot' }} • 
                            {{ Auth::user()->airline ? Auth::user()->airline->name : 'Air Jamaica Virtual' }} • 
                            Flight Operations Center
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pilot Statistics Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="stats-container">
                    <div class="stats-header">
                        <h2 class="stats-title">Your Flight Statistics</h2>
                        <p class="stats-subtitle">Track your progress as an Air Jamaica Virtual pilot</p>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-plane"></i>
                            </div>
                            <div class="stat-value">{{ number_format($user->flights) }}</div>
                            <div class="stat-label">{{ trans_choice('common.flight', $user->flights) }}</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: {{ min(($user->flights / 100) * 100, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-value">@minutestotime($user->flight_time)</div>
                            <div class="stat-label">Flight Hours</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: {{ min(($user->flight_time / 5000) * 100, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="stat-value">{{ optional($user->journal)->balance ?? '$0' }}</div>
                            <div class="stat-label">Balance</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: 75%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="stat-value">{{ $current_airport }}</div>
                            <div class="stat-label">Current Hub</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Operations Cards Section --}}
        <div class="row">
            <div class="col-12">
                <div class="operations-grid">
                    
                    {{-- Flight Operations Card --}}
                    <div class="operation-card">
                        <div class="operation-header">
                            <h3 class="operation-title">
                                <i class="fas fa-plane-departure"></i>
                                Flight Operations
                            </h3>
                        </div>
                        <div class="operation-body">
                            <a href="{{ route('frontend.pireps.create') }}" class="operation-item">
                                <div class="operation-icon flight">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>File PIREP</h5>
                                    <small>Submit your flight report and log hours</small>
                                </div>
                            </a>

                            <a href="{{ route('frontend.flights.index') }}" class="operation-item">
                                <div class="operation-icon flight">
                                    <i class="fas fa-route"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Browse Flights</h5>
                                    <small>Find and book available routes</small>
                                </div>
                            </a>

                            @if ($last_pirep === null)
                            <div class="text-center mt-3">
                                <a href="{{ route('frontend.pireps.create') }}" class="btn btn-jamaica">
                                    <i class="fas fa-plus me-2"></i>File Your First PIREP
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Flight Management Card --}}
                    <div class="operation-card">
                        <div class="operation-header">
                            <h3 class="operation-title">
                                <i class="fas fa-clipboard-list"></i>
                                Flight Management
                            </h3>
                        </div>
                        <div class="operation-body">
                            <a href="{{ route('frontend.flights.bids') }}" class="operation-item">
                                <div class="operation-icon manage">
                                    <i class="fas fa-bookmark"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>My Bids</h5>
                                    <small>Manage your booked flights</small>
                                </div>
                                @if(isset($current_bids) && $current_bids->count() > 0)
                                    <span class="operation-badge">{{ $current_bids->count() }}</span>
                                @endif
                            </a>

                            @if(isset($current_bids) && $current_bids->count() > 0)
                                @foreach($current_bids->take(2) as $bid)
                                <div class="operation-item" style="background: #e8f5e8; border-color: var(--jamaica-green);">
                                    <div class="operation-icon manage" style="width: 40px; height: 40px; font-size: 1rem;">
                                        <i class="fas fa-plane"></i>
                                    </div>
                                    <div class="operation-content">
                                        <h5>
                                            @if($bid->flight)
                                                {{ $bid->flight->ident }}
                                            @else
                                                Flight {{ $bid->flight_id }}
                                            @endif
                                        </h5>
                                        <small>{{ $bid->flight->dpt_airport_id ?? 'Unknown' }} → {{ $bid->flight->arr_airport_id ?? 'Unknown' }}</small>
                                    </div>
                                    <a href="{{ route('frontend.flights.show', $bid->flight_id) }}" class="btn btn-outline-jamaica btn-sm">View</a>
                                </div>
                                @endforeach
                            @endif

                            @if ($last_pirep !== null)
                            <div class="operation-item" style="background: #f0f8f0;">
                                <div class="operation-icon manage">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Last Flight: {{ $last_pirep->flight->ident ?? 'N/A' }}</h5>
                                    <small>{{ $last_pirep->dpt_airport_id }} → {{ $last_pirep->arr_airport_id }} • {{ $last_pirep->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tools & Information Card --}}
                    <div class="operation-card">
                        <div class="operation-header">
                            <h3 class="operation-title">
                                <i class="fas fa-tools"></i>
                                Tools & Information
                            </h3>
                        </div>
                        <div class="operation-body">
                            <a href="{{ route('frontend.downloads.index') }}" class="operation-item">
                                <div class="operation-icon tools">
                                    <i class="fas fa-download"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>ACARS Downloads</h5>
                                    <small>Flight tracking and simulation tools</small>
                                </div>
                            </a>

                            <div class="operation-item" style="background: #e6f7ff;">
                                <div class="operation-icon tools">
                                    <i class="fas fa-cloud-sun"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Weather - {{ $current_airport }}</h5>
                                    <small>Current conditions at your hub</small>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="{{ route('frontend.pireps.index') }}" class="btn btn-outline-jamaica btn-sm w-100">
                                            <i class="fas fa-history me-1"></i>History
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('frontend.profile.index') }}" class="btn btn-outline-jamaica btn-sm w-100">
                                            <i class="fas fa-user me-1"></i>Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate progress bars on load
            const progressBars = document.querySelectorAll('.stat-progress-bar');
            progressBars.forEach((bar, index) => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 300 + (index * 200));
            });

            // Add smooth hover effects
            const cards = document.querySelectorAll('.operation-card, .stat-item');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = this.classList.contains('operation-card') ? 'translateY(-8px)' : 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endsection