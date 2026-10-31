<?php

namespace App\Widgets;

use App\Contracts\Widget;
use App\Models\Pirep;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Performance Metrics Widget - Advanced pilot performance analytics
 */
class PerformanceMetrics extends Widget
{
    protected $config = [
        'show_trends' => true,
        'comparison_period' => 30, // days
        'show_goals' => true,
        'include_rankings' => false,
        'user_id' => null, // Allow specifying a user ID
    ];

    /**
     * Get performance metrics data
     */
    public function run()
    {
        // Get user from config if provided, otherwise use authenticated user
        $userId = $this->config['user_id'] ?? null;
        
        if ($userId) {
            /** @var User|null $user */
            $user = User::find($userId);
        } else {
            /** @var User|null $user */
            $user = Auth::user();
        }
        
        if (!$user) {
            return view('widgets.performance_metrics', [
                'config' => $this->config,
                'error' => $userId ? 'User not found' : 'User not authenticated',
                'updated_at' => Carbon::now(),
            ]);
        }

        $data = [
            'config' => $this->config,
            'user' => $user,
            'metrics' => $this->getPerformanceMetrics($user),
            'trends' => $this->config['show_trends'] ? $this->getTrendData($user) : null,
            'goals' => $this->config['show_goals'] ? $this->getGoalProgress($user) : null,
            'updated_at' => Carbon::now(),
        ];

        return view('widgets.performance_metrics', $data);
    }

    /**
     * Get comprehensive performance metrics
     */
    private function getPerformanceMetrics(User $user): array
    {
        // Get all accepted PIREPs from database
        $pireps = Pirep::where('user_id', $user->id)
            ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
            ->get();

        // Get PIREPs from current month (not last 30 days)
        $thisMonthPireps = Pirep::where('user_id', $user->id)
            ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->get();

        // Calculate on-time performance from database
        $onTimeFlights = $pireps->filter(function ($pirep) {
            return $this->isOnTime($pirep);
        });
        $onTimePercentage = $pireps->count() > 0 ? ($onTimeFlights->count() / $pireps->count()) * 100 : 0;

        // Calculate average landing rate from database
        $landingRates = $pireps->pluck('landing_rate')->filter()->values();
        $avgLandingRate = $landingRates->count() > 0 ? $landingRates->avg() : 0;

        // Flight hours this month from database (current month, not last 30 days)
        $thisMonthHours = $thisMonthPireps->sum('flight_time') / 60;

        // Safety rating calculation from database
        $safetyScore = $this->calculateSafetyRating($pireps);

        // Fuel efficiency from database
        $fuelEfficiency = $this->calculateFuelEfficiency($pireps);

        // Average score from database
        $averageScore = $pireps->count() > 0 ? ($pireps->avg('score') ?? 0) : 0;

        return [
            'on_time_percentage' => round($onTimePercentage, 1),
            'avg_landing_rate' => round($avgLandingRate),
            'monthly_hours' => round($thisMonthHours, 1),
            'safety_rating' => $safetyScore,
            'safety_stars' => $this->getStarRating($safetyScore),
            'fuel_efficiency' => round($fuelEfficiency, 1),
            'total_flights' => $pireps->count(),
            'perfect_landings' => $landingRates->filter(fn($rate) => abs($rate) <= 150)->count(),
            'average_score' => round($averageScore, 1),
        ];
    }

    /**
     * Get trend data for comparison - comparing current month vs previous month
     */
    private function getTrendData(User $user): array
    {
        // Current month (from database)
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentPireps = Pirep::where('user_id', $user->id)
            ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
            ->where('created_at', '>=', $currentMonthStart)
            ->get();

        // Previous month (from database)
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $previousPireps = Pirep::where('user_id', $user->id)
            ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->get();

        // Calculate trends from database data
        $currentOnTime = $currentPireps->count() > 0 
            ? ($currentPireps->filter(fn($p) => $this->isOnTime($p))->count() / $currentPireps->count()) * 100 
            : 0;
        
        $previousOnTime = $previousPireps->count() > 0 
            ? ($previousPireps->filter(fn($p) => $this->isOnTime($p))->count() / $previousPireps->count()) * 100 
            : 0;

        $currentLanding = $currentPireps->pluck('landing_rate')->filter()->avg() ?? 0;
        $previousLanding = $previousPireps->pluck('landing_rate')->filter()->avg() ?? 0;

        $currentHours = $currentPireps->sum('flight_time') / 60;
        $previousHours = $previousPireps->sum('flight_time') / 60;

        return [
            'on_time_trend' => $currentOnTime - $previousOnTime,
            'landing_trend' => $previousLanding - $currentLanding, // Better landing rate is lower (negative)
            'hours_trend' => $currentHours - $previousHours,
            'flights_trend' => $currentPireps->count() - $previousPireps->count(),
        ];
    }

