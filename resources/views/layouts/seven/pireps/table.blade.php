@php
$stateMap = [
    \App\Models\Enums\PirepState::ACCEPTED    => ['cls' => 's-accepted',  'icon' => 'fas fa-check-circle', 'label' => 'Accepted'],
    \App\Models\Enums\PirepState::PENDING     => ['cls' => 's-pending',   'icon' => 'fas fa-clock',         'label' => 'Pending'],
    \App\Models\Enums\PirepState::IN_PROGRESS => ['cls' => 's-progress',  'icon' => 'fas fa-plane',         'label' => 'In Progress'],
    \App\Models\Enums\PirepState::PAUSED      => ['cls' => 's-paused',    'icon' => 'fas fa-pause-circle',  'label' => 'Paused'],
    \App\Models\Enums\PirepState::REJECTED    => ['cls' => 's-rejected',  'icon' => 'fas fa-times-circle',  'label' => 'Rejected'],
    \App\Models\Enums\PirepState::DRAFT       => ['cls' => 's-draft',     'icon' => 'fas fa-pencil-alt',    'label' => 'Draft'],
    \App\Models\Enums\PirepState::CANCELLED   => ['cls' => 's-draft',     'icon' => 'fas fa-ban',           'label' => 'Cancelled'],
];
@endphp

@if($pireps->isEmpty())
    <div class="fl-empty">
        <div class="fl-empty-icon"><i class="fas fa-paper-plane"></i></div>
        <h3>No flights logged yet</h3>
        <p>File your first PIREP to start building your flight history.</p>
        <a href="{{ route('frontend.pireps.create') }}" class="btn-new-pirep mt-3" style="margin:1.5rem auto 0;display:inline-flex;">
            <i class="fas fa-plus"></i> File First Report
        </a>
    </div>
@else
    <div class="fl-list">
    @foreach($pireps as $pirep)
        @php
            $state   = $stateMap[$pirep->state] ?? ['cls' => 's-draft', 'icon' => 'fas fa-question', 'label' => 'Unknown'];
            $distNmi = $pirep->distance instanceof \App\Support\Units\Distance
                ? round($pirep->distance['nmi'])
                : (is_numeric($pirep->distance) ? (int)$pirep->distance : 0);
            $lrVal  = $pirep->landing_rate ? (int)$pirep->landing_rate : null;
            $lrStr  = $lrVal ? ($lrVal > 0 ? '+' : '') . number_format($lrVal) . ' fpm' : null;
            $lrColor = $lrVal
                ? ($lrVal >= -180 ? '#10b981' : ($lrVal >= -300 ? '#fbbf24' : '#ef4444'))
                : 'inherit';
            $flightMins = (int)$pirep->flight_time;
            $fh = floor($flightMins / 60);
            $fm = $flightMins % 60;
        @endphp

        <div class="fl-card {{ $state['cls'] }}">
            <a href="{{ route('frontend.pireps.show', $pirep->id) }}" class="fl-card-link">

                {{-- row 1: meta --}}
                <div class="fl-meta">
                    <div class="fl-meta-left">
                        <span class="fl-badge {{ $state['cls'] }}">
                            <i class="{{ $state['icon'] }}"></i>
                            {{ $state['label'] }}
                        </span>
                        <span class="fl-ident">{{ $pirep->ident }}</span>
                        @if($pirep->aircraft)
                            <span class="fl-sep">·</span>
                            <span class="fl-aircraft">
                                {{ $pirep->aircraft->registration ?? $pirep->aircraft->ident }}
                                @if($pirep->aircraft->name) — {{ $pirep->aircraft->name }} @endif
                            </span>
                        @endif
                        @if($pirep->source === \App\Models\Enums\PirepSource::ACARS)
                            <span class="fl-badge s-draft" style="font-size:.52rem">
                                <i class="fas fa-satellite-dish"></i> ACARS
                            </span>
                        @endif
                    </div>
                    <div class="fl-date">
                        @if(filled($pirep->submitted_at))
                            <i class="far fa-calendar-alt"></i>
                            {{ $pirep->submitted_at->format('M j, Y') }}
                            <span style="opacity:.5"> · {{ $pirep->submitted_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>

                {{-- row 2: route --}}
                <div class="fl-route">
                    <div class="fl-apt fl-dep">
                        <div class="fl-apt-code">{{ $pirep->dpt_airport_id }}</div>
                        <div class="fl-apt-name">
                            @if($pirep->dpt_airport){{ $pirep->dpt_airport->name }}@endif
                        </div>
                    </div>

                    <div class="fl-arrow">
                        <div class="fl-arrow-line"></div>
                        <i class="fas fa-plane fl-arrow-icon"></i>
                    </div>

                    <div class="fl-apt fl-arr">
                        <div class="fl-apt-code">{{ $pirep->arr_airport_id }}</div>
                        <div class="fl-apt-name">
                            @if($pirep->arr_airport){{ $pirep->arr_airport->name }}@endif
                        </div>
                    </div>
                </div>

                {{-- row 3: hud + actions --}}
                <div class="fl-hud">
                    @if($flightMins > 0)
                    <div class="fl-hud-item">
                        <i class="fas fa-clock"></i>
                        <span class="fl-hud-val">{{ $fh }}h {{ str_pad($fm,2,'0',STR_PAD_LEFT) }}m</span>
                    </div>
                    @endif

                    @if($distNmi > 0)
                    <div class="fl-hud-item">
                        <i class="fas fa-route"></i>
                        <span class="fl-hud-val">{{ number_format($distNmi) }} NM</span>
                    </div>
                    @endif

                    @if($lrStr)
                    <div class="fl-hud-item">
                        <i class="fas fa-arrow-down"></i>
                        <span class="fl-hud-val" style="color:{{ $lrColor }}">{{ $lrStr }}</span>
                    </div>
                    @endif

                    @if($pirep->score)
                    <div class="fl-hud-item">
                        <i class="fas fa-star"></i>
                        <span class="fl-hud-val" style="color:var(--amb)">{{ $pirep->score }}</span>
                    </div>
                    @endif

                    <div class="fl-hud-spacer"></div>

                    <div class="fl-actions" onclick="event.stopPropagation()">
                        <a href="{{ route('frontend.pireps.show', $pirep->id) }}"
                           class="fl-btn fl-btn-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                        @if(!$pirep->read_only)
                        <a href="{{ route('frontend.pireps.edit', $pirep->id) }}"
                           class="fl-btn fl-btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endif
                    </div>
                </div>

            </a>
        </div>
    @endforeach
    </div>

    @if($pireps->hasPages())
    <div class="fl-pages">
        <nav>{{ $pireps->withQueryString()->links('pagination.bootstrap-5') }}</nav>
    </div>
    @endif
@endif
