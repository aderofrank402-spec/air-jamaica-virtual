@extends('app')
@section('title', 'Flight Execution Center')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- MISSION CONTROL SYSTEM STYLES --- */
        :root {
            --emerald-glow: #10b981;
            --emerald-dim: rgba(16, 185, 129, 0.2);
            --slate-dark: #020617;
            --panel-bg: rgba(2, 6, 23, 0.9);
            --border-color: rgba(255, 255, 255, 0.1);
            --font-mono: 'JetBrains Mono', 'Courier New', monospace;
            --aj-blue: #0056b3;
        }

        /* Hide Footer & Remove Bottom Gaps for Mission Control */
        .footer,
        .footer-bottom {
            display: none !important;
        }

        body,
        .wrapper,
        .main-content-wrapper,
        .body.container {
            padding-bottom: 0 !important;
            margin-bottom: 0 !important;
            overflow: hidden !important;
        }

        body {
            font-family: 'JetBrains Mono', monospace !important;
            font-size: 16px;
        }

        /* Mission Control Tactical Interface */
        .fec-wrapper {
            display: flex;
            height: calc(100vh - 76px);
            /* Perfect fit below global navbar */
            position: relative;
            overflow: hidden;
        }

        /* Adjust main content container padding for this full-screen view */
        .body.container {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            height: 100% !important;
        }

        /* --- SIDEBAR: MISSION LIST --- */
        .fec-sidebar {
            width: 380px;
            background: #010409;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            z-index: 100;
            overflow-x: hidden;
        }

        .fec-sidebar h2 {
            color: var(--emerald-glow);
            font-weight: 900;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-bottom: 2rem;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
        }

        .mission-list {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .mission-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            box-sizing: border-box;
            max-width: 100%;
        }

        .mission-card:hover,
        .mission-card.active {
            border-color: var(--emerald-glow);
            background: rgba(16, 185, 129, 0.05);
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.1);
            transform: scale(1.02);
        }

        .mission-card.briefed {
            border-right: 4px solid var(--emerald-glow);
        }

        .mission-card.briefed::before {
            content: 'BRIEFED';
            position: absolute;
            bottom: 45px;
            right: 15px;
            font-size: 0.55rem;
            color: var(--emerald-glow);
            font-weight: 900;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        .mission-card .ident {
            font-size: 1.25rem;
            font-weight: 900;
            color: #fff;
            margin-bottom: 0.25rem;
        }

        .mission-card .route {
            font-family: var(--font-mono);
            color: var(--emerald-glow);
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        /* --- TACTICAL VIEWPORT (GLOBE) --- */
        .fec-viewport {
            flex: 1;
            position: relative;
            background: #000;
            overflow: hidden;
        }

        #globe-canvas {
            width: 100%;
            height: 100%;
            transform: translateX(-40px); /* Subtle shift to the left */
        }

        /* --- HUD: MISSION INFO (TOP RIGHT) --- */
        .fec-hud {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 320px;
            max-height: calc(100vh - 220px);
            background: var(--panel-bg);
            border: 1px solid rgba(16, 185, 129, 0.4);
            border-radius: 12px;
            padding: 1.25rem;
            font-family: var(--font-mono);
            color: var(--emerald-glow);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
            z-index: 200;
            overflow-y: auto;
            pointer-events: auto;
        }

        .hud-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            font-weight: 900;
            border-bottom: 1px solid var(--emerald-dim);
            padding-bottom: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .hud-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            padding: 0.35rem 0;
            border-bottom: 1px solid rgba(16, 185, 129, 0.05);
        }

        .hud-row .label {
            color: var(--emerald-glow);
            opacity: 0.8;
        }

        .hud-row .value {
            font-weight: 700;
        }

        /* --- FOOTER: MISSION PROGRESS --- */
        .fec-progress {
            position: absolute;
            bottom: 1.5rem; /* Lowered slightly */
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 1200px;
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.25rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(15px);
            z-index: 200;
        }

        /* --- MOBILE OPTIMIZATION (HUD-LITE) --- */
        @media (max-width: 991px) {
            .fec-wrapper {
                flex-direction: column;
                height: auto !important;
                overflow-y: auto !important;
            }

            .fec-sidebar {
                width: 100% !important;
                height: auto !important;
                min-height: 50vh;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }

            .fec-viewport,
            #fec-route-box {
                display: none !important;
            }

            .fec-hud {
                position: relative !important;
                top: 0 !important;
                right: 0 !important;
                width: 100% !important;
                margin-top: 1rem;
                border-radius: 12px;
                background: rgba(2, 6, 23, 0.95);
            }

            .fec-progress {
                position: relative !important;
                bottom: 0 !important;
                left: 0 !important;
                transform: none !important;
                width: 100% !important;
                margin-top: 1rem;
                border-radius: 12px;
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .progress-track {
                width: 100% !important;
                margin: 1rem 0;
            }

            body,
            .wrapper,
            .main-content-wrapper {
                overflow: auto !important;
                /* Allow scroll on mobile */
            }
        }

        .progress-track {
            flex: 1;
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
            margin: 0 3.5rem;
            position: relative;
        }

        .plane-node {
            position: absolute;
            top: 50%;
            left: 20%;
            transform: translate(-50%, -50%) rotate(45deg);
            background: var(--slate-dark);
            border: 1px solid var(--emerald-glow);
            padding: 8px;
            border-radius: 50%;
            color: var(--emerald-glow);
            box-shadow: 0 0 15px var(--emerald-dim);
        }

        .airport-node {
            text-align: center;
        }

        .airport-node .icao {
            font-size: 1.75rem;
            font-weight: 900;
            color: #fff;
            line-height: 1;
        }

        .airport-node .label {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 800;
            margin-top: 0.3rem;
        }

        /* --- SITE FOOTER FIX --- */
        .global-footer-wrap {
            position: relative;
            z-index: 300;
            background: #000;
        }
    </style>

    <div class="fec-wrapper">
        <!-- Sidebar -->
        <aside class="fec-sidebar">
            <h2>Active Assignments</h2>
            <div class="mission-list">
                @foreach($flights as $flight)
                    <div class="mission-card @if($flight->simbrief) briefed @endif" onclick="targetFlight('{{ $flight->id }}')"
                        data-id="{{ $flight->id }}" data-dpt-lat="{{ $flight->dpt_airport->lat }}"
                        data-dpt-lon="{{ $flight->dpt_airport->lon }}" data-arr-lat="{{ $flight->arr_airport->lat }}"
                        data-arr-lon="{{ $flight->arr_airport->lon }}" data-ident="{{ $flight->ident }}"
                        data-dpt-icao="{{ $flight->dpt_airport_id }}" data-arr-icao="{{ $flight->arr_airport_id }}"
                        data-alt="{{ $flight->alt_airport_id ?? 'NONE' }}" data-route="{{ $flight->route ?? 'DIRECT' }}"
                        data-dist="{{ $flight->distance->nmi ?? 0 }} NM"
                        data-time="{{ floor($flight->flight_time / 60) }}h {{ $flight->flight_time % 60 }}m"
                        data-subfleets="{{ $flight->subfleets->pluck('type')->join(' / ') }}"
                        data-briefed="{{ $flight->simbrief ? 'true' : 'false' }}"
                        data-aircraft="{{ $flight->aircraft->registration ?? 'ANY FLEET' }}"
                        data-active-pirep-id="{{ $active_pireps[$flight->id] ?? '' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="ident">{{ $flight->ident }}</span>
                            <button class="btn btn-sm btn-outline-danger border-0 remove_bid_btn"
                                data-flight-id="{{ $flight->id }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                        <div class="route">{{ $flight->dpt_airport_id }} <i class="fas fa-chevron-right mx-2"
                                style="font-size: 0.7rem;"></i> {{ $flight->arr_airport_id }}</div>

                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('frontend.simbrief.generate') }}?flight_id={{ $flight->id }}"
                                class="btn btn-sm btn-outline-warning w-100 fw-bold" style="font-size: 0.65rem;">BRIEFING</a>
                            <a href="{{ route('frontend.pireps.create') }}?flight_id={{ $flight->id }}"
                                class="btn btn-sm btn-outline-info w-100 fw-bold" style="font-size: 0.65rem;">PIREP</a>
                            <button class="btn btn-sm btn-outline-danger w-100 fw-bold remove_bid_btn"
                                data-flight-id="{{ $flight->id }}" style="font-size: 0.65rem;">CANCEL</button>
                        </div>
                    </div>
                @endforeach

                @if($flights->count() === 0)
                    <div class="text-center mt-5 text-muted">
                        <i class="fas fa-plane-slash d-block mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="small fw-bold">NO ACTIVE BIDS</p>
                        <a href="{{ route('frontend.flights.index') }}" class="btn btn-sm btn-success px-4 fw-bold">BROWSE
                            FLIGHTS</a>
                    </div>
                @endif
            </div>
        </aside>

        <!-- Tactical Viewport -->
        <main class="fec-viewport">
            <div id="globe-canvas"></div>

            <!-- HUD: Flight Info (Top Right) -->
            <div class="fec-hud" id="fec-hud" style="display: none;">
                <div class="hud-header">
                    <span>MISSION DATA RECOVERY</span>
                    <span id="hud-time">--:--:-- UTC</span>
                </div>
                <div class="hud-row"><span class="label">IDENT:</span> <span class="value" id="hud-ident">--</span></div>
                <div class="hud-row"><span class="label">DPT / ARR:</span> <span class="value"><span id="hud-dpt">--</span>
                        / <span id="hud-arr">--</span></span></div>
                <div class="hud-row"><span class="label">DIV DEST:</span> <span class="value" id="hud-alt"
                        style="color: #fbbf24;">--</span></div>
                <div class="hud-row"><span class="label">FLEET:</span> <span class="value" id="hud-fleet">--</span></div>

                <div class="mt-3 border-top border-secondary pt-2"></div>
                <div class="hud-row"><span class="label">DISTANCE:</span> <span class="value" id="hud-dist">--</span></div>
                <div class="hud-row"><span class="label">ETE:</span> <span class="value" id="hud-ete">--</span></div>
                <div class="hud-row"><span class="label">AIRCRAFT:</span> <span class="value" id="hud-aircraft">--</span>
                </div>

                <div class="mt-3 border-top border-secondary pt-2"></div>
                <div class="hud-row"><span class="label">WEATHER:</span> <span class="value small" id="hud-metar"
                        style="font-size: 0.65rem;">FETCHING METAR...</span></div>

                <div class="mt-3 border-top border-secondary pt-2"></div>
                <div class="hud-row"><span class="label">STATUS:</span> <span class="value"
                        style="color: var(--emerald-glow);">ACTIVE ASSIGNMENT</span></div>
            </div>

            <!-- Tactical Route Box -->
            <div id="fec-route-box"
                style="display: none; position: absolute; bottom: 12rem; left: 2rem; background: var(--panel-bg); border: 1px solid var(--border-color); padding: 1rem; border-radius: 8px; width: 300px; z-index: 200; backdrop-filter: blur(10px);">
                <div
                    style="font-size: 0.6rem; color: var(--emerald-glow); margin-bottom: 0.5rem; font-weight: 800; text-transform: uppercase;">
                    Tactical Route String</div>
                <div id="hud-route"
                    style="font-family: var(--font-mono); font-size: 0.75rem; color: #fff; line-height: 1.4; word-break: break-all;">
                    --</div>
            </div>

            <!-- Progress Footer -->
            <footer class="fec-progress" id="fec-footer" style="display: none;">
                <div class="airport-node">
                    <div class="icao" id="footer-dpt">--</div>
                    <div class="label">DEPARTURE</div>
                </div>

                <div class="progress-track">
                    <div class="plane-node" id="footer-plane">
                        <i class="fas fa-plane"></i>
                    </div>
                </div>

                <div class="airport-node">
                    <div class="icao" id="footer-arr">--</div>
                    <div class="label">ARRIVAL</div>
                </div>

                <div class="ms-5 text-end" style="min-width: 200px;">
                    <div style="font-weight: 900; font-size: 1.1rem;" id="footer-ident">--</div>
                    <div style="color: var(--emerald-glow); font-size: 0.75rem; font-weight: 800; font-family: var(--font-mono);"
                        id="footer-ac">--</div>
                    <div style="color: #64748b; font-size: 0.65rem; font-weight: 700; font-family: var(--font-mono); text-transform: uppercase; letter-spacing: .05em;"
                        id="footer-progress-status">Not departed</div>
                </div>
            </footer>
        </main>
    </div>

    <!-- REMOVE BID MODAL -->
    <div class="modal fade" id="removeBidModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold" style="color: var(--emerald-glow);">CANCEL ASSIGNMENT</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <p>Are you sure you want to remove this flight from your list? This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-light btn-sm fw-bold" data-bs-dismiss="modal">KEEP
                        ASSIGNMENT</button>
                    <button type="button" class="btn btn-danger btn-sm fw-bold" id="confirmRemoveBidBtn">REMOVE NOW</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/three"></script>
    <script src="https://unpkg.com/globe.gl"></script>

    <script>
        let globe = null;

        document.addEventListener('DOMContentLoaded', function () {
            initGlobe();
            updateTime();
            setInterval(updateTime, 1000);

            const firstItem = document.querySelector('.mission-card');
            if (firstItem) firstItem.click();

            // --- BID REMOVAL LOGIC ---
            let flightToRemove = null;
            const confirmBtn = document.getElementById('confirmRemoveBidBtn');
            const modalEl = document.getElementById('removeBidModal');
            const removeModal = modalEl ? new bootstrap.Modal(modalEl) : null;

            document.querySelectorAll('.remove_bid_btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    flightToRemove = this.getAttribute('data-flight-id');
                    if (removeModal) removeModal.show();
                });
            });

            if (confirmBtn) {
                confirmBtn.addEventListener('click', async function () {
                    if (!flightToRemove) return;
                    const originalText = confirmBtn.innerText;
                    confirmBtn.innerText = 'REMOVING...';
                    confirmBtn.disabled = true;

                    try {
                        const response = await fetch(window.location.origin + '/api/user/bids', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-API-KEY': document.querySelector('meta[name="api-key"]')?.content,
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: JSON.stringify({ _method: 'DELETE', flight_id: flightToRemove })
                        });
                        if (response.ok) location.reload();
                        else throw new Error('Failed');
                    } catch (e) {
                        confirmBtn.innerText = originalText;
                        confirmBtn.disabled = false;
                        alert('Error removing bid.');
                    }
                });
            }
        });

        function updateTime() {
            document.getElementById('hud-time').innerText = new Date().toISOString().substr(11, 8) + ' UTC';
        }

        // --- LIVE PROGRESS (bid whose flight is currently being flown via SmartCARS) ---
        let progressPollInterval = null;
        let progressPirepId = null;

        function updateProgressFooter(activePirepId) {
            if (progressPollInterval) {
                clearInterval(progressPollInterval);
                progressPollInterval = null;
            }
            progressPirepId = activePirepId || null;

            const statusEl = document.getElementById('footer-progress-status');

            if (!progressPirepId) {
                const planeEl = document.getElementById('footer-plane');
                if (planeEl) planeEl.style.left = '20%';
                if (statusEl) statusEl.textContent = 'Not departed';
                return;
            }

            if (statusEl) statusEl.textContent = 'En route…';
            pollFlightProgress();
            progressPollInterval = setInterval(pollFlightProgress, 10000);
        }

        async function pollFlightProgress() {
            const pirepId = progressPirepId;
            if (!pirepId) return;

            try {
                const response = await fetch(window.location.origin + `/api/pireps/${pirepId}/acars/geojson`, {
                    headers: {
                        'X-API-KEY': document.querySelector('meta[name="api-key"]')?.content,
                    }
                });
                if (!response.ok) throw new Error('geojson fetch failed');
                if (pirepId !== progressPirepId) return; // user switched flights while this was in flight

                const body = await response.json();
                const route = body.data;

                const planeEl = document.getElementById('footer-plane');
                const statusEl = document.getElementById('footer-progress-status');
                if (!planeEl) return;

                const features = (route && route.planned_points && route.planned_points.features) || [];
                const aircraft = route && route.position;

                if (features.length > 0 && aircraft && aircraft.lat) {
                    let passedCount = 0;
                    features.forEach((feature, idx) => {
                        if (idx >= features.length - 1) return;
                        const coords = feature.geometry && feature.geometry.coordinates;
                        const next = features[idx + 1].geometry && features[idx + 1].geometry.coordinates;
                        if (!coords || !next) return;
                        const dx1 = coords[1] - aircraft.lat, dy1 = coords[0] - aircraft.lon;
                        const dx2 = next[1] - aircraft.lat, dy2 = next[0] - aircraft.lon;
                        if ((dx1 * dx1 + dy1 * dy1) > (dx2 * dx2 + dy2 * dy2)) passedCount++;
                    });
                    const progress = (passedCount / features.length) * 100;
                    planeEl.style.left = `${Math.min(Math.max(progress, 5), 95)}%`;
                    if (statusEl) statusEl.textContent = `En route • ${Math.round(progress)}%`;
                } else if (aircraft && aircraft.lat) {
                    if (statusEl) statusEl.textContent = 'En route';
                }
            } catch (e) {
                // Keep the last known position/status on the banner rather than resetting it on a transient error
            }
        }

        function initGlobe() {
            const container = document.getElementById('globe-canvas');
            globe = Globe()(container)
                .globeImageUrl('https://unpkg.com/three-globe/example/img/earth-blue-marble.jpg')
                .bumpImageUrl('https://unpkg.com/three-globe/example/img/earth-topology.png')
                .backgroundColor('#000')
                .showAtmosphere(true)
                .atmosphereColor('#3b82f6')
                .atmosphereAltitude(0.25)
                .width(container.offsetWidth)
                .height(container.offsetHeight);

            globe.controls().autoRotate = true;
            globe.controls().autoRotateSpeed = 0.5;
            globe.controls().enableZoom = true; // Restored zoom functionality


            window.addEventListener('resize', () => {
                globe.width(container.offsetWidth).height(container.offsetHeight);
            });
        }

        function targetFlight(id) {
            const item = document.querySelector(`.mission-card[data-id="${id}"]`);
            if (!item) return;

            document.querySelectorAll('.mission-card').forEach(c => c.classList.remove('active'));
            item.classList.add('active');

            const d = item.dataset;
            const dpt = { lat: parseFloat(d.dptLat), lon: parseFloat(d.dptLon), icao: d.dptIcao };
            const arr = { lat: parseFloat(d.arrLat), lon: parseFloat(d.arrLon), icao: d.arrIcao };

            // HUD & Footer Update
            document.getElementById('fec-hud').style.display = 'block';
            document.getElementById('fec-footer').style.display = 'flex';
            document.getElementById('hud-ident').innerText = d.ident;
            document.getElementById('hud-dpt').innerText = dpt.icao;
            document.getElementById('hud-arr').innerText = arr.icao;
            document.getElementById('hud-alt').innerText = d.alt;
            document.getElementById('hud-fleet').innerText = d.subfleets;
            document.getElementById('hud-dist').innerText = d.dist;
            document.getElementById('hud-ete').innerText = d.time;
            document.getElementById('hud-aircraft').innerText = d.aircraft;
            document.getElementById('hud-route').innerText = d.route;
            document.getElementById('fec-route-box').style.display = 'block';

            // Simbrief indicator
            document.getElementById('hud-ident').style.color = (d.briefed === 'true') ? 'var(--emerald-glow)' : '#fff';

            // METAR Fetch
            fetchMetar(dpt.icao);

            document.getElementById('footer-dpt').innerText = dpt.icao;
            document.getElementById('footer-arr').innerText = arr.icao;
            document.getElementById('footer-ident').innerText = d.ident;
            document.getElementById('footer-ac').innerText = d.aircraft;

            updateProgressFooter(d.activePirepId);

            // Globe Arcs Update - Solid Permanent Path
            globe.arcsData([{ startLat: dpt.lat, startLng: dpt.lon, endLat: arr.lat, endLng: arr.lon, color: '#10b981' }])
                .arcColor('color')
                .arcStroke(0.5);

            // Zoom into the departure airport smoothly
            globe.pointOfView({ lat: dpt.lat, lng: dpt.lon, altitude: 1.8 }, 2000);

            // Add Interactive Labels
            const labelData = [
                { lat: dpt.lat, lng: dpt.lon, text: `DPT: ${dpt.icao}`, color: '#10b981', size: 2.5 },
                { lat: arr.lat, lng: arr.lon, text: `ARR: ${arr.icao}`, color: '#fbbf24', size: 2.5 }
            ];

            globe.labelsData(labelData)
                .labelText('text')
                .labelSize('size')
                .labelColor('color')
                .labelDotRadius(0.6)
                .labelAltitude(0.01)
                .onLabelClick(label => {
                    // Tactical Inspection Zoom
                    globe.pointOfView({ lat: label.lat, lng: label.lng, altitude: 0.5 }, 1500);
                });
        }

        async function fetchMetar(icao) {
            const el = document.getElementById('hud-metar');
            el.innerText = 'FETCHING...';
            try {
                const res = await fetch(`https://metar.vatsim.net/metar.php?id=${icao}`);
                const data = await res.text();
                el.innerText = data || 'NO DATA AVAILABLE';
            } catch (e) {
                el.innerText = 'METAR OFFLINE';
            }
        }
    </script>
@endsection