    /**
     * Get goal progress - all data from database
     */
    private function getGoalProgress(User $user): array
    {
        // Get current month PIREPs from database
        $thisMonth = Pirep::where('user_id', $user->id)
            ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->get();

        $monthlyFlights = $thisMonth->count();
        $monthlyHours = $thisMonth->sum('flight_time') / 60;

        // Calculate dynamic goals based on user's total flight hours from database
        // Get total flight hours from accepted PIREPs
        $totalMinutes = Pirep::where('user_id', $user->id)
            ->where('state', \App\Models\Enums\PirepState::ACCEPTED)
            ->sum('flight_time') ?? 0;
        $totalHours = $totalMinutes / 60;

        // Set goals dynamically based on experience level
        // More experienced pilots get higher goals
        if ($totalHours >= 6000) {
            // Senior pilots: 25 hours, 12 flights
            $monthlyHoursGoal = 25;
            $monthlyFlightGoal = 12;
        } elseif ($totalHours >= 3000) {
            // Experienced pilots: 20 hours, 10 flights
            $monthlyHoursGoal = 20;
            $monthlyFlightGoal = 10;
        } elseif ($totalHours >= 1000) {
            // Intermediate pilots: 15 hours, 8 flights
            $monthlyHoursGoal = 15;
            $monthlyFlightGoal = 8;
        } else {
            // New pilots: 10 hours, 5 flights
            $monthlyHoursGoal = 10;
            $monthlyFlightGoal = 5;
        }

        return [
            'monthly_flights' => [
                'current' => $monthlyFlights,
                'goal' => $monthlyFlightGoal,
                'percentage' => $monthlyFlightGoal > 0 ? min(100, ($monthlyFlights / $monthlyFlightGoal) * 100) : 0,
            ],
            'monthly_hours' => [
                'current' => round($monthlyHours, 1),
                'goal' => $monthlyHoursGoal,
                'percentage' => $monthlyHoursGoal > 0 ? min(100, ($monthlyHours / $monthlyHoursGoal) * 100) : 0,
            ],
        ];
    }

    /**
     * Check if flight was on time
     */
    private function isOnTime(Pirep $pirep): bool
    {
        if (!$pirep->flight || !$pirep->flight->arr_time || !$pirep->arr_time) {
            return true; // Default to on-time if no scheduled time
        }

        $scheduledArrival = Carbon::createFromFormat('H:i:s', $pirep->flight->arr_time);
        $actualArrival = $pirep->arr_time;

        // Consider on-time if within 15 minutes
        return abs($scheduledArrival->diffInMinutes($actualArrival)) <= 15;
    }

    /**
     * Calculate safety rating
     */
    private function calculateSafetyRating(object $pireps): float
    {
        if ($pireps->count() === 0) {
            return 100;
        }

        $score = 100;
        
        // Deduct points for hard landings
        $hardLandings = $pireps->filter(fn($p) => abs($p->landing_rate ?? 0) > 300)->count();
        $score -= ($hardLandings / $pireps->count()) * 30;

        // Deduct points for low scores
        $avgScore = $pireps->avg('score') ?? 100;
        if ($avgScore < 80) {
            $score -= (80 - $avgScore);
        }

        return max(0, min(100, $score));
    }

    /**
     * Calculate fuel efficiency
     */
    private function calculateFuelEfficiency(object $pireps): float
    {
        if ($pireps->count() === 0) {
            return 100;
        }

        // Simple fuel efficiency calculation based on flight distance vs fuel used
        $efficiency = $pireps->map(function ($pirep) {
            $fuelUsed = $pirep->fuel_used->internal() ?? 0;
            $distance = $pirep->distance->internal() ?? 1;
            
            if ($fuelUsed == 0 || $distance == 0) {
                return 100;
            }

            // Calculate efficiency (lower fuel per distance = higher efficiency)
            $fuelPerMile = $fuelUsed / $distance;
            return max(0, min(100, 100 - ($fuelPerMile * 10))); // Adjust multiplier as needed
        });

        return $efficiency->avg();
    }

    /**
     * Convert score to star rating
     */
    private function getStarRating(float $score): string
    {
        $stars = round($score / 20); // Convert 0-100 to 0-5 stars
        return str_repeat('⭐', max(1, min(5, $stars)));
    }
}