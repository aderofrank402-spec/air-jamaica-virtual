{{-- Flight Operations Center Widget - Professional Quick Actions Hub --}}
<div class="flight-operations-center">
    {{-- Quick Actions Grid --}}
    <div class="row g-3 mb-4">
        {{-- File PIREP --}}
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('frontend.pireps.create') }}" class="btn btn-flight-action btn-pirep w-100 h-100 d-flex flex-column justify-content-center">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <span class="fw-bold">FILE PIREP</span>
                <small class="text-muted">Flight Report</small>
                @if($has_active_flight)
                    <span class="badge bg-warning text-dark mt-1">Active Flight</span>
                @endif
            </a>
        </div>

        {{-- Book Flight --}}
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('frontend.flights.index') }}" class="btn btn-flight-action btn-flight w-100 h-100 d-flex flex-column justify-content-center">
                <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                <span class="fw-bold">BOOK FLIGHT</span>
                <small class="text-muted">Schedule Trip</small>
            </a>
        </div>

        {{-- SmartCARS Download --}}
        <div class="col-md-3 col-sm-6">
            @if($smartcars_available)
                <a href="{{ route('frontend.downloads.index') }}" class="btn btn-flight-action btn-smartcars w-100 h-100 d-flex flex-column justify-content-center">
                    <i class="fas fa-download fa-2x mb-2"></i>
                    <span class="fw-bold">SMARTCARS</span>
                    <small class="text-muted">ACARS Client</small>
                    <span class="badge bg-success mt-1">v3.2.1</span>
                </a>
            @else
                <a href="{{ route('frontend.downloads.index') }}" class="btn btn-flight-action btn-downloads w-100 h-100 d-flex flex-column justify-content-center">
                    <i class="fas fa-download fa-2x mb-2"></i>
                    <span class="fw-bold">DOWNLOADS</span>
                    <small class="text-muted">Tools & Apps</small>
                </a>
            @endif
        </div>

        {{-- My Bids --}}
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('frontend.users.bids', ['user' => $user->id]) }}" class="btn btn-flight-action btn-bids w-100 h-100 d-flex flex-column justify-content-center">
                <i class="fas fa-bookmark fa-2x mb-2"></i>
                <span class="fw-bold">MY BIDS</span>
                <small class="text-muted">Booked Flights</small>
                @if($bids_count > 0)
                    <span class="badge bg-primary mt-1">{{ $bids_count }}</span>
                @endif
            </a>
        </div>
    </div>

    {{-- Secondary Actions --}}
    <div class="row g-2">
        <div class="col-md-2 col-sm-4">
            <a href="{{ route('frontend.pireps.index') }}" class="btn btn-outline-primary btn-sm w-100">
                <i class="fas fa-book"></i> Logbook
            </a>
        </div>
        <div class="col-md-2 col-sm-4">
            <a href="{{ route('frontend.profile.show', ['user' => $user->id]) }}" class="btn btn-outline-primary btn-sm w-100">
                <i class="fas fa-user"></i> Profile
            </a>
        </div>
        @if($config['show_planning_tools'])
        <div class="col-md-2 col-sm-4">
            <a href="#" class="btn btn-outline-primary btn-sm w-100" onclick="alert('Flight planner coming soon!')">
                <i class="fas fa-map"></i> Planner
            </a>
        </div>
        @endif
        <div class="col-md-2 col-sm-4">
            <a href="{{ route('frontend.downloads.index') }}" class="btn btn-outline-primary btn-sm w-100">
                <i class="fas fa-book-open"></i> Handbook
            </a>
        </div>
        <div class="col-md-2 col-sm-4">
            <a href="#" class="btn btn-outline-primary btn-sm w-100" onclick="alert('Settings coming soon!')">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
        <div class="col-md-2 col-sm-4">
            <a href="#" class="btn btn-outline-primary btn-sm w-100" onclick="alert('Support coming soon!')">
                <i class="fas fa-headset"></i> Support
            </a>
        </div>
    </div>
</div>

<style>
.flight-operations-center .btn-flight-action {
    min-height: 100px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.flight-operations-center .btn-flight-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.flight-operations-center .btn-pirep {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border-color: #28a745;
}

.flight-operations-center .btn-pirep:hover {
    background: linear-gradient(135deg, #218838, #1aa085);
    color: white;
}

.flight-operations-center .btn-flight {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border-color: #007bff;
}

.flight-operations-center .btn-flight:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    color: white;
}

.flight-operations-center .btn-smartcars {
    background: linear-gradient(135deg, #fd7e14, #e55a00);
    color: white;
    border-color: #fd7e14;
}

.flight-operations-center .btn-smartcars:hover {
    background: linear-gradient(135deg, #e55a00, #cc5500);
    color: white;
}

.flight-operations-center .btn-downloads {
    background: linear-gradient(135deg, #6f42c1, #5a2d91);
    color: white;
    border-color: #6f42c1;
}

.flight-operations-center .btn-downloads:hover {
    background: linear-gradient(135deg, #5a2d91, #4e2474);
    color: white;
}

.flight-operations-center .btn-bids {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    border-color: #dc3545;
}

.flight-operations-center .btn-bids:hover {
    background: linear-gradient(135deg, #c82333, #bd2130);
    color: white;
}

.flight-operations-center .badge {
    font-size: 0.7rem;
    position: absolute;
    top: 10px;
    right: 10px;
}
</style>