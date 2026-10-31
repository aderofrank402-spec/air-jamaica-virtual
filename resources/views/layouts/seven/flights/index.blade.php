@extends('layouts.seven.public_app')
@section('title', trans_choice('common.flight', 2))

@section('css')
    {{-- Air Jamaica Virtual Flights Styles --}}
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
            font-family: 'JetBrains Mono', monospace !important;
            font-size: 16px;
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

        /* Flights Container */
        .jamaica-flights {
            padding: 80px 0 2rem 0;
            min-height: calc(100vh - 80px);
        }

        /* Large monitor: keep content readable, centred, not stretched across 1920px */
        .jamaica-flights > .container-fluid {
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Hero Section */
        /* Hero Section - Refined */
        .hero-section {
            background: rgba(15, 20, 25, 0.4);
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 2.5rem;
            text-align: center;
            color: var(--jamaica-white);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .hero-title {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            background: linear-gradient(45deg, var(--jamaica-green), var(--jamaica-yellow), var(--jamaica-green));
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shine 3s linear infinite;
        }

        @keyframes shine {
            to { background-position: 200% center; }
        }

        .hero-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            color: var(--jamaica-light);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Enhanced Card Styling */
        .card, .search-filters {
            background: rgba(30, 41, 59, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 18px !important;
            backdrop-filter: blur(12px) !important;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.25) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        }

        .card:hover {
            transform: translateY(-4px) !important;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4) !important;
            border-color: rgba(0, 166, 81, 0.5) !important;
        }

        /* Search and Filter Section - Optimized */
        .search-filters {
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .form-control, .form-select {
            background: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(71, 85, 105, 0.5) !important;
            border-radius: 12px !important;
            color: white !important;
            padding: 0.75rem 1.25rem !important;
            font-size: 0.875rem !important;
            transition: all 0.2s ease !important;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(15, 23, 42, 0.8) !important;
            border-color: var(--jamaica-green) !important;
            box-shadow: 0 0 0 4px rgba(0, 166, 81, 0.15) !important;
        }

        .form-label {
            color: var(--jamaica-green) !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            margin-bottom: 0.6rem !important;
        }

        /* Statistics - Refined */
        .flight-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.25rem 2rem;
            min-width: 160px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: scale(1.05);
            border-color: var(--jamaica-green);
        }

        .stat-number {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--jamaica-yellow);
            display: block;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 0.5rem;
            font-weight: 700;
        }

        /* Buttons - Optimized */
        .btn-jamaica {
            background: linear-gradient(135deg, var(--jamaica-green), #059669) !important;
            border: none !important;
            color: white !important;
            font-weight: 700 !important;
            padding: 0.8rem 2.5rem !important;
            border-radius: 12px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            font-size: 0.875rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.025em !important;
            box-shadow: 0 4px 15px rgba(0, 166, 81, 0.3) !important;
        }

        .btn-jamaica:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(0, 166, 81, 0.4) !important;
            filter: brightness(1.1);
        }

        .btn-outline-jamaica {
            background: rgba(0, 166, 81, 0.05) !important;
            border: 1px solid rgba(0, 166, 81, 0.4) !important;
            color: var(--jamaica-green) !important;
            font-weight: 700 !important;
            padding: 0.8rem 2.5rem !important;
            border-radius: 12px !important;
            transition: all 0.2s ease !important;
            font-size: 0.875rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.025em !important;
        }

        .btn-outline-jamaica:hover {
            background: var(--jamaica-green) !important;
            color: white !important;
            border-color: var(--jamaica-green) !important;
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

        /* View Toggle - Refined */
        .view-toggle {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .view-toggle .btn {
            background: rgba(30, 41, 59, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: rgba(255, 255, 255, 0.7) !important;
            padding: 0.6rem 1.25rem !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            transition: all 0.2s ease !important;
        }

        .view-toggle .btn.active {
            background: var(--jamaica-green) !important;
            color: white !important;
            border-color: var(--jamaica-green) !important;
            box-shadow: 0 4px 15px rgba(0, 166, 81, 0.3) !important;
        }

        /* Quick Filters - Refined */
        .quick-filters {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .quick-filter-btn {
            background: rgba(30, 41, 59, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 10px !important;
            font-size: 0.813rem !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
            text-decoration: none !important;
        }

        .quick-filter-btn:hover, .quick-filter-btn.active {
            background: rgba(0, 166, 81, 0.15) !important;
            border-color: var(--jamaica-green) !important;
            color: var(--jamaica-green) !important;
        }

        /* Pagination - Refined */
        .pagination {
            gap: 0.25rem;
        }

        .page-link {
            background: rgba(30, 41, 59, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 10px !important;
            padding: 0.5rem 1rem !important;
            color: white !important;
            transition: all 0.2s ease !important;
        }

        .page-item.active .page-link {
            background: var(--jamaica-green) !important;
            border-color: var(--jamaica-green) !important;
        }

        .page-link:hover {
            background: rgba(0, 166, 81, 0.2) !important;
            border-color: var(--jamaica-green) !important;
            color: white !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .jamaica-flights {
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
            
            .search-filters {
                padding: 1.5rem;
            }
            
            .view-toggle {
                justify-content: center;
            }
            
            .quick-filters {
                justify-content: center;
            }
            
            #tableView .btn {
                font-size: 0.75rem !important;
                padding: 0.4rem 0.75rem !important;
            }
            
            #tableView .table th,
            #tableView .table td {
                padding: 0.75rem 0.5rem !important;
                font-size: 0.85rem;
            }
        }
        /* Table View - Refined */
        .flights-table {
            background: rgba(30, 41, 59, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 18px !important;
            overflow: hidden !important;
            backdrop-filter: blur(12px) !important;
        }

        .flights-table table {
            color: white !important;
            margin-bottom: 0 !important;
        }

        .flights-table thead {
            background: rgba(0, 166, 81, 0.15) !important;
            border-bottom: 2px solid rgba(0, 166, 81, 0.2) !important;
        }

        .flights-table th {
            color: var(--jamaica-green) !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            font-size: 0.75rem !important;
            padding: 1.25rem 1rem !important;
            border: none !important;
        }

        .flights-table td {
            padding: 1.25rem 1rem !important;
            vertical-align: middle !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        .flights-table tr:last-child td {
            border-bottom: none !important;
        }

        .flights-table tr:hover {
            background: rgba(255, 255, 255, 0.03) !important;
        }

        .table-ident {
            font-size: 1.1rem !important;
            font-weight: 800 !important;
            color: var(--jamaica-green) !important;
        }

        .table-airport {
            font-weight: 700 !important;
            color: white !important;
            font-size: 1rem !important;
        }
    </style>
@endsection

@section('content_header')
@endsection

@section('content')
<div class="jamaica-flights">
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
                'icon' => 'bi bi-airplane-engines-fill'
            ]
        ];
        @endphp
        @include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

        {{-- Hero Section - Refined --}}
        <div class="hero-section">
            <h1 class="hero-title">Available Flights</h1>
            <p class="hero-subtitle">
                Discover the skies with Air Jamaica Virtual. Browse through our extensive network of global routes and select your next assignment.
            </p>
            
            <div class="flight-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $flights ? $flights->total() : 0 }}</span>
                    <div class="stat-label">Schedules</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $airlines ? (count($airlines) - 1) : 0 }}</span>
                    <div class="stat-label">Airlines</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $airports ? (count($airports) - 1) : 0 }}</span>
                    <div class="stat-label">Destinations</div>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @include('flash::message')

        {{-- Search and Filters - Refined --}}
        <div class="search-filters">
            <h5 class="mb-4" style="color: var(--jamaica-green); font-weight: 800; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em;">
                <i class="fas fa-filter me-2"></i> Flight Search & Filters
            </h5>
            
            <form method="GET" action="{{ route('frontend.flights.index') }}">
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Search Anywhere</label>
                        <input type="text" name="quick_search" class="form-control" value="{{ request('quick_search') }}" placeholder="Search ID, route, or city...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Airline</label>
                        <select name="airline_id" class="form-select select2">
                            <option value="">All Airlines</option>
                            @if($airlines)
                                @foreach($airlines as $id => $name)
                                    <option value="{{ $id }}" {{ request('airline_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Departure</label>
                        <select name="dep_icao" class="form-select select2">
                            <option value="">Any Departure</option>
                            @if($airports)
                                @foreach($airports as $id => $name)
                                    <option value="{{ $id }}" {{ request('dep_icao') == $id ? 'selected' : '' }}>{{ $id }} - {{ $name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Arrival</label>
                        <select name="arr_icao" class="form-select select2">
                            <option value="">Any Arrival</option>
                            @if($airports)
                                @foreach($airports as $id => $name)
                                    <option value="{{ $id }}" {{ request('arr_icao') == $id ? 'selected' : '' }}>{{ $id }} - {{ $name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-2 pt-4 border-top border-secondary border-opacity-20">
                    <div class="quick-filters">
                        <a href="{{ route('frontend.flights.index', ['flight_type' => 'J']) }}" class="quick-filter-btn {{ request('flight_type') == 'J' ? 'active' : '' }}">Passenger</a>
                        <a href="{{ route('frontend.flights.index', ['flight_type' => 'H']) }}" class="quick-filter-btn {{ request('flight_type') == 'H' ? 'active' : '' }}">Cargo</a>
                        <a href="{{ route('frontend.flights.index', ['flight_type' => 'C']) }}" class="quick-filter-btn {{ request('flight_type') == 'C' ? 'active' : '' }}">Charter</a>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <a href="{{ route('frontend.flights.index') }}" class="btn-outline-jamaica">Reset</a>
                        <button type="submit" class="btn btn-jamaica">Search Flights</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- View Toggle and Results --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="view-toggle">
                    <button type="button" class="btn active" onclick="switchView('card')" id="cardViewBtn">
                        <i class="fas fa-th-large me-2"></i>Card View
                    </button>
                    <button type="button" class="btn" onclick="switchView('table')" id="tableViewBtn">
                        <i class="fas fa-list me-2"></i>Table View
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end align-items-center">
                    <span class="text-white me-3">
                        @if($flights)
                            Showing {{ $flights->firstItem() }}-{{ $flights->lastItem() }} of {{ $flights->total() }} flights
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- Flights Table/Cards --}}
        <div class="row">
            <div class="col-12">
                <div id="loadingState" class="loading-state" style="display: none;">
                    <div class="loading-spinner"></div>
                    <h4>Loading Flights...</h4>
                    <p>Please wait while we fetch the latest flight information.</p>
                </div>

                <div id="cardView">
                    @if($flights && $flights->count() > 0)
                        @include('flights.table')
                    @else
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center py-5">
                                    <i class="fas fa-plane-slash" style="font-size: 4rem; color: var(--jamaica-green); opacity: 0.7; margin-bottom: 1.5rem;"></i>
                                    <h3 style="color: var(--jamaica-white); margin-bottom: 1rem;">No Flights Found</h3>
                                    <p style="color: rgba(255,255,255,0.8); margin-bottom: 2rem;">
                                        No flights match your current search criteria. Try adjusting your filters or browse all available flights.
                                    </p>
                                    <a href="{{ route('frontend.flights.index') }}" class="btn btn-jamaica">
                                        <i class="fas fa-search me-2"></i>View All Flights
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div id="tableView" style="display: none;">
                    @if($flights && $flights->count() > 0)
                        <div class="flights-table">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Flight</th>
                                            <th>Route</th>
                                            <th>Aircraft</th>
                                            <th>Distance</th>
                                            <th>Flight Time</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($flights as $flight)
                                            <tr>
                                                <td>
                                                    <div class="table-ident">{{ $flight->ident }}</div>
                                                    <div class="text-muted small" style="font-weight: 600;">{{ $flight->airline->name ?? '' }}</div>
                                                </td>
                                                <td>
                                                    <div class="table-airport">{{ $flight->dpt_airport_id }} → {{ $flight->arr_airport_id }}</div>
                                                    <div class="text-muted small" style="font-weight: 600;">
                                                        <i class="fas fa-clock me-1" style="color: var(--jamaica-yellow);"></i>{{ $flight->dpt_time }} - {{ $flight->arr_time }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-white small" style="font-weight: 700;">
                                                        {{ $flight->subfleets->first()->name ?? 'Any Aircraft' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="text-white small" style="font-weight: 700;">
                                                        <i class="fas fa-route me-1" style="color: var(--jamaica-green);"></i>{{ $flight->distance }} nm
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-white small" style="font-weight: 700;">
                                                        <i class="fas fa-hourglass-half me-1" style="color: var(--jamaica-yellow);"></i>@minutestotime($flight->flight_time)
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2 flex-wrap" style="min-width: 280px;">
                                                        <a href="{{ route('frontend.flights.show', $flight->id) }}" class="btn btn-action btn-primary" style="padding: 0.4rem 0.8rem !important; font-size: 0.7rem !important;">
                                                            <i class="fas fa-eye me-1"></i>VIEW
                                                        </a>

                                                        @if(Auth::check())
                                                            @php $hasBid = isset($saved[$flight->id]); @endphp
                                                            <button class="btn btn-action {{ $hasBid ? 'btn-danger' : 'btn-success' }} save_flight" 
                                                                    x-id="{{ $flight->id }}" x-saved-class="btn-danger" type="button"
                                                                    style="padding: 0.4rem 0.8rem !important; font-size: 0.7rem !important;">
                                                                <i class="fas {{ $hasBid ? 'fa-minus' : 'fa-plus' }} me-1"></i>{{ $hasBid ? 'REMOVE' : 'BID' }}
                                                            </button>
                                                        @endif

                                                        @if ($simbrief !== false)
                                                            @if ($flight->simbrief && $flight->simbrief->user_id === Auth::id())
                                                                <a href="{{ route('frontend.simbrief.briefing', $flight->simbrief->id) }}" class="btn btn-action btn-warning" style="padding: 0.4rem 0.8rem !important; font-size: 0.7rem !important;">
                                                                    <i class="fas fa-file-alt me-1"></i>BRIEF
                                                                </a>
                                                            @elseif ($simbrief_bids === false || ($simbrief_bids === true && isset($saved[$flight->id])))
                                                                <a href="{{ route('frontend.simbrief.generate') }}?flight_id={{ $flight->id }}" class="btn btn-action btn-warning" style="padding: 0.4rem 0.8rem !important; font-size: 0.7rem !important;">
                                                                    <i class="fas fa-plus me-1"></i>BRIEF
                                                                </a>
                                                            @endif
                                                        @endif

                                                        <a href="{{ route('frontend.pireps.create') }}?flight_id={{ $flight->id }}" class="btn btn-action btn-secondary" style="padding: 0.4rem 0.8rem !important; font-size: 0.7rem !important;">
                                                            <i class="fas fa-file-export me-1"></i>PIREP
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        @if($flights && $flights->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $flights->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
function switchView(view) {
    const cardView = document.getElementById('cardView');
    const tableView = document.getElementById('tableView');
    const cardBtn = document.getElementById('cardViewBtn');
    const tableBtn = document.getElementById('tableViewBtn');
    
    if (view === 'card') {
        cardView.style.display = 'block';
        tableView.style.display = 'none';
        cardBtn.classList.add('active');
        tableBtn.classList.remove('active');
    } else {
        cardView.style.display = 'none';
        tableView.style.display = 'block';
        cardBtn.classList.remove('active');
        tableBtn.classList.add('active');
    }
    
    localStorage.setItem('flightsView', view);
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('flightsView') || 'card';
    switchView(savedView);
    
    // Make airport dropdowns searchable
    const airportSelects = document.querySelectorAll('#dep_icao, #arr_icao');
    airportSelects.forEach(select => {
        // Add search functionality if there are many options
        if (select.options.length > 10) {
            select.setAttribute('data-live-search', 'true');
            select.style.minWidth = '200px';
        }
    });
});

// Auto-submit form on filter change (optional - for better UX)
document.addEventListener('DOMContentLoaded', function() {
    const autoSubmitSelects = document.querySelectorAll('#airline_id, #aircraft_id, #dep_icao, #arr_icao');
    autoSubmitSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Optionally auto-submit (uncomment next line if desired)
            // this.closest('form').submit();
        });
    });
    
    // Add search input functionality
    const quickSearch = document.getElementById('quick_search');
    if (quickSearch) {
        quickSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
    }
});
</script>
@endsection

@include('flights.bids_aircraft')
@include('flights.scripts')