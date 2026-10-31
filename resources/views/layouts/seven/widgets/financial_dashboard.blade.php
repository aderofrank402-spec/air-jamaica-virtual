{{-- Financial Dashboard Widget - Comprehensive pilot economics --}}
@php
    // Helper function to safely get money value
    function safeGetMoneyValue($value) {
        if (is_object($value) && method_exists($value, 'getValue')) {
            return $value->getValue();
        }
        return is_numeric($value) ? $value : 0;
    }
@endphp

<div class="financial-dashboard-widget">
    <div class="row g-3">
        {{-- Current Balance Card --}}
        <div class="col-md-6 col-lg-3">
            <div class="card financial-card balance-card">
                <div class="card-body text-center">
                    <div class="financial-icon">
                        <i class="fas fa-wallet fa-2x text-success"></i>
                    </div>
                    <h3 class="financial-value text-success">
                        {{ $financial_data['current_balance'] }}
                    </h3>
                    <p class="financial-label mb-0">Account Balance</p>
                    @if(is_object($financial_data['current_balance']) && method_exists($financial_data['current_balance'], 'getValue') && $financial_data['current_balance']->getValue() > 0)
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> Positive Balance
                        </small>
                    @endif
                </div>
            </div>
        </div>

        {{-- Monthly Earnings Card --}}
        <div class="col-md-6 col-lg-3">
            <div class="card financial-card earnings-card">
                <div class="card-body text-center">
                    <div class="financial-icon">
                        <i class="fas fa-chart-line fa-2x text-primary"></i>
                    </div>
                    <h3 class="financial-value text-primary">
                        @php
                            $monthlyEarnings = $financial_data['monthly_data']['earnings'] ?? 0;
                            $monthlyAmount = 0;
                            
                            if (is_object($monthlyEarnings) && method_exists($monthlyEarnings, 'getValue')) {
                                $monthlyAmount = $monthlyEarnings->getValue();
                            } elseif (is_numeric($monthlyEarnings)) {
                                $monthlyAmount = $monthlyEarnings;
                            }
                        @endphp
                        ${{ number_format($monthlyAmount, 2) }}
                    </h3>
                    <p class="financial-label mb-0">This Month</p>
                    <small class="text-muted">
                        {{ $financial_data['monthly_data']['flights'] }} flights
                    </small>
                </div>
            </div>
        </div>

        {{-- Average per Flight Card --}}
        <div class="col-md-6 col-lg-3">
            <div class="card financial-card average-card">
                <div class="card-body text-center">
                    <div class="financial-icon">
                        <i class="fas fa-calculator fa-2x text-info"></i>
                    </div>
                    <h3 class="financial-value text-info">
                        ${{ number_format(safeGetMoneyValue($financial_data['monthly_data']['avg_per_flight'] ?? 0), 0) }}
                    </h3>
                    <p class="financial-label mb-0">Avg per Flight</p>
                    <small class="text-muted">Monthly average</small>
                </div>
            </div>
        </div>

        {{-- Bonus Pool Card --}}
        <div class="col-md-6 col-lg-3">
            <div class="card financial-card bonus-card">
                <div class="card-body text-center">
                    <div class="financial-icon">
                        <i class="fas fa-trophy fa-2x text-warning"></i>
                    </div>
                    <h3 class="financial-value text-warning">
                        ${{ number_format(safeGetMoneyValue($financial_data['bonus_data']['bonus_pool'] ?? 0), 0) }}
                    </h3>
                    <p class="financial-label mb-0">Bonus Pool</p>
                    <small class="text-success">
                        {{ $financial_data['bonus_data']['on_time_percentage'] }}% On-time
                    </small>
                </div>
            </div>
        </div>
    </div>

    @if($config['show_monthly_breakdown'])
    {{-- Monthly Performance Breakdown --}}
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar"></i>
                        Monthly Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="performance-metric">
                                <div class="metric-label">Monthly Earnings</div>
                                <div class="metric-value text-success">
                                    ${{ number_format(safeGetMoneyValue($financial_data['monthly_data']['earnings'] ?? 0), 2) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="performance-metric">
                                <div class="metric-label">Flights Completed</div>
                                <div class="metric-value text-primary">
                                    {{ $financial_data['monthly_data']['flights'] }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="performance-metric">
                                <div class="metric-label">Last Salary</div>
                                <div class="metric-value text-info">
                                    ${{ number_format(safeGetMoneyValue($financial_data['monthly_data']['last_salary'] ?? 0), 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($config['show_rank_multiplier'])
                    <hr>
                    <div class="rank-bonus-info">
                        <h6 class="text-muted mb-2">Rank Benefits</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="badge bg-secondary me-2">{{ $financial_data['rank_data']['rank_name'] }}</span>
                                <small class="text-muted">Current Rank</small>
                            </div>
                            <div class="col-md-6">
                                <span class="text-success fw-bold">+{{ $financial_data['rank_data']['bonus_percentage'] }}%</span>
                                <small class="text-muted">Rank Bonus</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-award"></i>
                        Performance Bonus
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="bonus-circle">
                        <div class="percentage">{{ $financial_data['bonus_data']['on_time_percentage'] }}%</div>
                        <div class="label">On-Time</div>
                    </div>
                    <p class="mt-3 mb-2">
                        <strong class="text-success">
                            ${{ number_format(safeGetMoneyValue($financial_data['bonus_data']['bonus_pool'] ?? 0), 0) }}
                        </strong>
                        Bonus Available
                    </p>
                    @if($financial_data['bonus_data']['performance_bonus'] > 0)
                        <small class="text-success">
                            <i class="fas fa-star"></i>
                            +{{ $financial_data['bonus_data']['performance_bonus'] }}% Performance Multiplier
                        </small>
                    @else
                        <small class="text-muted">
                            Maintain 85%+ on-time for bonus multiplier
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($config['show_bonus_details'])
    {{-- Quick Financial Stats --}}
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="financial-summary">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="summary-item">
                            <span class="summary-value">${{ number_format(safeGetMoneyValue($financial_data['performance_data']['total_earned'] ?? 0), 0) }}</span>
                            <span class="summary-label">Total Earned</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-item">
                            <span class="summary-value">${{ number_format(safeGetMoneyValue($financial_data['performance_data']['avg_per_flight'] ?? 0), 0) }}</span>
                            <span class="summary-label">Career Avg/Flight</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-item">
                            <span class="summary-value">${{ number_format(safeGetMoneyValue($financial_data['performance_data']['best_flight'] ?? 0), 0) }}</span>
                            <span class="summary-label">Best Flight Pay</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-item">
                            <span class="summary-value">{{ $user->flights }}</span>
                            <span class="summary-label">Total Flights</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.financial-dashboard-widget .financial-card {
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.financial-dashboard-widget .financial-card:hover {
    transform: translateY(-5px);
}

.financial-dashboard-widget .financial-icon {
    margin-bottom: 10px;
}

.financial-dashboard-widget .financial-value {
    font-size: 1.8rem;
    font-weight: bold;
    margin: 10px 0;
}

.financial-dashboard-widget .financial-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
}

.financial-dashboard-widget .balance-card {
    background: linear-gradient(135deg, #e8f5e8, #f8fff8);
}

.financial-dashboard-widget .earnings-card {
    background: linear-gradient(135deg, #e3f2fd, #f3f9ff);
}

.financial-dashboard-widget .average-card {
    background: linear-gradient(135deg, #e0f2f1, #f0fffe);
}

.financial-dashboard-widget .bonus-card {
    background: linear-gradient(135deg, #fff3e0, #fffaf0);
}

.financial-dashboard-widget .performance-metric {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.financial-dashboard-widget .metric-label {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 5px;
}

.financial-dashboard-widget .metric-value {
    font-size: 1.4rem;
    font-weight: bold;
}

.financial-dashboard-widget .bonus-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: conic-gradient(from 0deg, #28a745 0deg {{ $financial_data['bonus_data']['on_time_percentage'] * 3.6 }}deg, #e9ecef {{ $financial_data['bonus_data']['on_time_percentage'] * 3.6 }}deg 360deg);
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #28a745;
    font-weight: bold;
}

.financial-dashboard-widget .percentage {
    font-size: 1.2rem;
    font-weight: bold;
}

.financial-dashboard-widget .label {
    font-size: 0.7rem;
}

.financial-dashboard-widget .financial-summary {
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.financial-dashboard-widget .summary-item {
    display: flex;
    flex-direction: column;
}

.financial-dashboard-widget .summary-value {
    font-size: 1.3rem;
    font-weight: bold;
    color: #007bff;
}

.financial-dashboard-widget .summary-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 3px;
}

.financial-dashboard-widget .rank-bonus-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}
</style>