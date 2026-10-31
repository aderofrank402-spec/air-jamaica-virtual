@extends('layouts.seven.public_app')
@section('title', 'Training Center')

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
        'icon' => 'bi bi-mortarboard'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

@section('css')
    {{-- Air Jamaica Virtual Training Styles --}}
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

        /* Training Dashboard Container */
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

        /* Hero Section */
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

        /* Enhanced Statistics Section */
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

        /* Enhanced Operation Cards */
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

        .operation-card.training-active::before {
            background: linear-gradient(90deg, #0066cc, #3399ff);
        }

        .operation-card.training-courses::before {
            background: linear-gradient(90deg, #00a651, #20c997);
        }

        .operation-card.training-certs::before {
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

        .operation-header.training {
            background: linear-gradient(135deg, rgba(0, 102, 204, 0.9), rgba(51, 153, 255, 0.7));
        }

        .operation-header.certifications {
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

        .operation-icon.training { 
            background: linear-gradient(135deg, rgba(0, 102, 204, 0.8), rgba(51, 153, 255, 0.6)); 
            border: 1px solid rgba(0, 102, 204, 0.6);
            box-shadow: 0 4px 15px rgba(0, 102, 204, 0.3);
        }

        .operation-icon.course { 
            background: linear-gradient(135deg, rgba(0, 166, 81, 0.8), rgba(32, 201, 151, 0.6)); 
            border: 1px solid rgba(0, 166, 81, 0.6);
            box-shadow: 0 4px 15px rgba(0, 166, 81, 0.3);
        }

        .operation-icon.cert { 
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

        .progress-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 8px;
            background: rgba(26, 31, 46, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 0.75rem;
        }

        .progress-item:last-child {
            margin-bottom: 0;
        }

        .progress-content {
            flex-grow: 1;
            margin-right: 1rem;
        }

        .progress-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--jamaica-white);
            margin-bottom: 0.25rem;
        }

        .progress-bar-container {
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--jamaica-green), var(--jamaica-yellow));
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .progress-percentage {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--jamaica-yellow);
            white-space: nowrap;
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

            .operations-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .operation-card {
                margin-bottom: 1rem;
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
                            <i class="fas fa-graduation-cap me-3"></i>
                            Air Jamaica Virtual Training Center
                        </h1>
                        <p class="hero-subtitle">
                            Enhance your skills and advance your career with our comprehensive pilot training programs
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Training Statistics Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="stats-container">
                    <div class="stats-header">
                        <h2 class="stats-title">Your Training Statistics</h2>
                        <p class="stats-subtitle">Track your progress in the Air Jamaica Virtual training program</p>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stat-value">{{ $totalCourses ?? 8 }}</div>
                            <div class="stat-label">Available Courses</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: 100%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="stat-value">{{ $activeEnrollments ?? 2 }}</div>
                            <div class="stat-label">Active Enrollments</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: {{ min((($activeEnrollments ?? 2) / 5) * 100, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="stat-value">{{ $completedCertifications ?? 3 }}</div>
                            <div class="stat-label">Certificates Earned</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: {{ min((($completedCertifications ?? 3) / 10) * 100, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-value">{{ number_format($completionRate ?? 75, 0) }}%</div>
                            <div class="stat-label">Completion Rate</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: {{ $completionRate ?? 75 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Training Operations Cards Section --}}
        <div class="row">
            <div class="col-12">
                <div class="operations-grid">
                    
                    {{-- Card 1: Active Training (Blue Theme) --}}
                    <div class="operation-card training-active">
                        <div class="operation-header training">
                            <h3 class="operation-title">
                                <i class="fas fa-play-circle"></i>
                                Active Training
                            </h3>
                        </div>
                        <div class="operation-body">
                            {{-- Active Training Progress --}}
                            <div class="progress-item">
                                <div class="progress-content">
                                    <div class="progress-title">Boeing 737 Systems Training</div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar-fill" style="width: 65%"></div>
                                    </div>
                                </div>
                                <div class="progress-percentage">65%</div>
                            </div>

                            <div class="progress-item">
                                <div class="progress-content">
                                    <div class="progress-title">Emergency Procedures</div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar-fill" style="width: 30%"></div>
                                    </div>
                                </div>
                                <div class="progress-percentage">30%</div>
                            </div>

                            {{-- Training Actions --}}
                            <a href="{{ route('frontend.training.courses') }}" class="operation-item">
                                <div class="operation-icon training">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Continue Training</h5>
                                    <small>Resume your active courses</small>
                                </div>
                                <span class="operation-badge">2 Active</span>
                            </a>

                            <a href="{{ route('frontend.training.courses') }}" class="operation-item">
                                <div class="operation-icon training">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Schedule Training</h5>
                                    <small>Plan your next training session</small>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Card 2: Training Courses (Green Theme) --}}
                    <div class="operation-card training-courses">
                        <div class="operation-header">
                            <h3 class="operation-title">
                                <i class="fas fa-book-open"></i>
                                Training Courses
                            </h3>
                        </div>
                        <div class="operation-body">
                            <a href="{{ route('frontend.training.courses') }}" class="operation-item">
                                <div class="operation-icon course">
                                    <i class="fas fa-compass"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>New Pilot Orientation</h5>
                                    <small>Essential training for new pilots</small>
                                </div>
                                <span class="operation-badge">4h</span>
                            </a>

                            <a href="{{ route('frontend.training.courses') }}" class="operation-item">
                                <div class="operation-icon course">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Aircraft Systems</h5>
                                    <small>Comprehensive systems training</small>
                                </div>
                                <span class="operation-badge">8h</span>
                            </a>

                            <a href="{{ route('frontend.training.courses') }}" class="operation-item">
                                <div class="operation-icon course">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Emergency Procedures</h5>
                                    <small>Critical safety protocols</small>
                                </div>
                                <span class="operation-badge">6h</span>
                            </a>

                            <a href="{{ route('frontend.training.courses') }}" class="operation-item">
                                <div class="operation-icon course">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Browse All Courses</h5>
                                    <small>View complete training catalog</small>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Card 3: Certifications (Yellow Theme) --}}
                    <div class="operation-card training-certs">
                        <div class="operation-header certifications">
                            <h3 class="operation-title">
                                <i class="fas fa-certificate"></i>
                                Certifications
                            </h3>
                        </div>
                        <div class="operation-body">
                            <a href="{{ route('frontend.training.certifications') }}" class="operation-item">
                                <div class="operation-icon cert">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>My Certificates</h5>
                                    <small>View earned certifications</small>
                                </div>
                                <span class="operation-badge">3</span>
                            </a>

                            <a href="{{ route('frontend.training.certifications') }}" class="operation-item">
                                <div class="operation-icon cert">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Available Certificates</h5>
                                    <small>Certifications you can earn</small>
                                </div>
                            </a>

                            <a href="{{ route('frontend.training.certifications') }}" class="operation-item">
                                <div class="operation-icon cert">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Renewal Status</h5>
                                    <small>Check expiration dates</small>
                                </div>
                            </a>

                            <a href="{{ route('frontend.dashboard.index') }}" class="operation-item">
                                <div class="operation-icon cert">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Back to Dashboard</h5>
                                    <small>Return to pilot center</small>
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