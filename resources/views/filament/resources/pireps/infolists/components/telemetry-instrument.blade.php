@php
    use App\Models\Enums\AcarsType;

    $record    = $getRecord();
    $metrics   = $getMetrics();
    $chartType = $getChartType();
    $uid       = 'telem-' . $chartType . '-' . str_replace('-', '', $record->id);
    $fuelUnit  = setting('units.fuel', 'lbs');

    // Fetch all flight-path ACARS positions for this pirep, in order
    $positions = $record->acars()
        ->where('type', AcarsType::FLIGHT_PATH)
        ->orderBy('order')
        ->get();

    $count = $positions->count();
    $hasData = $count > 0;

    // Build chart series arrays based on instrument type
    $labels        = [];
    $primaryData   = [];
    $secondaryData = [];
    $tableRows     = [];

    switch ($chartType) {
        case 'altitude':
            $primaryLabel   = 'MSL (ft)';
            $secondaryLabel = 'AGL (ft)';
            foreach ($positions as $i => $pos) {
                $labels[]        = '#' . ($i + 1);
                $p               = (int) round($pos->altitude_msl ?? 0);
                $s               = (int) round($pos->altitude_agl ?? 0);
                $primaryData[]   = $p;
                $secondaryData[] = $s;
                $tableRows[]     = [$i + 1, number_format($p), number_format($s)];
            }
            break;

        case 'airspeed':
            $primaryLabel   = 'IAS (kts)';
            $secondaryLabel = 'GS (kts)';
            foreach ($positions as $i => $pos) {
                $labels[]        = '#' . ($i + 1);
                $p               = (int) ($pos->ias ?? 0);
                $s               = (int) ($pos->gs ?? 0);
                $primaryData[]   = $p;
                $secondaryData[] = $s;
                $tableRows[]     = [$i + 1, $p, $s];
            }
            break;

        case 'navigation':
            $primaryLabel   = 'Heading (°)';
            $secondaryLabel = 'Vert Speed (fpm)';
            foreach ($positions as $i => $pos) {
                $labels[]        = '#' . ($i + 1);
                $p               = (int) ($pos->heading ?? 0);
                $s               = (int) round($pos->vs ?? 0);
                $primaryData[]   = $p;
                $secondaryData[] = $s;
                $tableRows[]     = [$i + 1, str_pad($p, 3, '0', STR_PAD_LEFT) . '°', ($s >= 0 ? '+' : '') . number_format($s)];
            }
            break;

        case 'fuel':
        default:
            $primaryLabel   = strtoupper($fuelUnit);
            $secondaryLabel = 'Flow (PPH)';
            foreach ($positions as $i => $pos) {
                $labels[]        = '#' . ($i + 1);
                $p               = (int) round($pos->fuel?->local(0) ?? 0);
                $s               = (int) round($pos->fuel_flow ?? 0);
                $primaryData[]   = $p;
                $secondaryData[] = $s;
                $tableRows[]     = [$i + 1, number_format($p), number_format($s)];
            }
            break;
    }

    // Determine point radius: hide dots when there are many data points
    $pointRadius = $count > 60 ? 0 : ($count > 30 ? 2 : 3);
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">

{{-- ═══════════════════════════════════════════════
     Instrument Card
═══════════════════════════════════════════════ --}}
<div
    id="{{ $uid }}-wrapper"
    x-data="{
        open: false,
        chart: null,
        toggle() {
            this.open = true;
            this.$nextTick(() => this.boot());
        },
        close() {
            this.open = false;
        },
        boot() {
            if (!window.Chart) {
                if (!document.getElementById('chartjs-cdn')) {
                    const s = document.createElement('script');
                    s.id  = 'chartjs-cdn';
                    s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js';
                    s.onload = () => this.initChart();
                    document.head.appendChild(s);
                } else {
                    const wait = setInterval(() => {
                        if (window.Chart) { clearInterval(wait); this.initChart(); }
                    }, 80);
                }
            } else {
                this.initChart();
            }
        },
        initChart() {
            const ctx = document.getElementById('{{ $uid }}-canvas');
            if (!ctx) return;
            if (this.chart) { this.chart.destroy(); this.chart = null; }
            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {{ json_encode($labels) }},
                    datasets: [
                        {
                            label: {{ json_encode($primaryLabel) }},
                            data: {{ json_encode($primaryData) }},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.08)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                            pointRadius: {{ $pointRadius }},
                            pointHoverRadius: 4,
                            pointBackgroundColor: '#10b981',
                        },
                        {
                            label: {{ json_encode($secondaryLabel) }},
                            data: {{ json_encode($secondaryData) }},
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.06)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: false,
                            pointRadius: {{ $pointRadius }},
                            pointHoverRadius: 4,
                            pointBackgroundColor: '#3b82f6',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 400 },
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#94a3b8',
                                font: { family: 'ui-monospace, monospace', size: 11 },
                                boxWidth: 12,
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(2,6,23,0.92)',
                            titleColor: '#10b981',
                            bodyColor: '#e2e8f0',
                            borderColor: 'rgba(16,185,129,0.25)',
                            borderWidth: 1,
                            titleFont: { family: 'ui-monospace, monospace', size: 11 },
                            bodyFont:  { family: 'ui-monospace, monospace', size: 11 },
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#475569',
                                font: { family: 'ui-monospace, monospace', size: 10 },
                                maxTicksLimit: 12,
                                maxRotation: 0,
                            },
                            grid: { color: 'rgba(255,255,255,0.04)' }
                        },
                        y: {
                            ticks: {
                                color: '#94a3b8',
                                font: { family: 'ui-monospace, monospace', size: 10 },
                            },
                            grid: { color: 'rgba(255,255,255,0.06)' }
                        }
                    }
                }
            });
        }
    }"
