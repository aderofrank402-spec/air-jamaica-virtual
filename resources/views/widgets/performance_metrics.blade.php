<div class="widget widget-performance-metrics">
    <div class="widget-header d-flex justify-content-between align-items-center mb-3">
        <h5 class="widget-title mb-0">
            <i class="fas fa-chart-line text-primary me-2"></i>
            Performance Metrics
        </h5>
        <small class="text-muted">
            <i class="fas fa-sync-alt me-1"></i>
            @if(isset($updated_at))
                {{ $updated_at->format('H:i') }}
            @else
                {{ now()->format('H:i') }}
            @endif
        </small>
    </div>

    @if(isset($error))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ $error }}
        </div>
    @else
        <div class="performance-metrics">
            <!-- Key Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <div class="metric-card bg-primary">
                        <div class="metric-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $metrics['on_time_percentage'] }}%</div>
                            <div class="metric-label">On-Time Performance</div>
                            @if($trends && isset($trends['on_time_trend']))
                                <div class="metric-trend {{ $trends['on_time_trend'] >= 0 ? 'positive' : 'negative' }}">
                                    <i class="fas fa-{{ $trends['on_time_trend'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ abs(round($trends['on_time_trend'], 1)) }}%
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="metric-card bg-success">
                        <div class="metric-icon">
                            <i class="fas fa-plane-arrival"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $metrics['avg_landing_rate'] }} fpm</div>
                            <div class="metric-label">Avg Landing Rate</div>
                            @if($trends && isset($trends['landing_trend']))
                                <div class="metric-trend {{ $trends['landing_trend'] >= 0 ? 'positive' : 'negative' }}">
                                    <i class="fas fa-{{ $trends['landing_trend'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ abs(round($trends['landing_trend'])) }} fpm
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Metrics -->
            <div class="metrics-list">
                <div class="metric-item">
                    <div class="metric-info">
                        <i class="fas fa-star text-warning me-2"></i>
                        <span class="metric-name">Safety Rating</span>
                    </div>
                    <div class="metric-details">
                        <span class="metric-stars">{{ $metrics['safety_stars'] }}</span>
                        <span class="metric-score">{{ round($metrics['safety_rating'], 1) }}%</span>
                    </div>
                </div>

                <div class="metric-item">
                    <div class="metric-info">
                        <i class="fas fa-gas-pump text-info me-2"></i>
                        <span class="metric-name">Fuel Efficiency</span>
                    </div>
                    <div class="metric-details">
                        <span class="metric-score">{{ $metrics['fuel_efficiency'] }}%</span>
                        @if($trends && isset($trends['fuel_trend']))
                            <span class="metric-trend-small {{ $trends['fuel_trend'] >= 0 ? 'positive' : 'negative' }}">
                                {{ $trends['fuel_trend'] >= 0 ? '+' : '' }}{{ round($trends['fuel_trend'], 1) }}%
                            </span>
                        @endif
                    </div>
                </div>

                <div class="metric-item">
                    <div class="metric-info">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <span class="metric-name">Monthly Hours</span>
                    </div>
                    <div class="metric-details">
                        <span class="metric-score">{{ $metrics['monthly_hours'] }}h</span>
                        @if($trends && isset($trends['hours_trend']))
                            <span class="metric-trend-small {{ $trends['hours_trend'] >= 0 ? 'positive' : 'negative' }}">
                                {{ $trends['hours_trend'] >= 0 ? '+' : '' }}{{ round($trends['hours_trend'], 1) }}h
                            </span>
                        @endif
                    </div>
                </div>

                <div class="metric-item">
                    <div class="metric-info">
                        <i class="fas fa-bullseye text-success me-2"></i>
                        <span class="metric-name">Perfect Landings</span>
                    </div>
                    <div class="metric-details">
                        <span class="metric-score">{{ $metrics['perfect_landings'] }}</span>
                        <span class="text-muted small">of {{ $metrics['total_flights'] }} flights</span>
                    </div>
                </div>

                <div class="metric-item">
                    <div class="metric-info">
                        <i class="fas fa-percentage text-warning me-2"></i>
                        <span class="metric-name">Average Score</span>
                    </div>
                    <div class="metric-details">
                        <span class="metric-score">{{ $metrics['average_score'] }}%</span>
                    </div>
                </div>
            </div>

            <!-- Goals Progress -->
            @if($goals)
                <div class="goals-section mt-4">
                    <h6 class="goals-title">
                        <i class="fas fa-target me-2"></i>Monthly Goals
                    </h6>
                    <div class="goal-item">
                        <div class="goal-info">
                            <span class="goal-name">Flight Hours</span>
                            <span class="goal-progress">{{ $goals['monthly_hours']['current'] }} / {{ $goals['monthly_hours']['goal'] }}h</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: {{ $goals['monthly_hours']['percentage'] }}%"></div>
                        </div>
                    </div>
                    <div class="goal-item">
                        <div class="goal-info">
                            <span class="goal-name">Number of Flights</span>
                            <span class="goal-progress">{{ $goals['monthly_flights']['current'] }} / {{ $goals['monthly_flights']['goal'] }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: {{ $goals['monthly_flights']['percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>

<style>
.widget-performance-metrics {
    background: rgba(15, 20, 25, 0.6);
    border-radius: 15px;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    color: #b0b0b0;
}

.metric-card {
    border-radius: 12px;
    padding: 1.2rem;
    color: white;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease;
    position: relative;
    overflow: hidden;
}

.metric-card:hover {
    transform: translateY(-2px);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    animation: shimmer 2s ease-in-out infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

.metric-icon {
    font-size: 2rem;
    opacity: 0.8;
    min-width: 50px;
}

.metric-content {
    flex: 1;
}

.metric-value {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.2rem;
}

.metric-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 0.3rem;
}

.metric-trend {
    font-size: 0.8rem;
    font-weight: 600;
}

.metric-trend.positive {
    color: #28a745;
}

.metric-trend.negative {
    color: #dc3545;
}

.metrics-list {
    background: rgba(26, 31, 46, 0.4);
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.metric-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.metric-item:last-child {
    border-bottom: none;
}

.metric-info {
    display: flex;
    align-items: center;
}

.metric-name {
    color: #e0e0e0;
    font-weight: 500;
}

.metric-details {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.metric-score {
    color: #ffffff;
    font-weight: 600;
    font-size: 1.1rem;
}

.metric-stars {
    font-size: 1.2rem;
    margin-right: 0.5rem;
}

.metric-trend-small {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    background: rgba(0,0,0,0.2);
}

.metric-trend-small.positive {
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.metric-trend-small.negative {
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

.goals-section {
    background: rgba(0, 166, 81, 0.05);
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid rgba(0, 166, 81, 0.2);
}

.goals-title {
    color: #00a651;
    font-weight: 600;
    margin-bottom: 1rem;
}

.goal-item {
    margin-bottom: 1rem;
}

.goal-item:last-child {
    margin-bottom: 0;
}

.goal-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.goal-name {
    color: #e0e0e0;
    font-weight: 500;
}

.goal-progress {
    color: #ffffff;
    font-weight: 600;
    font-size: 0.9rem;
}

.progress {
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    border-radius: 3px;
    transition: width 1s ease;
}
</style>