@php
  $custom_fields_exist = (isset($pirep) && $pirep->fields && $pirep->fields->isNotEmpty()) || (!isset($pirep) && $pirep_fields && $pirep_fields->isNotEmpty());
@endphp

@if(!empty($pirep) && $pirep->read_only)
  <div class="alert alert-info border-0 bg-primary bg-opacity-10 text-primary mb-5">
    <i class="fas fa-lock me-2"></i> This report is in read-only mode.
  </div>
@endif

<div class="row">
  <div class="col-12">
    {{-- Section 1: Flight Information --}}
    <div class="section-unified">
      <div class="section-header-sleek">
        <div class="section-icon-sleek"><i class="fas fa-info-circle"></i></div>
        <h3 class="section-title-sleek">Flight Information</h3>
      </div>
      
      <div class="row g-4">
        <div class="col-md-4">
          <label class="form-label">Airline</label>
          @if(!empty($pirep) && $pirep->read_only)
            <div class="form-control border-0 px-0 bg-transparent">{{ $pirep->airline->name }}</div>
          @else
            <select name="airline_id" class="form-select select2">
              @foreach($airline_list as $airline_id => $airline_label)
                <option value="{{ $airline_id }}" @if(!empty($pirep) && $airline_id === $pirep->airline_id) selected @endif>{{ $airline_label }}</option>
              @endforeach
            </select>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label">Flight Number / Code / Leg</label>
          @if(!empty($pirep) && $pirep->read_only)
            <div class="form-control border-0 px-0 bg-transparent">{{ $pirep->ident }}</div>
          @else
            <div class="input-group">
              <input type="text" name="flight_number" class="form-control" value="{{ !empty($pirep) ? $pirep->flight_number : old('flight_number') }}" placeholder="Number">
              <input type="text" name="route_code" class="form-control" value="{{ !empty($pirep) ? $pirep->route_code : old('route_code') }}" placeholder="Code">
              <input type="text" name="route_leg" class="form-control" value="{{ !empty($pirep) ? $pirep->route_leg : old('route_leg') }}" placeholder="Leg">
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label">Flight Type</label>
          @if(!empty($pirep) && $pirep->read_only)
            <div class="form-control border-0 px-0 bg-transparent">{{ \App\Models\Enums\FlightType::label($pirep->flight_type) }}</div>
          @else
            <select name="flight_type" class="form-select select2">
              @foreach(\App\Models\Enums\FlightType::select() as $type_id => $type_label)
                <option value="{{ $type_id }}" @if(!empty($pirep) && $pirep->flight_type == $type_id) selected @endif>{{ $type_label }}</option>
              @endforeach
            </select>
          @endif
        </div>
      </div>
    </div>

    {{-- Section 2: Departure & Arrival --}}
    <div class="section-unified">
      <div class="section-header-sleek">
        <div class="section-icon-sleek"><i class="fas fa-map-marker-alt"></i></div>
        <h3 class="section-title-sleek">Departure & Arrival Information</h3>
      </div>

      <div class="row g-4">
        <div class="col-md-6">
          <label class="form-label">Departure Airport</label>
          @if(!empty($pirep) && ($pirep->read_only || request()->has('flight_id')))
            <div class="form-control border-0 px-0 bg-transparent">{{ $pirep->dpt_airport->name }} ({{$pirep->dpt_airport->icao}})</div>
          @else
            <select name="dpt_airport_id" class="form-select airport_search">
              @foreach($airport_list as $id => $label)
                <option value="{{ $id }}" @if(!empty($pirep) && $pirep->dpt_airport_id == $id) selected @endif>{{ $label }}</option>
              @endforeach
            </select>
          @endif
        </div>

        <div class="col-md-6">
          <label class="form-label">Arrival Airport</label>
          @if(!empty($pirep) && ($pirep->read_only || request()->has('flight_id')))
            <div class="form-control border-0 px-0 bg-transparent">{{ $pirep->arr_airport->name }} ({{$pirep->arr_airport->icao}})</div>
          @else
            <select name="arr_airport_id" class="form-select airport_search">
              @foreach($airport_list as $id => $label)
                <option value="{{ $id }}" @if(!empty($pirep) && $pirep->arr_airport_id == $id) selected @endif>{{ $label }}</option>
              @endforeach
            </select>
          @endif
        </div>
      </div>
    </div>

    {{-- Section 3: Aircraft & Performance --}}
    <div class="section-unified">
      <div class="section-header-sleek">
        <div class="section-icon-sleek"><i class="fas fa-plane"></i></div>
        <h3 class="section-title-sleek">Aircraft & Performance</h3>
      </div>

      <div class="row g-4">
        <div class="col-md-4">
          <label class="form-label">Aircraft</label>
          @if(!empty($pirep) && $pirep->read_only)
            <div class="form-control border-0 px-0 bg-transparent">{{ $pirep->aircraft->name }}</div>
          @else
            <select name="aircraft_id" id="aircraft_select" class="form-select select2">
              @foreach($aircraft_list as $sf => $sf_a)
                @if(is_iterable($sf_a))
                  @foreach($sf_a as $id => $label)
                    <option value="{{ $id }}" @if(!empty($pirep) && $pirep->aircraft_id == $id) selected @endif>{{ $label }}</option>
                  @endforeach
                @else
                  <option value="">Select Aircraft</option>
                @endif
              @endforeach
            </select>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label">Flight Time (HH:MM)</label>
          <div class="input-group">
            <span class="input-group-text bg-deep border-secondary text-muted">HRS</span>
            <input type="number" name="hours" class="form-control" value="{{ !empty($pirep) ? $pirep->hours : old('hours') }}" @if(!empty($pirep) && $pirep->read_only) readonly @endif>
            <span class="input-group-text bg-deep border-secondary text-muted">MIN</span>
            <input type="number" name="minutes" class="form-control" value="{{ !empty($pirep) ? $pirep->minutes : old('minutes') }}" @if(!empty($pirep) && $pirep->read_only) readonly @endif>
          </div>
        </div>

        <div class="col-md-4">
          <label class="form-label">Cruise Level (FT)</label>
          <input type="number" name="level" class="form-control" value="{{ !empty($pirep) ? $pirep->level : old('level') }}" @if(!empty($pirep) && $pirep->read_only) readonly @endif>
        </div>

        <div class="col-md-6">
          <label class="form-label">Block Fuel ({{setting('units.fuel')}})</label>
          <input type="number" name="block_fuel" class="form-control" step="0.01" value="{{ !empty($pirep) ? $pirep->block_fuel : old('block_fuel') }}" @if(!empty($pirep) && $pirep->read_only) readonly @endif>
        </div>

        <div class="col-md-6">
          <label class="form-label">Fuel Used ({{setting('units.fuel')}})</label>
          <input type="number" name="fuel_used" class="form-control" step="0.01" value="{{ !empty($pirep) ? $pirep->fuel_used : old('fuel_used') }}" @if(!empty($pirep) && $pirep->read_only) readonly @endif>
        </div>
      </div>
    </div>

    <div id="fares_container">
      @include('pireps.fares')
    </div>

    {{-- Section 4: Route --}}
    <div class="section-unified">
      <div class="section-header-sleek">
        <div class="section-icon-sleek"><i class="fas fa-route"></i></div>
        <h3 class="section-title-sleek">Planned Route & Remarks</h3>
      </div>

      <div class="row g-4">
        <div class="col-12">
          <label class="form-label">Planned Route</label>
          <textarea name="route" class="form-control" rows="3">@if(!empty($pirep)){{ $pirep->route }}@else{{ old('route') }}@endif</textarea>
        </div>
        <div class="col-12">
          <label class="form-label">Pilot Remarks</label>
          <textarea name="notes" class="form-control" rows="3">@if(!empty($pirep)){{ $pirep->notes }}@else{{ old('notes') }}@endif</textarea>
        </div>
      </div>
    </div>

    {{-- Section 5: Custom Fields --}}
    @if($custom_fields_exist)
    <div class="section-unified">
      <div class="section-header-sleek">
        <div class="section-icon-sleek"><i class="fas fa-tasks"></i></div>
        <h3 class="section-title-sleek">Additional Data Fields</h3>
      </div>
      
      <div class="table-responsive">
        <table class="table table-borderless align-middle text-white mb-0">
          @if(isset($pirep) && $pirep->fields)
            @each('pireps.custom_fields', $pirep->fields, 'field')
          @else
            @each('pireps.custom_fields', $pirep_fields, 'field')
          @endif
        </table>
      </div>
    </div>
    @endif
  </div>
</div>
