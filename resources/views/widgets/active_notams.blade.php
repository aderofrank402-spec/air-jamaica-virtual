<div class="widget widget-notams">
    <div class="widget-header d-flex justify-content-between align-items-center mb-3">
        <h5 class="widget-title mb-0">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
            Active NOTAMs
        </h5>
        <div class="d-flex gap-2">
            @if($stats['critical'] > 0)
                <span class="badge bg-danger">{{ $stats['critical'] }} Critical</span>
            @endif
            @if($stats['expiring_soon'] > 0)
                <span class="badge bg-warning">{{ $stats['expiring_soon'] }} Expiring</span>
            @endif
            <span class="badge bg-info">{{ $stats['total_active'] }} Total</span>
        </div>
    </div>

    @if($notams->count() > 0)
        <div class="notam-list">
            @foreach($notams as $notam)
                <div class="notam-item border-start border-4 border-{{ $notam->priority_color }} p-3 mb-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="notam-header">
                            <h6 class="notam-title mb-1">
                                @if($config['show_priority_badges'])
                                    <span class="badge bg-{{ $notam->priority_color }} me-2">{{ $notam->priority }}</span>
                                @endif
                                {{ $notam->title }}
                            </h6>
                            <div class="notam-meta text-muted small">
                                <span class="notam-id fw-bold">{{ $notam->formatted_identifier }}</span>
                                @if($config['show_airports'])
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-plane me-1"></i>{{ $notam->icao_code }}
                                @endif
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock me-1"></i>{{ $notam->effective_from->format('M j, H:i') }}
                                @if($notam->effective_until)
                                    - {{ $notam->effective_until->format('M j, H:i') }}
                                @endif
                            </div>
                        </div>
                        @if($notam->priority === 'CRITICAL')
                            <i class="fas fa-exclamation-circle text-danger fs-5 pulsing"></i>
                        @endif
                    </div>
                    
                    <div class="notam-description">
                        <p class="mb-0 text-dark">{{ \Illuminate\Support\Str::limit($notam->description, 200) }}</p>
                    </div>

                    @if($notam->effective_until && $notam->effective_until->diffInHours() < 24)
                        <div class="notam-expiry mt-2">
                            <small class="text-warning">
                                <i class="fas fa-hourglass-half me-1"></i>
                                Expires in {{ $notam->effective_until->diffForHumans() }}
                            </small>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($stats['total_active'] > $config['count'])
            <div class="text-center mt-3">
                <a href="#" class="btn btn-outline-primary btn-sm">
                    View All {{ $stats['total_active'] }} NOTAMs
                    <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        @endif
    @else
        <div class="no-notams text-center py-4">
            <i class="fas fa-check-circle text-success fs-1 mb-3"></i>
            <h6 class="text-muted">No active NOTAMs</h6>
            <p class="text-muted small mb-0">All clear for operations</p>
        </div>
    @endif
</div>

<style>
.widget-notams .pulsing {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.notam-item {
    transition: all 0.3s ease;
    position: relative;
}

.notam-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.notam-item.priority-critical::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #dc3545, transparent);
    animation: critical-blink 3s infinite;
}

@keyframes critical-blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}
</style>