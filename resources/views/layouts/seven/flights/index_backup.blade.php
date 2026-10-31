@extends('app')
@section('title', trans_choice('common.flight', 2))

@section('content')
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

<style>
/* Enhanced Flights Page Styling */
.flights-container {
    background: linear-gradient(135deg, #1a202c 0%, #2d3748 50%, #1a202c 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.flights-header {
    background: linear-gradient(135deg, #00a651 0%, #1976d2 50%, #ffc107 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0,166,81,0.3);
}

.flights-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M10 10 L90 10 L90 90 L10 90 Z" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/></svg>');
    animation: pulse 3s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 0.1; }
    50% { opacity: 0.3; }
}

.flights-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 2;
}

.flights-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    position: relative;
    z-index: 2;
}

/* Flight Controls Bar */
.flight-controls {
    background: linear-gradient(145deg, #2c3e50, #34495e);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    border: 2px solid #4a5568;
    color: white;
}

.flight-stats {
    background: linear-gradient(45deg, #1a202c, #2d3748);
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid #4a5568;
}

.view-toggle {
    background: linear-gradient(145deg, #1a202c, #2d3748);
    border-radius: 10px;
    padding: 0.5rem;
    border: 1px solid #4a5568;
}

.view-toggle .btn {
    background: transparent;
    border: none;
    color: #a0aec0;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.view-toggle .btn.active {
    background: linear-gradient(45deg, #00a651, #1976d2);
    color: white;
    transform: translateY(-1px);
}

.view-toggle .btn:hover:not(.active) {
    background: rgba(0, 166, 81, 0.2);
    color: white;
}

/* Quick Filters */
.quick-filters {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.quick-filter-btn {
    background: linear-gradient(145deg, #1a202c, #2d3748);
    border: 1px solid #4a5568;
    color: #a0aec0;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.quick-filter-btn:hover,
.quick-filter-btn.active {
    background: linear-gradient(45deg, #00a651, #1976d2);
    color: white;
    border-color: #00a651;
    transform: translateY(-1px);
}

/* Per Page Selector */
.per-page-selector {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.per-page-selector select {
    background: linear-gradient(145deg, #1a202c, #2d3748);
    border: 1px solid #4a5568;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 5px;
    font-size: 0.875rem;
}

/* Search Card */
.search-card {
    background: linear-gradient(145deg, #2c3e50, #34495e);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    border: 2px solid #4a5568;
    overflow: hidden;
    color: white;
    position: sticky;
    top: 2rem;
}

.search-card .card-header {
    background: linear-gradient(45deg, #00a651, #1976d2);
    color: white;
    font-weight: 600;
    padding: 1rem 1.5rem;
    border: none;
}

.search-card .card-body {
    color: white;
    max-height: 70vh;
    overflow-y: auto;
}

/* Content Wrapper */
.content-wrapper {
    background: linear-gradient(145deg, #2c3e50, #34495e);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    border: 2px solid #4a5568;
    color: white;
}

/* Table View Styles */
.table-view {
    display: none;
}

.table-view.active {
    display: block;
}

.flights-table {
    background: transparent;
    color: white;
}

.flights-table th {
    background: linear-gradient(45deg, #1a202c, #2d3748);
    color: white;
    border: 1px solid #4a5568;
    padding: 1rem 0.75rem;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.flights-table td {
    background: linear-gradient(145deg, #2c3e50, #34495e);
    color: white;
    border: 1px solid #4a5568;
    padding: 0.75rem;
    vertical-align: middle;
}

.flights-table tbody tr:hover {
    background: linear-gradient(145deg, #34495e, #2c3e50);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(0,166,81,0.2);
}

/* Card View Styles */
.card-view {
    display: block;
}

.card-view.active {
    display: block;
}

/* Loading States */
.loading-skeleton {
    background: linear-gradient(90deg, #2c3e50 25%, #34495e 50%, #2c3e50 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 8px;
    height: 1rem;
    margin-bottom: 0.5rem;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.flight-loading {
    padding: 2rem;
    text-align: center;
    color: #a0aec0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .flights-container {
        padding: 1rem 0;
    }
    
    .flights-header {
        padding: 1.5rem 1rem;
        margin-bottom: 1.5rem;
    }
    
    .flights-title {
        font-size: 2rem;
    }
    
    .content-wrapper {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .flight-controls {
        padding: 1rem;
    }
    
    .quick-filters {
        justify-content: center;
    }
    
    .search-card {
        position: static;
    }
}

/* Dark theme form styling */
.search-card .form-control,
.search-card .form-select {
    background-color: #1a202c;
    border: 2px solid #4a5568;
    color: white;
    border-radius: 8px;
}

.search-card .form-control:focus,
.search-card .form-select:focus {
    background-color: #2d3748;
    border-color: #00a651;
    box-shadow: 0 0 0 0.2rem rgba(0, 166, 81, 0.25);
    color: white;
}

.search-card .form-label {
    color: white;
    font-weight: 600;
}

.search-card .btn {
    background: linear-gradient(45deg, #00a651, #1976d2);
    border: none;
    color: white;
    font-weight: 600;
}

.search-card .btn:hover {
    background: linear-gradient(45deg, #1976d2, #00a651);
    transform: translateY(-2px);
}

.content-wrapper .pagination .page-link {
    background-color: #2c3e50;
    border-color: #4a5568;
    color: white;
}

.content-wrapper .pagination .page-link:hover {
    background-color: #00a651;
    border-color: #00a651;
    color: white;
}

.content-wrapper .pagination .page-item.active .page-link {
    background-color: #00a651;
    border-color: #00a651;
}

/* Dark theme accordion styling */
.content-wrapper .accordion-item {
    background-color: #1a202c;
    border: 1px solid #4a5568;
}

.content-wrapper .accordion-button {
    background-color: #2d3748;
    color: white;
    border: none;
}

.content-wrapper .accordion-button:not(.collapsed) {
    background-color: #00a651;
    color: white;
}

.content-wrapper .accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(0, 166, 81, 0.25);
}

.content-wrapper .accordion-body {
    background-color: #1a202c;
    color: white;
}

/* Ensure consistent theming */
.flights-container * {
    color: inherit;
}

.flights-container .text-dark {
    color: white !important;
}

.flights-container .bg-white {
    background-color: #2c3e50 !important;
}

.flights-container .table {
    color: white;
}

.flights-container .table th,
.flights-container .table td {
    color: white;
    border-color: #4a5568;
}

.flights-container .card {
    background-color: #2c3e50 !important;
    color: white !important;
    border-color: #4a5568 !important;
}

.flights-container .form-control::placeholder {
    color: #a0aec0;
}

.flights-container .form-select option {
    background-color: #2c3e50;
    color: white;
}

/* TomSelect dark theme styling */
.search-card .ts-control {
    background-color: #1a202c !important;
    border: 2px solid #4a5568 !important;
    border-radius: 8px !important;
    color: white !important;
    min-height: 42px !important;
}

.search-card .ts-control.single .ts-control-input {
    color: white !important;
}

.search-card .ts-control .ts-control-input::placeholder {
    color: #a0aec0 !important;
}

.search-card .ts-dropdown {
    background-color: #1a202c !important;
    border: 2px solid #4a5568 !important;
    color: white !important;
    border-radius: 8px !important;
}

.search-card .ts-dropdown .option {
    background-color: #1a202c !important;
    color: white !important;
    padding: 8px 12px !important;
}

.search-card .ts-dropdown .option:hover {
    background-color: #00a651 !important;
    color: white !important;
}

.search-card .ts-dropdown .option.active {
    background-color: #00a651 !important;
    color: white !important;
}

.search-card .ts-dropdown .no-results {
    color: #a0aec0 !important;
    padding: 8px 12px !important;
    font-style: italic;
}

.search-card select.airport_search {
    display: none !important;
}
</style>

<div class="flights-container">
  <div class="container">
    {{-- Header Section --}}
    <div class="flights-header">
        <h1 class="flights-title">
            <i class="fas fa-plane me-3"></i>{{ trans_choice('common.flight', 2) }}
        </h1>
        <p class="flights-subtitle">Explore available flights and plan your next journey</p>
    </div>

    @include('flash::message')
    
    {{-- Flight Controls Bar --}}
    <div class="flight-controls">
        {{-- Flight Statistics --}}
        <div class="flight-stats">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="text-center">
                            <div class="h4 mb-0 text-primary">{{ $flights->total() }}</div>
                            <small class="text-muted">Total Flights</small>
                        </div>
                        <div class="text-center">
                            <div class="h4 mb-0 text-success">{{ $flights->currentPage() }}</div>
                            <small class="text-muted">Current Page</small>
                        </div>
                        <div class="text-center">
                            <div class="h4 mb-0 text-info">{{ $flights->lastPage() }}</div>
                            <small class="text-muted">Total Pages</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-md-end align-items-center gap-3">
                        <div class="per-page-selector">
                            <label class="text-muted small">Show:</label>
                            <select id="perPageSelect" onchange="changePerPage(this.value)">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 || !request('per_page') ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="view-toggle btn-group" role="group">
                            <button type="button" class="btn active" id="cardViewBtn" onclick="switchView('card')">
                                <i class="fas fa-th-large me-1"></i>Cards
                            </button>
                            <button type="button" class="btn" id="tableViewBtn" onclick="switchView('table')">
                                <i class="fas fa-table me-1"></i>Table
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Filters --}}
        <div class="quick-filters">
            <button class="quick-filter-btn" onclick="applyQuickFilter('today')" data-filter="today">
                <i class="fas fa-calendar-day me-1"></i>Today's Flights
            </button>
            <button class="quick-filter-btn" onclick="applyQuickFilter('my_airline')" data-filter="my_airline">
                <i class="fas fa-building me-1"></i>My Airline
            </button>
            <button class="quick-filter-btn" onclick="applyQuickFilter('available')" data-filter="available">
                <i class="fas fa-plane-departure me-1"></i>Available Aircraft
            </button>
            <button class="quick-filter-btn" onclick="applyQuickFilter('short_haul')" data-filter="short_haul">
                <i class="fas fa-clock me-1"></i>Short Haul (&lt;3h)
            </button>
            <button class="quick-filter-btn" onclick="applyQuickFilter('long_haul')" data-filter="long_haul">
                <i class="fas fa-globe me-1"></i>Long Haul (&gt;6h)
            </button>
            @if(request()->hasAny(['airline_id', 'flight_type', 'dep_icao', 'arr_icao', 'subfleet_id']))
                <button class="quick-filter-btn" onclick="clearAllFilters()" style="background: linear-gradient(45deg, #dc3545, #c82333);">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </button>
            @endif
        </div>
    </div>
    
    <div class="row">
      <div class="col-xl-9 col-md-12">
        <div class="content-wrapper">
          {{-- Mobile Search Accordion --}}
          <div class="d-xl-none mb-3">
            <div class="accordion" id="searchAccordion">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    <i class="fas fa-search me-2"></i>@lang('flights.search') & Filters
                  </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#searchAccordion">
                  <div class="accordion-body">
                    @include('flights.search')
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Loading State --}}
          <div id="loadingState" class="flight-loading" style="display: none;">
              <div class="loading-skeleton"></div>
              <div class="loading-skeleton" style="width: 80%;"></div>
              <div class="loading-skeleton" style="width: 60%;"></div>
              <p class="mt-3"><i class="fas fa-spinner fa-spin me-2"></i>Loading flights...</p>
          </div>

          {{-- Card View --}}
          <div id="cardView" class="card-view active">
              @include('flights.table')
          </div>

          {{-- Table View --}}
          <div id="tableView" class="table-view">
              <div class="table-responsive">
                  <table class="table flights-table">
                      <thead>
                          <tr>
                              <th>@sortablelink('airline_id', __('common.airline'))</th>
                              <th>@sortablelink('flight_number', __('flights.flightnumber'))</th>
                              <th>@sortablelink('dpt_airport_id', __('airports.departure'))</th>
                              <th>@sortablelink('arr_airport_id', __('airports.arrival'))</th>
                              <th>@sortablelink('dpt_time', 'STD')</th>
                              <th>@sortablelink('arr_time', 'STA')</th>
                              <th>@sortablelink('distance', 'Distance')</th>
                              <th>@sortablelink('flight_time', 'Flight Time')</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($flights as $flight)
                              <tr>
                                  <td>
                                      <div class="d-flex align-items-center">
                                          @if (optional($flight->airline)->logo)
                                              <img src="{{ $flight->airline->logo }}" alt="{{ $flight->airline->name }}"
                                                  style="max-width: 30px; height: auto; margin-right: 0.5rem;" />
                                          @endif
                                          <div>
                                              <div class="fw-bold">{{ $flight->airline->name }}</div>
                                              <small class="text-muted">{{ $flight->airline->icao }}</small>
                                          </div>
                                      </div>
                                  </td>
                                  <td>
                                      <div class="fw-bold text-primary">{{ $flight->ident }}</div>
                                      <small class="text-muted">{{ $flight->flight_number }}</small>
                                  </td>
                                  <td>
                                      <div class="fw-bold">{{ $flight->dpt_airport_id }}</div>
                                      <small class="text-muted d-block">{{ $flight->dpt_airport->name ?? '' }}</small>
                                  </td>
                                  <td>
                                      <div class="fw-bold">{{ $flight->arr_airport_id }}</div>
                                      <small class="text-muted d-block">{{ $flight->arr_airport->name ?? '' }}</small>
                                  </td>
                                  <td>{{ $flight->dpt_time }}</td>
                                  <td>{{ $flight->arr_time }}</td>
                                  <td>{{ $flight->distance ? $flight->distance . 'nm' : '-' }}</td>
                                  <td>{{ $flight->flight_time ? \App\Support\Units\Time::minutesToTimeString($flight->flight_time) : '-' }}</td>
                                  <td>
                                      <div class="btn-group-vertical btn-group-sm">
                                          <a href="{{ route('frontend.flights.show', [$flight->id]) }}" class="btn btn-primary btn-sm">
                                              <i class="fas fa-eye"></i>
                                          </a>
                                          @if (!setting('pilots.only_flights_from_current') || $flight->dpt_airport_id == $user->current_airport->icao)
                                              <button class="btn btn-sm save_flight {{ isset($saved[$flight->id]) ? 'btn-danger' : 'btn-success' }}"
                                                      x-id="{{ $flight->id }}" x-saved-class="btn-danger" type="button">
                                                  <i class="fas {{ isset($saved[$flight->id]) ? 'fa-minus' : 'fa-plus' }}"></i>
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
      </div>
      
      {{-- Desktop Search Sidebar --}}
      <div class="col-xl-3 d-none d-xl-block">
        <div class="card search-card">
          <div class="card-header">
            <i class="fas fa-search me-2"></i>@lang('flights.search') & Filters
          </div>
          <div class="card-body">
            @include('flights.search')
          </div>
        </div>
      </div>
    </div>
    
    {{-- Pagination --}}
    <div class="row">
      <div class="col-xl-9 col-lg-12">
        <div class="content-wrapper text-center">
          <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="text-muted">
                  Showing {{ $flights->firstItem() ?? 0 }} to {{ $flights->lastItem() ?? 0 }} of {{ $flights->total() }} flights
              </div>
              <div>
                  @if($flights->hasPages())
                      {{ $flights->withQueryString()->links('pagination.bootstrap-5') }}
                  @endif
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@if (setting('bids.block_aircraft', false))
  @include('flights.bids_aircraft')
@endif

<script>
// Enhanced Flights Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize view from localStorage or default to card
    const savedView = localStorage.getItem('flightsView') || 'card';
    switchView(savedView);
    
    // Initialize quick filters from URL parameters
    initializeQuickFilters();
});

// View Switching
function switchView(viewType) {
    const cardView = document.getElementById('cardView');
    const tableView = document.getElementById('tableView');
    const cardBtn = document.getElementById('cardViewBtn');
    const tableBtn = document.getElementById('tableViewBtn');
    
    if (viewType === 'table') {
        cardView.style.display = 'none';
        tableView.style.display = 'block';
        cardBtn.classList.remove('active');
        tableBtn.classList.add('active');
    } else {
        cardView.style.display = 'block';
        tableView.style.display = 'none';
        cardBtn.classList.add('active');
        tableBtn.classList.remove('active');
    }
    
    // Save preference
    localStorage.setItem('flightsView', viewType);
}

// Per Page Selection
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to first page
    showLoading();
    window.location.href = url.toString();
}

// Quick Filters
function applyQuickFilter(filterType) {
    const url = new URL(window.location);
    
    // Clear existing filters first
    url.searchParams.delete('page');
    
    switch(filterType) {
        case 'today':
            // This would need backend support to filter by today's flights
            url.searchParams.set('today', '1');
            break;
        case 'my_airline':
            @if(Auth::check())
                url.searchParams.set('airline_id', '{{ Auth::user()->airline_id }}');
            @endif
            break;
        case 'available':
            url.searchParams.set('available_aircraft', '1');
            break;
        case 'short_haul':
            url.searchParams.set('max_flight_time', '180'); // 3 hours
            break;
        case 'long_haul':
            url.searchParams.set('min_flight_time', '360'); // 6 hours
            break;
    }
    
    showLoading();
    window.location.href = url.toString();
}

function clearAllFilters() {
    const url = new URL(window.location.origin + window.location.pathname);
    const perPage = new URLSearchParams(window.location.search).get('per_page');
    if (perPage) {
        url.searchParams.set('per_page', perPage);
    }
    showLoading();
    window.location.href = url.toString();
}

function initializeQuickFilters() {
    const params = new URLSearchParams(window.location.search);
    
    // Highlight active filters
    document.querySelectorAll('.quick-filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (params.get('today')) {
        document.querySelector('[data-filter="today"]')?.classList.add('active');
    }
    if (params.get('airline_id') === '{{ Auth::user()->airline_id ?? "" }}') {
        document.querySelector('[data-filter="my_airline"]')?.classList.add('active');
    }
    if (params.get('available_aircraft')) {
        document.querySelector('[data-filter="available"]')?.classList.add('active');
    }
    if (params.get('max_flight_time') === '180') {
        document.querySelector('[data-filter="short_haul"]')?.classList.add('active');
    }
    if (params.get('min_flight_time') === '360') {
        document.querySelector('[data-filter="long_haul"]')?.classList.add('active');
    }
}

function showLoading() {
    const loadingState = document.getElementById('loadingState');
    const cardView = document.getElementById('cardView');
    const tableView = document.getElementById('tableView');
    
    if (loadingState) {
        loadingState.style.display = 'block';
        cardView.style.display = 'none';
        tableView.style.display = 'none';
    }
}

// Enhanced search with debouncing
let searchTimeout;
function debounceSearch(input, delay = 500) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        // Auto-submit form after delay
        input.closest('form').submit();
    }, delay);
}

// Add debouncing to search inputs
document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('#flight_number, #route_code');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            debounceSearch(this);
        });
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('#flight_number');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    // V to toggle view
    if (e.key === 'v' && !e.ctrlKey && !e.metaKey && !e.altKey) {
        const activeView = localStorage.getItem('flightsView') || 'card';
        switchView(activeView === 'card' ? 'table' : 'card');
    }
});

// Smooth scrolling for pagination
document.addEventListener('click', function(e) {
    if (e.target.closest('.pagination a')) {
        showLoading();
    }
});

// Auto-refresh functionality (optional)
let autoRefreshInterval;
function toggleAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
        autoRefreshInterval = null;
    } else {
        autoRefreshInterval = setInterval(() => {
            window.location.reload();
        }, 300000); // 5 minutes
    }
}
</script>

@endsection

@include('flights.scripts')

