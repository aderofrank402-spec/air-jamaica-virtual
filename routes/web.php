<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Frontend\AirportController;
use App\Http\Controllers\Frontend\CreditsController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\DownloadController;
use App\Http\Controllers\Frontend\FlightController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LanguageController;
use App\Http\Controllers\Frontend\LiveMapController;
use App\Http\Controllers\Frontend\NotamController;
use App\Http\Controllers\Frontend\OperationsManualController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\PirepController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\SimBriefController;
use App\Http\Controllers\Frontend\TrainingController;
use App\Http\Controllers\Frontend\AircraftDocumentationController;
use App\Http\Controllers\Frontend\UserController;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => '',
    'as'         => 'frontend.',
    'middleware' => (config('ajv.registration.email_verification', false) ? ['auth', 'verified'] : ['auth']),
], function () {
    // Custom dashboard routes for dedicated pages
    Route::get('dashboard/notams', [DashboardController::class, 'notams'])->name('dashboard.notams');
    Route::get('dashboard/news', [DashboardController::class, 'news'])->name('dashboard.news');
    Route::get('dashboard/activity', [DashboardController::class, 'activity'])->name('dashboard.activity');
    
    // API route for dashboard modals
    Route::get('api/user/flights', [DashboardController::class, 'getFlights'])->name('api.user.flights');
    
    // Built-in NOTAM system routes
    Route::get('notams', [NotamController::class, 'index'])->name('notams.index');
    Route::get('notams/{id}', [NotamController::class, 'show'])->name('notams.show');
    
    Route::resource('dashboard', DashboardController::class);
    Route::get('dashboard-enhanced', [DashboardController::class, 'enhanced'])->name('dashboard.enhanced');

    Route::get('airports/{id}', [AirportController::class, 'show'])->name('airports.show');

    Route::get('downloads', [DownloadController::class, 'index'])->name('downloads.index');
    Route::get('downloads/{id}', [DownloadController::class, 'show'])->name('downloads.download');

    Route::get('flights/bids', [FlightController::class, 'bids'])->name('flights.bids');
    Route::get('flights/search', [FlightController::class, 'search'])->name('flights.search');
    Route::resource('flights', FlightController::class);

    Route::get('pireps/fares', [PirepController::class, 'fares']);
    Route::post('pireps/{id}/submit', [PirepController::class, 'submit'])->name('pireps.submit');

    Route::resource('pireps', PirepController::class, [
        'except' => ['show'],
    ]);

    Route::get('profile/acars', [ProfileController::class, 'acars'])->name('profile.acars');
    Route::get('profile/regen_apikey', [ProfileController::class, 'regen_apikey'])->name('profile.regen_apikey');
    // Awards page for the authenticated user
    Route::get('profile/awards', [ProfileController::class, 'awards'])->name('profile.awards');
    
    Route::resource('profile', ProfileController::class)->except(['show', 'edit']);
    Route::get('profile/{profile}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('profile/{id}', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('simbrief/generate', [SimBriefController::class, 'generate'])->name('simbrief.generate');
    Route::post('simbrief/submit', [SimBriefController::class, 'submit'])->name('simbrief.submit');
    Route::get('simbrief/callback', [SimBriefController::class, 'callback'])->name('simbrief.callback');
    Route::get('simbrief/api', [SimBriefController::class, 'api'])->name('simbrief.api');
    Route::post('simbrief/apicode', [SimBriefController::class, 'api_code'])->name('simbrief.api_code');
    Route::get('simbrief/check_ofp', [SimBriefController::class, 'check_ofp'])->name('simbrief.check_ofp')->middleware('throttle:10,1');
    Route::get('simbrief/update_ofp', [SimBriefController::class, 'update_ofp'])->name('simbrief.update_ofp');
    Route::get('simbrief/{id}', [SimBriefController::class, 'briefing'])->name('simbrief.briefing');
    Route::get('simbrief/{id}/prefile', [SimBriefController::class, 'prefile'])->name('simbrief.prefile');
    Route::get('simbrief/{id}/cancel', [SimBriefController::class, 'cancel'])->name('simbrief.cancel');
    Route::get('simbrief/{id}/generate_new', [SimBriefController::class, 'generate_new'])->name('simbrief.generate_new');

    // Training System Routes
    Route::prefix('training')->name('training.')->group(function () {
        Route::get('/', [TrainingController::class, 'index'])->name('index');
        Route::get('courses', [TrainingController::class, 'courses'])->name('courses');
        Route::get('courses/{course}', [TrainingController::class, 'course'])->name('course');
        Route::post('courses/{course}/enroll', [TrainingController::class, 'enroll'])->name('enroll');
        Route::get('courses/{course}/modules/{module}', [TrainingController::class, 'module'])->name('module');
        Route::post('courses/{course}/modules/{module}/complete', [TrainingController::class, 'completeModule'])->name('module.complete');
        Route::get('courses/{course}/assessments/{assessment}', [TrainingController::class, 'assessment'])->name('assessment');
        Route::post('courses/{course}/assessments/{assessment}', [TrainingController::class, 'submitAssessment'])->name('assessment.submit');
        Route::get('certifications', [TrainingController::class, 'certifications'])->name('certifications');
    });

    // Aircraft Documentation Routes
    Route::prefix('aircraft-documentation')->name('aircraft-documentation.')->group(function () {
        Route::get('/', [AircraftDocumentationController::class, 'index'])->name('index');
        Route::get('search', [AircraftDocumentationController::class, 'search'])->name('search');
        Route::get('category/{category}', [AircraftDocumentationController::class, 'category'])->name('category');
        Route::get('{aircraft}', [AircraftDocumentationController::class, 'aircraft'])->name('aircraft');
        Route::get('{aircraft}/{category}/{document}', [AircraftDocumentationController::class, 'view'])->name('view');
        Route::get('{aircraft}/{category}/{document}/download', [AircraftDocumentationController::class, 'download'])->name('download');
    });
});

