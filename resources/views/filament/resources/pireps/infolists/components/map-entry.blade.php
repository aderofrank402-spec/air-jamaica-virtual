@php
    $record = $getRecord();
    $dpt    = $record->dpt_airport;
    $arr    = $record->arr_airport;
    $pos    = $record->position;

    // Planned route: departure → ROUTE waypoints → arrival  (with hover popup)
    $planned = [];
    if ($dpt?->lat && $dpt?->lon) {
        $planned[] = ['lat' => $dpt->lat, 'lon' => $dpt->lon, 'popup' => $record->dpt_airport_id . ' — ' . ($dpt->name ?? $record->dpt_airport_id)];
    }
    foreach ($record->acars_route as $wp) {
        if ($wp->lat && $wp->lon) {
            $planned[] = ['lat' => $wp->lat, 'lon' => $wp->lon, 'name' => $wp->name, 'popup' => ($wp->name ?? '') . '<br>' . number_format((float)$wp->lat, 4) . ', ' . number_format((float)$wp->lon, 4)];
        }
    }
    if ($arr?->lat && $arr?->lon) {
        $planned[] = ['lat' => $arr->lat, 'lon' => $arr->lon, 'popup' => $record->arr_airport_id . ' — ' . ($arr->name ?? $record->arr_airport_id)];
    }

    // Actual flown path: FLIGHT_PATH ACARS positions (with hover popup)
    $actual = $record->acars->filter(fn($p) => $p->lat && $p->lon)->map(fn($p) => [
        'lat'   => $p->lat,
        'lon'   => $p->lon,
        'popup' => 'Spd: ' . ($p->gs ?? 0) . ' kts<br>Alt: ' . number_format($p->altitude_msl ?? $p->altitude ?? 0) . ' ft<br>' . number_format((float)$p->lat, 4) . ', ' . number_format((float)$p->lon, 4),
    ])->values()->toArray();

    $mapConfig = [
        'id'      => $record->id,
        'dpt'     => ['lat' => $dpt?->lat, 'lon' => $dpt?->lon, 'icao' => $record->dpt_airport_id],
        'arr'     => ['lat' => $arr?->lat,  'lon' => $arr?->lon,  'icao' => $record->arr_airport_id],
        'pos'     => $pos ? ['lat' => $pos->lat, 'lon' => $pos->lon, 'hdg' => $pos->heading, 'alt' => number_format($pos->altitude_msl ?? $pos->altitude ?? 0)] : null,
        'planned' => $planned,
        'actual'  => $actual,
    ];
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div 
        wire:ignore
        x-data="{
            config: {{ json_encode($mapConfig) }},
            map: null,
            isExpanded: false,
            init() {
                this.initSurveillance();
            },
            toggleExpand() {
                this.isExpanded = !this.isExpanded;
                setTimeout(() => {
                    if (this.map) this.map.invalidateSize();
                }, 400);
            },
            toggleFullscreen() {
                const el = document.getElementById('canvas-' + this.config.id);
                if (!document.fullscreenElement) {
                    el.requestFullscreen().catch(err => {
                        console.error(`Error attempting to enable fullscreen: ${err.message}`);
                    });
                } else {
                    document.exitFullscreen();
                }
                setTimeout(() => {
                    if (this.map) this.map.invalidateSize();
                }, 400);
            },
            initSurveillance() {
                if (window.L) { 
                    setTimeout(() => this.renderMap(), 100);
                } else {
                    if (!document.getElementById('leaflet-css-v2')) {
                        const link = document.createElement('link'); 
                        link.id = 'leaflet-css-v2'; 
                        link.rel = 'stylesheet'; 
                        link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'; 
                        document.head.appendChild(link);
                    }
                    if (!document.getElementById('leaflet-js-v2')) {
                        const script = document.createElement('script'); 
                        script.id = 'leaflet-js-v2';
                        script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'; 
                        script.onload = () => this.renderMap(); 
                        document.head.appendChild(script);
                    } else {
                        // Script is already loading, wait for it
                        const checkL = setInterval(() => {
                            if (window.L) {
                                clearInterval(checkL);
                                this.renderMap();
                            }
                        }, 100);
                    }
                }
            },
            renderMap() {
                const el = document.getElementById('canvas-' + this.config.id);
                if (!el || el._leaflet_id) return;

                this.map = L.map(el, {
                    zoomControl: false,
                    attributionControl: false,
                    fadeAnimation: true
                });

                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19
                }).addTo(this.map);

                const bounds = [];
                const popupOpts = {
                    className: 'mc-popup',
                    closeButton: false,
                    offset: [0, -4],
                };

                // ── Planned route (dashed blue) ──────────────────────────────
                if (this.config.planned && this.config.planned.length >= 2) {
                    const coords = this.config.planned.map(p => [p.lat, p.lon]);
                    L.polyline(coords, { color: '#2563eb', weight: 2.5, opacity: 0.75, dashArray: '6 10' }).addTo(this.map);
                    coords.forEach(c => bounds.push(c));

                    // Waypoint markers with hover popup
                    this.config.planned.forEach((p, i) => {
                        const isFirst = i === 0;
                        const isLast  = i === this.config.planned.length - 1;
                        const color   = isFirst ? '#2563eb' : (isLast ? '#059669' : '#6366f1');
                        const radius  = (isFirst || isLast) ? 7 : 4;
                        L.circleMarker([p.lat, p.lon], {
                            radius, color, fillColor: color, fillOpacity: 1, weight: 2
                        }).addTo(this.map).bindTooltip(p.popup || (p.name || ''), {
                            ...popupOpts, permanent: false
                        });
                    });
                } else if (this.config.dpt && this.config.dpt.lat) {
                    // Fallback: at least show dpt/arr dots when no route waypoints
                    const dp = [this.config.dpt.lat, this.config.dpt.lon];
                    L.circleMarker(dp, { radius: 7, color: '#2563eb', fillColor: '#2563eb', fillOpacity: 1, weight: 2 })
                        .addTo(this.map).bindTooltip(this.config.dpt.icao || 'DEP', popupOpts);
                    bounds.push(dp);
                    if (this.config.arr && this.config.arr.lat) {
                        const ap = [this.config.arr.lat, this.config.arr.lon];
                        L.circleMarker(ap, { radius: 7, color: '#059669', fillColor: '#059669', fillOpacity: 1, weight: 2 })
                            .addTo(this.map).bindTooltip(this.config.arr.icao || 'ARR', popupOpts);
                        bounds.push(ap);
                        L.polyline([dp, ap], { color: '#2563eb', weight: 2.5, opacity: 0.75, dashArray: '6 10' }).addTo(this.map);
                    }
                }

                // ── Actual flown path (solid green) ─────────────────────────
                if (this.config.actual && this.config.actual.length >= 2) {
                    const coords = this.config.actual.map(p => [p.lat, p.lon]);
                    L.polyline(coords, { color: '#059669', weight: 3, opacity: 0.9 }).addTo(this.map);
                    coords.forEach(c => bounds.push(c));

                    // Hover markers along the flown path (sparse — every Nth point to avoid clutter)
                    const step = Math.max(1, Math.floor(this.config.actual.length / 20));
                    this.config.actual.forEach((p, i) => {
                        if (i % step !== 0 && i !== this.config.actual.length - 1) return;
                        L.circleMarker([p.lat, p.lon], {
                            radius: 3, color: '#059669', fillColor: '#fff', fillOpacity: 1, weight: 1.5
                        }).addTo(this.map).bindTooltip(p.popup || '', { ...popupOpts, permanent: false });
                    });
                }

                // ── Current position (plane icon) ────────────────────────────
                if (this.config.pos && this.config.pos.lat) {
                    const stats = document.getElementById('stats-' + this.config.id);
                    if (stats) {
                        stats.querySelector('.v-lat').innerText = parseFloat(this.config.pos.lat).toFixed(4);
                        stats.querySelector('.v-lon').innerText = parseFloat(this.config.pos.lon).toFixed(4);
                        stats.querySelector('.v-alt').innerText = this.config.pos.alt;
                    }
                    const icon = L.divIcon({
                        html: `<div style='transform:rotate(${this.config.pos.hdg||0}deg);color:#1d4ed8;text-shadow:0 0 8px rgba(29,78,216,.5);font-size:26px;line-height:1;'>✈</div>`,
                        className: 'p-icon', iconSize: [28, 28], iconAnchor: [14, 14]
                    });
                    L.marker([this.config.pos.lat, this.config.pos.lon], { icon }).addTo(this.map);
                    bounds.push([this.config.pos.lat, this.config.pos.lon]);
                }

                // ── Fit bounds / fallback view ───────────────────────────────
                if (bounds.length >= 2) {
                    this.map.fitBounds(L.latLngBounds(bounds), { padding: [50, 50] });
                } else if (bounds.length === 1) {
                    this.map.setView(bounds[0], 10);
                } else {
                    this.map.setView([20, 0], 2);
                }

                setTimeout(() => { this.map.invalidateSize(); }, 200);
                new ResizeObserver(() => { if (this.map) this.map.invalidateSize(); }).observe(el);
            }
        }"
        id="surveillance-container-{{ $record->id }}" 
        class="surveillance-engine-root"
    >
        <style>
            .surveillance-engine-root {
                width: 100%;
                position: relative;
            }

            .map-canvas-isolated {
                width: 100%;
                height: 500px;
                border-radius: 12px;
                border: 1px solid rgba(0, 0, 0, 0.1);
                background: #f8fafc;
                position: relative;
                overflow: hidden;
                transition: height 0.3s ease-in-out;
            }

            .map-canvas-isolated.is-expanded {
                height: 800px;
            }

            .surveillance-overlay {
                position: absolute;
                top: 15px;
                left: 15px;
                z-index: 1000;
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(0, 0, 0, 0.1);
                padding: 12px;
                border-radius: 8px;
                font-family: monospace;
                font-size: 11px;
                color: #2563eb;
                pointer-events: none;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            }

            .map-controls {
                position: absolute;
                top: 15px;
                right: 15px;
                z-index: 1000;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .map-btn {
                width: 36px;
                height: 36px;
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #475569;
                cursor: pointer;
                transition: all 0.2s;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
                pointer-events: auto;
            }

            .map-btn:hover {
                background: #fff;
                color: #2563eb;
                transform: translateY(-1px);
            }

            .pulse-dot-raw {
                width: 10px;
                height: 10px;
                background: #2563eb;
                border-radius: 50%;
                display: inline-block;
                margin-right: 8px;
                box-shadow: 0 0 8px rgba(37, 99, 235, 0.5);
                animation: pulse-raw 1.5s infinite;
            }

            @keyframes pulse-raw {
                0%,
                100% {
                    opacity: 1;
                    transform: scale(1);
                }

                50% {
                    opacity: 0.4;
                    transform: scale(0.9);
                }
            }

            .leaflet-container {
                background: #f8fafc !important;
            }

            /* Tooltip styling */
            .mc-popup {
                background: rgba(2,6,23,0.92) !important;
                border: 1px solid rgba(16,185,129,0.3) !important;
                border-radius: 6px !important;
                color: #e2e8f0 !important;
                font-family: ui-monospace, monospace !important;
                font-size: 11px !important;
                padding: 4px 8px !important;
                white-space: nowrap;
            }
            .mc-popup::before { display: none !important; }
        </style>
        <div 
            class="map-canvas-isolated" 
            id="canvas-{{ $record->id }}"
            :class="{ 'is-expanded': isExpanded }"
        >
            <!-- Map HUD -->
            <div class="surveillance-overlay">
                <div style="display: flex; align-items: center; margin-bottom: 5px; font-weight: bold;">
                    <span class="pulse-dot-raw"></span>ACTIVE TRACKING
                </div>
                <div id="stats-{{ $record->id }}" style="color: #475569; line-height: 1.4;">
                    LAT: <span class="v-lat">--.----</span><br>
                    LON: <span class="v-lon">--.----</span><br>
                    ALT: <span class="v-alt">--</span> FT
                </div>
            </div>

            <!-- Map Controls -->
            <div class="map-controls">
                <button 
                    @click="toggleFullscreen()" 
                    class="map-btn"
                    title="Fullscreen"
                >
                    <x-filament::icon icon="heroicon-o-arrows-pointing-out" class="w-5 h-5" />
                </button>
                <button 
                    @click="window.location.reload()" 
                    class="map-btn"
                    title="Refresh Data"
                >
                    <x-filament::icon icon="heroicon-o-arrow-path" class="w-5 h-5" />
                </button>
                <div class="h-[1px] w-full bg-gray-200 my-1"></div>
                <button 
                    @click="if(map) map.zoomIn()" 
                    class="map-btn font-bold text-lg"
                    title="Zoom In"
                >
                    <x-filament::icon icon="heroicon-o-plus" class="w-5 h-5" />
                </button>
                <button 
                    @click="if(map) map.zoomOut()" 
                    class="map-btn font-bold text-lg"
                    title="Zoom Out"
                >
                    <x-filament::icon icon="heroicon-o-minus" class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>
</x-dynamic-component>