import { LatLng } from "leaflet/dist/leaflet-src.esm";
import leaflet from "leaflet";
import jQuery from "jquery";
import draw_base_map from "./base_map";
import request from "../request";

window.$ = jQuery;
window.jquery = jQuery;

export default (_opts) => {
  const opts = Object.assign(
    {
      center: [29.98139, -95.33374],
      refresh_interval: 10,
      zoom: 5,
      acars_uri: "/api/acars",
      update_uri: "/api/acars/geojson",
      pirep_uri: "/api/pireps/{id}",
      render_elem: "map",
      flown_route_color: '#10b981',
      planned_route_color: '#fbbf24',
    },
    _opts
  );

  const initialZoom = Math.max(2, Math.min(Number(opts.zoom) || 5, 19));
  const centerCoords = new LatLng(Number(opts.center[0]), Number(opts.center[1]));
  const map = draw_base_map(Object.assign({}, opts, { zoom: initialZoom, center: [Number(opts.center[0]), Number(opts.center[1])] }));

  // ─── Tile Layer Toggle (Dark / Light) ─────────────────────────────────────
  // Strip whatever draw_base_map added, then manage tiles ourselves
  const _tilesToRemove = [];
  map.eachLayer(layer => { if (layer instanceof leaflet.TileLayer) _tilesToRemove.push(layer); });
  _tilesToRemove.forEach(layer => map.removeLayer(layer));

  const tileLayerDark = leaflet.tileLayer(
    'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
    { attribution: '&copy; OSM contributors &copy; CARTO', subdomains: 'abcd', maxZoom: 19, maxNativeZoom: 19 }
  );
  const tileLayerLight = leaflet.tileLayer(
    'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png',
    { attribution: '&copy; OSM contributors &copy; CARTO', subdomains: 'abcd', maxZoom: 19, maxNativeZoom: 19 }
  );

  let tileMode = 'light';
  tileLayerLight.addTo(map);

  const mapStyleBtn = document.getElementById('btn-toggle-mapstyle');
  if (mapStyleBtn) {
    mapStyleBtn.innerHTML = '<i class="fas fa-moon me-1"></i> DARK';
    mapStyleBtn.style.color = '#10b981';
    mapStyleBtn.style.borderColor = 'rgba(16,185,129,0.6)';
    mapStyleBtn.addEventListener('click', () => {
      if (tileMode === 'light') {
        map.removeLayer(tileLayerLight);
        tileLayerDark.addTo(map);
        tileMode = 'dark';
        mapStyleBtn.innerHTML = '<i class="fas fa-moon me-1"></i> DARK';
        mapStyleBtn.style.color = '#10b981';
        mapStyleBtn.style.borderColor = 'rgba(16,185,129,0.6)';
      } else {
        map.removeLayer(tileLayerDark);
        tileLayerLight.addTo(map);
        tileMode = 'light';
        mapStyleBtn.innerHTML = '<i class="fas fa-sun me-1"></i> LIGHT';
        mapStyleBtn.style.color = '#fbbf24';
        mapStyleBtn.style.borderColor = 'rgba(251,191,36,0.6)';
      }
    });
  }

  let markers_list = {};
  let waypointLayers = {};
  let layerFlights = null;
  let layerSelFlight = null;
  let layerSelPlanned = null;
  let selectedPirepId = null;
  let pannedToFlight = false;

  // ─── Lock-On Mode ─────────────────────────────────────────────────────────
  let isLockedOn = false;
  let lockedLatLng = null;

  const lockBtn = document.getElementById('btn-lock-on');
  if (lockBtn) {
    lockBtn.addEventListener('click', () => {
      isLockedOn = !isLockedOn;
      if (isLockedOn) {
        lockBtn.style.color = '#fbbf24';
        lockBtn.title = 'Lock-On ACTIVE — click to release';
        if (lockedLatLng) map.panTo(lockedLatLng);
      } else {
        lockBtn.style.color = '#a0a0a0';
        lockBtn.title = 'Lock Camera';
      }
    });
  }

  // ─── Day/Night Terminator (pure JS — no CDN dependency) ───────────────────
  let terminatorLayer = null;
  let dayNightActive = false;

  function calcSolarTerminatorPolygon() {
    const now = new Date();
    const J = now.getTime() / 86400000 + 2440587.5;
    const n = J - 2451545.0;
    const L = (280.460 + 0.9856474 * n) * Math.PI / 180;
    const g = (357.528 + 0.9856003 * n) * Math.PI / 180;
    const lam = L + (1.915 * Math.sin(g) + 0.020 * Math.sin(2 * g)) * Math.PI / 180;
    const eps = (23.439 - 0.0000004 * n) * Math.PI / 180;
    const dec = Math.asin(Math.sin(eps) * Math.sin(lam));
    const GHA = ((280.46061837 + 360.98564736629 * n) % 360) * Math.PI / 180;

    const line = [];
    for (let lngDeg = -180; lngDeg <= 180; lngDeg++) {
      const ha = lngDeg * Math.PI / 180 - GHA;
      const latRad = Math.atan2(-Math.cos(ha), Math.tan(dec));
      line.push([latRad * 180 / Math.PI, lngDeg]);
    }

    // Extend polygon to cover the night-side pole
    const nightPole = dec > 0 ? -90 : 90;
    return [...line, [nightPole, 180], [nightPole, -180]];
  }

  const dayNightBtn = document.getElementById('btn-toggle-daynight');
  if (dayNightBtn) {
    dayNightBtn.addEventListener('click', () => {
      dayNightActive = !dayNightActive;
      if (dayNightActive) {
        const coords = calcSolarTerminatorPolygon();
        terminatorLayer = leaflet.polygon(coords, {
          color: 'none',
          fillColor: '#000820',
          fillOpacity: 0.50,
          stroke: false,
          interactive: false,
        }).addTo(map);
        dayNightBtn.style.color = '#fbbf24';
        dayNightBtn.style.borderColor = 'rgba(251,191,36,0.6)';
      } else {
        if (terminatorLayer) { map.removeLayer(terminatorLayer); terminatorLayer = null; }
        dayNightBtn.style.color = '';
        dayNightBtn.style.borderColor = '';
      }
    });
  }

  // ─── Weather Radar (RainViewer) ────────────────────────────────────────────
  let weatherLayer = null;
  let weatherActive = false;

  async function loadRainViewer() {
    try {
      const resp = await fetch('https://api.rainviewer.com/public/weather-maps.json');
      const data = await resp.json();
      return { host: data.host, timestamps: data.radar.past.map(f => f.time) };
    } catch (e) {
      console.warn('RainViewer fetch failed:', e);
      return null;
    }
  }

  const weatherBtn = document.getElementById('btn-toggle-weather');
  if (weatherBtn) {
    weatherBtn.addEventListener('click', async () => {
      weatherActive = !weatherActive;
      if (weatherActive) {
        weatherBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> WX RADAR';
        const rv = await loadRainViewer();
        if (rv && rv.timestamps.length > 0) {
          const ts = rv.timestamps[rv.timestamps.length - 1];
          weatherLayer = leaflet.tileLayer(
            `${rv.host}/v2/radar/${ts}/256/{z}/{x}/{y}/4/1_1.png`,
            { opacity: 0.65, attribution: 'RainViewer' }
          ).addTo(map);
          weatherBtn.innerHTML = '<i class="fas fa-cloud-showers-heavy me-1"></i> WX ON';
          weatherBtn.style.color = '#60a5fa';
          weatherBtn.style.borderColor = 'rgba(96,165,250,0.6)';
        } else {
          weatherActive = false;
          weatherBtn.innerHTML = '<i class="fas fa-cloud-showers-heavy me-1"></i> WX RADAR';
        }
      } else {
        if (weatherLayer) { map.removeLayer(weatherLayer); weatherLayer = null; }
        weatherBtn.innerHTML = '<i class="fas fa-cloud-showers-heavy me-1"></i> WX RADAR';
        weatherBtn.style.color = '';
        weatherBtn.style.borderColor = '';
      }
    });
  }

  // ─── VATSIM ATC Layer ──────────────────────────────────────────────────────
  let vatsimLayer = null;
  let vatsimActive = false;
  let vatsimRefreshInterval = null;

  const facilityLabels = ['OBS', 'FSS', 'DEL', 'GND', 'TWR', 'APP', 'CTR'];
  const facilityColors = ['#555', '#a78bfa', '#60a5fa', '#34d399', '#fbbf24', '#f97316', '#f87171'];

  async function loadVatsimATC() {
    try {
      const resp = await fetch('https://data.vatsim.net/v3/vatsim-data.json');
      const data = await resp.json();

      if (vatsimLayer) map.removeLayer(vatsimLayer);
      vatsimLayer = leaflet.layerGroup().addTo(map);

      const controllers = (data.controllers || []).filter(c => c.facility > 0 && c.lat && c.lon);

      controllers.forEach(atc => {
        const facIdx = Math.min(atc.facility, facilityLabels.length - 1);
        const fac = facilityLabels[facIdx];
        const color = facilityColors[facIdx];
        const icon = leaflet.divIcon({
          className: '',
          html: `<div style="background:${color};color:#000;font-size:0.55rem;font-weight:900;padding:2px 5px;border-radius:3px;font-family:monospace;white-space:nowrap;box-shadow:0 0 6px ${color}80;">${fac}</div>`,
          iconSize: [30, 16],
          iconAnchor: [15, 8],
        });
        const atis = atc.text_atis ? atc.text_atis.slice(0, 3).join(' ') : '';
        leaflet.marker([atc.lat, atc.lon], { icon, zIndexOffset: -100 })
          .bindPopup(
            `<b style="color:${color};font-family:monospace">${atc.callsign}</b><br/>
            Freq: <b>${atc.frequency}</b><br/>
            ${atc.name}<br/>
            ${atis ? `<small style="color:#aaa">${atis}</small>` : ''}`
          )
          .addTo(vatsimLayer);
      });

      return controllers.length;
    } catch (e) {
      console.warn('VATSIM fetch failed:', e);
      return 0;
    }
  }

  const vatsimBtn = document.getElementById('btn-toggle-vatsim');
  if (vatsimBtn) {
    vatsimBtn.addEventListener('click', async () => {
      vatsimActive = !vatsimActive;
      if (vatsimActive) {
        vatsimBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> LOADING';
        const count = await loadVatsimATC();
        vatsimBtn.innerHTML = `<i class="fas fa-broadcast-tower me-1"></i> ATC (${count})`;
        vatsimBtn.style.color = '#a78bfa';
        vatsimBtn.style.borderColor = 'rgba(167,139,250,0.6)';
        vatsimRefreshInterval = setInterval(async () => {
          const n = await loadVatsimATC();
          vatsimBtn.innerHTML = `<i class="fas fa-broadcast-tower me-1"></i> ATC (${n})`;
        }, 120000);
      } else {
        if (vatsimLayer) { map.removeLayer(vatsimLayer); vatsimLayer = null; }
        if (vatsimRefreshInterval) { clearInterval(vatsimRefreshInterval); vatsimRefreshInterval = null; }
        vatsimBtn.innerHTML = '<i class="fas fa-broadcast-tower me-1"></i> VATSIM ATC';
        vatsimBtn.style.color = '';
        vatsimBtn.style.borderColor = '';
      }
    });
  }

  // ─── Telemetry Chart (Chart.js via CDN global) ────────────────────────────
  const telemetryHistory = {};
  let telemetryChart = null;

  function getTelemetryBucket(pirepId) {
    if (!telemetryHistory[pirepId]) {
      telemetryHistory[pirepId] = { labels: [], alt: [], gs: [] };
    }
    return telemetryHistory[pirepId];
  }

  function pushTelemetry(pirepId, altitude, gs) {
    const hist = getTelemetryBucket(pirepId);
    const t = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    hist.labels.push(t);
    hist.alt.push(Number(altitude) || 0);
    hist.gs.push(Number(gs) || 0);
    if (hist.labels.length > 30) {
      hist.labels.shift();
      hist.alt.shift();
      hist.gs.shift();
    }
  }

  function renderTelemetryChart(pirepId) {
    const canvas = document.getElementById('telemetry-chart');
    if (!canvas || !window.Chart) return;

    const hist = getTelemetryBucket(pirepId);

    if (!telemetryChart) {
      const ctx = canvas.getContext('2d');
      telemetryChart = new window.Chart(ctx, {
        type: 'line',
        data: {
          labels: hist.labels,
          datasets: [
            {
              label: 'ALT (ft)',
              data: hist.alt,
              borderColor: '#10b981',
              backgroundColor: 'rgba(16,185,129,0.08)',
              tension: 0.4,
              fill: true,
              yAxisID: 'yAlt',
              pointRadius: 0,
              borderWidth: 2,
            },
            {
              label: 'GS (kts)',
              data: hist.gs,
              borderColor: '#fbbf24',
              backgroundColor: 'transparent',
              tension: 0.4,
              fill: false,
              yAxisID: 'yGs',
              pointRadius: 0,
              borderWidth: 2,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          animation: false,
          plugins: {
            legend: {
              labels: { color: '#888', font: { size: 9, family: 'JetBrains Mono, monospace' } },
            },
          },
          scales: {
            x: {
              ticks: { color: '#555', font: { size: 8 }, maxTicksLimit: 6 },
              grid: { color: 'rgba(255,255,255,0.04)' },
            },
            yAlt: {
              position: 'left',
              ticks: { color: '#10b981', font: { size: 8 } },
              grid: { color: 'rgba(255,255,255,0.04)' },
              title: { display: true, text: 'ALT ft', color: '#10b981', font: { size: 8 } },
            },
            yGs: {
              position: 'right',
              ticks: { color: '#fbbf24', font: { size: 8 } },
              grid: { display: false },
              title: { display: true, text: 'GS kts', color: '#fbbf24', font: { size: 8 } },
            },
          },
        },
      });
    } else {
      telemetryChart.data.labels = hist.labels;
      telemetryChart.data.datasets[0].data = hist.alt;
      telemetryChart.data.datasets[1].data = hist.gs;
      telemetryChart.update('none');
    }
  }

  function destroyTelemetryChart() {
    if (telemetryChart) { telemetryChart.destroy(); telemetryChart = null; }
  }

  // ─── Helpers ───────────────────────────────────────────────────────────────
  function formatTime(minutes) {
    if (!minutes) return "00:00";
    const h = Math.floor(minutes / 60).toString().padStart(2, '0');
    const m = Math.floor(minutes % 60).toString().padStart(2, '0');
    return `${h}:${m}`;
  }

  // ─── Waypoint Marker Icon ──────────────────────────────────────────────────
  function makeWaypointIcon(name, isPassed) {
    const baseColor = '#2563eb';
    const triColor = isPassed ? '#1d4ed8' : baseColor;
    const labelColor = '#ffffff';
    const opacity = '1';
    const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
      <circle cx="12" cy="12" r="10" fill="${triColor}" stroke="#ffffff" stroke-width="2" />
    </svg>`;
    return leaflet.divIcon({
      className: '',
      html: `<div style="display:flex;align-items:center;white-space:nowrap;pointer-events:none;">
        ${svg}
        <span style="margin-left:10px;color:${labelColor};font-weight:900;font-size:0.85rem;font-family:'JetBrains Mono',monospace;text-shadow:0 0 3px rgba(0,0,0,0.85);opacity:${opacity};letter-spacing:0.02em;">${name}</span>
      </div>`,
      iconSize: [170, 28],
      iconAnchor: [12, 14],
    });
  }

  // ─── Draw Selected Flight Route ────────────────────────────────────────────
  function drawSelectedRoute(route) {
    if (layerSelFlight) map.removeLayer(layerSelFlight);
    if (layerSelPlanned) map.removeLayer(layerSelPlanned);
    Object.keys(waypointLayers).forEach(k => {
      if (waypointLayers[k]) map.removeLayer(waypointLayers[k]);
    });
    waypointLayers = {};

    if (route.planned_line && route.planned_line.features && route.planned_line.features.length > 0) {
      layerSelPlanned = new L.Geodesic([], {
        weight: 2, opacity: 0.5, color: opts.planned_route_color, dashArray: '6, 10', wrap: false,
      }).addTo(map);
      layerSelPlanned.fromGeoJson(route.planned_line);
    }

    if (route.line && route.line.features && route.line.features.length > 0) {
      layerSelFlight = new L.Geodesic([], {
        weight: 4, opacity: 0.9, color: opts.flown_route_color, wrap: false,
      }).addTo(map);
      layerSelFlight.fromGeoJson(route.line);
    }

    if (route.planned_points && route.planned_points.features && route.planned_points.features.length > 0) {
      const aircraftLat = route.position ? route.position.lat : null;
      const aircraftLon = route.position ? route.position.lon : null;

      route.planned_points.features.forEach((feature, idx) => {
        if (!feature.geometry || !feature.geometry.coordinates) return;
        const coords = feature.geometry.coordinates;
        const name = feature.properties.name || `WP${idx + 1}`;

        let isPassed = false;
        if (aircraftLat !== null && idx < route.planned_points.features.length - 1) {
          const nextF = route.planned_points.features[idx + 1];
          if (nextF && nextF.geometry) {
            const dx1 = coords[1] - aircraftLat, dy1 = coords[0] - aircraftLon;
            const dx2 = nextF.geometry.coordinates[1] - aircraftLat, dy2 = nextF.geometry.coordinates[0] - aircraftLon;
            isPassed = (dx1 * dx1 + dy1 * dy1) > (dx2 * dx2 + dy2 * dy2);
          }
        }

        const marker = leaflet.marker([coords[1], coords[0]], {
          icon: makeWaypointIcon(name, isPassed),
          zIndexOffset: 500,
        }).addTo(map);

        if (feature.properties.popup) {
          marker.bindPopup(`<b style="color:#fbbf24">${feature.properties.popup}</b>`);
        }
        waypointLayers[`wp_${idx}`] = marker;
      });
    }

    if (route.position && route.position.lat) {
      lockedLatLng = new LatLng(route.position.lat, route.position.lon);
      if (isLockedOn) {
        map.panTo(lockedLatLng);
      } else if (!pannedToFlight) {
        map.panTo(lockedLatLng);
        pannedToFlight = true;
      }
    }
  }

  // ─── Click Handler ─────────────────────────────────────────────────────────
  function onFlightClick(pirep_id) {
    if (selectedPirepId === pirep_id) return;
    selectedPirepId = pirep_id;
    pannedToFlight = false;
    destroyTelemetryChart();

    const pirep_uri = opts.pirep_uri.replace("{id}", pirep_id);
    const geojson_uri = `${pirep_uri}/acars/geojson`;

    const activeFlightView = document.getElementById('active-flight-view');
    if (activeFlightView) activeFlightView.style.display = 'block';

    const footer = document.getElementById('fec-footer');
    if (footer) footer.style.display = 'flex';

    // Fetch PIREP details (includes acars_logs)
    request(pirep_uri).then((response) => {
      if (selectedPirepId !== pirep_id) return; // user switched flights before response arrived
      const pirep = response.data.data;

      document.getElementById('hud-ident').textContent = pirep.ident || '---';
      document.getElementById('hud-status').textContent = pirep.status_text || '---';

      const dpt = pirep.dpt_airport ? pirep.dpt_airport.icao : '---';
      const arr = pirep.arr_airport ? pirep.arr_airport.icao : '---';
      document.getElementById('hud-route').innerHTML = `${dpt} <i class="fas fa-arrow-right mx-2 text-muted"></i> ${arr}`;
      document.getElementById('hud-time').textContent = formatTime(pirep.flight_time);

      if (footer) {
        document.getElementById('footer-dpt').innerText = dpt;
        document.getElementById('footer-arr').innerText = arr;
        document.getElementById('footer-ident').innerText = pirep.ident || '---';
        document.getElementById('footer-status').innerText = pirep.status_text || '---';
      }

      // ACARS log tab
      const logsContainer = document.getElementById('tab-logs');
      if (logsContainer) {
        logsContainer.innerHTML = '';
        const logs = pirep.acars_logs && pirep.acars_logs.data ? pirep.acars_logs.data : [];
        if (logs.length > 0) {
          [...logs].reverse().forEach(log => {
            const date = new Date(log.created_at);
            const timeStr = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            logsContainer.innerHTML += `
              <div class="log-item">
                <div class="log-time">${timeStr}</div>
                <div class="log-msg">${log.log || ''}</div>
              </div>`;
          });
        } else {
          logsContainer.innerHTML = '<div class="text-center p-3 text-muted" style="font-size:0.8rem;">No ACARS logs yet.</div>';
        }
      }
    });

    // Fetch GeoJSON route
    request(geojson_uri).then((response) => {
      if (selectedPirepId !== pirep_id) return; // user switched flights before response arrived
      const route = response.data.data;

      if (route.position) {
        const alt = route.position.altitude || 0;
        const gs = route.position.gs || 0;
        const hdg = route.position.heading || 0;
        document.getElementById('hud-alt').textContent = Number(alt).toLocaleString() + ' ft';
        document.getElementById('hud-gs').textContent = gs + ' kts';
        document.getElementById('hud-hdg').textContent = hdg + '°';
        pushTelemetry(pirep_id, alt, gs);
        renderTelemetryChart(pirep_id);
      }

      drawSelectedRoute(route);

      // Waypoints tab
      const wpContainer = document.getElementById('tab-waypoints');
      if (wpContainer) {
        wpContainer.innerHTML = '';

        if (route.planned_points && route.planned_points.features && route.planned_points.features.length > 0) {
          const aircraftLat = route.position ? route.position.lat : null;
          const aircraftLon = route.position ? route.position.lon : null;

          route.planned_points.features.forEach((feature, idx) => {
            const name = feature.properties.name || `WP${idx + 1}`;
            const coords = feature.geometry ? feature.geometry.coordinates : null;

            let isPassed = false;
            if (coords && aircraftLat !== null && idx < route.planned_points.features.length - 1) {
              const nextF = route.planned_points.features[idx + 1];
              if (nextF && nextF.geometry) {
                const dx1 = coords[1] - aircraftLat, dy1 = coords[0] - aircraftLon;
                const dx2 = nextF.geometry.coordinates[1] - aircraftLat, dy2 = nextF.geometry.coordinates[0] - aircraftLon;
                isPassed = (dx1 * dx1 + dy1 * dy1) > (dx2 * dx2 + dy2 * dy2);
              }
            }

            const cls = isPassed ? 'passed' : '';
            const icon = isPassed ? 'fa-check-circle' : 'fa-map-marker-alt';
            wpContainer.innerHTML += `
              <div class="waypoint-item ${cls}">
                <div class="wp-icon"><i class="fas ${icon}"></i></div>
                <div class="wp-name">${name}</div>
              </div>`;
          });

          // Update footer progress bar
          const totalWPs = route.planned_points.features.length;
          const passedCount = wpContainer.querySelectorAll('.waypoint-item.passed').length;
          const progress = totalWPs > 0 ? (passedCount / totalWPs) * 100 : 50;
          const footerPlane = document.getElementById('footer-plane');
          if (footerPlane) footerPlane.style.left = `${Math.min(Math.max(progress, 5), 95)}%`;
        } else {
          wpContainer.innerHTML = '<div class="text-center p-3 text-muted" style="font-size:0.8rem;">No planned waypoints found.</div>';
        }
      }
    });
  }

  // ─── Deselect ──────────────────────────────────────────────────────────────
  window.liveMapDeselect = function () {
    selectedPirepId = null;
    isLockedOn = false;
    pannedToFlight = false;
    destroyTelemetryChart();

    if (lockBtn) lockBtn.style.color = '#a0a0a0';
    if (layerSelFlight) { map.removeLayer(layerSelFlight); layerSelFlight = null; }
    if (layerSelPlanned) { map.removeLayer(layerSelPlanned); layerSelPlanned = null; }
    Object.keys(waypointLayers).forEach(k => {
      if (waypointLayers[k]) map.removeLayer(waypointLayers[k]);
    });
    waypointLayers = {};

    try {
      if (layerFlights && layerFlights.getBounds().isValid()) {
        map.fitBounds(layerFlights.getBounds(), { padding: [50, 50], maxZoom: 6 });
      } else {
        map.panTo(centerCoords);
      }
    } catch (e) {
      map.panTo(centerCoords);
    }
  };

  // ─── Main Update Loop ──────────────────────────────────────────────────────
  const updateMap = () => {
    // Sidebar flight list
    request(opts.acars_uri).then((response) => {
      const pireps = response.data.data;
      const listContainer = document.getElementById('flights-list-container');
      const statLive = document.getElementById('stat-live');

      if (statLive) statLive.textContent = pireps ? pireps.length : 0;

      if (!pireps || pireps.length === 0) {
        if (listContainer) {
          listContainer.innerHTML = `
            <div class="text-center p-4" style="color:rgba(16,185,129,0.6);font-family:monospace;font-size:0.8rem;">
              <i class="fas fa-broadcast-tower fa-spin" style="font-size:2rem;margin-bottom:1rem;display:block;"></i>
              NO ACTIVE FLIGHTS DETECTED
            </div>`;
        }
      } else {
        let html = '';
        pireps.forEach(p => {
          const dpt = p.dpt_airport ? p.dpt_airport.icao : '---';
          const arr = p.arr_airport ? p.arr_airport.icao : '---';
          const alt = p.position ? p.position.altitude : 0;
          const gs = p.position ? p.position.gs : 0;
          const isActive = selectedPirepId === p.id;
          html += `
            <div class="mission-card ${isActive ? 'active' : ''}" onclick="window.liveMapFlightClick('${p.id}')">
              <div class="ident">${p.ident}</div>
              <div class="route">${dpt} → ${arr}</div>
              <div style="display:flex;justify-content:space-between;margin-top:0.5rem;font-size:0.75rem;color:#888;">
                <span><i class="fas fa-level-up-alt me-1"></i>${alt ? Number(alt).toLocaleString() : 0} ft</span>
                <span><i class="fas fa-tachometer-alt me-1"></i>${gs || 0} kts</span>
              </div>
              <div class="status mt-2">● AIRBORNE</div>
            </div>`;
        });
        if (listContainer) listContainer.innerHTML = html;
      }

      window.liveMapFlightClick = onFlightClick;
    });

    // GeoJSON aircraft markers
    request({ url: opts.update_uri }).then((response) => {
      const flightGeoJson = response.data.data;

      if (layerFlights !== null) layerFlights.clearLayers();

      layerFlights = leaflet.geoJSON(flightGeoJson, {
        onEachFeature: (feature, layer) => {
          layer.on({
            click: () => {
              pannedToFlight = false;
              onFlightClick(feature.properties.pirep_id);
            },
          });

          if (feature.properties && feature.properties.popup) {
            layer.bindPopup(`<b>${feature.properties.popup}</b>`);
          }
          markers_list[feature.properties.pirep_id] = [feature, layer];

          // Live-update HUD for selected flight
          if (selectedPirepId === feature.properties.pirep_id) {
            const alt = feature.properties.alt || 0;  // GeoJSON uses 'alt', not 'altitude'
            const gs = feature.properties.gs || 0;
            const hdg = feature.properties.heading || 0;

            const altEl = document.getElementById('hud-alt');
            const gsEl = document.getElementById('hud-gs');
            const hdgEl = document.getElementById('hud-hdg');
            if (altEl) altEl.textContent = Number(alt).toLocaleString() + ' ft';
            if (gsEl) gsEl.textContent = gs + ' kts';
            if (hdgEl) hdgEl.textContent = hdg + '°';

            pushTelemetry(selectedPirepId, alt, gs);
            renderTelemetryChart(selectedPirepId);
          }
        },

        pointToLayer(feature, latlon) {
          const heading = feature.properties.heading || 0;
          const correctedHeading = heading - 45;
          const isSelected = selectedPirepId === feature.properties.pirep_id;
          const iconColor = isSelected ? '#1d4ed8' : '#2563eb';
          // A single tight, near-zero-blur shadow for contrast against the map tiles.
          const shadow = 'drop-shadow(0 1px 1px rgba(0,0,0,0.5))';

          const rotatedIcon = leaflet.divIcon({
            className: 'aircraft-marker-icon',
            html: `<i class="fas fa-plane" style="color:${iconColor};font-size:30px;transform:rotate(${correctedHeading}deg);transform-origin:center;display:block;filter:${shadow};transition:transform 1.5s linear;"></i>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15],
          });

          if (isSelected) {
            lockedLatLng = latlon;
            if (isLockedOn) map.panTo(latlon);
          }

          return leaflet.marker(latlon, {
            icon: rotatedIcon,
            zIndexOffset: isSelected ? 2000 : 1000,
          });
        },
      });

      layerFlights.addTo(map);

      // Refresh selected flight route
      if (selectedPirepId !== null) {
        const geojson_uri = `${opts.pirep_uri.replace("{id}", selectedPirepId)}/acars/geojson`;
        request(geojson_uri).then((response) => {
          const route = response.data.data;
          drawSelectedRoute(route);
        });
      } else if (!pannedToFlight) {
        try {
          if (layerFlights.getBounds().isValid()) {
            map.fitBounds(layerFlights.getBounds(), { padding: [50, 50], maxZoom: 6 });
          } else {
            map.panTo(centerCoords);
          }
        } catch (e) {
          map.panTo(centerCoords);
        }
      }
    });
  };

  updateMap();
  setInterval(updateMap, opts.refresh_interval * 1000);
};
