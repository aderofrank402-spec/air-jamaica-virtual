<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Models\AircraftType;
use App\Models\AircraftDocument;
use App\Models\AircraftDocumentCategory;
use App\Models\AircraftDocumentAccessLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AircraftDocumentationController extends Controller
{
    public function index()
    {
        $aircraftTypes = AircraftType::where('is_active', true)
            ->withCount('documents')
            ->orderBy('manufacturer')
            ->orderBy('model')
            ->get();

        $categories = AircraftDocumentCategory::where('is_active', true)
            ->withCount('documents')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $featuredDocuments = AircraftDocument::where('is_active', true)
            ->where('is_featured', true)
            ->with(['aircraftType', 'category'])
            ->orderBy('download_count', 'desc')
            ->limit(6)
            ->get();

        $totalDocuments = AircraftDocument::where('is_active', true)->count();

        // Recent documents for the dashboard
        $recentDocuments = AircraftDocument::where('is_active', true)
            ->with(['aircraftType', 'category'])
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        // Statistics for the dashboard cards
        $stats = [
            'aircraft_types' => $aircraftTypes->count(),
            'categories' => $categories->count(),
            'documents' => $totalDocuments,
        ];

        return view('frontend.aircraft-documentation.index', compact(
            'aircraftTypes',
            'categories', 
            'featuredDocuments',
            'totalDocuments',
            'recentDocuments',
            'stats'
        ));
    }

    public function aircraft($aircraftSlug)
    {
        // Try to find by slug first, then by ID as fallback
        $aircraftType = AircraftType::where('is_active', true)
            ->where(function ($query) use ($aircraftSlug) {
                $query->where('slug', $aircraftSlug)
                      ->orWhere('id', $aircraftSlug);
            })
            ->firstOrFail();

        $documents = AircraftDocument::where('aircraft_type_id', $aircraftType->id)
            ->where('is_active', true)
            ->with(['category'])
            ->orderBy('category_id')
            ->orderBy('title')
            ->get()
            ->groupBy('category.name');

        $categories = AircraftDocumentCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('frontend.aircraft-documentation.aircraft', compact(
            'aircraftType',
            'documents',
            'categories'
        ));
    }

    public function category($categorySlug)
    {
        // Try to find by slug first, then by ID as fallback
        $category = AircraftDocumentCategory::where('is_active', true)
            ->where(function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug)
                      ->orWhere('id', $categorySlug);
            })
            ->firstOrFail();

        $documents = AircraftDocument::where('category_id', $category->id)
            ->where('is_active', true)
            ->with(['aircraftType'])
            ->orderBy('aircraft_type_id')
            ->orderBy('title')
            ->paginate(12);

        $aircraftTypes = AircraftType::where('is_active', true)
            ->orderBy('manufacturer')
            ->orderBy('model')
            ->get();

        return view('frontend.aircraft-documentation.category', compact(
            'category',
            'documents',
            'aircraftTypes'
        ));
    }

    public function view($aircraftSlug, $categorySlug, $documentSlug)
    {
        $aircraftType = AircraftType::where('slug', $aircraftSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $category = AircraftDocumentCategory::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $document = AircraftDocument::where('aircraft_type_id', $aircraftType->id)
            ->where('category_id', $category->id)
            ->where('slug', $documentSlug)
            ->where('is_active', true)
            ->with(['aircraftType', 'category', 'uploader', 'versions'])
            ->firstOrFail();

        // Log the view
        if (Auth::check()) {
            AircraftDocumentAccessLog::create([
                'user_id' => Auth::id(),
                'document_id' => $document->id,
                'action' => 'view',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        $relatedDocuments = AircraftDocument::where('aircraft_type_id', $aircraftType->id)
            ->where('category_id', $category->id)
            ->where('id', '!=', $document->id)
            ->where('is_active', true)
            ->limit(5)
            ->get();

        return view('frontend.aircraft-documentation.view', compact(
            'document',
            'aircraftType',
            'category',
            'relatedDocuments'
        ));
    }

    public function download($aircraftSlug, $categorySlug, $documentSlug)
    {
        $aircraftType = AircraftType::where('slug', $aircraftSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $category = AircraftDocumentCategory::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $document = AircraftDocument::where('aircraft_type_id', $aircraftType->id)
            ->where('category_id', $category->id)
            ->where('slug', $documentSlug)
            ->where('is_active', true)
            ->firstOrFail();

        // For demo purposes, return a redirect with message
        // In production, you would check if Storage::exists($document->file_path)
        
        // Log the download
        if (Auth::check()) {
            AircraftDocumentAccessLog::create([
                'user_id' => Auth::id(),
                'document_id' => $document->id,
                'action' => 'download',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        // Increment download count
        $document->incrementDownloadCount();

        // For demo, redirect back with success message
        return redirect()->back()->with('success', "Download initiated for: {$document->title}");
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $aircraftFilter = $request->get('aircraft');
        $categoryFilter = $request->get('category');

        $documents = AircraftDocument::query()
            ->where('is_active', true)
            ->with(['aircraftType', 'category']);

        if ($query) {
            $documents->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });

            // Log the search
            if (Auth::check()) {
                AircraftDocumentAccessLog::create([
                    'user_id' => Auth::id(),
                    'document_id' => null,
                    'action' => 'search',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        }

        if ($aircraftFilter) {
            $documents->whereHas('aircraftType', function($q) use ($aircraftFilter) {
                $q->where('slug', $aircraftFilter);
            });
        }

        if ($categoryFilter) {
            $documents->whereHas('category', function($q) use ($categoryFilter) {
                $q->where('slug', $categoryFilter);
            });
        }

        $documents = $documents->orderBy('title')->paginate(12);

        $aircraftTypes = AircraftType::where('is_active', true)
            ->orderBy('manufacturer')
            ->orderBy('model')
            ->get();

        $categories = AircraftDocumentCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('frontend.aircraft-documentation.search', compact(
            'documents',
            'query',
            'aircraftFilter',
            'categoryFilter',
            'aircraftTypes',
            'categories'
        ));
    }
}
