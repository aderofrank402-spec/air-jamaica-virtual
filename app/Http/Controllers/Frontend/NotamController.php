<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Models\Notam;
use App\Repositories\NotamRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotamController extends Controller
{
    private NotamRepository $notamRepo;

    public function __construct(NotamRepository $notamRepo)
    {
        $this->notamRepo = $notamRepo;
    }

    /**
     * Display a listing of NOTAMs
     */
    public function index(Request $request): View
    {
        // Start with all active NOTAMs
        $notams = $this->notamRepo->getAllActive();
        
        // Apply filters if provided
        if ($request->has('icao') && $request->icao) {
            $notams = $this->notamRepo->getActiveForAirport($request->icao);
        } elseif ($request->has('priority') && $request->priority) {
            $notams = $this->notamRepo->getByPriority(strtoupper($request->priority));
        } elseif ($request->has('search') && $request->search) {
            $notams = $this->notamRepo->searchByText($request->search);
        }
        
        // Convert collection to paginator for consistency
        $perPage = 20;
        $page = request('page', 1);
        $total = $notams->count();
        $results = $notams->slice(($page - 1) * $perPage, $perPage);
        
        $notams = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );
        
        $stats = [
            'total_active' => $this->notamRepo->getAllActive()->count(),
            'critical' => $this->notamRepo->getCritical()->count(),
            'expiring_soon' => $this->notamRepo->getExpiringSoon()->count(),
        ];
        
        return view('frontend.notams.index', compact('notams', 'stats'));
    }

    /**
     * Display the specified NOTAM
     */
    public function show(string $id): View
    {
        $notam = $this->notamRepo->newQuery()
            ->with(['user', 'airport'])
            ->findOrFail($id);
        
        return view('frontend.notams.show', compact('notam'));
    }

    /**
     * Get NOTAMs for a specific airport (AJAX)
     */
    public function forAirport(string $icao)
    {
        $notams = $this->notamRepo->getActiveForAirport($icao);
        
        return response()->json([
            'data' => $notams->map(function ($notam) {
                return [
                    'id' => $notam->id,
                    'identifier' => $notam->identifier,
                    'title' => $notam->title,
                    'description' => $notam->description,
                    'priority' => $notam->priority,
                    'priority_color' => $notam->priority_color,
                    'effective_from' => $notam->effective_from->toISOString(),
                    'effective_until' => $notam->effective_until?->toISOString(),
                    'is_effective' => $notam->isEffective(),
                ];
            }),
        ]);
    }

    /**
     * Get critical NOTAMs (AJAX)
     */
    public function critical()
    {
        $notams = $this->notamRepo->getCritical();
        
        return response()->json([
            'data' => $notams->map(function ($notam) {
                return [
                    'id' => $notam->id,
                    'identifier' => $notam->identifier,
                    'icao_code' => $notam->icao_code,
                    'title' => $notam->title,
                    'description' => $notam->description,
                    'effective_from' => $notam->effective_from->toISOString(),
                    'effective_until' => $notam->effective_until?->toISOString(),
                ];
            }),
        ]);
    }
}