>

    {{-- Clickable instrument card --}}
    <button
        type="button"
        @click="toggle()"
        class="w-full text-left flex items-center gap-3 p-3 rounded-xl transition-all duration-200
               bg-white/5 border border-white/10
               hover:bg-white/8 hover:border-emerald-500/30 hover:shadow-lg
               focus:outline-none focus:ring-2 focus:ring-emerald-500/40
               {{ $hasData ? 'cursor-pointer' : 'cursor-default' }}"
        @if(!$hasData) disabled @endif
        title="{{ $hasData ? 'Click to view full flight history (' . $count . ' data points)' : 'No ACARS data recorded for this flight' }}"
    >
        {{-- Icon --}}
        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/15">
            <x-filament::icon :icon="$getIcon()" class="w-5 h-5" />
        </div>

        {{-- Label + metric values --}}
        <div class="flex-grow min-w-0">
            <div class="flex items-center justify-between gap-2 mb-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                    {{ $getLabelText() }}
                </span>
                @if($hasData)
                    <span class="text-[9px] text-emerald-500/60 font-mono border border-emerald-500/20 rounded px-1.5 py-0.5 bg-emerald-500/5">
                        {{ $count }} pts
                    </span>
                @endif
            </div>

            @if(count($metrics))
                <div class="flex items-baseline gap-3 flex-wrap">
                    @foreach($metrics as $metric)
                        <div class="flex items-baseline gap-1">
                            <span class="text-sm font-bold text-white font-mono">
                                {{ $metric['value'] }}
                            </span>
                            <span class="text-[10px] text-gray-500 uppercase tracking-wide">
                                {{ $metric['unit'] }}
                            </span>
                        </div>
                        @if(!$loop->last)
                            <span class="text-gray-700 text-xs">/</span>
                        @endif
                    @endforeach
                </div>
            @else
                <span class="text-[11px] text-gray-600">No position data</span>
            @endif
        </div>

        {{-- Chevron hint --}}
        @if($hasData)
            <div class="flex-shrink-0 text-gray-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        @endif
    </button>

    {{-- ═══════════════════════════════════════════════
         Modal overlay
    ═══════════════════════════════════════════════ --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="close()"
        style="display:none; position:fixed; inset:0; z-index:9990; overflow-y:auto;"
        class="flex items-center justify-center p-4"
        @click.self="close()"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

        {{-- Modal panel --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            style="position:relative; z-index:9991; width:100%; max-width:860px;
                   background:rgba(2,6,23,0.97); border:1px solid rgba(16,185,129,0.2);
                   border-radius:16px; box-shadow:0 25px 60px rgba(0,0,0,0.7);
                   backdrop-filter:blur(20px);"
        >

            {{-- Modal header --}}
            <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem;
                        padding:1rem 1.5rem; border-bottom:1px solid rgba(16,185,129,0.12);">
                <div style="display:flex; align-items:center; gap:.75rem;">
                    <div style="width:32px; height:32px; border-radius:8px;
                                background:rgba(16,185,129,0.12); border:1px solid rgba(16,185,129,0.25);
                                display:flex; align-items:center; justify-content:center; color:#10b981;">
                        <x-filament::icon :icon="$getIcon()" class="w-4 h-4" />
                    </div>
                    <div>
                        <p style="font-size:.65rem; font-weight:700; letter-spacing:.14em;
                                  text-transform:uppercase; color:#10b981; margin:0 0 .1rem;">
                            {{ $getLabelText() }} — Flight History
                        </p>
                        <p style="font-size:.7rem; color:#475569; margin:0; font-family:monospace;">
                            {{ $count }} position reports &bull; {{ $primaryLabel }} &amp; {{ $secondaryLabel }}
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="close()"
                    style="width:32px; height:32px; border-radius:8px;
                           background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1);
                           color:#94a3b8; display:flex; align-items:center; justify-content:center;
                           cursor:pointer; transition:all .18s;"
                    onmouseover="this.style.borderColor='rgba(239,68,68,.4)'; this.style.color='#ef4444';"
                    onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='#94a3b8';"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Chart area --}}
            <div style="padding:1.25rem 1.5rem;">
                @if($hasData)
                    <div style="position:relative; height:280px; margin-bottom:1.25rem;">
                        <canvas id="{{ $uid }}-canvas"></canvas>
                    </div>

                    {{-- Divider --}}
                    <div style="border-top:1px solid rgba(16,185,129,0.1); margin-bottom:1rem;
                                display:flex; align-items:center; gap:.75rem; padding-top:.75rem;">
                        <span style="font-size:.62rem; font-weight:700; letter-spacing:.14em;
                                     text-transform:uppercase; color:#334155; white-space:nowrap;">
                            Raw Data
                        </span>
                        <div style="flex:1; height:1px; background:rgba(16,185,129,0.08);"></div>
                    </div>

                    {{-- Scrollable data table --}}
                    <div style="max-height:220px; overflow-y:auto; border-radius:8px;
                                border:1px solid rgba(255,255,255,0.06);">
                        <table style="width:100%; border-collapse:collapse; font-family:ui-monospace,monospace; font-size:.75rem;">
                            <thead>
                                <tr style="background:rgba(16,185,129,0.07); position:sticky; top:0;">
                                    <th style="padding:.5rem .75rem; text-align:left; color:#475569;
                                               font-size:.6rem; font-weight:700; letter-spacing:.1em;
                                               text-transform:uppercase; border-bottom:1px solid rgba(16,185,129,0.1);">
                                        #
                                    </th>
                                    <th style="padding:.5rem .75rem; text-align:right; color:#10b981;
                                               font-size:.6rem; font-weight:700; letter-spacing:.1em;
                                               text-transform:uppercase; border-bottom:1px solid rgba(16,185,129,0.1);">
                                        {{ $primaryLabel }}
                                    </th>
                                    <th style="padding:.5rem .75rem; text-align:right; color:#3b82f6;
                                               font-size:.6rem; font-weight:700; letter-spacing:.1em;
                                               text-transform:uppercase; border-bottom:1px solid rgba(16,185,129,0.1);">
                                        {{ $secondaryLabel }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tableRows as $row)
                                    <tr style="{{ $loop->even ? 'background:rgba(255,255,255,0.02)' : '' }}">
                                        <td style="padding:.4rem .75rem; color:#334155;
                                                   border-bottom:1px solid rgba(255,255,255,0.03);">
                                            {{ $row[0] }}
                                        </td>
                                        <td style="padding:.4rem .75rem; text-align:right; color:#e2e8f0;
                                                   font-weight:600; border-bottom:1px solid rgba(255,255,255,0.03);">
                                            {{ $row[1] }}
                                        </td>
                                        <td style="padding:.4rem .75rem; text-align:right; color:#93c5fd;
                                                   border-bottom:1px solid rgba(255,255,255,0.03);">
                                            {{ $row[2] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- Empty state --}}
                    <div style="text-align:center; padding:3rem 1rem; color:#334155;">
                        <div style="width:48px; height:48px; border-radius:12px; background:rgba(255,255,255,0.03);
                                    border:1px solid rgba(255,255,255,0.07); display:flex; align-items:center;
                                    justify-content:center; margin:0 auto 1rem; color:#1e293b;">
                            <x-filament::icon :icon="$getIcon()" class="w-6 h-6" />
                        </div>
                        <p style="font-size:.8rem; font-weight:600; color:#475569; margin:0 0 .25rem;">
                            No ACARS Data
                        </p>
                        <p style="font-size:.72rem; color:#334155; margin:0;">
                            No flight-path position reports were recorded for this PIREP.
                        </p>
                    </div>
                @endif
            </div>

        </div>{{-- /.modal panel --}}
    </div>{{-- /.modal overlay --}}

</div>{{-- /x-data --}}
</x-dynamic-component>
