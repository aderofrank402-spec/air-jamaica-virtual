@extends('layouts.seven.public_app')
@section('title', 'Awards')

@section('css')
    {{-- Air Jamaica Virtual Awards Styles --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <style>
        /* Air Jamaica Virtual Branding Colors */
        :root {
            --jamaica-green: #00A651;
            --jamaica-yellow: #FED100;
            --jamaica-black: #000000;
            --jamaica-white: #FFFFFF;
            --jamaica-blue: #0056b3;
            --jamaica-light: #f8fafc;
            --jamaica-gray: #6c757d;
            --jamaica-dark: #212529;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --gradient-gold: linear-gradient(135deg, #FFD700, #FFA500);
            --gradient-green: linear-gradient(135deg, #00A651, #20c997);
            --gradient-blue: linear-gradient(135deg, #0056b3, #3399ff);
        }

        /* Body Styling */
        body {
            padding-top: 0 !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #2a2f3e 100%) !important;
            background-attachment: fixed;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Effects */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, 
                rgba(0, 166, 81, 0.1) 0%, 
                rgba(255, 215, 0, 0.05) 25%, 
                rgba(0, 166, 81, 0.08) 50%, 
                rgba(255, 215, 0, 0.03) 75%, 
                rgba(0, 166, 81, 0.1) 100%);
            z-index: -2;
            animation: backgroundShift 20s ease-in-out infinite;
        }

        /* Floating Particles Effect */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, rgba(0, 166, 81, 0.3), transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255, 215, 0, 0.3), transparent),
                radial-gradient(1px 1px at 90px 40px, rgba(0, 166, 81, 0.4), transparent),
                radial-gradient(1px 1px at 130px 80px, rgba(255, 215, 0, 0.4), transparent),
                radial-gradient(2px 2px at 160px 30px, rgba(0, 166, 81, 0.3), transparent);
            background-repeat: repeat;
            background-size: 200px 100px;
            z-index: -1;
            animation: particleFloat 15s linear infinite;
            opacity: 0.6;
        }

        @keyframes backgroundShift {
            0%, 100% { 
                opacity: 0.7;
                transform: scale(1);
            }
            50% { 
                opacity: 1;
                transform: scale(1.05);
            }
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(0px) translateX(0px);
            }
            25% {
                transform: translateY(-10px) translateX(5px);
            }
            50% {
                transform: translateY(-5px) translateX(-5px);
            }
            75% {
                transform: translateY(-15px) translateX(3px);
            }
            100% {
                transform: translateY(0px) translateX(0px);
            }
        }

        /* Awards Container */
        .jamaica-awards {
            padding: 120px 0 3rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Hero Section */
        .awards-hero {
            background: rgba(15, 20, 25, 0.3);
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 3rem;
            text-align: center;
            color: var(--jamaica-white);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .awards-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 166, 81, 0.1), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #ffffff, #ffd700);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 1px;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
            margin-bottom: 0;
            color: #a0a0a0;
        }

        /* Pilot Info Card */
        .pilot-info-card {
            background: rgba(26, 31, 46, 0.8);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(15px);
            position: relative;
            overflow: hidden;
        }

        .pilot-info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-gold);
        }

        .pilot-info-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .pilot-avatar {
            flex-shrink: 0;
        }

        .pilot-avatar img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid var(--jamaica-yellow);
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
        }

        .pilot-avatar i {
            color: var(--jamaica-yellow);
            font-size: 4rem;
        }

        .pilot-details {
            flex: 1;
            color: var(--jamaica-white);
        }

        .pilot-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--jamaica-white);
        }

        .pilot-id {
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .rank-info {
            text-align: right;
            color: var(--jamaica-white);
        }

        .rank-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--jamaica-green);
        }

        .rank-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(26, 31, 46, 0.8);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(15px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card.total::before {
            background: var(--gradient-gold);
        }

        .stat-card.recent::before {
            background: var(--gradient-green);
        }

        .stat-card.categories::before {
            background: var(--gradient-blue);
        }

        .stat-card.available::before {
            background: linear-gradient(90deg, #ff6b6b, #ee5a52);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .stat-card.total .stat-icon { color: var(--jamaica-yellow); }
        .stat-card.recent .stat-icon { color: var(--jamaica-green); }
        .stat-card.categories .stat-icon { color: var(--jamaica-blue); }
        .stat-card.available .stat-icon { color: #ff6b6b; }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--jamaica-white);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin: 0;
        }

        /* Navigation Tabs */
        .awards-tabs {
            background: rgba(26, 31, 46, 0.8);
            border-radius: 20px;
            padding: 1rem;
            margin-bottom: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
        }

        .nav-tabs {
            border: none;
            gap: 0.5rem;
        }

        .nav-tabs .nav-link {
            border: none;
            color: rgba(255, 255, 255, 0.7);
            padding: 1rem 1.5rem;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
        }

        .nav-tabs .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 166, 81, 0.1), transparent);
            transition: left 0.3s ease;
        }

        .nav-tabs .nav-link:hover::before {
            left: 100%;
        }

        .nav-tabs .nav-link:hover {
            color: var(--jamaica-white);
            background: rgba(0, 166, 81, 0.2);
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link.active {
            color: var(--jamaica-white);
            background: var(--gradient-green);
            box-shadow: 0 4px 12px rgba(0, 166, 81, 0.3);
        }

        .nav-tabs .nav-link .badge {
            background: rgba(255, 255, 255, 0.2);
            color: currentColor;
        }

        .nav-tabs .nav-link.active .badge {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Awards Grid */
        .awards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        /* Award Card */
        .award-card {
            background: rgba(26, 31, 46, 0.8);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(15px);
            transition: all 0.3s ease;
            position: relative;
            height: 100%;
        }

        .award-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .award-card.common::before { background: linear-gradient(90deg, #6c757d, #adb5bd); }
        .award-card.uncommon::before { background: linear-gradient(90deg, #28a745, #20c997); }
        .award-card.rare::before { background: linear-gradient(90deg, #007bff, #6610f2); }
        .award-card.legendary::before { background: linear-gradient(90deg, #fd7e14, #ffc107); }

        .award-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow-hover);
        }

        .award-card:hover::before {
            opacity: 1;
        }

        /* Award Image Container */
        .award-image-container {
            position: relative;
            padding: 2rem;
            text-align: center;
            background: rgba(0, 0, 0, 0.2);
        }

        .award-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .award-card:hover .award-image {
            transform: scale(1.1) rotateY(10deg);
        }

        .award-icon {
            font-size: 4rem;
            color: var(--jamaica-yellow);
            text-shadow: 0 4px 8px rgba(255, 215, 0, 0.3);
        }

        /* Rarity Indicator */
        .rarity-indicator {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.8);
            color: var(--jamaica-white);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(10px);
        }

        .rarity-indicator.common { background: rgba(108, 117, 125, 0.9); }
        .rarity-indicator.uncommon { background: rgba(40, 167, 69, 0.9); }
        .rarity-indicator.rare { background: rgba(0, 123, 255, 0.9); }
        .rarity-indicator.legendary { background: rgba(255, 193, 7, 0.9); color: var(--jamaica-black); }

        /* Award Content */
        .award-content {
            padding: 1.5rem;
            color: var(--jamaica-white);
        }

        .award-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--jamaica-white);
        }

        .award-description {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1rem;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .award-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .award-date {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        .rarity-percentage {
            color: var(--jamaica-yellow);
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(26, 31, 46, 0.6);
            border-radius: 20px;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            color: var(--jamaica-white);
            backdrop-filter: blur(10px);
        }

        .empty-state-icon {
            font-size: 4rem;
            color: var(--jamaica-gray);
            margin-bottom: 1.5rem;
        }

        .empty-state h3 {
            color: var(--jamaica-white);
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 2rem;
        }

        .how-to-earn-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .how-to-earn-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .earn-icon {
            font-size: 2.5rem;
            color: var(--jamaica-green);
            margin-bottom: 1rem;
        }

        .how-to-earn-card h6 {
            color: var(--jamaica-white);
            margin-bottom: 0.5rem;
        }

        .how-to-earn-card p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .awards-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .pilot-info-header {
                flex-direction: column;
                text-align: center;
            }
            
            .rank-info {
                text-align: center;
            }
            
            .hero-title {
                font-size: 2rem;
            }
        }

        /* Animations */
        .award-card {
            animation: slideInUp 0.6s ease-out;
        }

        .award-card:nth-child(1) { animation-delay: 0.1s; }
        .award-card:nth-child(2) { animation-delay: 0.2s; }
        .award-card:nth-child(3) { animation-delay: 0.3s; }
        .award-card:nth-child(4) { animation-delay: 0.4s; }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
{{-- Breadcrumb Navigation --}}
@php
$breadcrumbs = [
    [
        'title' => 'Pilot Center',
        'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
        'icon' => Auth::check() ? 'bi bi-speedometer2' : 'bi bi-house-fill'
    ],
    [
        'title' => __('common.profile'),
        'url' => route('frontend.profile.index'),
        'icon' => 'bi bi-person-circle'
    ],
    [
        'title' => 'Awards',
        'icon' => 'fas fa-trophy'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

    <div class="container-fluid awards-container">
        <div class="row">
            <div class="col-12">
                <h1 class="page-title mb-4">
                    <i class="fas fa-trophy me-3 jamaica-gold"></i>Awards Program
                </h1>
            </div>
        </div>

        <!-- Pilot Info Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card pilot-header-card">
                    <div class="card-body">
                        <div class="pilot-info-header">
                            <div class="pilot-avatar">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="Pilot Avatar" class="avatar-img">
                                @else
                                    <i class="fas fa-user-pilot fa-3x text-muted"></i>
                                @endif
                            </div>
                            <div class="pilot-details">
                                <h4 class="pilot-name">{{ Auth::user()->name_private }}</h4>
                                <p class="pilot-id">Pilot ID: {{ Auth::user()->pilot_id }}</p>
                            </div>
                            <div class="rank-info">
                                <div class="rank-title">{{ Auth::user()->rank->name ?? 'Pilot' }}</div>
                                <div class="rank-subtitle">Current Rank</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-tabs" id="awardsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                            <i class="fas fa-dashboard me-2"></i>Overview
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="my-awards-tab" data-bs-toggle="tab" data-bs-target="#my-awards" type="button" role="tab">
                            <i class="fas fa-trophy me-2"></i>My Awards <span class="badge bg-secondary ms-1">{{ $award_stats['total_awards'] }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="available-tab" data-bs-toggle="tab" data-bs-target="#available" type="button" role="tab">
                            <i class="fas fa-compass me-2"></i>Available <span class="badge bg-info ms-1">{{ $award_stats['available_awards'] }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                            <i class="fas fa-chart-line me-2"></i>Analytics
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="community-tab" data-bs-toggle="tab" data-bs-target="#community" type="button" role="tab">
                            <i class="fas fa-users me-2"></i>Community
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="awardsTabContent">
            
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <!-- Awards Statistics Dashboard -->
                <div class="row mb-4">
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stat-card gold">
                            <div class="stat-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-number">{{ $award_stats['total_awards'] }}</div>
                                <div class="stat-label">Total Awards</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stat-card green">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-number">{{ $award_stats['recent_awards'] }}</div>
                                <div class="stat-label">This Month</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stat-card blue">
                            <div class="stat-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-number">{{ $award_stats['categories'] }}</div>
                                <div class="stat-label">Award Types</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stat-card red">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-number">{{ Auth::user()->rank->hours ?? 0 }}</div>
                                <div class="stat-label">Required Hours</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Milestones -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card progress-card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-line me-2"></i>Progress to Next Milestone
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="milestone-item">
                                            <div class="milestone-header">
                                                <i class="fas fa-clock jamaica-gold"></i>
                                                <span class="milestone-title">Flight Hours Goal</span>
                                            </div>
                                            <div class="milestone-progress">
                                                @php
                                                    $currentHours = Auth::user()->flight_time ? round(Auth::user()->flight_time / 60, 1) : 0;
                                                    $nextGoal = 100;
                                                    $progress = min(($currentHours / $nextGoal) * 100, 100);
                                                @endphp
                                                <div class="progress-info">
                                                    <span class="current">{{ $currentHours }}h</span>
                                                    <span class="target">/ {{ $nextGoal }}h</span>
                                                </div>
                                                <div class="progress mb-2">
                                                    <div class="progress-bar bg-gradient-gold" style="width: {{ $progress }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ $nextGoal - $currentHours }} hours remaining</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="milestone-item">
                                            <div class="milestone-header">
                                                <i class="fas fa-plane-departure jamaica-green"></i>
                                                <span class="milestone-title">Total Flights</span>
                                            </div>
                                            <div class="milestone-progress">
                                                @php
                                                    $currentFlights = Auth::user()->flights ?? 0;
                                                    $nextFlightGoal = 50;
                                                    $flightProgress = min(($currentFlights / $nextFlightGoal) * 100, 100);
                                                @endphp
                                                <div class="progress-info">
                                                    <span class="current">{{ $currentFlights }}</span>
                                                    <span class="target">/ {{ $nextFlightGoal }} flights</span>
                                                </div>
                                                <div class="progress mb-2">
                                                    <div class="progress-bar bg-gradient-green" style="width: {{ $flightProgress }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ $nextFlightGoal - $currentFlights }} flights to next milestone</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Award Categories Preview -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card categories-card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-th-large me-2"></i>Award Categories
                                    </h5>
                                    <div class="category-stats">
                                        <span class="badge bg-info">{{ $awardCategories && $awardCategories->count() ? $awardCategories->count() : 0 }} Categories</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($awardCategories && $awardCategories->count() > 0)
                                    <div class="row">
                                        @foreach($awardCategories->take(4) as $category)
                                            @php
                                                $categoryName = $category->category ? str_replace('App\\Models\\', '', $category->category) : 'General';
                                                $earnedCount = $category->earned_awards ?? 0;
                                                $totalCount = $category->total_awards ?? 0;
                                                $progressPercent = $totalCount > 0 ? ($earnedCount / $totalCount) * 100 : 0;
                                            @endphp
                                            <div class="col-lg-3 col-md-6 mb-3">
                                                <div class="category-item-compact">
                                                    <div class="d-flex align-items-center">
                                                        <div class="category-icon-small me-3">
                                                            @switch($categoryName)
                                                                @case('Pirep')
                                                                @case('Flight')
                                                                    <i class="fas fa-plane-departure"></i>
                                                                    @break
                                                                @case('User')
                                                                    <i class="fas fa-user-tie"></i>
                                                                    @break
                                                                @default
                                                                    <i class="fas fa-trophy"></i>
                                                            @endswitch
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">{{ ucwords(str_replace(['_', '-'], ' ', $categoryName)) }}</h6>
                                                            <div class="progress mb-1" style="height: 6px;">
                                                                <div class="progress-bar bg-success" style="width: {{ $progressPercent }}%"></div>
                                                            </div>
                                                            <small class="text-muted">{{ $earnedCount }}/{{ $totalCount }} earned</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($awardCategories->count() > 4)
                                        <div class="text-center mt-3">
                                            <button class="btn btn-outline-primary btn-sm" onclick="switchTab('available-tab')">
                                                <i class="fas fa-plus me-1"></i>View All Categories
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-th-large fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No award categories available yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Awards Tab -->
            <div class="tab-pane fade" id="my-awards" role="tabpanel">
                <div class="row">
                    @if($user_awards->count() > 0)
                        @foreach($user_awards as $user_award)
                            @php
                                $award = $user_award; // $user_award is already the award model
                                $rarityClass = 'common';
                                $rarityIcon = 'fas fa-circle';
                                if(isset($awardRarity[$award->id])) {
                                    $rarity = $awardRarity[$award->id];
                                    if($rarity <= 10) {
                                        $rarityClass = 'legendary';
                                        $rarityIcon = 'fas fa-star';
                                    } elseif($rarity <= 25) {
                                        $rarityClass = 'rare';
                                        $rarityIcon = 'fas fa-gem';
                                    } elseif($rarity <= 50) {
                                        $rarityClass = 'uncommon';
                                        $rarityIcon = 'fas fa-certificate';
                                    }
                                }
                            @endphp
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 award-card {{ $rarityClass }}">
                                    <div class="rarity-indicator">
                                        <i class="{{ $rarityIcon }}"></i>
                                        <span class="rarity-text">{{ ucfirst($rarityClass) }}</span>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="award-icon mb-3">
                                            @if($award->image_url)
                                                <img src="{{ $award->image_url }}" alt="{{ $award->name }}" class="award-image">
                                            @else
                                                <i class="fas fa-trophy fa-3x jamaica-gold"></i>
                                            @endif
                                        </div>
                                        <h5 class="card-title">{{ $award->name }}</h5>
                                        <p class="card-text">{{ $award->description }}</p>
                                        @if($award->ref_model)
                                            <div class="badge bg-primary mb-2">
                                                {{ ucfirst(str_replace('App\\Models\\', '', $award->ref_model)) }}
                                            </div>
                                        @endif
                                        <div class="award-date">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Earned {{ $user_award->created_at->format('M j, Y') }}
                                        </div>
                                        @if(isset($awardRarity[$award->id]))
                                            <div class="rarity-percentage">
                                                <small class="text-muted">{{ round($awardRarity[$award->id], 1) }}% of pilots have this</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-trophy fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted">My Achievements</h4>
                                <p class="text-muted mb-4">Your Award Journey Begins Here!</p>
                                <p class="text-muted">Complete flights and achieve milestones to earn your first awards. Every flight brings you closer to new achievements!</p>
                                
                                <div class="row mt-4">
                                    <div class="col-md-4 mb-3">
                                        <div class="how-to-earn-card">
                                            <div class="earn-icon">
                                                <i class="fas fa-plane-departure"></i>
                                            </div>
                                            <h6>Complete Flights</h6>
                                            <p class="small">Submit PIREPs and build your flight hours</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="how-to-earn-card">
                                            <div class="earn-icon">
                                                <i class="fas fa-route"></i>
                                            </div>
                                            <h6>Fly Routes</h6>
                                            <p class="small">Explore different airports and destinations</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="how-to-earn-card">
                                            <div class="earn-icon">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <h6>Build Hours</h6>
                                            <p class="small">Accumulate flight time for milestone awards</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <button class="btn btn-primary mt-3" onclick="switchTab('available-tab')">
                                    <i class="fas fa-compass me-2"></i>Explore Available Awards
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Awards Tab -->
            <div class="tab-pane fade" id="available" role="tabpanel">
                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="awardSearch" placeholder="Search awards...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="categoryFilter">
                            <option value="">All Categories</option>
                            @if($awardCategories && $awardCategories->count() > 0)
                                @foreach($awardCategories as $category)
                                    @php
                                        $categoryName = $category->category ? str_replace('App\\Models\\', '', $category->category) : 'General';
                                    @endphp
                                    <option value="{{ $categoryName }}">{{ ucwords(str_replace(['_', '-'], ' ', $categoryName)) }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Available Awards Grid -->
                <div class="row">
                    @if($availableAwards->count() > 0)
                        @foreach($availableAwards as $award)
                            <div class="col-lg-4 col-md-6 mb-4 award-item" data-category="{{ $award->ref_model ? str_replace('App\\Models\\', '', $award->ref_model) : 'General' }}">
                                <div class="card h-100 available-award-card">
                                    <div class="card-body text-center">
                                        <div class="award-icon mb-3">
                                            @if($award->image_url)
                                                <img src="{{ $award->image_url }}" alt="{{ $award->name }}" class="award-image opacity-50">
                                            @else
                                                <i class="fas fa-trophy fa-3x text-muted"></i>
                                            @endif
                                        </div>
                                        <h6 class="card-title text-muted">{{ $award->name }}</h6>
                                        <p class="card-text small text-muted">{{ $award->description }}</p>
                                        @if($award->ref_model)
                                            <div class="badge bg-light text-dark mb-2">
                                                {{ ucfirst(str_replace('App\\Models\\', '', $award->ref_model)) }}
                                            </div>
                                        @endif
                                        <div class="award-status">
                                            <small class="text-warning">
                                                <i class="fas fa-lock me-1"></i>Not Earned Yet
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle fa-4x jamaica-gold mb-3"></i>
                                <h4 class="jamaica-gold">Congratulations!</h4>
                                <p class="text-muted">You've earned all available awards!</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Analytics Tab -->
            <div class="tab-pane fade" id="analytics" role="tabpanel">
                <div class="row">
                    <!-- Timeline Chart -->
                    <div class="col-lg-8 mb-4">
                        <div class="card timeline-card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-line me-2"></i>Achievement Timeline
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline-chart">
                                    <canvas id="achievementChart" width="400" height="200"></canvas>
                                </div>
                                @if($timelineData && $timelineData->count() > 0)
                                    <div class="timeline-stats mt-3">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <h6 class="jamaica-gold">{{ $timelineData->sum('count') }}</h6>
                                                <small class="text-muted">Last 12 Months</small>
                                            </div>
                                            <div class="col-4">
                                                <h6 class="jamaica-green">{{ $timelineData->avg('count') ? round($timelineData->avg('count'), 1) : 0 }}</h6>
                                                <small class="text-muted">Avg per Month</small>
                                            </div>
                                            <div class="col-4">
                                                <h6 class="text-primary">{{ $timelineData->max('count') ?? 0 }}</h6>
                                                <small class="text-muted">Best Month</small>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-chart-line fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">Start earning awards to see your progress timeline!</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Performance Metrics -->
                    <div class="col-lg-4 mb-4">
                        <div class="card performance-card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>Performance Metrics
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="metric-item">
                                    <div class="metric-label">
                                        <i class="fas fa-plane-departure jamaica-gold me-2"></i>
                                        Total Flights
                                    </div>
                                    <div class="metric-value">{{ number_format($performanceMetrics['total_flights']) }}</div>
                                </div>
                                <div class="metric-item">
                                    <div class="metric-label">
                                        <i class="fas fa-clock jamaica-green me-2"></i>
                                        Flight Time
                                    </div>
                                    <div class="metric-value">{{ round($performanceMetrics['total_flight_time'] / 60, 1) }}h</div>
                                </div>
                                <div class="metric-item">
                                    <div class="metric-label">
                                        <i class="fas fa-route text-primary me-2"></i>
                                        Distance Flown
                                    </div>
                                    <div class="metric-value">{{ number_format($performanceMetrics['total_distance']) }} nm</div>
                                </div>
                                @if($performanceMetrics['avg_landing_rate'])
                                    <div class="metric-item">
                                        <div class="metric-label">
                                            <i class="fas fa-plane-arrival text-info me-2"></i>
                                            Avg Landing Rate
                                        </div>
                                        <div class="metric-value">{{ round($performanceMetrics['avg_landing_rate']) }} fpm</div>
                                    </div>
                                @endif
                                <div class="metric-item border-top pt-3 mt-3">
                                    <div class="metric-label">
                                        <i class="fas fa-trophy text-warning me-2"></i>
                                        Pilot Ranking
                                    </div>
                                    <div class="metric-value">#{{ $pilotRanking }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Tab -->
            <div class="tab-pane fade" id="community" role="tabpanel">
                <div class="row">
                    <!-- Recent Community Activity -->
                    <div class="col-12 mb-4">
                        <div class="card community-card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-users me-2"></i>Recent Community Achievements
                                    <small class="text-muted">(Last 7 days)</small>
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($recentCommunityAwards->count() > 0)
                                    <div class="community-timeline">
                                        @foreach($recentCommunityAwards as $communityAward)
                                            <div class="community-item">
                                                <div class="community-avatar">
                                                    <i class="fas fa-user-circle fa-2x text-muted"></i>
                                                </div>
                                                <div class="community-content">
                                                    <div class="community-text">
                                                        <strong>{{ $communityAward->user->pilot_id ?? 'Pilot' }}</strong> 
                                                        earned <span class="jamaica-gold">{{ $communityAward->award->name }}</span>
                                                    </div>
                                                    <div class="community-time">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $communityAward->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                                <div class="community-award">
                                                    <i class="fas fa-trophy text-warning"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No recent community activity to display.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Air Jamaica Virtual Color Palette */
        :root {
            --jamaica-gold: #FFD700;
            --jamaica-gold-light: #FFED4E;
            --jamaica-gold-dark: #E6C200;
            --jamaica-green: #009639;
            --jamaica-green-light: #00B346;
            --jamaica-green-dark: #007B2F;
            --jamaica-black: #1a1a1a;
            --jamaica-white: #ffffff;
            --gradient-gold: linear-gradient(135deg, #FFD700, #FFA500);
            --gradient-green: linear-gradient(135deg, #009639, #00CC4A);
            --gradient-dark: linear-gradient(135deg, #2c3e50, #34495e);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 15px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 25px rgba(0, 0, 0, 0.15);
            --shadow-xl: 0 12px 35px rgba(0, 0, 0, 0.2);
            --border-radius: 15px;
            --border-radius-sm: 8px;
            --border-radius-lg: 20px;
        }

        .jamaica-gold { color: var(--jamaica-gold) !important; }
        .jamaica-green { color: var(--jamaica-green) !important; }
        .bg-gradient-gold { background: var(--gradient-gold) !important; }
        .bg-gradient-green { background: var(--gradient-green) !important; }

        /* Global Enhancements - Consistent with Dashboard */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #2a2f3e 100%) !important;
            background-attachment: fixed;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            position: relative;
            overflow-x: hidden;
        }

        /* Awards Container */
        .awards-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Page Title - Dark Theme */
        .page-title {
            font-weight: 700;
            font-size: 2.5rem;
            color: var(--jamaica-white);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
            position: relative;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100px;
            height: 4px;
            background: var(--gradient-gold);
            border-radius: 2px;
        }

        /* Tab Navigation - Dark Theme Enhancement */
        .nav-tabs {
            border: none;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 0.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
            overflow-x: auto;
            white-space: nowrap;
        }

        .nav-tabs .nav-item {
            margin: 0 0.25rem;
        }

        .nav-tabs .nav-link {
            border: none;
            color: rgba(255, 255, 255, 0.7);
            padding: 1rem 1.5rem;
            font-weight: 500;
            border-radius: var(--border-radius-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            min-width: 120px;
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-tabs .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-gold);
            opacity: 0.1;
            transition: all 0.3s ease;
            z-index: 0;
        }

        .nav-tabs .nav-link:hover::before {
            left: 0;
        }

        .nav-tabs .nav-link:hover {
            color: var(--jamaica-gold);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.2);
            background: rgba(255, 215, 0, 0.1);
        }

        .nav-tabs .nav-link.active {
            color: white;
            background: var(--gradient-gold);
            font-weight: 600;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .nav-tabs .nav-link.active::before {
            display: none;
        }

        .nav-tabs .badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }

        /* Pilot Header - Dark Theme Enhanced */
        .pilot-header-card {
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .pilot-header-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-gold);
        }

        .pilot-info-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            padding: 1rem;
        }

        .pilot-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            background: var(--gradient-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
            border: 4px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .pilot-avatar::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            padding: 4px;
            background: var(--gradient-gold);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: xor;
            -webkit-mask-composite: xor;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .pilot-details {
            flex-grow: 1;
        }

        .pilot-name {
            margin: 0;
            color: var(--jamaica-white);
            font-size: 1.75rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .pilot-id {
            margin: 0.5rem 0 0 0;
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            font-weight: 500;
        }

        .rank-info {
            text-align: right;
            background: var(--gradient-gold);
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .rank-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }

        .rank-subtitle {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        /* Statistics Cards - Dark Theme Enhanced */
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            padding: 2rem 1.5rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 140px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            transition: all 0.3s ease;
        }

        .stat-card.gold::before { background: var(--gradient-gold); }
        .stat-card.green::before { background: var(--gradient-green); }
        .stat-card.blue::before { background: linear-gradient(135deg, #007bff, #0056b3); }
        .stat-card.red::before { background: linear-gradient(135deg, #dc3545, #c82333); }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4);
            background: rgba(255, 255, 255, 0.15);
        }

        .stat-card:hover::before {
            height: 6px;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .stat-card.gold .stat-icon { 
            color: var(--jamaica-gold);
            text-shadow: 2px 2px 8px rgba(255, 215, 0, 0.5);
        }
        .stat-card.green .stat-icon { 
            color: var(--jamaica-green);
            text-shadow: 2px 2px 8px rgba(0, 150, 57, 0.5);
        }
        .stat-card.blue .stat-icon { 
            color: #007bff;
            text-shadow: 2px 2px 8px rgba(0, 123, 255, 0.5);
        }
        .stat-card.red .stat-icon { 
            color: #dc3545;
            text-shadow: 2px 2px 8px rgba(220, 53, 69, 0.5);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--jamaica-white);
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .stat-label {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Progress Cards - Dark Theme Enhanced */
        .progress-card, .categories-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            position: relative;
        }

        .progress-card::before, .categories-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-green);
        }

        .card-header {
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
        }

        .card-header h5 {
            color: var(--jamaica-white);
            font-weight: 700;
            margin: 0;
        }

        .milestone-item {
            padding: 1.5rem;
            border-radius: var(--border-radius);
            background: rgba(255, 255, 255, 0.05);
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .milestone-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.1);
        }

        .milestone-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .milestone-header i {
            font-size: 1.5rem;
            margin-right: 0.75rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .milestone-title {
            font-weight: 700;
            color: var(--jamaica-white);
            font-size: 1.1rem;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .progress-info .current {
            font-weight: 800;
            color: var(--jamaica-white);
            font-size: 1.1rem;
        }

        .progress-info .target {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
        }

        .progress {
            height: 8px;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
            position: relative;
            overflow: hidden;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent);
            background-size: 1rem 1rem;
            animation: progressAnimation 1s linear infinite;
        }

        @keyframes progressAnimation {
            0% { background-position: 0 0; }
            100% { background-position: 1rem 0; }
        }

        /* Category Items - Dark Theme Enhanced */
        .category-item-compact {
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--border-radius);
            padding: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .category-item-compact::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .category-item-compact:hover::before {
            left: 100%;
        }

        .category-item-compact:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: var(--jamaica-gold);
            background: rgba(255, 215, 0, 0.1);
        }

        .category-icon-small {
            width: 50px;
            height: 50px;
            background: var(--gradient-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
            transition: all 0.3s ease;
        }

        .category-item-compact:hover .category-icon-small {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        }

        .category-stats .badge {
            background: var(--gradient-gold) !important;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        /* Award Cards - Dark Theme Enhanced */
        .award-card {
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .award-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        }

        .award-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-gold);
            transition: height 0.3s ease;
        }

        .award-card:hover::before {
            height: 6px;
        }

        /* Rarity Indicators - Enhanced */
        .rarity-indicator {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            z-index: 10;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            letter-spacing: 0.5px;
        }

        .award-card.legendary .rarity-indicator { 
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
        }
        .award-card.rare .rarity-indicator { 
            background: linear-gradient(135deg, #9B59B6, #8E44AD);
            color: white;
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.4);
        }
        .award-card.uncommon .rarity-indicator { 
            background: linear-gradient(135deg, #3498DB, #2980B9);
            color: white;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
        }
        .award-card.common .rarity-indicator { 
            background: linear-gradient(135deg, #95A5A6, #7F8C8D);
            color: white;
            box-shadow: 0 4px 15px rgba(149, 165, 166, 0.4);
        }

        .award-image {
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
            border-radius: var(--border-radius-sm);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .award-card:hover .award-image {
            transform: scale(1.1) rotate(5deg);
        }

        .award-icon {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .award-icon .fa-trophy {
            transition: all 0.3s ease;
            filter: drop-shadow(2px 2px 8px rgba(255, 215, 0, 0.5));
        }

        .award-card:hover .award-icon .fa-trophy {
            transform: scale(1.15) rotate(-5deg);
            filter: drop-shadow(4px 4px 12px rgba(255, 215, 0, 0.7));
        }

        .card-title {
            font-weight: 700;
            color: var(--jamaica-white);
            font-size: 1.25rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .card-text {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .award-date {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 500;
        }

        .rarity-percentage {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: rgba(0, 0, 0, 0.2);
            border-radius: var(--border-radius-sm);
        }

        /* Available Awards - Dark Theme Enhanced */
        .available-award-card {
            border: 2px dashed rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
        }

        .available-award-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .available-award-card:hover::before {
            left: 100%;
        }

        .available-award-card:hover {
            border-color: var(--jamaica-gold);
            background: rgba(255, 215, 0, 0.1);
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .available-award-card .award-image {
            opacity: 0.6;
            filter: grayscale(50%);
            transition: all 0.3s ease;
        }

        .available-award-card:hover .award-image {
            opacity: 0.8;
            filter: grayscale(20%);
        }

        .available-award-card .card-title {
            color: rgba(255, 255, 255, 0.6);
        }

        .available-award-card .card-text {
            color: rgba(255, 255, 255, 0.5);
        }

        /* How to Earn Cards - Dark Theme Enhanced */
        .how-to-earn-card {
            text-align: center;
            padding: 2rem 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--border-radius);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .how-to-earn-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-green);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .how-to-earn-card:hover::before {
            transform: scaleX(1);
        }

        .how-to-earn-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: var(--jamaica-green);
            background: rgba(0, 150, 57, 0.1);
        }

        .earn-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
            transition: all 0.3s ease;
        }

        .how-to-earn-card:hover .earn-icon {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        }

        .how-to-earn-card h6 {
            font-weight: 700;
            color: var(--jamaica-white);
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .how-to-earn-card .small {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.5;
        }

        /* Performance Metrics - Dark Theme Enhanced */
        .timeline-card, .performance-card, .community-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            position: relative;
        }

        .timeline-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-green);
        }

        .performance-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-gold);
        }

        .community-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #007bff, #0056b3);
        }

        .metric-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .metric-item:hover {
            background: rgba(255, 215, 0, 0.1);
            padding-left: 1rem;
            padding-right: 1rem;
            margin-left: -1rem;
            margin-right: -1rem;
            border-radius: var(--border-radius-sm);
        }

        .metric-item:last-child {
            border-bottom: none;
        }

        .metric-label {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: var(--jamaica-white);
        }

        .metric-value {
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--jamaica-white);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .timeline-stats {
            background: rgba(255, 215, 0, 0.1);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .timeline-stats h6 {
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
            color: var(--jamaica-white);
        }

        /* Community Activity - Dark Theme Enhanced */
        .community-timeline {
            max-height: 450px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--jamaica-gold) transparent;
        }

        .community-timeline::-webkit-scrollbar {
            width: 6px;
        }

        .community-timeline::-webkit-scrollbar-track {
            background: transparent;
        }

        .community-timeline::-webkit-scrollbar-thumb {
            background: var(--gradient-gold);
            border-radius: 3px;
        }

        .community-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .community-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--gradient-gold);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .community-item:hover::before {
            transform: scaleY(1);
        }

        .community-item:hover {
            background: rgba(255, 215, 0, 0.1);
            transform: translateX(8px);
        }

        .community-item:last-child {
            border-bottom: none;
        }

        .community-avatar {
            margin-right: 1.5rem;
            position: relative;
        }

        .community-avatar::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            background: var(--jamaica-green);
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .community-content {
            flex-grow: 1;
        }

        .community-text {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--jamaica-white);
        }

        .community-text strong {
            color: var(--jamaica-gold);
            font-weight: 700;
        }

        .community-time {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .community-award {
            margin-left: 1rem;
            font-size: 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* Search and Filter - Dark Theme Enhanced */
        .input-group {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .input-group-text {
            background: var(--gradient-gold);
            color: white;
            border: none;
            font-weight: 600;
        }

        .form-control, .form-select {
            border: none;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            color: var(--jamaica-white);
            backdrop-filter: blur(10px);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
            border-color: var(--jamaica-gold);
            background: rgba(255, 255, 255, 0.15);
        }

        /* Button Enhancements - Dark Theme */
        .btn {
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--gradient-gold);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .btn-primary:hover {
            background: var(--gradient-green);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        }

        .btn-outline-primary {
            border: 2px solid var(--jamaica-gold);
            color: var(--jamaica-gold);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--gradient-gold);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .badge {
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            border-radius: 15px;
        }

        .badge.bg-secondary {
            background: var(--gradient-dark) !important;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #17a2b8, #138496) !important;
        }

        /* Text Colors for Dark Theme */
        .text-muted {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        /* Responsive Enhancements */
        @media (max-width: 768px) {
            .awards-container {
                padding: 1rem;
            }
            
            .page-title {
                font-size: 2rem;
                text-align: center;
            }
            
            .nav-tabs {
                padding: 0.25rem;
            }
            
            .nav-tabs .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                min-width: 100px;
            }
            
            .stat-card {
                height: 120px;
                padding: 1.5rem 1rem;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .stat-label {
                font-size: 0.75rem;
            }
            
            .pilot-info-header {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }
            
            .rank-info {
                text-align: center;
            }
            
            .pilot-avatar {
                width: 80px;
                height: 80px;
            }
            
            .milestone-item {
                padding: 1rem;
            }
            
            .community-item {
                padding: 1rem;
            }
            
            .community-timeline {
                max-height: 300px;
            }
        }

        @media (max-width: 576px) {
            .nav-tabs {
                flex-wrap: wrap;
            }
            
            .nav-tabs .nav-link {
                flex: 1;
                min-width: auto;
                margin-bottom: 0.25rem;
            }
            
            .stat-card {
                height: 100px;
                padding: 1rem;
            }
            
            .how-to-earn-card {
                padding: 1.5rem 1rem;
            }
            
            .earn-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        /* Loading States */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid var(--jamaica-gold);
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Print Styles */
        @media print {
            .nav-tabs, .btn, .input-group {
                display: none;
            }
            
            .tab-content .tab-pane {
                display: block !important;
            }
            
            .card {
                break-inside: avoid;
            }
        }
    </style>

    <script>
        // Tab Switching Function
        function switchTab(tabId) {
            try {
                const clickedTab = document.getElementById(tabId);
                if (clickedTab) {
                    const tabTrigger = new bootstrap.Tab(clickedTab);
                    tabTrigger.show();
                    
                    // Initialize chart if analytics tab is shown
                    const target = clickedTab.getAttribute('data-bs-target');
                    if (target === '#analytics') {
                        setTimeout(initializeChart, 100);
                    }
                }
            } catch (error) {
                console.error('Error switching tabs:', error);
            }
        }

        // Chart.js Timeline Chart
        function initializeChart() {
            try {
                const ctx = document.getElementById('achievementChart');
                if (!ctx) return;

                const timelineData = @json($timelineData ?? collect());
                
                if (!timelineData || timelineData.length === 0) {
                    return;
                }

                const labels = timelineData.map(item => item.month_name || 'Unknown');
                const data = timelineData.map(item => item.count || 0);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Awards Earned',
                            data: data,
                            borderColor: '#FFD700',
                            backgroundColor: 'rgba(255, 215, 0, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#FFD700',
                            pointBorderColor: '#FFA500',
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing chart:', error);
            }
        }

        // Award Search and Filter
        function initializeSearch() {
            try {
                const searchInput = document.getElementById('awardSearch');
                const categoryFilter = document.getElementById('categoryFilter');
                
                if (searchInput) {
                    searchInput.addEventListener('input', filterAwards);
                }
                
                if (categoryFilter) {
                    categoryFilter.addEventListener('change', filterAwards);
                }
            } catch (error) {
                console.error('Error initializing search:', error);
            }
        }

        function filterAwards() {
            try {
                const searchTerm = document.getElementById('awardSearch')?.value.toLowerCase() || '';
                const selectedCategory = document.getElementById('categoryFilter')?.value || '';
                const awardItems = document.querySelectorAll('.award-item');
                
                awardItems.forEach(item => {
                    const title = item.querySelector('.card-title')?.textContent.toLowerCase() || '';
                    const description = item.querySelector('.card-text')?.textContent.toLowerCase() || '';
                    const category = item.getAttribute('data-category') || '';
                    
                    const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
                    const matchesCategory = !selectedCategory || category === selectedCategory;
                    
                    if (matchesSearch && matchesCategory) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            } catch (error) {
                console.error('Error filtering awards:', error);
            }
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Initialize Bootstrap tabs
                const triggerTabList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tab"]'));
                triggerTabList.forEach(function (triggerEl) {
                    try {
                        const tabTrigger = new bootstrap.Tab(triggerEl);
                        
                        triggerEl.addEventListener('click', function (event) {
                            event.preventDefault();
                            tabTrigger.show();
                            
                            // Initialize chart if analytics tab is shown
                            const target = triggerEl.getAttribute('data-bs-target');
                            if (target === '#analytics') {
                                setTimeout(initializeChart, 100);
                            }
                        });
                    } catch (error) {
                        console.error('Error initializing tab:', error);
                    }
                });
                
                // Initialize search functionality
                initializeSearch();
                
            } catch (error) {
                console.error('Error during initialization:', error);
            }
        });
    </script>

@endsection