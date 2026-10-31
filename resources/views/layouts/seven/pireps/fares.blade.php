<div class="section-unified">
    <div class="section-header-sleek">
        <div class="section-icon-sleek"><i class="fas fa-users"></i></div>
        <h3 class="section-title-sleek">Fare Information</h3>
    </div>

    @if(!empty($fares) && count($fares) > 0)
        <div class="row g-4">
            @foreach($fares as $fare)
                <div class="col-md-4">
                    <label class="form-label">{{ $fare->name }} ({{ $fare->code }})</label>
                    <div class="input-group">
                        <input type="number" name="fare_{{ $fare->id }}" class="form-control" value="{{ $fare->count }}" @if(!empty($pirep) && $pirep->read_only) readonly @endif>
                        <span class="input-group-text bg-deep border-secondary text-muted">QTY</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 bg-deep rounded-3 border border-secondary border-opacity-20">
            <i class="fas fa-info-circle text-muted mb-2 d-block fa-2x"></i>
            <span class="text-muted">No fare data available for this aircraft.</span>
        </div>
    @endif
</div>