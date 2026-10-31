@extends('layouts.seven.app')

@section('title', 'NOTAMs')

@section('content')
@php
$breadcrumbs = [
    [
        'title' => 'Pilot Center',
        'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
        'icon' => 'fas fa-home'
    ],
    [
        'title' => 'NOTAMs',
        'icon' => 'fas fa-bell'
    ]
];
@endphp

@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

<div class="container-fluid notams-container">
    <div class="row">
        <div class="col-12">
            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-bell me-2"></i>Notice to Airmen (NOTAMs)
                    </h1>
                    <p class="text-muted">Current operational notices and advisories</p>
                </div>
                <div class="notams-count">
                    <span class="badge">{{ $notams->total() }} Active NOTAMs</span>
                </div>
            </div>

            {{-- Statistics Dashboard --}}
            <div class="row mb-4">
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card blue">
                        <div class="stat-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-number">{{ $stats['total'] ?? $notams->total() }}</div>
                            <div class="stat-label">Total NOTAMs</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card red">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-number">{{ $stats['critical'] ?? 0 }}</div>
                            <div class="stat-label">Critical</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card orange">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-number">{{ $stats['expiring_soon'] ?? 0 }}</div>
                            <div class="stat-label">Expiring Soon</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="stat-card green">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-number">{{ ($stats['total'] ?? $notams->total()) - ($stats['critical'] ?? 0) }}</div>
                            <div class="stat-label">Normal</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search Filters --}}
            <div class="card search-card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('frontend.notams.index') }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Search NOTAMs...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="icao" class="form-label">ICAO Code</label>
                                <input type="text" class="form-control" id="icao" name="icao" 
                                       value="{{ request('icao') }}" placeholder="e.g., KJFK">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="">All Priorities</option>
                                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end mb-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i>Filter
                                </button>
                            </div>
                        </div>
                        @if(request()->hasAny(['search', 'icao', 'priority']))
                            <div class="text-end">
                                <a href="{{ route('frontend.notams.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-times me-1"></i>Clear Filters
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            {{-- NOTAMs List --}}
            <div class="row">
                @forelse($notams as $notam)
                    <div class="col-12 mb-3">
                        <div class="card notam-card border-start border-3" style="border-color: 
                            @switch($notam->priority ?? 'medium')
                                @case('critical') #dc3545 @break
                                @case('high') #fd7e14 @break
                                @case('medium') #ffc107 @break
                                @case('low') #6c757d @break
                                @default #6c757d
                            @endswitch
                        ;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-1">
                                            <span class="badge me-2" style="background-color:
                                                @switch($notam->priority ?? 'medium')
                                                    @case('critical') #dc3545 @break
                                                    @case('high') #fd7e14 @break
                                                    @case('medium') #ffc107 @break
                                                    @case('low') #6c757d @break
                                                    @default #6c757d
                                                @endswitch
                                            ;">{{ strtoupper($notam->identifier) }}</span>
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $notam->icao_code }}
                                        </h5>
                                        @if($notam->user)
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>{{ $notam->user->pilot_id ?? 'System' }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $notam->priority_color ?? 'secondary' }}">
                                            {{ ucfirst($notam->priority) }}
                                        </span>
                                        @if($notam->effective_until)
                                            <div class="small text-muted mt-1">
                                                <i class="fas fa-clock me-1"></i>
                                                Until: {{ $notam->effective_until->format('M d, Y H:i') }} UTC
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <p class="card-text">{{ $notam->body }}</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Effective: {{ $notam->effective_from->format('M d, Y H:i') }} UTC
                                    </small>
                                    <div>
                                        <a href="{{ route('frontend.notams.show', $notam->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                        @if($notam->effective_until && $notam->effective_until->isToday())
                                            <span class="badge bg-warning ms-2">
                                                <i class="fas fa-clock me-1"></i>Expires Today
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No NOTAMs Found</h4>
                            @if(request()->hasAny(['search', 'icao', 'priority']))
                                <p class="text-muted">Try adjusting your filters or search terms.</p>
                                <a href="{{ route('frontend.notams.index') }}" class="btn btn-primary">
                                    <i class="fas fa-refresh me-1"></i>View All NOTAMs
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($notams->hasPages())
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $notams->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.notams-container {
    padding: 2rem 0;
}

.page-title {
    color: var(--jamaica-white);
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    margin-bottom: 0.5rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    height: 140px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.stat-card.blue { border-left: 4px solid #007bff; }
.stat-card.red { border-left: 4px solid #dc3545; }
.stat-card.orange { border-left: 4px solid #fd7e14; }
.stat-card.green { border-left: 4px solid #28a745; }

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: var(--jamaica-gold);
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: var(--jamaica-white);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: rgba(255, 255, 255, 0.8);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
}

.search-card, .notam-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius);
}

.notam-card {
    transition: all 0.3s ease;
}

.notam-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.form-control, .form-select {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: var(--jamaica-white);
}

.form-control:focus, .form-select:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: var(--jamaica-gold);
    box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
    color: var(--jamaica-white);
}

.btn-primary {
    background: linear-gradient(135deg, var(--jamaica-green), var(--jamaica-gold));
    border: none;
    color: var(--jamaica-black);
    font-weight: 700;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--jamaica-gold), var(--jamaica-green));
    transform: translateY(-1px);
}

.btn-outline-primary {
    border: 2px solid var(--jamaica-gold);
    color: var(--jamaica-gold);
    background: transparent;
}

.btn-outline-primary:hover {
    background: var(--jamaica-gold);
    color: var(--jamaica-black);
}

.badge {
    font-weight: 600;
    padding: 0.5rem 0.75rem;
}

.notams-count .badge {
    background: var(--jamaica-green);
    color: white;
    font-size: 1rem;
    padding: 0.75rem 1rem;
}
</style>

@endsection