@extends('layouts.seven.public_app')
@section('title', trans_choice('common.flight', 2))

@section('css')
    {{-- Air Jamaica Virtual Flights Styles --}}
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

        /* Flights Container */
        .jamaica-flights {
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

        /* Search and Filter Section */
        .search-filters {
            background: rgba(26, 31, 46, 0.6);
            border: 1px solid rgba(42, 47, 62, 0.3);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .form-control, .form-select {
            background: rgba(15, 20, 25, 0.6) !important;
            border: 1px solid rgba(42, 47, 62, 0.5) !important;
            border-radius: 12px !important;
            color: var(--jamaica-white) !important;
            padding: 0.875rem 1.25rem !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(10px) !important;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(15, 20, 25, 0.8) !important;
            border-color: var(--jamaica-green) !important;
            box-shadow: 0 0 0 3px rgba(0, 166, 81, 0.2) !important;
            color: var(--jamaica-white) !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
            opacity: 1 !important;
        }

        .form-label {
            color: var(--jamaica-green) !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            margin-bottom: 0.5rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        /* Buttons */
        .btn-jamaica {
            background: linear-gradient(45deg, var(--jamaica-green), var(--jamaica-blue)) !important;
            border: none !important;
            color: white !important;
            font-weight: 600 !important;
            padding: 0.875rem 2rem !important;
            border-radius: 25px !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            box-shadow: 0 4px 15px rgba(0, 166, 81, 0.3) !important;
            font-size: 0.9rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
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
            padding: 0.75rem 1.5rem !important;
            border-radius: 25px !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
            font-size: 0.9rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        .btn-outline-jamaica:hover {
            background: var(--jamaica-green) !important;
            color: white !important;
            transform: translateY(-2px) !important;
        }

        /* View Toggle */
        .view-toggle {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .view-toggle .btn {
            background: rgba(26, 31, 46, 0.6) !important;
            border: 1px solid rgba(42, 47, 62, 0.3) !important;
            color: var(--jamaica-white) !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 15px !important;
            transition: all 0.3s ease !important;
        }

        .view-toggle .btn.active {
            background: linear-gradient(45deg, var(--jamaica-green), var(--jamaica-blue)) !important;
            border-color: var(--jamaica-green) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 15px rgba(0, 166, 81, 0.3) !important;
        }

        .view-toggle .btn:hover:not(.active) {
            background: rgba(0, 166, 81, 0.2) !important;
            border-color: rgba(0, 166, 81, 0.4) !important;
        }

        /* Quick Filters */
        .quick-filters {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .quick-filter-btn {
            background: rgba(26, 31, 46, 0.6) !important;
            border: 1px solid rgba(42, 47, 62, 0.3) !important;
            color: var(--jamaica-white) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 20px !important;
            font-size: 0.85rem !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
            text-decoration: none !important;
        }

        .quick-filter-btn:hover,
        .quick-filter-btn.active {
            background: linear-gradient(45deg, var(--jamaica-green), var(--jamaica-blue)) !important;
            color: white !important;
            border-color: var(--jamaica-green) !important;
            transform: translateY(-1px) !important;
        }

        /* Statistics */
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

        /* Pagination */
        .pagination {
            justify-content: center !important;
        }

        .page-link {
            background: rgba(26, 31, 46, 0.6) !important;
            border: 1px solid rgba(42, 47, 62, 0.3) !important;
            color: var(--jamaica-white) !important;
            border-radius: 10px !important;
            margin: 0 0.25rem !important;
            transition: all 0.3s ease !important;
        }

        .page-link:hover {
            background: var(--jamaica-green) !important;
            border-color: var(--jamaica-green) !important;
            color: white !important;
        }

        .page-item.active .page-link {
            background: linear-gradient(45deg, var(--jamaica-green), var(--jamaica-blue)) !important;
            border-color: var(--jamaica-green) !important;
        }

        /* Loading State */
        .loading-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--jamaica-white);
        }

        .loading-spinner {
            border: 4px solid rgba(0, 166, 81, 0.3);
            border-top: 4px solid var(--jamaica-green);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

        {{-- Hero Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-section">
                    <div class="hero-content">
                        <h1 class="hero-title">Available Flights</h1>
                        <p class="hero-subtitle">
                            <i class="fas fa-globe me-2"></i>
                            Discover and bid on flights from Air Jamaica Virtual's extensive route network
                        </p>
                        
                        {{-- Flight Statistics --}}
                        <div class="flight-stats">
                            <div class="stat-item">
                                <span class="stat-number">{{ $flights ? $flights->total() : 0 }}</span>
                                <div class="stat-label">Total Flights</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $airlines ? $airlines->count() : 0 }}</span>
                                <div class="stat-label">Airlines</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $airports ? count($airports) : 0 }}</span>
                                <div class="stat-label">Airports</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @include('flash::message')

        {{-- Search and Filters --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="search-filters">
                    <form method="GET" action="{{ route('frontend.flights.index') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="airline_id" class="form-label">Airline</label>
                                <select name="airline_id" id="airline_id" class="form-select">
                                    <option value="">All Airlines</option>
                                    @if($airlines)
                                        @foreach($airlines as $airline)
                                            <option value="{{ $airline->id }}" {{ request('airline_id') == $airline->id ? 'selected' : '' }}>
                                                {{ $airline->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="flight_number" class="form-label">Flight Number</label>
                                <input type="text" class="form-control" id="flight_number" name="flight_number" 
                                       value="{{ request('flight_number') }}" placeholder="e.g., JM123">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="route_code" class="form-label">Route</label>
                                <input type="text" class="form-control" id="route_code" name="route_code" 
                                       value="{{ request('route_code') }}" placeholder="e.g., KIN-MIA">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="aircraft_id" class="form-label">Aircraft</label>
                                <select name="aircraft_id" id="aircraft_id" class="form-select">
                                    <option value="">All Aircraft</option>
                                    @if($subfleets)
                                        @foreach($subfleets as $subfleet)
                                            <option value="{{ $subfleet->id }}" {{ request('aircraft_id') == $subfleet->id ? 'selected' : '' }}>
                                                {{ $subfleet->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-jamaica me-2">
                                    <i class="fas fa-search me-2"></i>Search Flights
                                </button>
                                <a href="{{ route('frontend.flights.index') }}" class="btn btn-outline-jamaica">
                                    <i class="fas fa-times me-2"></i>Clear Filters
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Quick Filters --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="quick-filters">
                    <a href="{{ route('frontend.flights.index', ['flight_type' => 'J']) }}" 
                       class="quick-filter-btn {{ request('flight_type') == 'J' ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i>Passenger Flights
                    </a>
                    <a href="{{ route('frontend.flights.index', ['flight_type' => 'H']) }}" 
                       class="quick-filter-btn {{ request('flight_type') == 'H' ? 'active' : '' }}">
                        <i class="fas fa-boxes me-2"></i>Cargo Flights
                    </a>
                    <a href="{{ route('frontend.flights.index', ['flight_type' => 'C']) }}" 
                       class="quick-filter-btn {{ request('flight_type') == 'C' ? 'active' : '' }}">
                        <i class="fas fa-plane me-2"></i>Charter Flights
                    </a>
                    @if(Auth::check())
                        <a href="{{ route('frontend.flights.bids') }}" class="quick-filter-btn">
                            <i class="fas fa-bookmark me-2"></i>My Bids
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- View Toggle and Results --}}
        <div class="row mb-4">
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
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Flight</th>
                                                <th>Route</th>
                                                <th>Aircraft</th>
                                                <th>Distance</th>
                                                <th>Flight Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($flights as $flight)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $flight->ident }}</strong><br>
                                                        <small class="text-muted">{{ $flight->airline->name ?? '' }}</small>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $flight->dpt_airport_id }} → {{ $flight->arr_airport_id }}</strong><br>
                                                        <small class="text-muted">{{ $flight->distance }} {{ setting('units.distance') }}</small>
                                                    </td>
                                                    <td>{{ $flight->subfleets->first()->name ?? 'Any Aircraft' }}</td>
                                                    <td>{{ $flight->distance }} nm</td>
                                                    <td>@minutestotime($flight->flight_time)</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('frontend.flights.show', $flight->id) }}" class="btn btn-outline-jamaica btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if(Auth::check())
                                                                <button class="btn btn-jamaica btn-sm">
                                                                    <i class="fas fa-bookmark"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
});

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('#airline_id, #aircraft_id');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
</script>
@endsection

@include('flights.scripts')