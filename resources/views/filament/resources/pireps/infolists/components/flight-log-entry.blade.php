<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div id="flight-log-wrapper" {{ $getExtraAttributeBag() }}>
        @php
            $pirep = $getRecord();
            $logs = $pirep->acars_logs ?? collect();
        @endphp

        <style>
            .mission-chronology {
                background: rgba(0, 0, 0, 0.4);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 12px;
                padding: 1.5rem;
                height: 500px;
                overflow-y: auto;
                font-family: 'Roboto Mono', monospace;
            }

            .mission-chronology::-webkit-scrollbar {
                width: 4px;
            }
            .mission-chronology::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.02);
            }
            .mission-chronology::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 2px;
            }

            .timeline-event {
                position: relative;
                padding-left: 2rem;
                padding-bottom: 1.5rem;
                border-left: 1px dashed rgba(255, 255, 255, 0.2);
            }

            .timeline-event:last-child {
                border-left-color: transparent;
                padding-bottom: 0;
            }

            .event-marker {
                position: absolute;
                left: -6px;
                top: 0;
                width: 11px;
                height: 11px;
                background: #1e293b;
                border: 2px solid #475569;
                border-radius: 50%;
                z-index: 10;
            }

            .timeline-event.critical .event-marker {
                border-color: #00d4ff;
                box-shadow: 0 0 10px rgba(0, 212, 255, 0.4);
                background: #00d4ff;
            }

            .event-time {
                font-size: 10px;
                color: #e2e8f0;
                font-weight: 700;
                margin-bottom: 4px;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .event-content {
                font-size: 12px;
                color: #f1f5f9;
                line-height: 1.5;
            }

            .event-content b {
                color: #fff;
                font-weight: 600;
            }

            .event-icon {
                font-size: 14px;
                margin-right: 4px;
            }
        </style>

        @if($logs->isEmpty())
            <div class="mission-chronology flex items-center justify-center text-gray-600 text-xs tracking-widest uppercase italic">
                NO CHRONOLOGICAL DATA
            </div>
        @else
            <div class="mission-chronology custom-scrollbar">
                @foreach($logs->sortBy('sim_time')->sortBy('created_at') as $log)
                    @php
                        $msg = strtolower($log->log ?? $log->status);
                        $isCritical = str_contains($msg, 'takeoff') || 
                                      str_contains($msg, 'landed') ||
                                      str_contains($msg, 'arrived') ||
                                      str_contains($msg, 'started') ||
                                      str_contains($msg, 'shutdown');
                        
                        $icon = '○';
                        if(str_contains($msg, 'takeoff')) $icon = '🛫';
                        if(str_contains($msg, 'landed')) $icon = '🛬';
                        if(str_contains($msg, 'engine')) $icon = '⚙️';
                        if(str_contains($msg, 'fuel')) $icon = '⛽';
                    @endphp
                    <div class="timeline-event {{ $isCritical ? 'critical' : '' }}">
                        <div class="event-marker"></div>
                        <div class="event-time">
                            <span style="color:#60a5fa;">T+</span>
                            @if($log->sim_time)
                                {{ \Carbon\Carbon::parse($log->sim_time)->format('H:i:s') }}
                            @elseif($log->created_at)
                                {{ $log->created_at->format('H:i:s') }}
                            @else
                                --:--:--
                            @endif
                        </div>
                        <div class="event-content">
                            <span class="event-icon">{{ $icon }}</span>
                            {!! str_replace(['[', ']'], ['<b>', '</b>'], $log->log ?? $log->status ?? 'N/A') !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-dynamic-component>
