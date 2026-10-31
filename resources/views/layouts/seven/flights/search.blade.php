<div class="row">
  <div class="col-12">
    <div class="form-group search-form">
      <form method="get" action="{{ route('frontend.flights.search') }}" id="flightSearchForm">
        @csrf
        
        {{-- Quick Search --}}
        <div class="mb-4">
          <label for="quick_search" class="form-label">
            <i class="fas fa-search me-1"></i>Quick Search
          </label>
          <input type="text" name="quick_search" id="quick_search" class="form-control" 
                 placeholder="Flight number, route, or airport code..." 
                 value="{{ request()->get('quick_search') }}" />
          <small class="text-muted">Search across flight numbers, routes, and airport codes</small>
        </div>

        {{-- Advanced Filters Accordion --}}
        <div class="accordion" id="advancedFilters">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button {{ request()->hasAny(['airline_id', 'flight_type', 'dep_icao', 'arr_icao']) ? '' : 'collapsed' }}" 
                      type="button" data-bs-toggle="collapse" data-bs-target="#basicFilters">
                <i class="fas fa-filter me-2"></i>Basic Filters
              </button>
            </h2>
            <div id="basicFilters" class="accordion-collapse collapse {{ request()->hasAny(['airline_id', 'flight_type', 'dep_icao', 'arr_icao']) ? 'show' : '' }}">
              <div class="accordion-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="airline_id" class="form-label">@lang('common.airline')</label>
                    <select name="airline_id" id="airline_id" class="form-select">
                      <option value="">All Airlines</option>
                      @foreach($airlines as $airline_id => $airline_label)
                        <option value="{{ $airline_id }}" @if(request()->get('airline_id') == $airline_id) selected @endif>{{ $airline_label }}</option>
                      @endforeach
                    </select>
                  </div>
                  
                  <div class="col-md-6 mb-3">
                    <label for="flight_type" class="form-label">@lang('flights.flighttype')</label>
                    <select name="flight_type" id="flight_type" class="form-select">
                      <option value="">All Types</option>
                      @foreach($flight_types as $flight_type_id => $flight_type_label)
                        <option value="{{ $flight_type_id }}" @if(request()->get('flight_type') == $flight_type_id) selected @endif>{{ $flight_type_label }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="flight_number" class="form-label">@lang('flights.flightnumber')</label>
                    <input type="text" name="flight_number" id="flight_number" class="form-control" 
                           placeholder="e.g., JA001" value="{{ request()->get('flight_number') }}" />
                  </div>
                  
                  <div class="col-md-6 mb-3">
                    <label for="route_code" class="form-label">@lang('flights.code')</label>
                    <input type="text" name="route_code" id="route_code" class="form-control" 
                           placeholder="e.g., KJFK-EGLL" value="{{ request()->get('route_code') }}" />
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="dep_icao" class="form-label">@lang('airports.departure')</label>
                    <select name="dep_icao" id="dep_icao" class="form-select select2">
                      <option value="">Any Departure</option>
                      @foreach($airports as $airport_id => $airport_label)
                        <option value="{{ $airport_id }}" @if(request()->get('dep_icao') == $airport_id) selected @endif>{{ $airport_label }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="arr_icao" class="form-label">@lang('airports.arrival')</label>
                    <select name="arr_icao" id="arr_icao" class="form-select select2">
                      <option value="">Any Arrival</option>
                      @foreach($airports as $airport_id => $airport_label)
                        <option value="{{ $airport_id }}" @if(request()->get('arr_icao') == $airport_id) selected @endif>{{ $airport_label }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#aircraftFilters">
                <i class="fas fa-plane me-2"></i>Aircraft & Equipment
              </button>
            </h2>
            <div id="aircraftFilters" class="accordion-collapse collapse">
              <div class="accordion-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="subfleet_id" class="form-label">@lang('common.subfleet')</label>
                    <select name="subfleet_id" id="subfleet_id" class="form-select select2">
                      <option value="">Any Subfleet</option>
                      @foreach($subfleets as $subfleet_id => $subfleet_label)
                        <option value="{{ $subfleet_id }}" @if(request()->get('subfleet_id') == $subfleet_id) selected @endif>{{ $subfleet_label }}</option>
                      @endforeach
                    </select>
                  </div>

                  @if(filled($icao_codes))
                    <div class="col-md-6 mb-3">
                      <label for="icao_type" class="form-label">ICAO Aircraft Type</label>
                      <select name="icao_type" id="icao_type" class="form-select select2">
                        <option value="">Any Aircraft Type</option>
                        @foreach($icao_codes as $icao)
                          <option value="{{ $icao }}" @if(request()->get('icao_type') == $icao) selected @endif>{{ $icao }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif
                </div>

                @if(filled($type_ratings))
                  <div class="mb-3">
                    <label for="type_rating_id" class="form-label">Type Rating Required</label>
                    <select name="type_rating_id" id="type_rating_id" class="form-select select2">
                      <option value="">Any Type Rating</option>
                      @foreach($type_ratings as $tr)
                        <option value="{{ $tr->id }}" @if(request()->get('type_rating_id') == $tr->id) selected @endif>{{ $tr->type.' | '.$tr->name }}</option>
                      @endforeach
                    </select>
                  </div>
                @endif
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#timeFilters">
                <i class="fas fa-clock me-2"></i>Time & Distance
              </button>
            </h2>
            <div id="timeFilters" class="accordion-collapse collapse">
              <div class="accordion-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="min_flight_time" class="form-label">Min Flight Time (minutes)</label>
                    <input type="number" name="min_flight_time" id="min_flight_time" class="form-control" 
                           placeholder="e.g., 60" value="{{ request()->get('min_flight_time') }}" />
                  </div>
                  
                  <div class="col-md-6 mb-3">
                    <label for="max_flight_time" class="form-label">Max Flight Time (minutes)</label>
                    <input type="number" name="max_flight_time" id="max_flight_time" class="form-control" 
                           placeholder="e.g., 480" value="{{ request()->get('max_flight_time') }}" />
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="min_distance" class="form-label">Min Distance (nm)</label>
                    <input type="number" name="min_distance" id="min_distance" class="form-control" 
                           placeholder="e.g., 100" value="{{ request()->get('min_distance') }}" />
                  </div>
                  
                  <div class="col-md-6 mb-3">
                    <label for="max_distance" class="form-label">Max Distance (nm)</label>
                    <input type="number" name="max_distance" id="max_distance" class="form-control" 
                           placeholder="e.g., 3000" value="{{ request()->get('max_distance') }}" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-between align-items-center mt-4">
          <div>
            <button type="submit" class="btn btn-primary me-2">
              <i class="fas fa-search me-1"></i>@lang('common.find')
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="saveSearch()">
              <i class="fas fa-bookmark me-1"></i>Save Search
            </button>
          </div>
          <div>
            <a href="{{ route('frontend.flights.index') }}" class="btn btn-secondary">
              <i class="fas fa-times me-1"></i>@lang('common.reset')
            </a>
          </div>
        </div>

        {{-- Hidden fields for quick filters --}}
        <input type="hidden" name="per_page" value="{{ request()->get('per_page', 25) }}">
        <input type="hidden" name="today" value="{{ request()->get('today') }}">
        <input type="hidden" name="available_aircraft" value="{{ request()->get('available_aircraft') }}">
      </form>
    </div>
  </div>
</div>

<script>
// Enhanced search functionality
function saveSearch() {
    const formData = new FormData(document.getElementById('flightSearchForm'));
    const searchParams = new URLSearchParams(formData);
    const searchName = prompt('Enter a name for this search:');
    
    if (searchName) {
        const savedSearches = JSON.parse(localStorage.getItem('savedFlightSearches') || '[]');
        savedSearches.push({
            name: searchName,
            params: searchParams.toString(),
            date: new Date().toISOString()
        });
        localStorage.setItem('savedFlightSearches', JSON.stringify(savedSearches));
        alert('Search saved successfully!');
    }
}

// Auto-complete for airport codes
document.addEventListener('DOMContentLoaded', function() {
    const quickSearch = document.getElementById('quick_search');
    if (quickSearch) {
        quickSearch.addEventListener('input', function(e) {
            // Add debounced search suggestions here
            console.log('Quick search:', e.target.value);
        });
    }
});
</script>
