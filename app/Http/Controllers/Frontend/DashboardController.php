<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Models\User;
use App\Models\Bid;
use App\Models\Pirep;
use App\Models\Acars;
use App\Repositories\PirepRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Class DashboardController
 */
class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct(
        private readonly PirepRepository $pirepRepo
    ) {}

    /**
     * Show the application dashboard.
     */
    public function index(): View
    {
        $last_pirep = null;
        // Support retrieval of deleted relationships
        $with_pirep = [
            'aircraft' => function ($query) {
                return $query->withTrashed();
            },
            'arr_airport' => function ($query) {
                return $query->withTrashed();
            },
            'comments',
            'dpt_airport' => function ($query) {
                return $query->withTrashed();
            },
        ];

        /** @var User $user */
        $user = Auth::user();
        $user->loadMissing(['journal', 'airline', 'rank', 'home_airport', 'current_airport']);

        try {
            $last_pirep = $this->pirepRepo->with($with_pirep)->find($user->last_pirep_id);
        } catch (Exception $e) {
        }

        // Get the current airport for the weather
        $current_airport = $user->curr_airport_id ?? $user->home_airport_id;

        // Get current bids for enhanced dashboard
        $current_bids = Bid::where(['user_id' => $user->id])
            ->with(['flight.dpt_airport', 'flight.arr_airport', 'flight.airline'])
            ->get();

        // Get additional statistics for enhanced dashboard
        $awards_count = $user->awards()->count();
        $pending_pireps_count = Pirep::where('user_id', $user->id)
            ->where('state', '!=', 2) // Not accepted
            ->count();
        
        // Calculate average score from PIREPs (only those with scores)
        $avg_score = Pirep::where('user_id', $user->id)
            ->where('state', 2) // Accepted PIREPs only
            ->whereNotNull('score') // Only PIREPs with scores
            ->avg('score') ?? 0;

        // Financial data from Journal system
        $financial_data = [
            'current_balance' => optional($user->journal)->balance ?? 0,
            'monthly_earnings' => $this->getMonthlyEarnings($user),
            'total_earnings' => $this->getTotalEarnings($user),
        ];

        // Active flight data (ACARS)
        $active_pirep = Pirep::where('user_id', $user->id)
            ->whereIn('state', [0, 1, 3]) // Draft, In Progress, Submitted
            ->with(['flight.dpt_airport', 'flight.arr_airport'])
            ->first();

        $flight_status = null;
        if ($active_pirep) {
            $latest_acars = Acars::where('pirep_id', $active_pirep->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $flight_status = [
                'pirep' => $active_pirep,
                'acars' => $latest_acars,
                'phase' => $this->determineFlightPhase($latest_acars),
                'progress' => $this->calculateProgress($active_pirep, $latest_acars),
            ];
        }

        // Latest news and awards
        $latest_news = \App\Models\News::with('user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $recent_awards = \App\Models\UserAward::with(['award', 'user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Network statistics
        $network_stats = [
            'pilots_online' => \App\Models\User::where('last_pirep_id', '!=', null)
                ->where('updated_at', '>=', now()->subHours(2))
                ->count(),
            'active_flights' => Pirep::whereIn('state', [0, 1, 3])->count(),
            'total_pilots' => \App\Models\User::where('state', 1)->count(),
        ];

        return view('layouts.seven.dashboard.index', [
            'user' => $user,
            'current_airport' => $current_airport,
            'last_pirep' => $last_pirep,
            'current_bids' => $current_bids,
            'awards_count' => $awards_count,
            'pending_pireps_count' => $pending_pireps_count,
            'avg_score' => $avg_score,
            'financial_data' => $financial_data,
            'flight_status' => $flight_status,
            'latest_news' => $latest_news,
            'recent_awards' => $recent_awards,
            'network_stats' => $network_stats,
        ]);
    }

    /**
     * Get monthly earnings for the user
     */
    private function getMonthlyEarnings(User $user)
    {
        if (!$user->journal) {
            return 0;
        }

        $start_of_month = Carbon::now()->startOfMonth();
        
        $sum = $user->journal->transactions()
            ->where('created_at', '>=', $start_of_month)
            ->where('memo', 'like', '%Pilot Pay%')
            ->sum('credit') ?? 0;
            
        return is_numeric($sum) ? $sum : 0;
    }

    /**
     * Get total earnings for the user
     */
    private function getTotalEarnings(User $user)
    {
        if (!$user->journal) {
            return 0;
        }

        $sum = $user->journal->transactions()
            ->where('memo', 'like', '%Pilot Pay%')
            ->sum('credit') ?? 0;
            
        return is_numeric($sum) ? $sum : 0;
    }

    /**
     * Determine flight phase based on ACARS data
     */
    private function determineFlightPhase($acars): string
    {
        if (!$acars) {
            return 'PREFLIGHT';
        }

        $altitude = $acars->altitude ?? 0;
        $groundspeed = $acars->gs ?? 0;

        if ($altitude < 1000 && $groundspeed < 50) {
            return 'TAXI';
        } elseif ($altitude < 1000 && $groundspeed > 50) {
            return 'TAKEOFF';
        } elseif ($altitude > 10000) {
            return 'CRUISE';
        } elseif ($altitude < 10000 && $altitude > 1000) {
            return 'DESCENT';
        }

        return 'EN-ROUTE';
    }

    /**
     * Calculate flight progress percentage
     */
    private function calculateProgress($pirep, $acars): int
    {
        if (!$pirep || !$acars || !$pirep->flight) {
            return 0;
        }

        // Simple progress calculation based on time elapsed
        $flight_time = $pirep->created_at->diffInMinutes(now());
        $estimated_flight_time = (float) ($pirep->flight->flight_time ?? 120); // Default 2 hours
        
        // Prevent division by zero
        if ($estimated_flight_time <= 0) {
            $estimated_flight_time = 120;
        }
        
        return (int) min(($flight_time / $estimated_flight_time) * 100, 100);
    }

    /**
     * Get user's flight history for the modal (API endpoint)
     */
    public function getFlights(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get user's PIREPs with relationships
        $pireps = Pirep::where('user_id', $user->id)
            ->with([
                'aircraft' => function ($query) {
                    return $query->withTrashed();
                },
                'arr_airport' => function ($query) {
                    return $query->withTrashed();
                },
                'dpt_airport' => function ($query) {
                    return $query->withTrashed();
                },
                'flight',
                'airline'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Format flights data
        $flights = $pireps->map(function ($pirep) {
            return [
                'id' => $pirep->id,
                'flight_number' => $pirep->flight->ident ?? 'N/A',
                'airline' => $pirep->airline->name ?? 'N/A',
                'dpt_airport' => $pirep->dpt_airport_id,
                'arr_airport' => $pirep->arr_airport_id,
                'route' => $pirep->dpt_airport_id . ' → ' . $pirep->arr_airport_id,
                'route_code' => $pirep->route ?? 'Direct',
                'aircraft' => $pirep->aircraft->name ?? 'N/A',
                'aircraft_type' => $pirep->aircraft->icao ?? '',
                'flight_time' => $pirep->flight_time ? \App\Support\Units\Time::minutesToTimeString($pirep->flight_time) : 'N/A',
                'distance' => $pirep->distance ? round($pirep->distance->local()) : null,
                'score' => $pirep->score,
                'status' => $pirep->state,
                'created_at' => $pirep->created_at,
                'submitted_at' => $pirep->submitted_at,
            ];
        });

        // Calculate summary statistics
        $summary = [
            'total_flights' => $pireps->count(),
            'total_hours' => \App\Support\Units\Time::minutesToTimeString($user->flight_time),
            'total_distance' => round($pireps->sum(function ($pirep) {
                return $pirep->distance ? $pirep->distance->local() : 0;
            })),
            'avg_score' => round($pireps->where('state', 2)->avg('score') ?? 0, 1)
        ];

        return response()->json([
            'flights' => $flights,
            'summary' => $summary
        ]);
    }

    /**
     * Show the news page
     */
    public function news(): View
    {
        $news = \App\Models\News::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.news.index', [
            'news' => $news
        ]);
    }
}
