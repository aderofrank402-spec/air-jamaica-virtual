{{-- Active Flight Status Widget - Real-time flight monitoring --}}
<div class="active-flight-status-widget">
    @if($flight_data)
        {{-- Last Approved Flight Display --}}
        <div class="card border-success">
            <div class="card-header bg-success text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="fas fa-plane text-white me-2"></i>
                    LAST APPROVED FLIGHT
                </h5>
                <span class="badge bg-white text-success">APPROVED</span>
            </div>
            <div class="card-body">
                {{-- Flight Info --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="text-primary mb-1">
                            @if($flight_data['pirep']->flight)
                                {{ $flight_data['pirep']->flight->ident }}
                            @else
                                Manual Flight
                            @endif
                        </h4>
                        <p class="text-muted mb-2">
                            {{ optional($flight_data['pirep']->aircraft)->name ?? 'Unknown Aircraft' }}
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h5 class="text-success mb-1">{{ $flight_phase }}</h5>
                        <small class="text-muted">Flight Phase</small>
                    </div>
                </div>

                {{-- Route Info --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="flight-route d-flex align-items-center justify-content-center">
                            <div class="airport-code">
                                <span class="badge bg-primary fs-6">{{ $flight_data['pirep']->dpt_airport_id }}</span>
                                <div class="airport-name">{{ optional($flight_data['pirep']->dpt_airport)->name ?? 'Unknown' }}</div>
                            </div>
                            <div class="route-line mx-3">
                                <i class="fas fa-plane text-primary"></i>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: {{ $flight_data['progress_percentage'] }}%"></div>
                                </div>
                            </div>
                            <div class="airport-code">
                                <span class="badge bg-secondary fs-6">{{ $flight_data['pirep']->arr_airport_id }}</span>
                                <div class="airport-name">{{ optional($flight_data['pirep']->arr_airport)->name ?? 'Unknown' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Flight Statistics --}}
                @if($latest_acars && $config['show_acars_details'])
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="flight-stat text-center">
                            <div class="stat-value">{{ number_format($latest_acars->altitude ?? 0) }}</div>
                            <div class="stat-label">Altitude (ft)</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="flight-stat text-center">
                            <div class="stat-value">{{ number_format($latest_acars->gs ?? 0) }}</div>
                            <div class="stat-label">Ground Speed (kts)</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="flight-stat text-center">
                            <div class="stat-value">{{ gmdate('H:i', $flight_data['flight_time'] * 60) }}</div>
                            <div class="stat-label">Elapsed Time</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="flight-stat text-center">
                            <div class="stat-value">{{ number_format($flight_data['estimated_remaining']) }}m</div>
                            <div class="stat-label">ETA Remaining</div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row g-3">
                    <div class="col-md-4 col-sm-6">
                        <div class="flight-stat text-center">
                            <div class="stat-value">{{ gmdate('H:i', $flight_data['flight_time'] * 60) }}</div>
                            <div class="stat-label">Elapsed Time</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="flight-stat text-center">
                            <div class="stat-value">{{ $flight_data['progress_percentage'] }}%</div>
                            <div class="stat-label">Progress</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="flight-stat text-center">
                            <div class="stat-value">{{ number_format($flight_data['estimated_remaining']) }}m</div>
                            <div class="stat-label">ETA</div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ route('frontend.pireps.show', $flight_data['pirep']->id) }}" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-eye"></i> View Flight
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- No Approved Flight --}}
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-plane text-white me-2"></i>
                    FLIGHT STATUS
                </h5>
            </div>
            <div class="card-body text-center py-4">
                <i class="fas fa-plane-departure fa-3x text-muted mb-3"></i>
                <h4 class="text-muted mb-2">No Approved Flights</h4>
                <p class="text-muted mb-3">You have no approved flight to display</p>
                <a href="{{ route('frontend.flights.index') }}" class="btn btn-primary">
                    <i class="fas fa-search"></i> Find Flights
                </a>
                <a href="{{ route('frontend.pireps.create') }}" class="btn btn-success ms-2">
                    <i class="fas fa-plus"></i> File PIREP
                </a>
            </div>
        </div>
    @endif
</div>

<style>
.active-flight-status-widget .pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.active-flight-status-widget .flight-route {
    position: relative;
}

.active-flight-status-widget .airport-code {
    text-align: center;
}

.active-flight-status-widget .airport-name {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 4px;
}

.active-flight-status-widget .route-line {
    flex: 1;
    position: relative;
}

.active-flight-status-widget .route-line .fas {
    position: absolute;
    top: -8px;
    left: 50%;
    transform: translateX(-50%);
}

.active-flight-status-widget .flight-stat {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
}

.active-flight-status-widget .stat-value {
    font-size: 1.25rem;
    font-weight: bold;
    color: #007bff;
}

.active-flight-status-widget .stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 2px;
}

.active-flight-status-widget .card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh flight status every 30 seconds if there's an active flight
    @if($flight_data && $config['refresh_interval'])
    setInterval(function() {
        // You can implement AJAX refresh here
        // For now, we'll just reload the page section
        console.log('Flight status refresh interval triggered');
    }, {{ $config['refresh_interval'] }});
    @endif
});
</script>