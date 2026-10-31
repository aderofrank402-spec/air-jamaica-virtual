@extends('layouts.seven.public_app')
@section('title', 'Course Details')

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
        'url' => route('frontend.training.courses'),
        'icon' => 'bi bi-book'
    ],
    [
        'title' => isset($course) ? $course->title : 'Course Details',
        'icon' => 'bi bi-journal-text'
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
            margin-bottom: 1rem;
            color: #a0a0a0;
        }

        .course-category {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0;
        }

        .category-orientation { background: linear-gradient(45deg, #FF6B35, #F7931E); color: white; }
        .category-aircraft_systems { background: linear-gradient(45deg, #4ECDC4, #44A08D); color: white; }
        .category-safety { background: linear-gradient(45deg, #FF416C, #FF4B2B); color: white; }
        .category-navigation { background: linear-gradient(45deg, #667eea, #764ba2); color: white; }
        .category-weather { background: linear-gradient(45deg, #f093fb, #f5576c); color: white; }
        .category-regulations { background: linear-gradient(45deg, #4facfe, #00f2fe); color: white; }

        /* Enhanced Operation Cards from Dashboard */
        .operations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .operation-card {
            background: rgba(26, 31, 46, 0.8);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            backdrop-filter: blur(15px);
            position: relative;
        }

        .operation-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .operation-card.modules::before {
            background: linear-gradient(90deg, #00a651, #20c997);
        }

        .operation-card.progress::before {
            background: linear-gradient(90deg, #0066cc, #3399ff);
        }

        .operation-card.actions::before {
            background: linear-gradient(90deg, #ffd700, #ffed4a);
        }

        .operation-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow-hover);
            border-color: rgba(0, 166, 81, 0.3);
        }

        .operation-card:hover::before {
            opacity: 1;
        }

        .operation-header {
            background: linear-gradient(135deg, rgba(0, 166, 81, 0.9), rgba(32, 201, 151, 0.7));
            color: var(--jamaica-white);
            padding: 1.5rem 2rem;
            position: relative;
        }

        .operation-header.progress {
            background: linear-gradient(135deg, rgba(0, 102, 204, 0.9), rgba(51, 153, 255, 0.7));
        }

        .operation-header.actions {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.9), rgba(255, 237, 74, 0.7));
            color: var(--jamaica-dark);
        }

        .operation-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: rgba(255, 255, 255, 0.3);
        }

        .operation-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .operation-body {
            padding: 2rem;
            background: rgba(15, 20, 25, 0.4);
        }

        .operation-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 12px;
            text-decoration: none;
            color: var(--jamaica-white);
            transition: all 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
            background: rgba(26, 31, 46, 0.4);
            backdrop-filter: blur(10px);
        }

        .operation-item:hover {
            background: rgba(0, 166, 81, 0.15);
            color: var(--jamaica-white);
            border-color: rgba(0, 166, 81, 0.4);
            text-decoration: none;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 166, 81, 0.2);
        }

        .operation-item:last-child {
            margin-bottom: 0;
        }

        .operation-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.3rem;
            color: var(--jamaica-white);
            transition: all 0.2s ease;
            backdrop-filter: blur(10px);
        }

        .operation-icon.module { 
            background: linear-gradient(135deg, rgba(0, 166, 81, 0.8), rgba(32, 201, 151, 0.6)); 
            border: 1px solid rgba(0, 166, 81, 0.6);
            box-shadow: 0 4px 15px rgba(0, 166, 81, 0.3);
        }

        .operation-icon.progress { 
            background: linear-gradient(135deg, rgba(0, 102, 204, 0.8), rgba(51, 153, 255, 0.6)); 
            border: 1px solid rgba(0, 102, 204, 0.6);
            box-shadow: 0 4px 15px rgba(0, 102, 204, 0.3);
        }

        .operation-icon.action { 
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.8), rgba(255, 237, 74, 0.6)); 
            border: 1px solid rgba(255, 215, 0, 0.6);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
            color: var(--jamaica-dark) !important;
        }

        .operation-content h5 {
            margin: 0 0 0.25rem 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .operation-content small {
            color: var(--jamaica-gray);
        }

        .operation-badge {
            margin-left: auto;
            background: var(--jamaica-green);
            color: var(--jamaica-white);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-completed {
            background: var(--jamaica-green) !important;
        }

        .badge-current {
            background: var(--jamaica-blue) !important;
        }

        .badge-locked {
            background: var(--jamaica-gray) !important;
        }

        /* Course Overview */
        .course-overview {
            background: rgba(15, 20, 25, 0.4);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            color: var(--jamaica-white);
        }

        .course-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .meta-item {
            text-align: center;
            padding: 1rem;
            background: rgba(26, 31, 46, 0.6);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .meta-icon {
            font-size: 1.5rem;
            color: var(--jamaica-green);
            margin-bottom: 0.5rem;
        }

        .meta-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--jamaica-white);
            margin-bottom: 0.25rem;
        }

        .meta-label {
            font-size: 0.8rem;
            color: #a0a0a0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-enroll {
            background: linear-gradient(45deg, var(--jamaica-green), #2ECC71);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-size: 1rem;
        }

        .btn-enroll:hover {
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

            .operations-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .course-meta-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
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
                        @if(isset($course))
                            <span class="course-category category-{{ $course->category ?? 'orientation' }}">
                                {{ ucfirst(str_replace('_', ' ', $course->category ?? 'Orientation')) }}
                            </span>
                            <h1 class="hero-title">{{ $course->title }}</h1>
                            <p class="hero-subtitle">{{ $course->description }}</p>
                        @else
                            <span class="course-category category-orientation">Orientation</span>
                            <h1 class="hero-title">New Pilot Orientation</h1>
                            <p class="hero-subtitle">Essential training for new pilots joining Air Jamaica Virtual</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Course Overview --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="course-overview">
                    <h3 class="mb-3">Course Overview</h3>
                    
                    <div class="course-meta-grid">
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="meta-value">{{ isset($course) ? $course->estimated_hours : 4 }}h</div>
                            <div class="meta-label">Duration</div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="meta-value">{{ isset($course) ? ($course->modules_count ?? 6) : 6 }}</div>
                            <div class="meta-label">Modules</div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="meta-value">{{ isset($course) ? ucfirst($course->difficulty_level) : 'Beginner' }}</div>
                            <div class="meta-label">Level</div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="meta-value">{{ rand(15, 45) }}</div>
                            <div class="meta-label">Students</div>
                        </div>

                        @if(isset($course) && $course->aircraft_type)
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-plane"></i>
                            </div>
                            <div class="meta-value">{{ $course->aircraft_type }}</div>
                            <div class="meta-label">Aircraft</div>
                        </div>
                        @endif
                    </div>

                    <div class="text-center">
                        <a href="#" class="btn-enroll">
                            <i class="fas fa-play me-2"></i>Start Course
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Course Content --}}
        <div class="row">
            <div class="col-12">
                <div class="operations-grid">
                    
                    {{-- Course Modules --}}
                    <div class="operation-card modules">
                        <div class="operation-header">
                            <h3 class="operation-title">
                                <i class="fas fa-list"></i>
                                Course Modules
                            </h3>
                        </div>
                        <div class="operation-body">
                            <a href="#" class="operation-item">
                                <div class="operation-icon module">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Module 1: Introduction</h5>
                                    <small>Welcome and course overview</small>
                                </div>
                                <span class="operation-badge badge-current">Current</span>
                            </a>

                            <a href="#" class="operation-item">
                                <div class="operation-icon module">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Module 2: Basic Procedures</h5>
                                    <small>Fundamental flight procedures</small>
                                </div>
                                <span class="operation-badge badge-locked">Locked</span>
                            </a>

                            <a href="#" class="operation-item">
                                <div class="operation-icon module">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Module 3: Aircraft Systems</h5>
                                    <small>Understanding aircraft systems</small>
                                </div>
                                <span class="operation-badge badge-locked">Locked</span>
                            </a>

                            <a href="#" class="operation-item">
                                <div class="operation-icon module">
                                    <i class="fas fa-route"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Module 4: Navigation</h5>
                                    <small>Navigation fundamentals</small>
                                </div>
                                <span class="operation-badge badge-locked">Locked</span>
                            </a>
                        </div>
                    </div>

                    {{-- Progress Tracking --}}
                    <div class="operation-card progress">
                        <div class="operation-header progress">
                            <h3 class="operation-title">
                                <i class="fas fa-chart-line"></i>
                                Your Progress
                            </h3>
                        </div>
                        <div class="operation-body">
                            <a href="#" class="operation-item">
                                <div class="operation-icon progress">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Course Completion</h5>
                                    <small>0% complete</small>
                                </div>
                                <span class="operation-badge">0/6</span>
                            </a>

                            <a href="#" class="operation-item">
                                <div class="operation-icon progress">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Time Spent</h5>
                                    <small>Total study time</small>
                                </div>
                                <span class="operation-badge">0h 0m</span>
                            </a>

                            <a href="#" class="operation-item">
                                <div class="operation-icon progress">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Quiz Scores</h5>
                                    <small>Average quiz performance</small>
                                </div>
                                <span class="operation-badge">-</span>
                            </a>

                            <a href="#" class="operation-item">
                                <div class="operation-icon progress">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Estimated Completion</h5>
                                    <small>Based on current progress</small>
                                </div>
                                <span class="operation-badge">-</span>
                            </a>
                        </div>
                    </div>

                    {{-- Course Actions --}}
                    <div class="operation-card actions">
                        <div class="operation-header actions">
                            <h3 class="operation-title">
                                <i class="fas fa-tools"></i>
                                Course Actions
                            </h3>
                        </div>
                        <div class="operation-body">
                            <a href="{{ route('frontend.training.courses') }}" class="operation-item">
                                <div class="operation-icon action">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Back to Courses</h5>
                                    <small>Return to course catalog</small>
                                </div>
                            </a>

                            <a href="#" class="operation-item">
                                <div class="operation-icon action">
                                    <i class="fas fa-download"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Download Materials</h5>
                                    <small>Course handbooks and guides</small>
                                </div>
                            </a>

                            <a href="#" class="operation-item">
                                <div class="operation-icon action">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Get Help</h5>
                                    <small>Contact course instructor</small>
                                </div>
                            </a>

                            <a href="{{ route('frontend.training.index') }}" class="operation-item">
                                <div class="operation-icon action">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Training Center</h5>
                                    <small>Return to training dashboard</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection