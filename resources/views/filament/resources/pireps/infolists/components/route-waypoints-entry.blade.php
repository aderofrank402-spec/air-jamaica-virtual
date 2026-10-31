<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
@php
    $pirep     = $getRecord();
    $waypoints = $pirep->acars_route ?? collect();
    $hasAcars  = $waypoints->isNotEmpty();

    // Fall back to parsing the route text string
    $routeString = trim($pirep->route ?? '');
    $routeFixes  = $routeString !== '' ? array_values(array_filter(explode(' ', $routeString))) : [];

    // Last fallback: sample FLIGHT_PATH positions to show a trajectory
    $flightPath = collect();
    $hasPath    = false;
    if (!$hasAcars && empty($routeFixes)) {
        $positions = $pirep->acars()
            ->where('type', \App\Models\Enums\AcarsType::FLIGHT_PATH)
            ->orderBy('order')
            ->get(['lat', 'lon', 'order', 'created_at']);
        $total = $positions->count();
        if ($total > 0) {
            $hasPath = true;
            // Sample up to 8 evenly-spaced interior positions plus first/last
            if ($total <= 10) {
                $flightPath = $positions;
            } else {
                $step = (int) floor($total / 7);
                $sampled = collect();
                for ($i = 0; $i < $total; $i += $step) {
                    $sampled->push($positions[$i]);
                }
                // Ensure the last position is included
                if ($sampled->last()?->created_at !== $positions->last()->created_at) {
                    $sampled->push($positions->last());
                }
                $flightPath = $sampled;
            }
        }
    }
@endphp

<div id="route-waypoints-wrapper" {{ $getExtraAttributeBag() }}>

    @if(!$hasAcars && empty($routeFixes) && !$hasPath)
        {{-- True empty state --}}
        <div style="text-align:center; padding:2rem 1rem;">
            <div style="width:40px; height:40px; border-radius:10px; margin:0 auto 0.75rem;
                        background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07);
                        display:flex; align-items:center; justify-content:center;">
                <x-filament::icon icon="heroicon-o-map-pin" class="w-5 h-5 text-slate-600" />
            </div>
            <p style="font-size:.8rem; font-weight:600; color:#475569; margin:0 0 .2rem;">No route filed</p>
            <p style="font-size:.72rem; color:#334155; margin:0;">No waypoints or route string recorded for this PIREP.</p>
        </div>

    @elseif($hasPath)
        {{-- ── FLIGHT_PATH trajectory (sampled positions) ── --}}
        <div style="display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; padding:.25rem 0;">
            @foreach($flightPath as $i => $pos)
                @php
                    $isFirst = $loop->first;
                    $isLast  = $loop->last;
                    if ($isFirst)      { $chipColor = '#3b82f6'; $chipBg = 'rgba(59,130,246,0.12)'; $chipBorder = 'rgba(59,130,246,0.3)'; }
                    elseif ($isLast)   { $chipColor = '#10b981'; $chipBg = 'rgba(16,185,129,0.12)'; $chipBorder = 'rgba(16,185,129,0.3)'; }
                    else               { $chipColor = '#94a3b8'; $chipBg = 'rgba(255,255,255,0.04)'; $chipBorder = 'rgba(255,255,255,0.1)'; }
                @endphp
                <div style="display:inline-flex; flex-direction:column; align-items:center; gap:.2rem;">
                    <div style="background:{{ $chipBg }}; border:1px solid {{ $chipBorder }};
                                border-radius:8px; padding:.35rem .65rem; font-family:ui-monospace,monospace;
                                font-size:.65rem; font-weight:700; color:{{ $chipColor }};
                                letter-spacing:.04em; white-space:nowrap;">
                        {{ number_format((float)$pos->lat, 4) }}, {{ number_format((float)$pos->lon, 4) }}
                    </div>
                    @if($isFirst)
                        <span style="font-size:.58rem; color:#3b82f6; font-weight:700; letter-spacing:.06em;">DEP</span>
                    @elseif($isLast)
                        <span style="font-size:.58rem; color:#10b981; font-weight:700; letter-spacing:.06em;">ARR</span>
                    @endif
                </div>

                @if(!$loop->last)
                    <svg width="14" height="10" viewBox="0 0 14 10" fill="none" style="flex-shrink:0; color:#1e293b; margin-top:-12px;">
                        <path d="M0 5h12M8 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                @endif
            @endforeach
        </div>
        <div style="margin-top:.6rem; display:flex; align-items:center; gap:.5rem;">
            <span style="font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#1e293b;">
                ACARS trajectory
            </span>
            <span style="font-family:ui-monospace,monospace; font-size:.62rem; color:#334155;">
                sampled from {{ $positions->count() }} position reports — no explicit route filed
            </span>
        </div>

    @elseif($hasAcars)
        {{-- ── Full ACARS waypoints with lat/lon ── --}}
        <div style="display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; padding:.25rem 0;">
            @foreach($waypoints->sortBy('order') as $i => $wp)

                {{-- Fix chip --}}
                <div style="display:inline-flex; flex-direction:column; align-items:center; gap:.2rem;">
                    <div style="background:rgba(16,185,129,0.08); border:1px solid rgba(16,185,129,0.25);
                                border-radius:8px; padding:.35rem .75rem; font-family:ui-monospace,monospace;
                                font-size:.72rem; font-weight:700; color:#10b981; letter-spacing:.05em;
                                white-space:nowrap;">
                        {{ strtoupper($wp->name ?? '—') }}
                    </div>
                    @if($wp->lat && $wp->lon)
                        <span style="font-family:ui-monospace,monospace; font-size:.6rem; color:#334155;">
                            {{ number_format((float)$wp->lat, 4) }}, {{ number_format((float)$wp->lon, 4) }}
                        </span>
                    @endif
                </div>

                @if(!$loop->last)
                    <svg width="14" height="10" viewBox="0 0 14 10" fill="none" style="flex-shrink:0; color:#1e293b; margin-top:-12px;">
                        <path d="M0 5h12M8 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                @endif

            @endforeach
        </div>

        {{-- Route string reference --}}
        @if($routeString)
            <div style="margin-top:.75rem; padding:.5rem .75rem; background:rgba(255,255,255,0.02);
                        border:1px solid rgba(255,255,255,0.05); border-radius:8px;">
                <span style="font-size:.6rem; font-weight:700; text-transform:uppercase;
                             letter-spacing:.1em; color:#334155;">Filed Route</span>
                <p style="font-family:ui-monospace,monospace; font-size:.72rem; color:#475569;
                          margin:.25rem 0 0; word-break:break-all;">{{ $routeString }}</p>
            </div>
        @endif

    @else
        {{-- ── Route string parsed into chips (no ACARS waypoints) ── --}}
        <div style="display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; padding:.25rem 0;">
            @foreach($routeFixes as $idx => $fix)

                @php
                    $isDep = $idx === 0;
                    $isArr = $idx === count($routeFixes) - 1;
                    if ($isDep)      { $chipColor = '#3b82f6'; $chipBg = 'rgba(59,130,246,0.12)'; $chipBorder = 'rgba(59,130,246,0.3)'; }
                    elseif ($isArr)  { $chipColor = '#10b981'; $chipBg = 'rgba(16,185,129,0.12)'; $chipBorder = 'rgba(16,185,129,0.3)'; }
                    else             { $chipColor = '#94a3b8'; $chipBg = 'rgba(255,255,255,0.04)'; $chipBorder = 'rgba(255,255,255,0.1)'; }
                @endphp

                <div style="background:{{ $chipBg }}; border:1px solid {{ $chipBorder }};
                            border-radius:8px; padding:.35rem .75rem; font-family:ui-monospace,monospace;
                            font-size:.72rem; font-weight:700; color:{{ $chipColor }};
                            letter-spacing:.05em; white-space:nowrap;">
                    {{ strtoupper($fix) }}
                </div>

                @if($idx < count($routeFixes) - 1)
                    <svg width="14" height="10" viewBox="0 0 14 10" fill="none" style="flex-shrink:0; color:#1e293b;">
                        <path d="M0 5h12M8 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                @endif

            @endforeach
        </div>

        <div style="margin-top:.6rem; display:flex; align-items:center; gap:.5rem;">
            <span style="font-size:.6rem; font-weight:700; text-transform:uppercase;
                         letter-spacing:.1em; color:#1e293b;">Route string</span>
            <span style="font-family:ui-monospace,monospace; font-size:.65rem; color:#334155;">
                {{ $routeString }}
            </span>
        </div>
    @endif

</div>
</x-dynamic-component>
