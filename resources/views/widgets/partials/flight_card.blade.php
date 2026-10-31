<div class="flight-card">
    <div class="flight-header">
        <div class="flight-number">
            <i class="fas fa-plane me-2"></i>
            {{ $flight['flight_number'] }}
        </div>
        <div class="flight-duration">
            <i class="fas fa-clock me-1"></i>
            {{ $flight['flight_time_formatted'] }}
        </div>
    </div>

    <div class="flight-route">
        <div class="airport departure">
            <div class="airport-code">{{ $flight['departure']['icao'] }}</div>
            <div class="airport-name">{{ Str::limit($flight['departure']['name'], 20) }}</div>
            @if($flight['departure']['city'])
                <div class="airport-city">{{ $flight['departure']['city'] }}</div>
            @endif
        </div>

        <div class="route-line">
            <div class="route-path">
                <i class="fas fa-arrow-right"></i>
            </div>
            @if(isset($config['show_distance']) && $config['show_distance'] && $flight['distance'] > 0)
                <div class="route-distance">{{ number_format($flight['distance']) }} nm</div>
            @endif
        </div>

        <div class="airport arrival">
            <div class="airport-code">{{ $flight['arrival']['icao'] }}</div>
            <div class="airport-name">{{ Str::limit($flight['arrival']['name'], 20) }}</div>
            @if($flight['arrival']['city'])
                <div class="airport-city">{{ $flight['arrival']['city'] }}</div>
            @endif
        </div>
    </div>

    @if(isset($config['show_aircraft']) && $config['show_aircraft'] && isset($flight['aircraft']))
        <div class="flight-aircraft">
            <i class="fas fa-plane text-info me-2"></i>
            <span class="aircraft-type">{{ $flight['aircraft']['icao'] }}</span>
            <span class="aircraft-name">{{ Str::limit($flight['aircraft']['name'], 25) }}</span>
        </div>
    @endif

    @if($flight['route'])
        <div class="flight-route-details">
            <i class="fas fa-route me-2"></i>
            <span class="route-text">{{ Str::limit($flight['route'], 40) }}</span>
        </div>
    @endif

    <div class="flight-actions">
        <a href="{{ route('frontend.flights.show', $flight['id']) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-eye me-1"></i>
            View Details
        </a>
        <a href="{{ route('frontend.pireps.create') }}?flight_id={{ $flight['id'] }}" class="btn btn-success btn-sm">
            <i class="fas fa-play me-1"></i>
            Fly Now
        </a>
    </div>
</div>

<style>
.flight-card {
    background: rgba(26, 31, 46, 0.6);
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.flight-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

.flight-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
    transition: left 0.5s ease;
}

.flight-card:hover::before {
    left: 100%;
}

.flight-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.flight-number {
    font-weight: 700;
    font-size: 1.1rem;
    color: #ffffff;
    display: flex;
    align-items: center;
}

.flight-duration {
    background: rgba(0, 123, 255, 0.2);
    color: #007bff;
    padding: 0.2rem 0.6rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.flight-route {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1rem;
}

.airport {
    text-align: center;
}

.airport.departure {
    text-align: left;
}

.airport.arrival {
    text-align: right;
}

.airport-code {
    font-size: 1.2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.2rem;
}

.airport-name {
    font-size: 0.8rem;
    color: #e0e0e0;
    margin-bottom: 0.1rem;
    line-height: 1.2;
}

.airport-city {
    font-size: 0.7rem;
    color: #a0a0a0;
}

.route-line {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.route-path {
    color: #667eea;
    font-size: 1.2rem;
    margin-bottom: 0.2rem;
}

.route-distance {
    font-size: 0.7rem;
    color: #a0a0a0;
    background: rgba(0, 0, 0, 0.3);
    padding: 0.1rem 0.4rem;
    border-radius: 8px;
}

.flight-aircraft {
    display: flex;
    align-items: center;
    margin-bottom: 0.8rem;
    padding: 0.4rem 0.6rem;
    background: rgba(23, 162, 184, 0.1);
    border-radius: 6px;
    border-left: 3px solid #17a2b8;
}

.aircraft-type {
    font-weight: 600;
    color: #17a2b8;
    margin-right: 0.5rem;
}

.aircraft-name {
    color: #e0e0e0;
    font-size: 0.9rem;
}

.flight-route-details {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding: 0.4rem 0.6rem;
    background: rgba(108, 117, 125, 0.1);
    border-radius: 6px;
    border-left: 3px solid #6c757d;
}

.route-text {
    color: #e0e0e0;
    font-size: 0.8rem;
    font-family: monospace;
}

.flight-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: space-between;
}

.flight-actions .btn {
    flex: 1;
    font-size: 0.8rem;
    padding: 0.4rem 0.6rem;
    border-radius: 6px;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.flight-actions .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.flight-actions .btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.flight-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.flight-actions .btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4c93 100%);
}

.flight-actions .btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1e7e8c 100%);
}
</style>