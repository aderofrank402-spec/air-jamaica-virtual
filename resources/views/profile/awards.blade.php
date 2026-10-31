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

        /* Breadcrumb positioning for awards page */
        .breadcrumb {
            position: relative;
            z-index: 1000;
            margin-top: 80px;
            margin-bottom: 2rem;
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
            margin-bottom: 0.5rem;
            display: block;
        }

        .award-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 120px;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 2px dashed rgba(255, 255, 255, 0.2);
        }

        .award-placeholder-text {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            margin-top: 0.5rem;
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

        /* Enhanced Overview Styles */
        .achievement-summary {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .achievement-icon {
            flex-shrink: 0;
            font-size: 2.5rem;
        }

        .achievement-content {
            flex: 1;
        }

        .achievement-content h3 {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }

        .progress-section {
            margin-bottom: 1.5rem;
        }

        .achievement-stats {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 1rem;
        }

        .goal-item {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border-left: 4px solid var(--jamaica-blue);
        }

        .motivation-section {
            position: relative;
            overflow: hidden;
        }

        .motivation-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-green);
            animation: shimmer 2s ease-in-out infinite;
        }

        .summary-item {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .summary-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
        }

        .award-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1rem;
        }

        .award-placeholder-text {
            margin-top: 0.5rem;
            text-align: center;
        }

        .award-placeholder-text small {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        /* Enhanced award image container */
        .award-image-container {
            position: relative;
            padding: 2rem;
            text-align: center;
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 120px;
        }

        /* Progress bars */
        .progress {
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
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

@section('content')

{{-- Breadcrumb Navigation --}}
@php
$breadcrumbs = [
    [
        'title' => 'Pilot Center',
        'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
        'icon' => Auth::check() ? 'fas fa-tachometer-alt' : 'fas fa-home'
    ],
    [
        'title' => 'Profile',
        'url' => route('frontend.profile.index'),
        'icon' => 'fas fa-user'
    ],
    [
        'title' => 'Awards & Achievements',
        'icon' => 'fas fa-trophy'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

<div class="jamaica-awards">
    <div class="container">
        {{-- Hero Section --}}
        <div class="awards-hero animate__animated animate__fadeInDown">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-trophy me-3"></i>Pilot Awards
                </h1>
                <p class="hero-subtitle">
                    Track your achievements and showcase your flying excellence with Air Jamaica Virtual
                </p>
            </div>
        </div>

        {{-- Pilot Info Card --}}
        <div class="pilot-info-card animate__animated animate__fadeInUp animate__delay-1s">
            <div class="pilot-info-header">
                <div class="pilot-avatar">
                    @if($user->resolveAvatarUrl())
                        <img src="{{ $user->resolveAvatarUrl() }}" alt="{{ $user->name_private }}">
                    @else
                        <i class="fas fa-user-circle"></i>
                    @endif
                </div>
                <div class="pilot-details">
                    <h3 class="pilot-name">{{ $user->name_private }}</h3>
                    <p class="pilot-id">Pilot ID: {{ $user->ident }}</p>
                </div>
                @if($user->rank)
                <div class="rank-info">
                    <div class="rank-title">{{ $user->rank->name }}</div>
                    <div class="rank-subtitle">Current Rank</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="stats-grid animate__animated animate__fadeInUp animate__delay-2s">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-medal"></i>
                </div>
                <div class="stat-value">{{ $totalAwards }}</div>
                <p class="stat-label">Total Awards</p>
            </div>
            <div class="stat-card recent">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value">{{ $recentAwards }}</div>
                <p class="stat-label">Recent Awards (30 days)</p>
            </div>
            <div class="stat-card categories">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-value">{{ $categoryCount }}</div>
                <p class="stat-label">Award Categories</p>
            </div>
            <div class="stat-card available">
                <div class="stat-icon">
                    <i class="fas fa-target"></i>
                </div>
                <div class="stat-value">{{ $availableAwards }}</div>
                <p class="stat-label">Available to Earn</p>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="awards-tabs animate__animated animate__fadeInUp animate__delay-3s">
            <ul class="nav nav-tabs justify-content-center" id="awardsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                        <i class="fas fa-chart-pie me-2"></i>Overview
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="my-awards-tab" data-bs-toggle="tab" data-bs-target="#my-awards" type="button" role="tab">
                        <i class="fas fa-trophy me-2"></i>My Awards
                        <span class="badge ms-2">{{ $totalAwards }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="available-tab" data-bs-toggle="tab" data-bs-target="#available" type="button" role="tab">
                        <i class="fas fa-bullseye me-2"></i>Available
                        <span class="badge ms-2">{{ $availableAwards }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                        <i class="fas fa-chart-bar me-2"></i>Analytics
                    </button>
                </li>
            </ul>
        </div>

        {{-- Tab Content --}}
        <div class="tab-content" id="awardsTabContent">
            {{-- Overview Tab --}}
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                @if($userAwards->count() > 0)
                    {{-- Recent Achievements Section --}}
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="stat-card p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <h4 class="text-white mb-2">
                                            <i class="fas fa-star me-2" style="color: var(--jamaica-yellow);"></i>
                                            Recent Achievements
                                        </h4>
                                        <p class="text-muted mb-0">Your latest accomplishments with Air Jamaica Virtual</p>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">Last 5 awards earned</small>
                                        <br>
                                        <small class="text-success">
                                            <i class="fas fa-trophy me-1"></i>
                                            Keep up the great work!
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="awards-grid">
                                    @foreach($userAwards->take(5) as $userAward)
                                        @php
                                            // Handle different award relationship structures
                                            $award = $userAward->award ?? $userAward;
                                            if (!$award) continue; // Skip if award is null
                                            
                                            $rarity = $award->rarity ?? 'common';
                                            $rarityLabels = [
                                                'common' => 'Common',
                                                'uncommon' => 'Uncommon', 
                                                'rare' => 'Rare',
                                                'legendary' => 'Legendary'
                                            ];
                                            
                                            // Calculate days since earned
                                            $earnedDate = $userAward->pivot->created_at ?? $userAward->created_at;
                                            $daysAgo = now()->diffInDays($earnedDate);
                                        @endphp
                                        
                                        @if($award)
                                        <div class="award-card {{ $rarity }}">
                                            <div class="rarity-indicator {{ $rarity }}">
                                                @switch($rarity)
                                                    @case('common')
                                                        <i class="fas fa-circle"></i>
                                                        @break
                                                    @case('uncommon')
                                                        <i class="fas fa-gem"></i>
                                                        @break
                                                    @case('rare')
                                                        <i class="fas fa-star"></i>
                                                        @break
                                                    @case('legendary')
                                                        <i class="fas fa-crown"></i>
                                                        @break
                                                @endswitch
                                                {{ $rarityLabels[$rarity] ?? 'Common' }}
                                            </div>
                                            
                                            <div class="award-image-container">
                                                @if($award && $award->image)
                                                    <img src="{{ $award->image }}" alt="{{ $award->name }}" class="award-image" 
                                                         onerror="this.style.display='none'; this.parentNode.querySelector('.award-placeholder').style.display='block';">
                                                    <div class="award-placeholder" style="display: none;">
                                                        <i class="award-icon fas fa-trophy"></i>
                                                    </div>
                                                @else
                                                    <div class="award-placeholder">
                                                        @switch($rarity)
                                                            @case('legendary')
                                                                <i class="award-icon fas fa-crown" style="color: #ffd700;"></i>
                                                                @break
                                                            @case('rare')
                                                                <i class="award-icon fas fa-star" style="color: #e74c3c;"></i>
                                                                @break
                                                            @case('uncommon')
                                                                <i class="award-icon fas fa-gem" style="color: #9b59b6;"></i>
                                                                @break
                                                            @default
                                                                <i class="award-icon fas fa-trophy" style="color: var(--jamaica-yellow);"></i>
                                                        @endswitch
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="award-content">
                                                <h5 class="award-title">{{ $award->name ?? 'Unknown Award' }}</h5>
                                                <p class="award-description">{!! Str::limit(strip_tags($award->description ?? 'Congratulations on earning this achievement!'), 80) !!}</p>
                                                
                                                <div class="award-meta">
                                                    <span class="award-date">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        @if($daysAgo == 0)
                                                            Today
                                                        @elseif($daysAgo == 1)
                                                            Yesterday
                                                        @elseif($daysAgo < 7)
                                                            {{ $daysAgo }} days ago
                                                        @else
                                                            {{ $earnedDate->format('M j, Y') }}
                                                        @endif
                                                    </span>
                                                    <span class="rarity-percentage">
                                                        @switch($rarity)
                                                            @case('common')
                                                                <i class="fas fa-users me-1"></i>Common
                                                                @break
                                                            @case('uncommon')
                                                                <i class="fas fa-user-friends me-1"></i>Special
                                                                @break
                                                            @case('rare')
                                                                <i class="fas fa-user me-1"></i>Rare
                                                                @break
                                                            @case('legendary')
                                                                <i class="fas fa-crown me-1"></i>Legendary
                                                                @break
                                                        @endswitch
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress & Motivation Section --}}
                    <div class="row mb-5">
                        <div class="col-md-6 mb-4">
                            <div class="stat-card p-4">
                                <h5 class="text-white mb-3">
                                    <i class="fas fa-chart-line me-2" style="color: var(--jamaica-green);"></i>
                                    Achievement Progress
                                </h5>
                                
                                @php
                                    $totalPossible = $totalAwards + $availableAwards;
                                    $progressPercentage = $totalPossible > 0 ? round(($totalAwards / $totalPossible) * 100) : 0;
                                @endphp
                                
                                <div class="progress-section mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">Overall Progress</span>
                                        <span class="text-white">{{ $progressPercentage }}%</span>
                                    </div>
                                    <div class="progress" style="height: 10px; background: rgba(255,255,255,0.1);">
                                        <div class="progress-bar" style="background: var(--gradient-green); width: {{ $progressPercentage }}%;"></div>
                                    </div>
                                    <small class="text-muted mt-1">{{ $totalAwards }} of {{ $totalPossible }} awards earned</small>
                                </div>

                                <div class="achievement-stats">
                                    @php
                                        $rarityStats = [
                                            'common' => ['count' => 0, 'color' => 'secondary', 'icon' => 'circle'],
                                            'uncommon' => ['count' => 0, 'color' => 'success', 'icon' => 'gem'],
                                            'rare' => ['count' => 0, 'color' => 'primary', 'icon' => 'star'],
                                            'legendary' => ['count' => 0, 'color' => 'warning', 'icon' => 'crown']
                                        ];
                                        
                                        foreach($userAwards as $userAward) {
                                            $award = $userAward->award ?? $userAward;
                                            if ($award) {
                                                $rarity = $award->rarity ?? 'common';
                                                if (isset($rarityStats[$rarity])) {
                                                    $rarityStats[$rarity]['count']++;
                                                }
                                            }
                                        }
                                    @endphp
                                    
                                    @foreach($rarityStats as $rarity => $stats)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">
                                                <i class="fas fa-{{ $stats['icon'] }} me-2 text-{{ $stats['color'] }}"></i>
                                                {{ ucfirst($rarity) }}
                                            </span>
                                            <span class="text-white">{{ $stats['count'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="stat-card p-4">
                                <h5 class="text-white mb-3">
                                    <i class="fas fa-bullseye me-2" style="color: var(--jamaica-yellow);"></i>
                                    Next Goals
                                </h5>
                                
                                @php
                                    // Calculate legendary count for this section
                                    $legendaryCount = $userAwards->where('rarity', 'legendary')->count() + 
                                                    $userAwards->filter(function($award) { 
                                                        return ($award->award->rarity ?? ($award->rarity ?? 'common')) === 'legendary'; 
                                                    })->count();
                                @endphp
                                
                                @if($availableAwards > 0)
                                    <div class="goal-item mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-target text-success me-2"></i>
                                            <span class="text-white">Earn {{ min(3, $availableAwards) }} more awards</span>
                                        </div>
                                        <small class="text-muted">{{ $availableAwards }} awards are waiting for you!</small>
                                    </div>
                                @endif
                                
                                @if($legendaryCount == 0)
                                    <div class="goal-item mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-crown text-warning me-2"></i>
                                            <span class="text-white">Earn your first Legendary award</span>
                                        </div>
                                        <small class="text-muted">The ultimate achievement awaits!</small>
                                    </div>
                                @endif
                                
                                @if($recentAwards == 0)
                                    <div class="goal-item mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-calendar-plus text-info me-2"></i>
                                            <span class="text-white">Earn an award this month</span>
                                        </div>
                                        <small class="text-muted">Get back into the achievement groove!</small>
                                    </div>
                                @endif
                                
                                <div class="motivation-section mt-4 p-3" style="background: rgba(0, 166, 81, 0.1); border-radius: 12px; border-left: 4px solid var(--jamaica-green);">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-lightbulb text-warning me-2"></i>
                                        <strong class="text-white">Pro Tip</strong>
                                    </div>
                                    <small class="text-muted">
                                        @if($totalAwards < 5)
                                            Complete more flights to unlock milestone awards!
                                        @elseif($totalAwards < 10)
                                            Explore different routes to earn exploration awards!
                                        @else
                                            Check the Available Awards tab for specific requirements!
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3>No Awards Yet</h3>
                        <p>Start flying and completing achievements to earn your first awards!</p>
                        
                        <div class="how-to-earn-grid">
                            <div class="how-to-earn-card">
                                <div class="earn-icon">
                                    <i class="fas fa-plane-departure"></i>
                                </div>
                                <h6>Complete Flights</h6>
                                <p>Finish flights to earn milestone awards</p>
                            </div>
                            <div class="how-to-earn-card">
                                <div class="earn-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h6>Flight Hours</h6>
                                <p>Accumulate flying hours for time-based awards</p>
                            </div>
                            <div class="how-to-earn-card">
                                <div class="earn-icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <h6>Explore Routes</h6>
                                <p>Fly to new destinations and airports</p>
                            </div>
                            <div class="how-to-earn-card">
                                <div class="earn-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <h6>Special Events</h6>
                                <p>Participate in airline events and challenges</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- My Awards Tab --}}
            <div class="tab-pane fade" id="my-awards" role="tabpanel">
                @if($userAwards->count() > 0)
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-medal me-2" style="color: var(--jamaica-yellow);"></i>
                            Your Earned Awards ({{ $totalAwards }})
                        </h4>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-2"></i>Filter by Rarity
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-filter="all">All Awards</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="legendary">Legendary</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="rare">Rare</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="uncommon">Uncommon</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="common">Common</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="awards-grid" id="myAwardsGrid">
                        @foreach($userAwards as $userAward)
                            @php
                                // Handle different award relationship structures
                                $award = $userAward->award ?? $userAward;
                                if (!$award) continue; // Skip if award is null
                                
                                $rarity = $award->rarity ?? 'common';
                                $rarityLabels = [
                                    'common' => 'Common',
                                    'uncommon' => 'Uncommon',
                                    'rare' => 'Rare', 
                                    'legendary' => 'Legendary'
                                ];
                            @endphp
                            
                            @if($award)
                            <div class="award-card {{ $rarity }}" data-rarity="{{ $rarity }}">
                                <div class="rarity-indicator {{ $rarity }}">
                                    @switch($rarity)
                                        @case('common')
                                            <i class="fas fa-circle"></i>
                                            @break
                                        @case('uncommon')
                                            <i class="fas fa-gem"></i>
                                            @break
                                        @case('rare')
                                            <i class="fas fa-star"></i>
                                            @break
                                        @case('legendary')
                                            <i class="fas fa-crown"></i>
                                            @break
                                    @endswitch
                                    {{ $rarityLabels[$rarity] ?? 'Common' }}
                                </div>
                                
                                <div class="award-image-container">
                                    @if($award && $award->image)
                                        <img src="{{ $award->image }}" alt="{{ $award->name }}" class="award-image"
                                             onerror="this.style.display='none'; this.parentNode.querySelector('.award-placeholder').style.display='block';">
                                        <div class="award-placeholder" style="display: none;">
                                            <i class="award-icon fas fa-trophy"></i>
                                        </div>
                                    @else
                                        <div class="award-placeholder">
                                            @switch($rarity)
                                                @case('legendary')
                                                    <i class="award-icon fas fa-crown" style="color: #ffd700;"></i>
                                                    @break
                                                @case('rare')
                                                    <i class="award-icon fas fa-star" style="color: #e74c3c;"></i>
                                                    @break
                                                @case('uncommon')
                                                    <i class="award-icon fas fa-gem" style="color: #9b59b6;"></i>
                                                    @break
                                                @default
                                                    <i class="award-icon fas fa-trophy" style="color: var(--jamaica-yellow);"></i>
                                            @endswitch
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="award-content">
                                    <h5 class="award-title">{{ $award->name ?? 'Unknown Award' }}</h5>
                                    <p class="award-description">{!! $award->description ?? 'No description available' !!}</p>
                                    
                                    <div class="award-meta">
                                        <span class="award-date">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ ($userAward->pivot->created_at ?? $userAward->created_at)->format('M j, Y') }}
                                        </span>
                                        <span class="rarity-percentage">
                                            @switch($rarity)
                                                @case('common')
                                                    <i class="fas fa-users me-1"></i>Common
                                                    @break
                                                @case('uncommon')
                                                    <i class="fas fa-user-friends me-1"></i>25% of pilots
                                                    @break
                                                @case('rare')
                                                    <i class="fas fa-user me-1"></i>10% of pilots
                                                    @break
                                                @case('legendary')
                                                    <i class="fas fa-crown me-1"></i>1% of pilots
                                                    @break
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    
                    {{-- Pagination --}}
                    @if($userAwards->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $userAwards->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3>No Awards Earned Yet</h3>
                        <p>Start your journey with Air Jamaica Virtual to earn your first award!</p>
                        
                        <div class="text-center mt-4">
                            <a href="{{ route('frontend.flights.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plane-departure me-2"></i>
                                Browse Flights
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Available Awards Tab --}}
            <div class="tab-pane fade" id="available" role="tabpanel">
                @if($availableAwardsData && $availableAwardsData->count() > 0)
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-bullseye me-2" style="color: var(--jamaica-green);"></i>
                            Available Awards ({{ $availableAwards }})
                        </h4>
                        <small class="text-muted">Awards you can still earn</small>
                    </div>
                    
                    <div class="awards-grid">
                        @foreach($availableAwardsData as $award)
                            @php
                                $rarity = $award->rarity ?? 'common';
                                $rarityLabels = [
                                    'common' => 'Common',
                                    'uncommon' => 'Uncommon',
                                    'rare' => 'Rare',
                                    'legendary' => 'Legendary'
                                ];
                            @endphp
                            
                            <div class="award-card {{ $rarity }}" style="opacity: 0.8;">
                                <div class="rarity-indicator {{ $rarity }}">
                                    @switch($rarity)
                                        @case('common')
                                            <i class="fas fa-circle"></i>
                                            @break
                                        @case('uncommon')
                                            <i class="fas fa-gem"></i>
                                            @break
                                        @case('rare')
                                            <i class="fas fa-star"></i>
                                            @break
                                        @case('legendary')
                                            <i class="fas fa-crown"></i>
                                            @break
                                    @endswitch
                                    {{ $rarityLabels[$rarity] ?? 'Common' }}
                                </div>
                                
                                <div class="award-image-container">
                                    @if($award && $award->image)
                                        <img src="{{ $award->image }}" alt="{{ $award->name }}" class="award-image" style="filter: grayscale(50%);"
                                             onerror="this.style.display='none'; this.parentNode.querySelector('.award-placeholder').style.display='block';">
                                        <div class="award-placeholder" style="display: none;">
                                            <i class="award-icon fas fa-trophy" style="opacity: 0.6; color: #666;"></i>
                                        </div>
                                    @else
                                        <div class="award-placeholder">
                                            @switch($rarity)
                                                @case('legendary')
                                                    <i class="award-icon fas fa-crown" style="opacity: 0.6; color: #a09060;"></i>
                                                    @break
                                                @case('rare')
                                                    <i class="award-icon fas fa-star" style="opacity: 0.6; color: #9e6060;"></i>
                                                    @break
                                                @case('uncommon')
                                                    <i class="award-icon fas fa-gem" style="opacity: 0.6; color: #7e5f8e;"></i>
                                                    @break
                                                @default
                                                    <i class="award-icon fas fa-trophy" style="opacity: 0.6; color: #b08d00;"></i>
                                            @endswitch
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="award-content">
                                    <h5 class="award-title">{{ $award->name }}</h5>
                                    <p class="award-description">{{ $award->description }}</p>
                                    
                                    <div class="award-meta">
                                        <span class="award-date">
                                            <i class="fas fa-lock me-1"></i>
                                            Not Earned
                                        </span>
                                        <span class="rarity-percentage">
                                            @switch($rarity)
                                                @case('common')
                                                    <i class="fas fa-users me-1"></i>Common
                                                    @break
                                                @case('uncommon')
                                                    <i class="fas fa-user-friends me-1"></i>25% rarity
                                                    @break
                                                @case('rare')
                                                    <i class="fas fa-user me-1"></i>10% rarity
                                                    @break
                                                @case('legendary')
                                                    <i class="fas fa-crown me-1"></i>1% rarity
                                                    @break
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3>All Awards Earned!</h3>
                        <p>Congratulations! You've earned all available awards.</p>
                        <small class="text-muted">New awards may be added with future updates.</small>
                    </div>
                @endif
            </div>

            {{-- Analytics Tab --}}
            <div class="tab-pane fade" id="analytics" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="stat-card">
                            <h5 class="text-white mb-3">
                                <i class="fas fa-chart-pie me-2" style="color: var(--jamaica-yellow);"></i>
                                Award Distribution
                            </h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Common</span>
                                <span class="text-white">{{ $userAwards->where('award.rarity', 'common')->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Uncommon</span>
                                <span class="text-white">{{ $userAwards->where('award.rarity', 'uncommon')->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Rare</span>
                                <span class="text-white">{{ $userAwards->where('award.rarity', 'rare')->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Legendary</span>
                                <span class="text-white">{{ $userAwards->where('award.rarity', 'legendary')->count() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="stat-card">
                            <h5 class="text-white mb-3">
                                <i class="fas fa-calendar-alt me-2" style="color: var(--jamaica-green);"></i>
                                Recent Activity
                            </h5>
                            @php
                                $recentActivity = $userAwards->where('created_at', '>=', now()->subDays(30));
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">This Month</span>
                                <span class="text-white">{{ $recentActivity->count() }} awards</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">This Year</span>
                                <span class="text-white">{{ $userAwards->where('created_at', '>=', now()->startOfYear())->count() }} awards</span>
                            </div>
                            @if($userAwards->count() > 0)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Latest Award</span>
                                <span class="text-white">{{ $userAwards->first()->created_at->diffForHumans() }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($userAwards->count() > 0)
                <div class="stat-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-trophy me-2" style="color: var(--jamaica-yellow);"></i>
                        Achievement Timeline
                    </h5>
                    <div class="timeline">
                        @foreach($userAwards->take(10) as $userAward)
                        <div class="timeline-item">
                            <div class="timeline-content">
                                @php
                                    $award = $userAward->award ?? $userAward;
                                @endphp
                                <h6 class="text-white">{{ $award->name ?? 'Unknown Award' }}</h6>
                                <small class="text-muted">{{ ($userAward->pivot->created_at ?? $userAward->created_at)->format('M j, Y g:i A') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for filtering and interactions --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Award filtering functionality
    const filterButtons = document.querySelectorAll('[data-filter]');
    const awardCards = document.querySelectorAll('#myAwardsGrid .award-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.getAttribute('data-filter');
            
            awardCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-rarity') === filter) {
                    card.style.display = 'block';
                    card.style.animation = 'slideInUp 0.6s ease-out';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Add hover effects to award cards
    document.querySelectorAll('.award-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Tab switching with local storage
    const tabTriggers = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const targetId = this.getAttribute('data-bs-target');
            localStorage.setItem('activeAwardsTab', targetId);
        });
    });
    
    // Restore active tab from local storage
    const savedTab = localStorage.getItem('activeAwardsTab');
    if (savedTab) {
        const savedTabButton = document.querySelector(`[data-bs-target="${savedTab}"]`);
        if (savedTabButton) {
            const tab = new bootstrap.Tab(savedTabButton);
            tab.show();
        }
    }
});
</script>
@endsection