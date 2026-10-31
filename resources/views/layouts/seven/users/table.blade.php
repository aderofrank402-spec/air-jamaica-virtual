<div class="pilots-table-wrapper">
    <table class="table pilots-table" id="users-table">
        <thead>
            <tr>
                <th class="avatar-col"><i class="fas fa-user-circle"></i></th>
                <th class="sortable-col">@sortablelink('name', __('common.name')) <i class="fas fa-sort ms-1"></i></th>
                <th class="sortable-col">@sortablelink('airline_id', __('common.airline')) <i class="fas fa-sort ms-1"></i></th>
                <th class="sortable-col">@sortablelink('curr_airport_id', __('user.location')) <i class="fas fa-sort ms-1"></i></th>
                <th class="sortable-col">@sortablelink('flights', trans_choice('common.flight', 2)) <i class="fas fa-sort ms-1"></i></th>
                <th class="sortable-col">@sortablelink('flight_time', trans_choice('common.hour', 2)) <i class="fas fa-sort ms-1"></i></th>
                <th class="text-center"><i class="fas fa-trophy" title="Awards"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="pilot-row" data-airline="{{ $user->airline->icao ?? '' }}" data-status="{{ $user->state }}">
                    <td class="avatar-cell">
                        <div class="pilot-avatar-container">
                            @php $avatarUrl = $user->avatar ? $user->avatar->url : null; @endphp
                            <img class="pilot-avatar"
                                 src="{{ !empty($avatarUrl) ? $avatarUrl : public_asset('/assets/img/air-jamaica-logo.png') }}"
                                 alt="{{ $user->name_private }}">
                            <div class="status-dot {{ $user->state == \App\Models\Enums\UserState::ACTIVE ? 'active' : '' }}"></div>
                        </div>
                    </td>
                    <td class="pilot-info">
                        <div class="pilot-name-wrap">
                            <a href="{{ route('frontend.users.show.public', [$user->id]) }}" class="pilot-link">
                                <span class="pilot-id-badge">{{ $user->ident }}</span>
                                <span class="pilot-name">{{ $user->name_private }}</span>
                            </a>
                            @if (filled($user->country))
                                <span class="fi fi-{{ $user->country }} country-flag ms-1"
                                      title="{{ $country->alpha2($user->country)['name'] }}"></span>
                            @endif
                            @if($user->rank)
                                <div class="pilot-rank">
                                    <i class="fas fa-star"></i> {{ $user->rank->name }}
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($user->airline)
                            <span class="info-chip"><i class="fas fa-plane"></i> {{ $user->airline->icao }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->current_airport)
                            <span class="info-chip loc"><i class="fas fa-map-marker-alt"></i> {{ $user->curr_airport_id }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="stat-item">
                            <i class="fas fa-plane-departure"></i>
                            <span>{{ number_format($user->flights) }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="stat-item">
                            <i class="fas fa-clock"></i>
                            @php
                                $totalMinutes = $user->pireps()
                                    ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
                                    ->sum('flight_time') ?? 0;
                            @endphp
                            <span>@minutestotime($totalMinutes)</span>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($user->awards_count > 0)
                            <span class="awards-chip">
                                <i class="fas fa-trophy"></i> {{ $user->awards_count }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
.pilots-table-wrapper {
    overflow-x: auto;
}

.pilots-table {
    margin: 0;
    width: 100%;
    border-collapse: collapse;
    color: #e2e8f0;
    background: transparent;
}

/* ── Head ── */
.pilots-table thead tr {
    border-bottom: 1px solid rgba(16,185,129,.18);
    background: rgba(16,185,129,.06);
}

.pilots-table thead th {
    border: none;
    color: #94a3b8;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 700;
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 1rem 1.25rem;
    white-space: nowrap;
}

.sortable-col { cursor: pointer; }
.sortable-col a { color: #94a3b8 !important; text-decoration: none !important; }
.sortable-col a:hover { color: #10b981 !important; }
.sortable-col .fas { opacity: .5; }

.avatar-col { width: 70px; text-align: center; }

/* ── Rows ── */
.pilot-row {
    border-bottom: 1px solid rgba(16,185,129,.08);
    background: transparent;
}

.pilot-row td {
    border: none;
    padding: 1rem 1.25rem;
    vertical-align: middle;
    color: #e2e8f0;
    font-family: 'JetBrains Mono', monospace;
    font-size: .85rem;
    background: transparent;
}

/* ── Avatar ── */
.pilot-avatar-container {
    position: relative;
    display: inline-block;
}

.pilot-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(16,185,129,.25);
    display: block;
}

.status-dot {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #0f172a;
    background: #475569;
}

.status-dot.active {
    background: #10b981;
    box-shadow: 0 0 6px rgba(16,185,129,.5);
}

/* ── Pilot info ── */
.pilot-name-wrap {
    display: flex;
    flex-direction: column;
    gap: .3rem;
}

.pilot-link {
    display: flex;
    align-items: center;
    gap: .5rem;
    text-decoration: none !important;
    color: #e2e8f0 !important;
}

.pilot-link:hover .pilot-name { color: #10b981; }

.pilot-id-badge {
    background: rgba(16,185,129,.15);
    border: 1px solid rgba(16,185,129,.2);
    color: #10b981;
    padding: .15rem .5rem;
    border-radius: 20px;
    font-size: .68rem;
    font-weight: 700;
    font-family: 'JetBrains Mono', monospace;
    text-transform: uppercase;
    flex-shrink: 0;
}

.pilot-name {
    font-weight: 600;
    font-size: .88rem;
    color: #e2e8f0;
    transition: color .15s;
}

.pilot-rank {
    font-size: .72rem;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: .35rem;
}

.pilot-rank i { color: #fbbf24; font-size: .65rem; }

.country-flag {
    font-size: 1rem;
    border-radius: 2px;
    vertical-align: middle;
}

/* ── Chips ── */
.info-chip {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: rgba(15,23,42,.6);
    border: 1px solid rgba(16,185,129,.15);
    color: #e2e8f0;
    padding: .28rem .7rem;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 600;
    font-family: 'JetBrains Mono', monospace;
}

.info-chip i { color: #10b981; font-size: .72rem; }
.info-chip.loc i { color: #3b82f6; }

/* ── Stat item ── */
.stat-item {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-weight: 600;
    font-size: .88rem;
    color: #e2e8f0;
}

.stat-item i { color: #fbbf24; font-size: .8rem; width: 14px; text-align: center; }

/* ── Awards ── */
.awards-chip {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    background: rgba(251,191,36,.1);
    border: 1px solid rgba(251,191,36,.2);
    color: #fbbf24;
    padding: .28rem .65rem;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 700;
    font-family: 'JetBrains Mono', monospace;
}

.awards-chip i { font-size: .72rem; }

.text-muted { color: #475569 !important; }

/* ── Responsive ── */
@media (max-width: 768px) {
    .pilots-table { min-width: 700px; }
    .pilot-row td { padding: .75rem .75rem; }
    .pilot-avatar { width: 38px; height: 38px; }
    .pilots-table thead th { padding: .75rem .75rem; font-size: .65rem; }
}
</style>
