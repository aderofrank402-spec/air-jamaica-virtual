<div class="widget widget-financial-dashboard">
    <div class="widget-header d-flex justify-content-between align-items-center mb-3">
        <h5 class="widget-title mb-0">
            <i class="fas fa-chart-pie text-success me-2"></i>
            Financial Dashboard
        </h5>
        <div class="widget-actions">
            <small class="text-muted">
                <i class="fas fa-sync-alt me-1"></i>
                Updated
            </small>
        </div>
    </div>

    <div class="financial-metrics">
        <!-- Balance & Earnings Overview -->
        <div class="balance-section mb-4">
            <div class="balance-card">
                <div class="balance-header">
                    <div class="balance-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="balance-info">
                        <div class="balance-amount">${{ number_format($financial_data['current_balance'], 2) }}</div>
                        <div class="balance-label">Current Balance</div>
                    </div>
                </div>
                <div class="balance-details">
                    <div class="balance-stat">
                        <span class="stat-label">Monthly Earnings</span>
                        <span class="stat-value text-success">${{ number_format($financial_data['monthly_data']['earnings'], 2) }}</span>
                    </div>
                    <div class="balance-stat">
                        <span class="stat-label">Avg per Flight</span>
                        <span class="stat-value">${{ number_format($financial_data['monthly_data']['avg_per_flight'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Performance -->
        <div class="monthly-section mb-4">
            <h6 class="section-title">
                <i class="fas fa-calendar-alt me-2"></i>
                This Month's Performance
            </h6>
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="metric-card bg-primary">
                        <div class="metric-icon">
                            <i class="fas fa-plane-departure"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $financial_data['monthly_data']['flights'] }}</div>
                            <div class="metric-label">Flights Completed</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="metric-card bg-info">
                        <div class="metric-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">${{ number_format($financial_data['monthly_data']['last_salary'], 2) }}</div>
                            <div class="metric-label">Last Flight Pay</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rank & Bonus Information -->
        <div class="rank-section mb-4">
            <h6 class="section-title">
                <i class="fas fa-user-tie me-2"></i>
                Rank Benefits
            </h6>
            <div class="rank-card">
                <div class="rank-info">
                    <div class="rank-badge">
                        <i class="fas fa-star"></i>
                        {{ $financial_data['rank_data']['rank_name'] }}
                    </div>
                    <div class="rank-benefits">
                        <div class="benefit-item">
                            <span class="benefit-label">Pay Multiplier</span>
                            <span class="benefit-value">{{ number_format($financial_data['rank_data']['multiplier'], 2) }}x</span>
                        </div>
                        <div class="benefit-item">
                            <span class="benefit-label">Rank Bonus</span>
                            <span class="benefit-value">{{ $financial_data['rank_data']['bonus_percentage'] }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Bonuses -->
        @if($config['show_bonus_details'])
        <div class="bonus-section mb-4">
            <h6 class="section-title">
                <i class="fas fa-trophy me-2"></i>
                Performance Bonuses
            </h6>
            <div class="bonus-grid">
                <div class="bonus-item">
                    <div class="bonus-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="bonus-content">
                        <div class="bonus-title">On-Time Performance</div>
                        <div class="bonus-value">{{ $financial_data['bonus_data']['on_time_percentage'] }}%</div>
                        @if($financial_data['bonus_data']['on_time_percentage'] >= 90)
                            <div class="bonus-status active">
                                <i class="fas fa-check"></i> Bonus Active
                            </div>
                        @else
                            <div class="bonus-status">
                                Need {{ 90 - $financial_data['bonus_data']['on_time_percentage'] }}% more
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bonus-item">
                    <div class="bonus-icon bg-success">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="bonus-content">
                        <div class="bonus-title">Performance Bonus</div>
                        <div class="bonus-value">{{ $financial_data['bonus_data']['performance_bonus'] }}%</div>
                        @if($financial_data['bonus_data']['performance_bonus'] > 0)
                            <div class="bonus-status active">
                                <i class="fas fa-check"></i> Earning Bonus
                            </div>
                        @else
                            <div class="bonus-status">
                                Maintain 85%+ scores
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($financial_data['bonus_data']['bonus_pool'] > 0)
            <div class="bonus-pool">
                <div class="pool-info">
                    <i class="fas fa-piggy-bank me-2"></i>
                    <span>Bonus Pool Available: <strong>${{ number_format($financial_data['bonus_data']['bonus_pool']) }}</strong></span>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Overall Statistics -->
        <div class="stats-section">
            <h6 class="section-title">
                <i class="fas fa-chart-bar me-2"></i>
                Overall Statistics
            </h6>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave text-success"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">${{ number_format($financial_data['performance_data']['total_earned'], 2) }}</div>
                        <div class="stat-label">Total Earned</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-calculator text-info"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">${{ number_format($financial_data['performance_data']['avg_per_flight'], 2) }}</div>
                        <div class="stat-label">Avg per Flight</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-award text-warning"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">${{ number_format($financial_data['performance_data']['best_flight'], 2) }}</div>
                        <div class="stat-label">Best Flight</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.widget-financial-dashboard {
    background: rgba(15, 20, 25, 0.6);
    border-radius: 15px;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    color: #b0b0b0;
}

.balance-card {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border-radius: 12px;
    padding: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.balance-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    animation: shimmer 3s ease-in-out infinite;
}

.balance-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.balance-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

.balance-amount {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.2rem;
}

.balance-label {
    font-size: 1rem;
    opacity: 0.9;
}

.balance-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.balance-stat {
    text-align: center;
}

.stat-label {
    display: block;
    font-size: 0.8rem;
    opacity: 0.8;
    margin-bottom: 0.2rem;
}

.stat-value {
    font-size: 1.1rem;
    font-weight: 600;
}

.section-title {
    color: #e0e0e0;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.metric-card {
    border-radius: 10px;
    padding: 1rem;
    color: white;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    transition: transform 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-2px);
}

.metric-icon {
    font-size: 1.5rem;
    opacity: 0.8;
}

.metric-value {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 0.2rem;
}

.metric-label {
    font-size: 0.85rem;
    opacity: 0.9;
}

.rank-card {
    background: rgba(26, 31, 46, 0.4);
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.rank-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.rank-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rank-benefits {
    display: flex;
    gap: 1rem;
}

.benefit-item {
    text-align: center;
}

.benefit-label {
    display: block;
    font-size: 0.8rem;
    color: #a0a0a0;
    margin-bottom: 0.2rem;
}

.benefit-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #ffffff;
}

.bonus-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.bonus-item {
    background: rgba(26, 31, 46, 0.4);
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.bonus-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.bonus-title {
    font-weight: 600;
    color: #e0e0e0;
    margin-bottom: 0.3rem;
}

.bonus-value {
    font-size: 1.3rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.3rem;
}

.bonus-status {
    font-size: 0.8rem;
    color: #a0a0a0;
}

.bonus-status.active {
    color: #28a745;
}

.bonus-pool {
    background: rgba(0, 166, 81, 0.1);
    border: 1px solid rgba(0, 166, 81, 0.3);
    border-radius: 8px;
    padding: 0.8rem;
    text-align: center;
}

.pool-info {
    color: #00a651;
    font-weight: 600;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.stat-item {
    background: rgba(26, 31, 46, 0.4);
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
    text-align: center;
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-2px);
}

.stat-icon {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
}

.stat-content .stat-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.3rem;
}

.stat-content .stat-label {
    font-size: 0.85rem;
    color: #a0a0a0;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}
</style>