<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" integrity="sha512-Zcn6bjR/8RZbLEpLIeOwNtzREBAJnUKESxces60Mpoj+2okopSAcSUIUOseddDm0cxnGQzxIR7vJgsLZbdLE3w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* --- TACTICAL LIVE MAP STYLES --- */
    #livemap-container {
        display: flex;
        width: 100%;
        height: 100%;
        position: relative;
    }

    /* --- SIDEBAR: MISSION LIST --- */
    .fec-sidebar {
        width: 380px;
        min-width: 380px;
        background: #010409;
        border-right: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        padding: 1.5rem;
        z-index: 100;
        overflow: hidden;
        flex-shrink: 0;
        transition: width 0.3s cubic-bezier(0.4,0,0.2,1),
                    min-width 0.3s cubic-bezier(0.4,0,0.2,1),
                    padding 0.3s cubic-bezier(0.4,0,0.2,1),
                    opacity 0.25s ease,
                    border-right-width 0.3s;
    }

    .fec-sidebar.sidebar-collapsed {
        width: 0 !important;
        min-width: 0 !important;
        padding: 0 !important;
        opacity: 0;
        border-right-width: 0;
    }

    /* Sidebar toggle tab — sits on the left edge of the map viewport */
    .sidebar-toggle-tab {
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        z-index: 1100;
        background: rgba(2,6,23,0.92);
        border: 1px solid rgba(16,185,129,0.45);
        border-left: none;
        border-radius: 0 8px 8px 0;
        width: 22px;
        height: 64px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #10b981;
        transition: background 0.2s, color 0.2s, width 0.2s;
        padding: 0;
    }
    .sidebar-toggle-tab:hover {
        background: rgba(16,185,129,0.12);
        color: #fff;
        width: 26px;
    }
    .sidebar-toggle-tab i {
        font-size: 0.65rem;
        transition: transform 0.3s;
    }

    .fec-sidebar h2 {
        color: var(--emerald-glow);
        font-weight: 900;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        margin-bottom: 0.75rem;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1rem;
    }

    /* Stats Row */
    .sidebar-stats {
        display: flex;
        justify-content: space-around;
        padding: 0.75rem 0;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
        gap: 0.5rem;
    }

    .stat-item {
        text-align: center;
        flex: 1;
    }

    .stat-value {
        font-size: 1.4rem;
        font-weight: 900;
        color: #fff;
        line-height: 1;
        font-family: var(--font-mono);
    }

    .stat-label {
        font-size: 0.55rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: rgba(16, 185, 129, 0.7);
        margin-top: 0.25rem;
    }

    .mission-list {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Scrollbar */
    .mission-list::-webkit-scrollbar { width: 6px; }
    .mission-list::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
    .mission-list::-webkit-scrollbar-thumb { background: rgba(16,185,129,0.3); border-radius: 3px; }
    .mission-list::-webkit-scrollbar-thumb:hover { background: rgba(16,185,129,0.6); }

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
    }

    .mission-card:hover,
    .mission-card.active {
        border-color: var(--emerald-glow);
        background: rgba(16, 185, 129, 0.05);
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.1);
        transform: scale(1.02);
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
        margin-bottom: 0.5rem;
    }

    .mission-card .status {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 12px;
        background: rgba(16, 185, 129, 0.2);
        color: var(--emerald-glow);
        display: inline-block;
    }

    /* --- TACTICAL VIEWPORT (MAP) --- */
    .fec-viewport {
        flex: 1;
        position: relative;
        background: #000;
        overflow: hidden;
    }

    #map {
        width: 100%;
        height: 100%;
        background: #000;
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
        z-index: 1000;
        overflow-y: auto;
        pointer-events: auto;
        display: none;
    }

    .hud-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        font-weight: 900;
        border-bottom: 1px solid var(--emerald-dim);
        padding-bottom: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .hud-close-btn {
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
        opacity: 0.6;
        transition: 0.3s;
    }
    .hud-close-btn:hover { opacity: 1; color: #ff4444; }

    .hud-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        padding: 0.35rem 0;
        border-bottom: 1px solid rgba(16, 185, 129, 0.05);
    }

    .hud-row .label { color: var(--emerald-glow); opacity: 0.8; }
    .hud-row .value { font-weight: 700; color: #fff;}

    .sidebar-tabs {
        display: flex;
        margin-top: 15px;
        border-bottom: 1px solid var(--emerald-dim);
    }
    .sidebar-tab {
        flex: 1;
        text-align: center;
        padding: 8px 0;
        cursor: pointer;
        color: #888;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s;
    }
    .sidebar-tab:hover { color: #fff; }
    .sidebar-tab.active {
        color: var(--emerald-glow);
        border-bottom: 2px solid var(--emerald-glow);
    }

    /* --- FOOTER: MISSION PROGRESS --- */
    .fec-progress {
        position: absolute;
        bottom: 1.5rem;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        max-width: 1200px;
        background: var(--panel-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.25rem 2.5rem;
        display: none;
        align-items: center;
        justify-content: space-between;
        backdrop-filter: blur(15px);
        z-index: 1000;
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
        left: 50%;
        transform: translate(-50%, -50%) rotate(45deg);
        background: var(--slate-dark);
        border: 1px solid var(--emerald-glow);
        padding: 8px;
        border-radius: 50%;
        color: var(--emerald-glow);
        box-shadow: 0 0 15px var(--emerald-dim);
        transition: left 1s ease-in-out;
    }

    .airport-node { text-align: center; }
    .airport-node .icao { font-size: 1.75rem; font-weight: 900; color: #fff; line-height: 1; }
    .airport-node .label { font-size: 0.7rem; color: rgba(255, 255, 255, 0.5); font-weight: 800; margin-top: 0.3rem; }

    /* Map Controls */
    .map-controls-top {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 1000;
    }
    .map-btn {
        background: rgba(2, 6, 23, 0.9);
        border: 1px solid rgba(16, 185, 129, 0.4);
        border-radius: 6px;
        padding: 8px 15px;
        cursor: pointer;
        color: var(--emerald-glow);
        font-weight: 900;
        font-family: var(--font-mono);
        backdrop-filter: blur(5px);
        transition: all 0.2s;
        font-size: 0.8rem;
    }
    .map-btn:hover { background: rgba(16, 185, 129, 0.1); box-shadow: 0 0 10px rgba(16,185,129,0.2); }

    /* Leaflet Overrides */
    .aircraft-marker-icon { background: transparent !important; border: none !important; text-align: center; color: var(--emerald-glow) !important; }
    .waypoint-marker-icon { background: transparent !important; border: none !important; display: flex; align-items: center; white-space: nowrap; }
    .waypoint-dot { width: 12px; height: 12px; clip-path: polygon(50% 0%, 0% 100%, 100% 100%); background: #fbbf24; border: none; margin-right: 4px; }
    .waypoint-label { color: #fbbf24; font-weight: 900; font-size: 0.65rem; font-family: var(--font-mono); text-shadow: 1px 1px 2px #000; }
    .leaflet-container { background: #000 !important; font-family: var(--font-mono); }

    /* Lists */
    .log-item { display: flex; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .log-time { font-size: 0.7rem; color: #888; width: 60px; font-weight: 500; }
    .log-msg { font-size: 0.75rem; color: #ddd; flex-grow: 1; }
    .waypoint-item { display: flex; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05); align-items: center; }
    .wp-icon { width: 30px; text-align: center; color: #666; font-size: 0.9rem; }
    .wp-name { font-weight: bold; color: #ccc; font-size: 0.85rem; }
    .waypoint-item.passed .wp-icon { color: var(--emerald-glow); }
    .waypoint-item.passed .wp-name { color: #888; text-decoration: line-through; }
    .waypoint-item.active .wp-icon { color: #fbbf24; font-size: 1.1rem; }
    .waypoint-item.active .wp-name { color: #fff; }

    @media (max-width: 991px) {
        #livemap-container { flex-direction: column; }
        .fec-sidebar {
            width: 100% !important;
            min-width: 100% !important;
            height: 350px;
            border-right: none;
            border-bottom: 1px solid var(--border-color);
            transition: height 0.3s cubic-bezier(0.4,0,0.2,1),
                        padding 0.3s cubic-bezier(0.4,0,0.2,1),
                        opacity 0.25s ease;
        }
        .fec-sidebar.sidebar-collapsed {
            width: 100% !important;
            min-width: 100% !important;
            height: 0 !important;
            padding: 0 !important;
            opacity: 0;
            border-bottom-width: 0;
        }
        .sidebar-toggle-tab {
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            border: 1px solid rgba(16,185,129,0.45);
            border-top: none;
            border-radius: 0 0 8px 8px;
            width: 64px;
            height: 22px;
        }
        .fec-hud { position: fixed; top: 10px; right: 10px; left: 10px; width: auto; max-height: 50vh; z-index: 2000; }
        .fec-progress { display: none !important; }
    }
</style>

<div id="livemap-container">
    <!-- Sidebar List -->
    <aside class="fec-sidebar" id="fec-sidebar">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;padding-bottom:1rem;border-bottom:1px solid var(--border-color);">
            <h2 style="margin:0;border:none;padding:0;">Live Radar Feed</h2>
            <button type="button" id="btn-sidebar-hamburger" title="Collapse panel" style="background:none;border:none;color:#10b981;cursor:pointer;padding:4px 6px;border-radius:5px;display:flex;align-items:center;justify-content:center;transition:background 0.2s;" onmouseover="this.style.background='rgba(16,185,129,0.1)'" onmouseout="this.style.background='none'">
                <i class="fas fa-bars" style="font-size:1rem;"></i>
            </button>
        </div>

        <!-- Live Stats -->
        <div class="sidebar-stats">
            <div class="stat-item">
                <div class="stat-value" id="stat-live">--</div>
                <div class="stat-label">LIVE NOW</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="stat-today">--</div>
                <div class="stat-label">TODAY</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="stat-airports">--</div>
                <div class="stat-label">AIRPORTS</div>
            </div>
        </div>

        <div class="mission-list" id="flights-list-container">
            <div class="text-center p-4" id="no-flights-msg" style="color: var(--emerald-glow); font-family: var(--font-mono); font-size: 0.8rem; opacity: 0.7;">
                <i class="fas fa-radar fa-spin me-2 mb-3" style="font-size: 2rem;"></i><br>
                SCANNING TRANSPONDERS...
            </div>
        </div>
    </aside>

    <!-- Tactical Viewport -->
    <main class="fec-viewport">
        <!-- Sidebar pull-tab (always visible on the left edge of the map) -->
        <button type="button" class="sidebar-toggle-tab" id="btn-sidebar-tab" title="Toggle panel">
            <i class="fas fa-chevron-left" id="sidebar-tab-icon"></i>
        </button>

        <div class="map-controls-top">
            <button type="button" class="map-btn" onclick="window.location.reload()">
                <i class="fas fa-sync-alt me-1"></i> RADAR
            </button>
            <button type="button" class="map-btn ms-2" id="btn-toggle-weather">
                <i class="fas fa-cloud-showers-heavy me-1"></i> WX RADAR
            </button>
            <button type="button" class="map-btn ms-2" id="btn-toggle-daynight">
                <i class="fas fa-moon me-1"></i> DAY/NIGHT
            </button>
            <button type="button" class="map-btn ms-2" id="btn-toggle-vatsim">
                <i class="fas fa-broadcast-tower me-1"></i> VATSIM ATC
            </button>
            <button type="button" class="map-btn ms-2" id="btn-toggle-mapstyle" style="color:#10b981;border-color:rgba(16,185,129,.6);">
                <i class="fas fa-moon me-1"></i> DARK
            </button>
            <button type="button" class="map-btn ms-2" id="btn-enlarge">
                <i class="fas fa-expand me-1"></i> ENLARGE
            </button>
        </div>
        <div id="map"></div>

        <!-- HUD: Flight Info (Overlaid) -->
        <div class="fec-hud" id="active-flight-view">
            <div class="hud-header">
                <span>TELEMETRY DATA</span>
                <div>
                    <button class="hud-close-btn me-3" id="btn-lock-on" style="color: #a0a0a0;" title="Lock Camera"><i class="fas fa-crosshairs"></i></button>
                    <button class="hud-close-btn" id="btn-close-hud"><i class="fas fa-times"></i></button>
                </div>
            </div>

            <div class="hud-row"><span class="label">IDENT:</span> <span class="value" id="hud-ident" style="color: var(--emerald-glow);">AJM---</span></div>
            <div class="hud-row"><span class="label">STATUS:</span> <span class="value" id="hud-status">---</span></div>

            <div class="mt-2 border-top border-secondary pt-2"></div>
            <div class="hud-row"><span class="label">ALTITUDE:</span> <span class="value" id="hud-alt">--- ft</span></div>
            <div class="hud-row"><span class="label">GROUND SPD:</span> <span class="value" id="hud-gs">--- kts</span></div>
            <div class="hud-row"><span class="label">HEADING:</span> <span class="value" id="hud-hdg">---&deg;</span></div>
            <div class="hud-row"><span class="label">FLIGHT TIME:</span> <span class="value" id="hud-time">---</span></div>

            <div class="mt-2 border-top border-secondary pt-2"></div>
            <div class="hud-row"><span class="label">ROUTE:</span> <span class="value" id="hud-route" style="font-size: 0.65rem; word-break: break-all;">---</span></div>

            <div class="sidebar-tabs">
                <div class="sidebar-tab active" data-target="#tab-waypoints">WAYPOINTS</div>
                <div class="sidebar-tab" data-target="#tab-logs">ACARS LOG</div>
                <div class="sidebar-tab" data-target="#tab-telemetry">TELEMETRY</div>
            </div>

            <div class="scrollable-list mt-2" id="tab-waypoints" style="max-height: 200px; overflow-y: auto;">
                <!-- Waypoints inserted here via JS -->
            </div>

            <div class="scrollable-list mt-2" id="tab-logs" style="display:none; max-height: 200px; overflow-y: auto;">
                <!-- Logs inserted here via JS -->
            </div>

            <div class="scrollable-list mt-2" id="tab-telemetry" style="display:none; height: 200px; position: relative;">
                <canvas id="telemetry-chart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>

        <!-- Progress Footer (Overlaid) -->
        <footer class="fec-progress" id="fec-footer">
            <div class="airport-node">
                <div class="icao" id="footer-dpt">---</div>
                <div class="label">DEPARTURE</div>
            </div>

            <div class="progress-track">
                <div class="plane-node" id="footer-plane">
                    <i class="fas fa-plane"></i>
                </div>
            </div>

            <div class="airport-node">
                <div class="icao" id="footer-arr">---</div>
                <div class="label">ARRIVAL</div>
            </div>

            <div class="ms-5 text-end" style="min-width: 150px;">
                <div style="font-weight: 900; font-size: 1.1rem; color: #fff;" id="footer-ident">---</div>
                <div style="color: var(--emerald-glow); font-size: 0.75rem; font-weight: 800; font-family: var(--font-mono);" id="footer-status">---</div>
            </div>
        </footer>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ── Tab switching in HUD ───────────────────────────────────────────────
        document.querySelectorAll('.sidebar-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.sidebar-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.scrollable-list').forEach(el => el.style.display = 'none');
                document.querySelector(this.getAttribute('data-target')).style.display = 'block';
            });
        });

        // ── Sidebar collapse ──────────────────────────────────────────────────
        const sidebar      = document.getElementById('fec-sidebar');
        const tabIcon      = document.getElementById('sidebar-tab-icon');
        const hamburgerBtn = document.getElementById('btn-sidebar-hamburger');

        function toggleSidebar() {
            const collapsed = sidebar.classList.toggle('sidebar-collapsed');
            if (tabIcon) {
                tabIcon.className = collapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
            }
            // Let Leaflet recalculate its container size after the CSS transition
            setTimeout(() => window.dispatchEvent(new Event('resize')), 320);
        }

        if (sidebar) {
            document.getElementById('btn-sidebar-tab')?.addEventListener('click', toggleSidebar);
            hamburgerBtn?.addEventListener('click', toggleSidebar);
        }

        // ── Close HUD ─────────────────────────────────────────────────────────
        document.getElementById('btn-close-hud').addEventListener('click', function() {
            document.getElementById('active-flight-view').style.display = 'none';
            document.getElementById('fec-footer').style.display = 'none';
            if (window.liveMapDeselect) window.liveMapDeselect();
        });

        // ── Enlarge / Fullscreen toggle ───────────────────────────────────────
        const enlargeBtn = document.getElementById('btn-enlarge');
        const fecWrapper = document.querySelector('.fec-wrapper');

        if (enlargeBtn && fecWrapper) {
            enlargeBtn.addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    fecWrapper.requestFullscreen().catch(err => {
                        console.warn('Fullscreen not available:', err);
                    });
                } else {
                    document.exitFullscreen();
                }
            });

            document.addEventListener('fullscreenchange', () => {
                if (document.fullscreenElement) {
                    enlargeBtn.innerHTML = '<i class="fas fa-compress me-1"></i> EXIT';
                    enlargeBtn.style.color = '#fbbf24';
                    enlargeBtn.style.borderColor = 'rgba(251,191,36,0.6)';
                    fecWrapper.style.height = '100vh';
                } else {
                    enlargeBtn.innerHTML = '<i class="fas fa-expand me-1"></i> ENLARGE';
                    enlargeBtn.style.color = '';
                    enlargeBtn.style.borderColor = '';
                    fecWrapper.style.height = '';
                }
                // Let Leaflet know the container size changed
                setTimeout(() => window.dispatchEvent(new Event('resize')), 200);
            });
        }

        // ── Populate server-side stats (flightsToday, activeAirports) ─────────
        if (window.liveMapStats) {
            const s = window.liveMapStats;
            const statToday = document.getElementById('stat-today');
            const statAirports = document.getElementById('stat-airports');
            if (statToday) statToday.textContent = s.flightsToday;
            if (statAirports) statAirports.textContent = s.activeAirports;
        }

        // ── Render the map ────────────────────────────────────────────────────
        ajv.map.render_live_map({
            center: ['{{ $center[0] }}', '{{ $center[1] }}'],
            zoom: '{{ $zoom }}',
            refresh_interval: {{ setting('acars.update_interval', 60) }},
            units: '{{ setting('units.distance') }}',
            flown_route_color: '#10b981',
            planned_route_color: '#fbbf24',
            leafletOptions: {
                scrollWheelZoom: true,
                providers: {
                    'CartoDB.DarkMatter': {}
                }
            }
        });
    });
</script>
