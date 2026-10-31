<div class="widget widget-dispatch-center">
    <div class="widget-header d-flex justify-content-between align-items-center mb-4">
        <h5 class="widget-title mb-0">
            <i class="fas fa-tower-control text-primary me-2"></i>
            Dispatch Center
        </h5>
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted">
                <i class="fas fa-sync-alt me-1"></i>
                Updated: {{ $updated_at->format('H:i:s') }}
            </small>
            <div class="status-indicator bg-success"></div>
        </div>
    </div>

    <!-- Dispatch Statistics -->
    <div class="dispatch-stats row g-3 mb-4">
        <div class="col-sm-6 col-md-3">
            <div class="stat-card bg-primary text-white p-3 rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['active_flights'] }}</h3>
                        <small>Active Flights</small>
                    </div>
                    <i class="fas fa-plane fs-3 opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="stat-card bg-warning text-dark p-3 rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['pending_departures'] }}</h3>
                        <small>Pending Departures</small>
                    </div>
                    <i class="fas fa-clock fs-3 opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="stat-card bg-info text-white p-3 rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['online_pilots'] }}</h3>
                        <small>Online Pilots</small>
                    </div>
                    <i class="fas fa-user-pilot fs-3 opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="stat-card bg-success text-white p-3 rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['todays_flights'] }}</h3>
                        <small>Today's Flights</small>
                    </div>
                    <i class="fas fa-chart-line fs-3 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Flights Monitor -->
    @if($config['show_active_flights'] && isset($active_flights) && $active_flights->count() > 0)
        <div class="active-flights-section mb-4">
            <h6 class="section-title">
                <i class="fas fa-radar-alt me-2"></i>
                Active Flight Monitor
            </h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Flight</th>
                            <th>Pilot</th>
                            <th>Aircraft</th>
                            <th>Route</th>
                            <th>Phase</th>
                            <th>Altitude</th>
                            <th>ETA</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($active_flights as $flight)
                            <tr class="flight-row" data-flight-id="{{ $flight['id'] }}">
                                <td class="fw-bold">{{ $flight['flight_number'] }}</td>
                                <td>{{ $flight['pilot'] }}</td>
                                <td>{{ $flight['aircraft'] }}</td>
                                <td>
                                    <span class="route">{{ $flight['departure'] }} → {{ $flight['arrival'] }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $flight['phase'] }}</span>
                                </td>
                                <td>{{ number_format($flight['altitude']) }} ft</td>
                                <td>{{ $flight['eta'] ?? '--:--' }}</td>
                                <td>
                                    <div class="status-indicator-small bg-success" title="Live"></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Pending Departures -->
    @if($config['show_pending_departures'] && isset($pending_departures) && $pending_departures->count() > 0)
        <div class="pending-departures-section mb-4">
            <h6 class="section-title">
                <i class="fas fa-plane-departure me-2"></i>
                Pending Departures
            </h6>
            <div class="departure-list">
                @foreach($pending_departures as $departure)
                    <div class="departure-item d-flex justify-content-between align-items-center p-2 border rounded mb-2">
                        <div class="flight-info">
                            <strong>{{ $departure['flight_number'] }}</strong>
                            <span class="text-muted">{{ $departure['pilot'] }}</span>
                            <small class="ms-2">{{ $departure['departure'] }} → {{ $departure['arrival'] }}</small>
                        </div>
                        <div class="departure-status d-flex align-items-center gap-2">
                            @if($departure['delay'] > 0)
                                <span class="badge bg-warning">{{ $departure['delay'] }}min delay</span>
                            @endif
                            <span class="badge bg-primary">{{ $departure['status'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Weather Information -->
    @if($config['show_weather'] && isset($weather))
        <div class="weather-section mb-4">
            <h6 class="section-title">
                <i class="fas fa-cloud-sun me-2"></i>
                Hub Weather ({{ $weather['icao'] }})
            </h6>
            <div class="weather-info bg-light p-3 rounded">
                @if($weather['metar'])
                    <div class="metar mb-2">
                        <strong>METAR:</strong>
                        <code class="text-dark">{{ $weather['metar'] }}</code>
                    </div>
                @endif
                @if($weather['taf'])
                    <div class="taf">
                        <strong>TAF:</strong>
                        <code class="text-dark">{{ \Illuminate\Support\Str::limit($weather['taf'], 150) }}</code>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Critical NOTAMs -->
    @if($config['show_notams'] && isset($critical_notams) && $critical_notams->count() > 0)
        <div class="critical-notams-section">
            <h6 class="section-title text-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Critical NOTAMs
            </h6>
            <div class="notam-alerts">
                @foreach($critical_notams as $notam)
                    <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
                        <strong>{{ $notam->icao_code }}</strong> - {{ $notam->title }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
.widget-dispatch-center {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #dee2e6;
}

.status-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.status-indicator-small {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.stat-card {
    transition: transform 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.section-title {
    color: #495057;
    border-bottom: 2px solid #007bff;
    padding-bottom: 5px;
    margin-bottom: 15px;
    font-weight: 600;
}

.flight-row:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

.route {
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
}

.departure-item {
    transition: all 0.3s ease;
    background: white;
}

.departure-item:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.weather-info code {
    background: #fff;
    padding: 5px;
    border-radius: 3px;
    font-size: 0.85em;
}

@keyframes pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.1); }
    100% { opacity: 1; transform: scale(1); }
}

/* Auto-refresh indicator */
.widget-dispatch-center::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #007bff, #28a745);
    animation: refresh-bar 30s linear infinite;
}

@keyframes refresh-bar {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
</style>

<script>
// Auto-refresh functionality
if (typeof dispatchCenterInterval === 'undefined') {
    var dispatchCenterInterval = setInterval(function() {
        // Refresh dispatch center data
        location.reload();
    }, {{ ($config['refresh_interval'] ?? 30) * 1000 }});
}
</script>