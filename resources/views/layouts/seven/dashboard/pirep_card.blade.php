<div class="card-body pirep-card">
  <div class="row">
    <div class="col-md-10">
      <div class="d-flex flex-column">
        <div class="pirep-header d-flex align-items-center mb-2">
          <i class="bi bi-airplane me-2"></i>
          <h5 class="mb-0">
            <a href="{{ route('frontend.pireps.show', [$pirep->id]) }}">{{ $pirep->ident }}</a>
          </h5>
          @if($pirep->state === PirepState::PENDING)
            <span class="badge bg-warning ms-2">
          @elseif($pirep->state === PirepState::ACCEPTED)
              <span class="badge bg-success ms-2">
          @elseif($pirep->state === PirepState::REJECTED)
              <span class="badge bg-danger ms-2">
          @else
             <span class="badge bg-info ms-2">
          @endif
            {{ PirepState::label($pirep->state) }}</span>
        </div>
        
        <div class="pirep-route">
          <div class="d-flex align-items-center">
            <div class="airport-info">
              <span class="airport-name">{{ $pirep->dpt_airport->name }}</span>
              <span class="airport-code">
                (<a href="{{route('frontend.airports.show', ['id' => $pirep->dpt_airport->icao])}}">
                  {{$pirep->dpt_airport->icao}}
                </a>)
              </span>
            </div>
            
            <div class="route-arrow mx-2">
              <i class="bi bi-arrow-right"></i>
            </div>
            
            <div class="airport-info">
              <span class="airport-name">{{ $pirep->arr_airport->name }}</span>
              <span class="airport-code">
                (<a href="{{route('frontend.airports.show', ['id' => $pirep->arr_airport->icao])}}">
                  {{$pirep->arr_airport->icao}}
                </a>)
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-2 d-flex align-items-center justify-content-end">
      <a href="{{ route('frontend.pireps.edit', [$pirep->id]) }}" class="btn btn-sm btn-info">
        <i class="bi bi-pencil me-1"></i>@lang('common.edit')
      </a> 
    </div>
  </div>
</div>
