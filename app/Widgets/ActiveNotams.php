<?php

namespace App\Widgets;

use App\Contracts\Widget;
use App\Repositories\NotamRepository;

/**
 * Show active NOTAMs (Notice to Airmen) in the dashboard
 */
class ActiveNotams extends Widget
{
    protected $config = [
        'count' => 5,
        'icao' => null, // If set, show NOTAMs for specific airport
        'show_critical_only' => false,
        'show_priority_badges' => true,
        'show_airports' => true,
    ];

    /**
     * Get active NOTAMs to display
     */
    public function run()
    {
        /** @var NotamRepository $notamRepo */
        $notamRepo = app(NotamRepository::class);
        
        // Get NOTAMs based on configuration
        if ($this->config['show_critical_only']) {
            $notams = $notamRepo->getCritical();
        } elseif ($this->config['icao']) {
            $notams = $notamRepo->getActiveForAirport($this->config['icao']);
        } else {
            $notams = $notamRepo->getRecent($this->config['count']);
        }

        // Get summary statistics
        $stats = [
            'total_active' => $notamRepo->getAllActive()->count(),
            'critical' => $notamRepo->getCritical()->count(),
            'expiring_soon' => $notamRepo->getExpiringSoon()->count(),
        ];

        return view('widgets.active_notams', [
            'config' => $this->config,
            'notams' => $notams->take($this->config['count']),
            'stats' => $stats,
        ]);
    }
}