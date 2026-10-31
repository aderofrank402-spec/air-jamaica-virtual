<?php

namespace App\Widgets;

use App\Contracts\Widget;
use App\Models\Pirep;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Financial Dashboard Widget - Comprehensive pilot economics
 */
class FinancialDashboard extends Widget
{
    protected $config = [
        'show_monthly_breakdown' => true,
        'show_bonus_details' => true,
        'show_rank_multiplier' => true,
        'user_id' => null, // Allow specifying a user ID
    ];

    public function run()
    {
        // Get user from config if provided, otherwise use authenticated user
        $userId = $this->config['user_id'] ?? null;
        
        if ($userId) {
            /** @var User|null $user */
            $user = User::find($userId);
            if (!$user) {
                return view('widgets.financial_dashboard', [
                    'user' => null,
                    'financial_data' => [],
                    'config' => $this->config,
                    'error' => 'User not found',
                ]);
            }
        } else {
            /** @var User|null $user */
            $user = Auth::user();
            if (!$user) {
                return view('widgets.financial_dashboard', [
                    'user' => null,
                    'financial_data' => [],
                    'config' => $this->config,
                    'error' => 'User not authenticated',
                ]);
            }
        }
        
        $user->loadMissing(['journal', 'rank']);

        $financial_data = [
            'current_balance' => optional($user->journal)->balance ?? 0,
            'monthly_data' => $this->getMonthlyData($user),
            'bonus_data' => $this->getBonusData($user),
            'performance_data' => $this->getPerformanceFinancials($user),
            'rank_data' => $this->getRankFinancials($user),
        ];

        return view('widgets.financial_dashboard', [
            'user' => $user,
            'financial_data' => $financial_data,
            'config' => $this->config,
        ]);
    }

    /**
     * Get monthly financial data
     */
    private function getMonthlyData(User $user): array
    {
        $start_of_month = Carbon::now()->startOfMonth();

        $monthly_pireps = Pirep::where('user_id', $user->id)
            ->where('created_at', '>=', $start_of_month)
            ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
            ->get();

        $monthly_earnings = $monthly_pireps->sum('pilot_pay') ?? 0;
        $monthly_flights = $monthly_pireps->count();
        $avg_per_flight = $monthly_flights > 0 ? ($monthly_earnings / $monthly_flights) : 0;

        return [
            'earnings' => $monthly_earnings,
            'flights' => $monthly_flights,
            'avg_per_flight' => $avg_per_flight,
            'last_salary' => $monthly_pireps->last()->pilot_pay ?? 0,
        ];
    }

    /**
     * Get bonus and incentive data
     */
    private function getBonusData(User $user): array
    {
        // Calculate on-time bonus (example calculation)
        $recent_flights = Pirep::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
            ->get();

        $on_time_count = 0;
        foreach ($recent_flights as $pirep) {
            // Simplified on-time calculation
            if ($pirep->score > 80) {
                $on_time_count++;
            }
        }

        $on_time_percentage = $recent_flights->count() > 0 ?
            ($on_time_count / $recent_flights->count()) * 100 : 0;

        $bonus_pool = 0;
        if ($on_time_percentage >= 90) {
            $bonus_pool = $recent_flights->count() * 50; // $50 per flight bonus
        }

        return [
            'on_time_percentage' => round($on_time_percentage),
            'bonus_pool' => $bonus_pool,
            'performance_bonus' => $on_time_percentage >= 85 ? 15 : 0, // 15% bonus
        ];
    }

    /**
     * Get performance-based financial data
     */
    private function getPerformanceFinancials(User $user): array
    {
        $total_earnings = optional($user->journal)->balance->getValue() ?? 0;
        $total_flights = $user->flights;

        $avg_per_flight = $total_flights > 0 ? ($total_earnings / $total_flights) : 0;

        return [
            'total_earned' => $total_earnings,
            'avg_per_flight' => $avg_per_flight,
            'best_flight' => Pirep::where('user_id', $user->id)
                ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
                ->orderBy('pilot_pay', 'desc')
                ->value('pilot_pay') ?? 0,
        ];
    }

    /**
     * Get rank-based financial information
     */
    private function getRankFinancials(User $user): array
    {
        $rank_multiplier = 1.0;
        $rank_bonus = 0;

        if ($user->rank) {
            // Example rank multipliers
            switch ($user->rank->name) {
                case 'Captain':
                    $rank_multiplier = 1.15;
                    $rank_bonus = 15;
                    break;
                case 'Senior Captain':
                    $rank_multiplier = 1.25;
                    $rank_bonus = 25;
                    break;
                case 'Check Captain':
                    $rank_multiplier = 1.35;
                    $rank_bonus = 35;
                    break;
                default:
                    $rank_multiplier = 1.0;
                    $rank_bonus = 0;
            }
        }

        return [
            'rank_name' => optional($user->rank)->name ?? 'First Officer',
            'multiplier' => $rank_multiplier,
            'bonus_percentage' => $rank_bonus,
        ];
    }
}