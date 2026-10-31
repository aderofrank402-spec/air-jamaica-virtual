<x-filament-panels::page class="fi-dashboard-page">
    <style>
        :root {
            --nasa-green: #00ff88;
            --nasa-gold: #ffcc00;
            --nasa-blue: #00d4ff;
            --nasa-red: #ff4444;
            --nasa-bg: #0a0c10;
            --nasa-card-bg: rgba(255, 255, 255, 0.03);
            --nasa-border: rgba(255, 255, 255, 0.08);
        }

        .nasa-container {
            font-family: 'Inter', sans-serif;
            color: #fff;
        }

        .mission-header {
            border-bottom: 1px solid var(--nasa-border);
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .nasa-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .nasa-card {
            background: var(--nasa-card-bg);
            border: 1px solid var(--nasa-border);
            border-radius: 12px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nasa-card:hover {
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-4px);
            background: rgba(255, 255, 255, 0.05);
        }

        .card-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #888;
            margin-bottom: 0.5rem;
            display: block;
        }

        .card-value {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 1rem;
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .card-sub {
            font-size: 0.75rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .dot-green { background: var(--nasa-green); box-shadow: 0 0 8px var(--nasa-green); }
        .dot-gold { background: var(--nasa-gold); box-shadow: 0 0 8px var(--nasa-gold); }
        .dot-blue { background: var(--nasa-blue); box-shadow: 0 0 8px var(--nasa-blue); }

        .progress-bar {
            height: 4px;
            background: rgba(255,255,255,0.05);
            border-radius: 2px;
            margin-top: 1rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 2px;
            transition: width 1s ease-out;
        }

        .live-ticker {
            font-family: 'Roboto Mono', monospace;
            font-size: 0.7rem;
            color: var(--nasa-green);
            background: rgba(0, 255, 136, 0.05);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            border: 1px solid rgba(0, 255, 136, 0.2);
        }

        /* Intelligence Modules */
        .intel-section {
            margin-bottom: 3rem;
        }

        .intel-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--nasa-blue);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .intel-title::after {
            content: '';
            flex-grow: 1;
            height: 1px;
            background: linear-gradient(90deg, var(--nasa-border), transparent);
        }

        .radar-table {
            width: 100%;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.75rem;
            border-collapse: collapse;
        }

        .radar-table th {
            text-align: left;
            color: #555;
            padding: 0.75rem;
            border-bottom: 1px solid var(--nasa-border);
            text-transform: uppercase;
            font-size: 0.65rem;
        }

        .radar-table td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.02);
            color: #aaa;
        }

        .radar-table tr:hover td {
            background: rgba(255,255,255,0.02);
            color: #fff;
        }

        .callsign-cell {
            color: var(--nasa-green) !important;
            font-weight: 700;
        }

        .intel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .intel-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--nasa-border);
            border-radius: 8px;
            padding: 1.25rem;
        }

        .intel-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 0.8rem;
        }

        .intel-row:last-child { margin-bottom: 0; }

        .intel-value { color: #fff; font-weight: 600; }
        .intel-label { color: #777; }
    </style>

    <div class="nasa-container">
        <div class="mission-header">
            <div class="live-ticker">
                SYS_STATUS: OPTIMAL // LIVE_FLIGHTS: {{ $metrics['operations']['active_count'] }} // UTC: {{ now()->format('H:i:s') }}
            </div>
        </div>

        <!-- Primary Strategic Metrics -->
        <div class="nasa-grid">
            <div class="nasa-card" style="border-left: 3px solid #fff">
                <span class="card-label">Active Pilots</span>
                <div class="card-value">
                    {{ number_format($metrics['personnel']['active']) }}
                </div>
                <div class="card-sub">
                    <span class="status-dot" style="background: #fff"></span>
                    Total Roster: {{ $metrics['personnel']['total'] }}
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%; background: #fff; opacity: 0.2"></div>
                </div>
            </div>

            <div class="nasa-card" style="border-left: 3px solid var(--nasa-blue)">
                <span class="card-label">Destinations</span>
                <div class="card-value">
                    {{ number_format($metrics['destinations']['total']) }}
                </div>
                <div class="card-sub">
                    <span class="status-dot dot-blue"></span>
                    Global network active
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%; background: var(--nasa-blue); opacity: 0.2"></div>
                </div>
            </div>

            <div class="nasa-card" style="border-left: 3px solid var(--nasa-green)">
                <span class="card-label">Completed Flights</span>
                <div class="card-value">
                    {{ number_format($metrics['operations']['completed']) }}
                </div>
                <div class="card-sub">
                    <span class="status-dot dot-green"></span>
                    Successfully logged operations
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%; background: var(--nasa-green); opacity: 0.2"></div>
                </div>
            </div>

            <div class="nasa-card" style="border-left: 3px solid var(--nasa-gold)">
                <span class="card-label">Active Aircraft</span>
                <div class="card-value">
                    {{ number_format($metrics['fleet']['active']) }}
                </div>
                <div class="card-sub">
                    <span class="status-dot dot-gold"></span>
                    Fleet Size: {{ $metrics['fleet']['total'] }}
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%; background: var(--nasa-gold); opacity: 0.2"></div>
                </div>
            </div>
        </div>

        <!-- Live Traffic Radar -->
        <div class="intel-section">
            <div class="intel-title">Recent Flight Activity // Last 5 Completed</div>
            <div class="nasa-card" style="padding: 0;">
                <table class="radar-table">
                    <thead>
                        <tr>
                            <th>Callsign</th>
                            <th>Pilot Command</th>
                            <th>Aircraft</th>
                            <th>Dep/Arr</th>
                            <th>Altitude (MSL)</th>
                            <th>GS (KTS)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($metrics['operations']['live'] as $flight)
                        <tr>
                            <td class="callsign-cell">{{ $flight['callsign'] }}</td>
                            <td>{{ $flight['pilot'] }}</td>
                            <td>{{ $flight['aircraft'] }}</td>
                            <td>{{ $flight['dep'] }} > {{ $flight['arr'] }}</td>
                            <td>{{ number_format($flight['altitude']) }} FT</td>
                            <td>{{ $flight['gs'] }}</td>
                            <td><span class="status-dot dot-green"></span> COMPLETED</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #555;">NO COMPLETED FLIGHTS YET</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Intelligence Grid -->
        <div class="intel-grid">
            <!-- Hub Activity -->
            <div class="intel-card">
                <div class="intel-title" style="margin-bottom: 1rem; color: var(--nasa-gold);">Strategic Hub Activity</div>
                @foreach($metrics['hubs'] as $hub)
                <div class="intel-row">
                    <span class="intel-label">{{ $hub['icao'] }} - {{ $hub['name'] }}</span>
                    <span class="intel-value">{{ $hub['activity'] }} deps</span>
                </div>
                @endforeach
            </div>

            <!-- Training Pipeline -->
            <div class="intel-card">
                <div class="intel-title" style="margin-bottom: 1rem; color: var(--nasa-blue);">Training & Recruitment</div>
                <div class="intel-row">
                    <span class="intel-label">Pending Applicants</span>
                    <span class="intel-value" style="color: var(--nasa-gold)">{{ $metrics['personnel']['pending'] }}</span>
                </div>
                <div class="intel-row">
                    <span class="intel-label">Active Trainees</span>
                    <span class="intel-value">{{ $metrics['training']['active'] }}</span>
                </div>
                <div class="intel-row">
                    <span class="intel-label">Wings Awarded (7d)</span>
                    <span class="intel-value" style="color: var(--nasa-green)">+{{ $metrics['training']['certs'] }}</span>
                </div>
            </div>

            <!-- Fleet Health -->
            <div class="intel-card">
                <div class="intel-title" style="margin-bottom: 1rem; color: var(--nasa-red);">Fleet Health Intelligence</div>
                <div class="intel-row">
                    <span class="intel-label">Units in Maintenance</span>
                    <span class="intel-value" style="color: var(--nasa-red)">{{ $metrics['fleet']['maintenance'] }}</span>
                </div>
                @foreach($metrics['fleet']['high_hours'] as $a)
                <div class="intel-row">
                    <span class="intel-label">{{ $a['reg'] }} Utilization</span>
                    <span class="intel-value">{{ $a['hours'] }}h</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="fi-widgets" style="margin-top: 3rem;">
            @php
                $widgets = $this->getVisibleWidgets();
                $columns = $this->getColumns();
            @endphp

            <x-filament-widgets::widgets
                :columns="$columns"
                :data="$this->getWidgetData()"
                :widgets="$widgets"
            />
        </div>
    </div>
</x-filament-panels::page>
