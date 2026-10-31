<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperationsManualController extends Controller
{
    /**
     * Display the operations manual index
     */
    public function index(Request $request): View
    {
        return view('operations.index');
    }

    /**
     * Display a specific section of the operations manual
     */
    public function section(Request $request, string $section): View
    {
        // Validate section exists
        $validSections = [
            'general-operations',
            'flight-procedures',
            'aircraft-procedures',
            'route-network',
            'administrative',
            'quality-assurance',
            'emergency-procedures',
            'technical-operations'
        ];

        if (!in_array($section, $validSections)) {
            abort(404);
        }

        return view('operations.section', [
            'section' => $section,
            'sectionTitle' => $this->getSectionTitle($section)
        ]);
    }

    /**
     * Get the title for a section
     */
    private function getSectionTitle(string $section): string
    {
        $titles = [
            'general-operations' => 'General Operations',
            'flight-procedures' => 'Flight Operations Procedures',
            'aircraft-procedures' => 'Aircraft-Specific Procedures',
            'route-network' => 'Route & Network Information',
            'administrative' => 'Administrative Procedures',
            'quality-assurance' => 'Quality Assurance',
            'emergency-procedures' => 'Emergency Procedures',
            'technical-operations' => 'Technical Operations'
        ];

        return $titles[$section] ?? 'Operations Manual';
    }
}