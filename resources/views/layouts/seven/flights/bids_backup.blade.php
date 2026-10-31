@extends('layouts.seven.public_app')
@section('title', __('flights.mybid'))

@section('css')
    {{-- Air Jamaica Virtual Flight Bids Styles --}}
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
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #2a2f3e 100%) !important;
            background-attachment: fixed;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Effects */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 80%, rgba(0, 166, 81, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(254, 209, 0, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        /* Flight Bids Container */
        .jamaica-flight-bids {
            padding: 100px 0 3rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Hero Section */
        .hero-section {
            background: rgba(15, 20, 25, 0.3);
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 3rem;
            text-align: center;
            color: var(--jamaica-white);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, var(--jamaica-green), var(--jamaica-yellow));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            color: var(--jamaica-light);
        }

        /* Enhanced Card Styling */
        .card {
            background: rgba(26, 31, 46, 0.6) !important;
            border: 1px solid rgba(42, 47, 62, 0.3) !important;
            border-radius: 20px !important;
            backdrop-filter: blur(10px) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3) !important;
            transition: all 0.3s ease !important;
            position: relative !important;
            overflow: hidden !important;
            margin-bottom: 2rem !important;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--jamaica-green), var(--jamaica-yellow));
            opacity: 0.8;
        }

        .card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4) !important;
            border-color: rgba(0, 166, 81, 0.4) !important;
        }

        .card-header {
            background: rgba(0, 166, 81, 0.1) !important;
            border-bottom: 1px solid rgba(42, 47, 62, 0.3) !important;
            color: var(--jamaica-white) !important;
            font-weight: 600 !important;
            padding: 1.5rem !important;
        }

        .card-body {
            color: var(--jamaica-white) !important;
            position: relative;
            z-index: 1;
            padding: 2rem !important;
        }

        .card-title {
            color: var(--jamaica-green) !important;
            font-weight: 600 !important;
            margin-bottom: 1.5rem !important;
            font-size: 1.25rem !important;
        }

        /* Action Buttons */
        .btn-jamaica {
            background: linear-gradient(45deg, var(--jamaica-green), var(--jamaica-blue)) !important;
            border: none !important;
            color: white !important;
            font-weight: 600 !important;
            padding: 0.75rem 2rem !important;
            border-radius: 25px !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            box-shadow: 0 4px 15px rgba(0, 166, 81, 0.3) !important;
        }

        .btn-jamaica:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(0, 166, 81, 0.4) !important;
            color: white !important;
        }

        .btn-outline-jamaica {
            background: transparent !important;
            border: 2px solid var(--jamaica-green) !important;
            color: var(--jamaica-green) !important;
            font-weight: 600 !important;
            padding: 0.5rem 1.5rem !important;
            border-radius: 25px !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
        }

        .btn-outline-jamaica:hover {
            background: var(--jamaica-green) !important;
            color: white !important;
            transform: translateY(-2px) !important;
        }

        /* Table Styling */
        .table {
            color: var(--jamaica-white) !important;
            margin-bottom: 0 !important;
        }

        .table th {
            background: rgba(0, 166, 81, 0.1) !important;
            border-color: rgba(42, 47, 62, 0.3) !important;
            color: var(--jamaica-white) !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 0.85rem !important;
            letter-spacing: 0.5px !important;
            padding: 1rem !important;
        }

        .table td {
            border-color: rgba(42, 47, 62, 0.3) !important;
            vertical-align: middle !important;
            padding: 1rem !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 166, 81, 0.1) !important;
        }

        /* Badge Styling */
        .badge {
            font-size: 0.75rem !important;
            padding: 0.5rem 1rem !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        /* Alert Styling */
        .alert {
            border: none !important;
            border-radius: 15px !important;
            padding: 1rem 1.5rem !important;
            margin-bottom: 2rem !important;
            backdrop-filter: blur(10px) !important;
        }

        .alert-success {
            background: rgba(0, 166, 81, 0.2) !important;
            border-left: 4px solid var(--jamaica-green) !important;
            color: #22c55e !important;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2) !important;
            border-left: 4px solid #dc3545 !important;
            color: #f87171 !important;
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.2) !important;
            border-left: 4px solid #ffc107 !important;
            color: #fbbf24 !important;
        }

        .alert-info {
            background: rgba(23, 162, 184, 0.2) !important;
            border-left: 4px solid #17a2b8 !important;
            color: #06b6d4 !important;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--jamaica-white);
        }

        .empty-state-icon {
            font-size: 4rem;
            color: var(--jamaica-green);
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }

        .empty-state-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--jamaica-white);
        }

        .empty-state-text {
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Flight Stats */
        .flight-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .stat-item {
            background: rgba(26, 31, 46, 0.6);
            border: 1px solid rgba(42, 47, 62, 0.3);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            min-width: 150px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            border-color: rgba(0, 166, 81, 0.4);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--jamaica-green);
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--jamaica-white);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }

        /* Links */
        a {
            color: var(--jamaica-green) !important;
            text-decoration: none !important;
            transition: color 0.3s ease !important;
        }

        a:hover {
            color: var(--jamaica-yellow) !important;
        }

        .text-jamaica {
            color: var(--jamaica-green) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .jamaica-flight-bids {
                padding: 80px 0 2rem 0;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .flight-stats {
                flex-direction: column;
                align-items: center;
            }
            
            .stat-item {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
@endsection

@section('content_header')
@endsection

@section('content')
<div class="jamaica-flight-bids">
    <div class="container-fluid">

        {{-- Breadcrumb Navigation --}}
        @php
        $breadcrumbs = [
            [
                'title' => 'Pilot Center',
                'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
                'icon' => Auth::check() ? 'bi bi-speedometer2' : 'bi bi-house-fill'
            ],
            [
                'title' => trans_choice('common.flight', 2),
                'url' => route('frontend.flights.index'),
                'icon' => 'bi bi-airplane-engines-fill'
            ],
            [
                'title' => __('flights.mybid'),
                'icon' => 'bi bi-bookmark-star-fill'
            ]
        ];
        @endphp
        @include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

        {{-- Hero Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-section">
                    <div class="hero-content">
                        <h1 class="hero-title">My Flight Bids</h1>
                        <p class="hero-subtitle">
                            <i class="fas fa-bookmark me-2"></i>
                            Manage your reserved flights and upcoming missions
                        </p>
                        
                        {{-- Flight Stats --}}
                        <div class="flight-stats">
                            <div class="stat-item">
                                <span class="stat-number">{{ $flights ? $flights->count() : 0 }}</span>
                                <div class="stat-label">Active Bids</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $flights ? $flights->where('flight_type', 'J')->count() : 0 }}</span>
                                <div class="stat-label">Passenger Flights</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $flights ? $flights->where('flight_type', 'H')->count() : 0 }}</span>
                                <div class="stat-label">Cargo Flights</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @include('flash::message')

        {{-- Bids Content --}}
        <div class="row">
            <div class="col-12">
                @if($flights && $flights->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-bookmark-star me-2"></i>
                                Your Reserved Flights
                            </h5>
                        </div>
                        <div class="card-body">
                            @include('flights.table')
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-bookmark"></i>
                                </div>
                                <h3 class="empty-state-title">No Flight Bids Yet</h3>
                                <p class="empty-state-text">
                                    You haven't reserved any flights yet. Browse our available flights and place bids on the routes you'd like to fly.
                                </p>
                                <a href="{{ route('frontend.flights.index') }}" class="btn btn-jamaica">
                                    <i class="fas fa-search me-2"></i>
                                    Browse Available Flights
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('frontend.flights.index') }}" class="btn btn-outline-jamaica w-100">
                                    <i class="fas fa-search me-2"></i>
                                    Find New Flights
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('frontend.pireps.create') }}" class="btn btn-outline-jamaica w-100">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    File a PIREP
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('frontend.pireps.index') }}" class="btn btn-outline-jamaica w-100">
                                    <i class="fas fa-file-text me-2"></i>
                                    View My PIREPs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@include('flights.scripts')