Route::group([
    'prefix' => '',
    'as'     => 'frontend.',
], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('r/{id}', [PirepController::class, 'show'])->name('pirep.show.public');
    Route::get('pireps/{id}', [PirepController::class, 'show'])->name('pireps.show');

    Route::get('users/{id}', [ProfileController::class, 'show'])->name('users.show.public');
    Route::get('pilots/{id}', [ProfileController::class, 'show'])->name('pilots.show.public');

    Route::get('page/{slug}', [PageController::class, 'show'])->name('pages.show');

    // Public pilot profile route - RENAMED to avoid conflict with profile resource routes
    Route::get('pilot/{id}', [ProfileController::class, 'show'])->name('pilot.show.public');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('pilots', [UserController::class, 'index'])->name('pilots.index')->middleware('auth');

    Route::get('livemap', [LiveMapController::class, 'index'])->name('livemap.index');

    // Operations Manual routes
    Route::get('operations', [OperationsManualController::class, 'index'])->name('operations.index');
    Route::get('operations/{section}', [OperationsManualController::class, 'section'])->name('operations.section');

    Route::get('lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');

    Route::get('credits', [CreditsController::class, 'index'])->name('credits');

    // Custom pages routes
    Route::get('about', function () {
        return view('public.about');
    })->name('about');

    Route::get('contact', function () {
        return view('public.contact');
    })->name('contact');

    Route::get('privacy', function () {
        return view('public.privacy');
    })->name('privacy');

    Route::get('pilot-manual', function () {
        $ranks = \App\Models\Rank::orderBy('hours', 'asc')->get();
        // Get all aircraft regardless of active status, and handle subfleet relationship
        $aircraft = \App\Models\Aircraft::with('subfleet')->orderBy('name')->get();
        $airports = \App\Models\Airport::orderBy('id')->get();
        $routes = \App\Models\Flight::with(['dpt_airport', 'arr_airport', 'subfleets'])->where('active', 1)->get();
        $trainingCourses = \App\Models\TrainingCourse::where('is_active', true)->orderBy('title')->get();
        $user = auth()->check() ? auth()->user() : null;
        
        // Get total distance as numeric value
        $totalDistance = \App\Models\Pirep::where('state', \App\Models\Enums\PirepState::ACCEPTED)->sum('distance');
        $totalDistance = is_object($totalDistance) ? (float) $totalDistance : $totalDistance;
        
        $stats = [
            'total_flights' => \App\Models\Pirep::where('state', \App\Models\Enums\PirepState::ACCEPTED)->count(),
            'total_hours' => \App\Models\Pirep::where('state', \App\Models\Enums\PirepState::ACCEPTED)->sum('flight_time'),
            'total_distance' => $totalDistance,
            'active_pilots' => \App\Models\User::where('state', \App\Models\Enums\UserState::ACTIVE)->count()
        ];
        return view('public.pilot-manual', compact('ranks', 'aircraft', 'airports', 'routes', 'trainingCourses', 'user', 'stats'));
    })->name('pilot-manual');

    Route::get('faq', function () {
        // Get real data for FAQ
        $stats = [
            'total_pilots' => \App\Models\User::where('state', \App\Models\Enums\UserState::ACTIVE)->count(),
            'total_aircraft' => \App\Models\Aircraft::count(),
            'total_routes' => \App\Models\Flight::where('active', 1)->count(),
            'total_airports' => \App\Models\Airport::count(),
            'total_flights_completed' => \App\Models\Pirep::where('state', \App\Models\Enums\PirepState::ACCEPTED)->count()
        ];
        $ranks = \App\Models\Rank::orderBy('hours', 'asc')->take(3)->get(); // Show first 3 ranks
        $aircraft_types = \App\Models\Aircraft::with('subfleet')->get()->groupBy('subfleet.name');
        $hubs = \App\Models\Airport::whereIn('id', \App\Models\User::pluck('home_airport_id')->unique())->get();
        
        return view('public.faq', compact('stats', 'ranks', 'aircraft_types', 'hubs'));
    })->name('faq');

    Route::get('partners', function () {
        return view('public.partners');
    })->name('partners');
});

Route::group([
    'prefix' => 'oauth',
    'as'     => 'oauth.',
], function () {
    Route::get('{provider}/redirect', [OAuthController::class, 'redirectToProvider'])->name('redirect');
    Route::get('{provider}/callback', [OAuthController::class, 'handleProviderCallback'])->name('callback');
    Route::get('{provider}/logout', [OAuthController::class, 'logoutProvider'])->name('logout')->middleware('auth');
});

// Widget test route (temporary for development)
Route::get('/widget-test', function () {
    return view('widget-test');
})->name('widget.test');

// Enhanced widget test route (temporary for development)
Route::get('/widget-test-enhanced', function () {
    return view('widget-test-enhanced');
})->name('widget.test.enhanced')->middleware('auth');

Route::get('/logout', [LoginController::class, 'logout'])->name(Logout::class);
Auth::routes(['verify' => true]);

Route::get('/update', function () {
    return redirect('/system/update');
});

