<div class="widget widget-quick-flight-finder">
    <div class="widget-header d-flex justify-content-between align-items-center mb-3">
        <h5 class="widget-title mb-0">
            <i class="fas fa-search text-info me-2"></i>
            Quick Flight Finder
        </h5>
        <div class="widget-actions">
            <a href="{{ route('frontend.flights.index') }}" class="btn btn-sm btn-outline-light">
                <i class="fas fa-list me-1"></i>
                View All
            </a>
        </div>
    </div>

    @if(isset($error))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ $error }}
        </div>
    @else
        <div class="flight-finder-content">
            <!-- Quick Search Tabs -->
            <div class="finder-tabs mb-4">
                <nav class="nav nav-pills finder-nav">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#nearby-flights">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        Nearby
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#popular-routes">
                        <i class="fas fa-fire me-1"></i>
                        Popular
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#quick-hops">
                        <i class="fas fa-clock me-1"></i>
                        Quick
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#long-hauls">
                        <i class="fas fa-globe me-1"></i>
                        Long Haul
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="tab-content finder-content">
                <!-- Nearby Flights -->
                <div class="tab-pane fade show active" id="nearby-flights">
                    @if(count($quick_flights['nearby_flights']) > 0)
                        <div class="flights-grid">
                            @foreach($quick_flights['nearby_flights'] as $flight)
                                @include('widgets.partials.flight_card', ['flight' => $flight])
                            @endforeach
                        </div>
                    @else
                        <div class="no-flights">
                            <i class="fas fa-plane-departure text-muted mb-2"></i>
                            <p class="text-muted">No flights available from your current location</p>
                        </div>
                    @endif
                </div>

                <!-- Popular Routes -->
                <div class="tab-pane fade" id="popular-routes">
                    @if(count($quick_flights['popular_routes']) > 0)
                        <div class="flights-grid">
                            @foreach($quick_flights['popular_routes'] as $flight)
                                @include('widgets.partials.flight_card', ['flight' => $flight])
                            @endforeach
                        </div>
                    @else
                        <div class="no-flights">
                            <i class="fas fa-fire text-muted mb-2"></i>
                            <p class="text-muted">No popular routes available</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Hops -->
                <div class="tab-pane fade" id="quick-hops">
                    @if(count($quick_flights['quick_hops']) > 0)
                        <div class="flights-grid">
                            @foreach($quick_flights['quick_hops'] as $flight)
                                @include('widgets.partials.flight_card', ['flight' => $flight])
                            @endforeach
                        </div>
                    @else
                        <div class="no-flights">
                            <i class="fas fa-clock text-muted mb-2"></i>
                            <p class="text-muted">No quick flights available</p>
                        </div>
                    @endif
                </div>

                <!-- Long Hauls -->
                <div class="tab-pane fade" id="long-hauls">
                    @if(count($quick_flights['long_hauls']) > 0)
                        <div class="flights-grid">
                            @foreach($quick_flights['long_hauls'] as $flight)
                                @include('widgets.partials.flight_card', ['flight' => $flight])
                            @endforeach
                        </div>
                    @else
                        <div class="no-flights">
                            <i class="fas fa-globe text-muted mb-2"></i>
                            <p class="text-muted">No long haul flights available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Filters -->
            <div class="quick-filters mt-4">
                <h6 class="filter-title">
                    <i class="fas fa-filter me-2"></i>
                    Quick Filters
                </h6>
                <div class="filter-row">
                    <!-- Aircraft Types -->
                    @if(count($quick_flights['aircraft_types']) > 0)
                        <div class="filter-group">
                            <label class="filter-label">Aircraft</label>
                            <div class="filter-buttons">
                                @foreach(array_slice($quick_flights['aircraft_types'], 0, 4) as $aircraft)
                                    <button class="filter-btn" data-filter="aircraft" data-value="{{ $aircraft['icao'] }}">
                                        <i class="fas fa-plane me-1"></i>
                                        {{ $aircraft['icao'] }}
                                        <span class="badge">{{ $aircraft['available_count'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Popular Airports -->
                    @if(count($quick_flights['airports']) > 0)
                        <div class="filter-group">
                            <label class="filter-label">Airports</label>
                            <div class="filter-buttons">
                                @foreach(array_slice($quick_flights['airports'], 0, 4) as $airport)
                                    <button class="filter-btn" data-filter="airport" data-value="{{ $airport['icao'] }}">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $airport['icao'] }}
                                        <span class="badge">{{ $airport['total_flights'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions mt-4">
                <div class="row g-2">
                    <div class="col-sm-6">
                        <a href="{{ route('frontend.flights.search') }}" class="action-btn primary">
                            <i class="fas fa-search"></i>
                            <span>Advanced Search</span>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ route('frontend.pireps.create') }}" class="action-btn secondary">
                            <i class="fas fa-plus"></i>
                            <span>File PIREP</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.widget-quick-flight-finder {
    background: rgba(15, 20, 25, 0.6);
    border-radius: 15px;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    color: #b0b0b0;
}

.finder-nav {
    background: rgba(26, 31, 46, 0.6);
    border-radius: 10px;
    padding: 0.3rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.finder-nav .nav-link {
    background: transparent;
    border: none;
    color: #a0a0a0;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    margin: 0 0.2rem;
}

.finder-nav .nav-link:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.05);
}

.finder-nav .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.flights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.no-flights {
    text-align: center;
    padding: 2rem;
    background: rgba(26, 31, 46, 0.3);
    border-radius: 10px;
    border: 1px dashed rgba(255, 255, 255, 0.1);
}

.no-flights i {
    font-size: 2rem;
    display: block;
}

.quick-filters {
    background: rgba(26, 31, 46, 0.4);
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.filter-title {
    color: #e0e0e0;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.filter-group {
    margin-bottom: 1rem;
}

.filter-group:last-child {
    margin-bottom: 0;
}

.filter-label {
    display: block;
    font-size: 0.9rem;
    color: #a0a0a0;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.filter-btn {
    background: rgba(52, 58, 76, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #e0e0e0;
    border-radius: 6px;
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.filter-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
}

.filter-btn .badge {
    background: rgba(0, 123, 255, 0.8);
    color: white;
    border-radius: 10px;
    padding: 0.1rem 0.4rem;
    font-size: 0.7rem;
    margin-left: 0.2rem;
}

.quick-actions .action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.8rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.action-btn.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.action-btn.secondary {
    background: rgba(26, 31, 46, 0.6);
    color: #e0e0e0;
    border-color: rgba(255, 255, 255, 0.1);
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.action-btn.primary:hover {
    color: white;
}

.action-btn.secondary:hover {
    color: #ffffff;
    background: rgba(52, 58, 76, 0.8);
}
</style>