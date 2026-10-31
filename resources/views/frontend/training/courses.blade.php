@extends('layouts.seven.public_app')
@section('title', 'Training Courses')

@section('content_header')
{{-- Breadcrumb Navigation --}}
@php
$breadcrumbs = [
    [
        'title' => 'Pilot Center',
        'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
        'icon' => Auth::check() ? 'bi bi-speedometer2' : 'bi bi-house-fill'
    ],
    [
        'title' => 'Training Center',
        'url' => route('frontend.training.index'),
        'icon' => 'bi bi-mortarboard'
    ],
    [
        'title' => 'Training Courses',
        'icon' => 'bi bi-book'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

@section('css')
    {{-- Air Jamaica Virtual Dashboard Styles --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        }

        /* Body Styling */
        body {
            padding-top: 76px !important; /* Account for fixed navbar */
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #2a2f3e 100%) !important;
            background-attachment: fixed;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Effects from Dashboard */
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

        /* Floating Particles Effect from Dashboard */
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

        /* Dashboard Container */
        .jamaica-dashboard {
            padding: 100px 0 3rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Navigation Integration */
        .public-nav {
            background: linear-gradient(135deg, var(--jamaica-green) 0%, var(--jamaica-blue) 100%) !important;
            backdrop-filter: blur(10px) !important;
            border-bottom: 3px solid var(--jamaica-yellow) !important;
        }

        /* Hero Section with Dashboard Style */
        .hero-section {
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

        .hero-section::before {
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
            background: linear-gradient(45deg, #ffffff, #00a651);
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

        /* Enhanced Statistics Section from Dashboard */
        .stats-container {
            background: rgba(15, 20, 25, 0.4);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .stats-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .stats-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--jamaica-white);
            margin-bottom: 0.5rem;
        }

        .stats-subtitle {
            color: #a0a0a0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .stat-item {
            background: rgba(26, 31, 46, 0.6);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(42, 47, 62, 0.3);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #00a651, #ffd700);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
            border-color: rgba(0, 166, 81, 0.4);
        }

        .stat-item:hover::before {
            opacity: 1;
        }

        .stat-icon {
            font-size: 2rem;
            color: var(--jamaica-green);
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--jamaica-white);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #a0a0a0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-progress {
            margin-top: 1rem;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
        }

        .stat-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--jamaica-green), var(--jamaica-yellow));
            border-radius: 2px;
            transition: width 1s ease;
        }

        /* Course Grid */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .course-card {
            background: rgba(26, 31, 46, 0.8);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            backdrop-filter: blur(15px);
            position: relative;
            text-decoration: none;
            color: inherit;
        }

        .course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .course-card.orientation::before {
            background: linear-gradient(90deg, #FF6B35, #F7931E);
        }

        .course-card.aircraft_systems::before {
            background: linear-gradient(90deg, #4ECDC4, #44A08D);
        }

        .course-card.safety::before {
            background: linear-gradient(90deg, #FF416C, #FF4B2B);
        }

        .course-card.navigation::before {
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .course-card.weather::before {
            background: linear-gradient(90deg, #f093fb, #f5576c);
        }

        .course-card.regulations::before {
            background: linear-gradient(90deg, #4facfe, #00f2fe);
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow-hover);
            border-color: rgba(0, 166, 81, 0.3);
            text-decoration: none;
            color: inherit;
        }

        .course-card:hover::before {
            opacity: 1;
        }

        .course-header {
            padding: 1.5rem 2rem;
            background: rgba(15, 20, 25, 0.6);
            position: relative;
        }

        .course-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
        }

        .course-category {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
        }

        .category-orientation { background: linear-gradient(45deg, #FF6B35, #F7931E); color: white; }
        .category-aircraft_systems { background: linear-gradient(45deg, #4ECDC4, #44A08D); color: white; }
        .category-safety { background: linear-gradient(45deg, #FF416C, #FF4B2B); color: white; }
        .category-navigation { background: linear-gradient(45deg, #667eea, #764ba2); color: white; }
        .category-weather { background: linear-gradient(45deg, #f093fb, #f5576c); color: white; }
        .category-regulations { background: linear-gradient(45deg, #4facfe, #00f2fe); color: white; }

        .course-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--jamaica-white);
            margin-bottom: 0.5rem;
        }

        .course-subtitle {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        .course-body {
            padding: 2rem;
            background: rgba(15, 20, 25, 0.4);
        }

        .course-description {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .course-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        .meta-item i {
            margin-right: 0.5rem;
            color: var(--jamaica-green);
        }

        .course-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .course-difficulty {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .difficulty-beginner {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .difficulty-intermediate {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .difficulty-advanced {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .btn-enroll {
            background: linear-gradient(45deg, var(--jamaica-green), #2ECC71);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-enroll:hover {
            background: linear-gradient(45deg, #2ECC71, var(--jamaica-green));
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(46, 204, 113, 0.3);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(26, 31, 46, 0.6);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .empty-state-icon {
            font-size: 4rem;
            color: var(--jamaica-yellow);
            margin-bottom: 1.5rem;
        }

        .empty-state-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--jamaica-white);
            margin-bottom: 1rem;
        }

        .empty-state-text {
            color: #a0a0a0;
            margin-bottom: 2rem;
        }

        .btn-jamaica {
            background: linear-gradient(45deg, var(--jamaica-green), #2ECC71);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-jamaica:hover {
            background: linear-gradient(45deg, #2ECC71, var(--jamaica-green));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.3);
            color: white;
            text-decoration: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .jamaica-dashboard {
                padding: 80px 1rem 2rem 1rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .courses-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="jamaica-dashboard">
    <div class="container-fluid">
        {{-- Hero Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-section">
                    <div class="hero-content">
                        <h1 class="hero-title">
                            <i class="fas fa-book-open me-3"></i>
                            Training Courses Catalog
                        </h1>
                        <p class="hero-subtitle">
                            Explore our comprehensive training programs designed to enhance your aviation skills
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Course Statistics Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="stats-container">
                    <div class="stats-header">
                        <h2 class="stats-title">Course Catalog Overview</h2>
                        <p class="stats-subtitle">Comprehensive training opportunities available at Air Jamaica Virtual</p>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stat-value">{{ isset($courses) ? count($courses) : 8 }}</div>
                            <div class="stat-label">Total Courses</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: 100%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-play-circle"></i>
                            </div>
                            <div class="stat-value">6</div>
                            <div class="stat-label">Available Now</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: 75%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-value">156</div>
                            <div class="stat-label">Active Students</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: 85%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-value">4.8</div>
                            <div class="stat-label">Average Rating</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: 96%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Courses Grid --}}
        <div class="row">
            <div class="col-12">
                @if(isset($courses) && count($courses) > 0)
                    <div class="courses-grid">
                        @foreach($courses as $course)
                            <a href="{{ route('frontend.training.course', $course) }}" class="course-card {{ $course->category }}">
                                <div class="course-header">
                                    <span class="course-category category-{{ $course->category }}">
                                        {{ ucfirst(str_replace('_', ' ', $course->category)) }}
                                    </span>
                                    <h3 class="course-title">{{ $course->title }}</h3>
                                    <p class="course-subtitle">{{ $course->subtitle ?? 'Professional aviation training course' }}</p>
                                </div>
                                <div class="course-body">
                                    <p class="course-description">{{ $course->description }}</p>
                                    
                                    <div class="course-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $course->estimated_hours }}h</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-list"></i>
                                            <span>{{ $course->modules_count ?? 8 }} Modules</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-users"></i>
                                            <span>{{ rand(15, 45) }} Students</span>
                                        </div>
                                        @if($course->aircraft_type)
                                        <div class="meta-item">
                                            <i class="fas fa-plane"></i>
                                            <span>{{ $course->aircraft_type }}</span>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="course-footer">
                                        <span class="course-difficulty difficulty-{{ $course->difficulty_level }}">
                                            {{ ucfirst($course->difficulty_level) }}
                                        </span>
                                        <button class="btn-enroll">
                                            Enroll Now <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    {{-- Sample Courses --}}
                    <div class="courses-grid">
                        <a href="{{ route('frontend.training.courses') }}" class="course-card orientation">
                            <div class="course-header">
                                <span class="course-category category-orientation">Orientation</span>
                                <h3 class="course-title">New Pilot Orientation</h3>
                                <p class="course-subtitle">Essential foundation for new pilots</p>
                            </div>
                            <div class="course-body">
                                <p class="course-description">Comprehensive introduction to Air Jamaica Virtual operations, procedures, and standards. Perfect starting point for new virtual pilots.</p>
                                
                                <div class="course-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>4h</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-list"></i>
                                        <span>6 Modules</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>32 Students</span>
                                    </div>
                                </div>

                                <div class="course-footer">
                                    <span class="course-difficulty difficulty-beginner">Beginner</span>
                                    <button class="btn-enroll">
                                        Enroll Now <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('frontend.training.courses') }}" class="course-card aircraft_systems">
                            <div class="course-header">
                                <span class="course-category category-aircraft_systems">Aircraft Systems</span>
                                <h3 class="course-title">Boeing 737 Systems Training</h3>
                                <p class="course-subtitle">Comprehensive aircraft systems knowledge</p>
                            </div>
                            <div class="course-body">
                                <p class="course-description">In-depth study of Boeing 737 systems including hydraulics, electrical, flight controls, and engine operations for realistic flight simulation.</p>
                                
                                <div class="course-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>8h</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-list"></i>
                                        <span>12 Modules</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>24 Students</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-plane"></i>
                                        <span>Boeing 737</span>
                                    </div>
                                </div>

                                <div class="course-footer">
                                    <span class="course-difficulty difficulty-intermediate">Intermediate</span>
                                    <button class="btn-enroll">
                                        Enroll Now <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('frontend.training.courses') }}" class="course-card safety">
                            <div class="course-header">
                                <span class="course-category category-safety">Safety</span>
                                <h3 class="course-title">Emergency Procedures</h3>
                                <p class="course-subtitle">Critical safety protocols and procedures</p>
                            </div>
                            <div class="course-body">
                                <p class="course-description">Essential emergency procedures training covering fire, decompression, engine failure, and other critical scenarios every pilot must know.</p>
                                
                                <div class="course-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>6h</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-list"></i>
                                        <span>8 Modules</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>18 Students</span>
                                    </div>
                                </div>

                                <div class="course-footer">
                                    <span class="course-difficulty difficulty-intermediate">Intermediate</span>
                                    <button class="btn-enroll">
                                        Enroll Now <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('frontend.training.courses') }}" class="course-card navigation">
                            <div class="course-header">
                                <span class="course-category category-navigation">Navigation</span>
                                <h3 class="course-title">Advanced Navigation Systems</h3>
                                <p class="course-subtitle">Modern navigation technology mastery</p>
                            </div>
                            <div class="course-body">
                                <p class="course-description">Master modern navigation systems including GPS, ILS, VOR, and advanced flight management systems for precise flight operations.</p>
                                
                                <div class="course-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>7h</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-list"></i>
                                        <span>10 Modules</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>15 Students</span>
                                    </div>
                                </div>

                                <div class="course-footer">
                                    <span class="course-difficulty difficulty-advanced">Advanced</span>
                                    <button class="btn-enroll">
                                        Enroll Now <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('frontend.training.courses') }}" class="course-card weather">
                            <div class="course-header">
                                <span class="course-category category-weather">Weather</span>
                                <h3 class="course-title">Weather & Meteorology</h3>
                                <p class="course-subtitle">Advanced weather interpretation skills</p>
                            </div>
                            <div class="course-body">
                                <p class="course-description">Comprehensive weather training covering interpretation of forecasts, reading weather patterns, and making informed flight decisions.</p>
                                
                                <div class="course-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>5h</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-list"></i>
                                        <span>7 Modules</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>28 Students</span>
                                    </div>
                                </div>

                                <div class="course-footer">
                                    <span class="course-difficulty difficulty-intermediate">Intermediate</span>
                                    <button class="btn-enroll">
                                        Enroll Now <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('frontend.training.courses') }}" class="course-card regulations">
                            <div class="course-header">
                                <span class="course-category category-regulations">Regulations</span>
                                <h3 class="course-title">Aviation Regulations</h3>
                                <p class="course-subtitle">Current aviation law and procedures</p>
                            </div>
                            <div class="course-body">
                                <p class="course-description">Stay current with aviation regulations, procedures, and legal requirements for safe and compliant flight operations.</p>
                                
                                <div class="course-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>6h</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-list"></i>
                                        <span>9 Modules</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>22 Students</span>
                                    </div>
                                </div>

                                <div class="course-footer">
                                    <span class="course-difficulty difficulty-beginner">Beginner</span>
                                    <button class="btn-enroll">
                                        Enroll Now <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection