<style>
    /* 
     * AIR JAMAICA VIRTUAL - "CLEAN PRO" FLIGHT CARDS
     * A professional, streamlined design for clarity and efficiency.
     */
    
    :root {
        --jamaica-green: #00A651;
        --jamaica-yellow: #FED100;
        --card-bg: #1e293b;
        --border-color: rgba(255, 255, 255, 0.1);
        --text-muted: rgba(255, 255, 255, 0.5);
    }

    .flight-card {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
        transition: transform 0.2s ease, border-color 0.2s ease !important;
        margin-bottom: 1.5rem !important;
        overflow: hidden !important;
        position: relative !important;
    }

    .flight-card:hover {
        transform: translateY(-4px) !important;
        border-color: var(--jamaica-green) !important;
    }

    .flight-card .card-body {
        padding: 1.5rem !important;
        color: #ffffff !important;
    }

    /* --- Header --- */
    .flight-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .airline-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .airline-logo {
        height: 32px;
        width: auto;
    }

    .flight-number {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        color: var(--jamaica-green) !important;
        line-height: 1 !important;
    }

    .flight-type-badge {
        background: rgba(254, 209, 0, 0.1) !important;
        color: var(--jamaica-yellow) !important;
        border: 1px solid rgba(254, 209, 0, 0.2) !important;
        padding: 0.4rem 0.8rem !important;
        border-radius: 6px !important;
        font-size: 0.7rem !important;
        font-weight: 800 !important;
        text-transform: uppercase;
    }

    .booked-badge {
        background: var(--jamaica-green) !important;
        color: white !important;
        padding: 0.4rem 0.8rem !important;
        border-radius: 6px !important;
        font-size: 0.7rem !important;
        font-weight: 800 !important;
        margin-left: 0.5rem;
    }

    /* --- Route --- */
    .route-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 1.5rem 0;
    }

    .airport-node {
        flex: 1;
    }

    .airport-node.destination { text-align: right; }

    .icao-code {
        font-size: 1.75rem !important;
        font-weight: 800 !important;
        color: white !important;
        line-height: 1 !important;
    }

    .airport-name {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 0.25rem;
        text-transform: uppercase;
    }

    .flight-time {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--jamaica-yellow);
        margin-top: 0.5rem;
    }

    .route-path {
        flex: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        padding: 0 1rem;
    }

    .path-line {
        width: 100%;
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
        position: relative;
    }

    .path-line::after {
        content: '\f5b0';
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(45deg);
        color: var(--jamaica-green);
        background: var(--card-bg);
        padding: 0 8px;
        font-size: 1rem;
    }

    .route-meta {
        margin-top: 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        display: flex;
        gap: 1rem;
    }

    /* --- Details --- */
    .details-row {
        background: rgba(0, 0, 0, 0.2);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
    }

    .aircraft-label {
        color: var(--text-muted);
        font-weight: 600;
    }

    .aircraft-value {
        color: var(--jamaica-yellow);
        font-weight: 800;
        margin-left: 0.5rem;
    }

    /* --- Actions --- */
    .action-bar {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-pro {
        flex: 1;
        min-width: 120px;
        padding: 0.6rem 1rem !important;
        border-radius: 8px !important;
        font-weight: 700 !important;
        font-size: 0.8rem !important;
        text-transform: uppercase !important;
        border: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: filter 0.2s ease !important;
        text-decoration: none !important;
    }

    .btn-pro-primary {
        background: var(--jamaica-green) !important;
        color: white !important;
    }

    .btn-pro-warning {
        background: var(--jamaica-yellow) !important;
        color: #000 !important;
    }

    .btn-pro-secondary {
        background: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }

    .btn-pro-danger {
        background: #ef4444 !important;
        color: white !important;
    }

    .btn-pro:hover {
        filter: brightness(1.1) !important;
        color: inherit !important;
    }

    @media (max-width: 768px) {
        .route-container { flex-direction: column; gap: 1rem; text-align: center; }
        .airport-node { width: 100%; text-align: center !important; }
        .route-path { display: none; }
        .icao-code { font-size: 1.5rem !important; }
    }
</style>

@foreach($flights as $flight)
    <div class="flight-card" x-id="{{ $flight->id }}">
        <div class="card-body">
            {{-- --- Header --- --}}
            <div class="flight-header">
                <div class="airline-info">
                    @if (optional($flight->airline)->logo)
                        <img src="{{ $flight->airline->logo }}" alt="{{ $flight->airline->name }}" class="airline-logo" />
                    @endif
                    <div class="flight-number">
                        {{ $flight->ident }}
                    </div>
                </div>
                <div class="status-badges">
                    <span class="flight-type-badge">
                        {{ \App\Models\Enums\FlightType::label($flight->flight_type) }}
                    </span>
                    @if(isset($saved_flights) && in_array($flight->id, $saved_flights))
                        <span class="booked-badge">BOOKED</span>
                    @endif
                </div>
            </div>

            {{-- --- Route --- --}}
            <div class="route-container">
                <div class="airport-node">
                    <div class="icao-code">{{ $flight->dpt_airport_id }}</div>
                    <div class="airport-name">{{ optional($flight->dpt_airport)->name }}</div>
                    <div class="flight-time">
                        <i class="fas fa-plane-departure me-2"></i>{{ $flight->dpt_time }}
                    </div>
                </div>

                <div class="route-path">
                    <div class="path-line"></div>
                    <div class="route-meta">
                        <span><i class="fas fa-clock me-1"></i>@if($flight->flight_time) @minutestotime($flight->flight_time) @else --:-- @endif</span>
                        <span><i class="fas fa-route me-1"></i>{{ $flight->distance ?? '0' }}nm</span>
                    </div>
                </div>

                <div class="airport-node destination">
                    <div class="icao-code">{{ $flight->arr_airport_id }}</div>
                    <div class="airport-name">{{ optional($flight->arr_airport)->name }}</div>
                    <div class="flight-time">
                        <i class="fas fa-plane-arrival me-2"></i>{{ $flight->arr_time }}
                    </div>
                </div>
            </div>

            {{-- --- Aircraft Info --- --}}
            <div class="details-row">
                <div>
                    <span class="aircraft-label">AIRCRAFT:</span>
                    <span class="aircraft-value">
                        @if (count($flight->subfleets) !== 0)
                            @php $arr = []; foreach ($flight->subfleets as $sf) { $arr[] = $sf->type; } @endphp
                            {{ implode(', ', $arr) }}
                        @else
                            ANY FLEET
                        @endif
                    </span>
                </div>
                <a href="{{ route('frontend.flights.show', [$flight->id]) }}" class="text-white text-decoration-none fw-bold" style="font-size: 0.75rem;">
                    VIEW DETAILS <i class="fas fa-chevron-right ms-1"></i>
                </a>
            </div>

            {{-- --- Actions --- --}}
            <div class="action-bar">
                <a href="{{ route('frontend.flights.show', [$flight->id]) }}" class="btn-pro btn-pro-primary">
                    <i class="fas fa-eye"></i> VIEW
                </a>

                @if(isset($saved) && isset($saved[$flight->id]))
                    <a href="{{ route('frontend.simbrief.generate') }}?flight_id={{ $flight->id }}" class="btn-pro btn-pro-warning">
                        <i class="fas fa-file-alt"></i> SIMBRIEF
                    </a>
                    <a href="{{ route('frontend.pireps.create') }}?flight_id={{ $flight->id }}" class="btn-pro btn-pro-secondary">
                        <i class="fas fa-plus"></i> PIREP
                    </a>
                    <button class="btn-pro btn-pro-danger remove_bid_btn" 
                            data-bs-toggle="modal"
                            data-bs-target="#removeBidModal"
                            data-flight-id="{{ $flight->id }}"
                            style="cursor: pointer;">
                        <i class="fas fa-times"></i> REMOVE
                    </button>
                @else
                    <button class="btn-pro btn-pro-primary save_flight" 
                            x-id="{{ $flight->id }}"
                            x-saved-class="saved"
                            style="cursor: pointer;">
                        <i class="fas fa-bookmark"></i> BID
                    </button>
                @endif
            </div>
        </div>
    </div>
@endforeach

@if($flights->count() === 0)
    <div class="flight-card">
        <div class="card-body text-center py-5">
            <i class="fas fa-plane-slash" style="font-size: 3rem; color: var(--jamaica-green); opacity: 0.5; margin-bottom: 1.5rem;"></i>
            <h4 style="color: #ffffff; margin-bottom: 1rem;">No flights found</h4>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">
                Try adjusting your search criteria or clearing filters.
            </p>
            <a href="{{ route('frontend.flights.index') }}" class="btn-pro btn-pro-primary d-inline-flex" style="max-width: 200px;">
                <i class="fas fa-sync-alt"></i> RESET SEARCH
            </a>
        </div>
    </div>
@endif