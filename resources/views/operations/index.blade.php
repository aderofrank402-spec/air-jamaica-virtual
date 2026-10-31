@extends('layouts.seven.public_app')
@section('title', 'Operations Manual')

{{-- Breadcrumb Navigation --}}
@php
$breadcrumbs = [
    [
        'title' => 'Pilot Center',
        'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
        'icon' => Auth::check() ? 'fas fa-tachometer-alt' : 'fas fa-home'
    ],
    [
        'title' => 'Operations Manual',
        'icon' => 'fas fa-book'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

@section('css')
    {{-- Air Jamaica Virtual Operations Manual Styles --}}
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

        /* Operations Manual Container */
        .jamaica-operations {
            padding: 120px 0 3rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Breadcrumb positioning for operations page */
        .breadcrumb {
            position: relative;
            z-index: 1000;
            margin-top: 80px;
            margin-bottom: 2rem;
        }

        /* Hero Section */
        .operations-hero {
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

        .operations-hero::before {
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

        /* Manual Sections Grid */
        .manual-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .section-card {
            background: rgba(26, 31, 46, 0.8);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(15px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            color: var(--jamaica-white);
            text-decoration: none;
        }

        .section-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow-hover);
            text-decoration: none;
            color: var(--jamaica-white);
        }

        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .section-card.general::before { background: var(--gradient-green); }
        .section-card.flight::before { background: var(--gradient-blue); }
        .section-card.aircraft::before { background: var(--gradient-gold); }
        .section-card.network::before { background: linear-gradient(90deg, #e74c3c, #c0392b); }
        .section-card.admin::before { background: linear-gradient(90deg, #9b59b6, #8e44ad); }
        .section-card.quality::before { background: linear-gradient(90deg, #f39c12, #e67e22); }
        .section-card.emergency::before { background: linear-gradient(90deg, #e74c3c, #c0392b); }
        .section-card.technical::before { background: linear-gradient(90deg, #34495e, #2c3e50); }

        .section-card:hover::before {
            opacity: 1;
        }

        .section-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            display: block;
        }

        .section-card.general .section-icon { color: var(--jamaica-green); }
        .section-card.flight .section-icon { color: var(--jamaica-blue); }
        .section-card.aircraft .section-icon { color: var(--jamaica-yellow); }
        .section-card.network .section-icon { color: #e74c3c; }
        .section-card.admin .section-icon { color: #9b59b6; }
        .section-card.quality .section-icon { color: #f39c12; }
        .section-card.emergency .section-icon { color: #e74c3c; }
        .section-card.technical .section-icon { color: #34495e; }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--jamaica-white);
        }

        .section-description {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .section-topics {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .section-topics li {
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .section-topics li:last-child {
            border-bottom: none;
        }

        .section-topics li::before {
            content: '✈';
            color: var(--jamaica-green);
            margin-right: 0.5rem;
        }

        /* Quick Access Section */
        .quick-access {
            background: rgba(26, 31, 46, 0.8);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(15px);
        }

        .quick-access h3 {
            color: var(--jamaica-white);
            margin-bottom: 1.5rem;
        }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .quick-link {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            color: var(--jamaica-white);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .quick-link:hover {
            background: rgba(0, 166, 81, 0.2);
            color: var(--jamaica-white);
            text-decoration: none;
            transform: translateY(-2px);
        }

        .quick-link i {
            margin-right: 1rem;
            color: var(--jamaica-green);
            font-size: 1.2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .manual-sections {
                grid-template-columns: 1fr;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .quick-links {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        .section-card {
            animation: slideInUp 0.6s ease-out;
        }

        .section-card:nth-child(1) { animation-delay: 0.1s; }
        .section-card:nth-child(2) { animation-delay: 0.2s; }
        .section-card:nth-child(3) { animation-delay: 0.3s; }
        .section-card:nth-child(4) { animation-delay: 0.4s; }

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

<div class="jamaica-operations">
    <div class="container">
        {{-- Hero Section --}}
        <div class="operations-hero animate__animated animate__fadeInDown">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-book me-3"></i>Operations Manual
                </h1>
                <p class="hero-subtitle">
                    Comprehensive Standard Operating Procedures for Air Jamaica Virtual
                </p>
            </div>
        </div>

        {{-- Quick Access Section --}}
        <div class="quick-access animate__animated animate__fadeInUp animate__delay-1s">
            <h3>
                <i class="fas fa-bolt me-2" style="color: var(--jamaica-yellow);"></i>
                Quick Access
            </h3>
            <div class="quick-links">
                <a href="#emergency" class="quick-link">
                    <i class="fas fa-exclamation-triangle"></i>
                    Emergency Procedures
                </a>
                <a href="{{ route('frontend.flights.index') }}" class="quick-link">
                    <i class="fas fa-plane-departure"></i>
                    Flight Booking
                </a>
                <a href="{{ route('frontend.livemap.index') }}" class="quick-link">
                    <i class="fas fa-map"></i>
                    Live Map
                </a>
                <a href="{{ route('frontend.notams.index') }}" class="quick-link">
                    <i class="fas fa-bullhorn"></i>
                    Current NOTAMs
                </a>
            </div>
        </div>

        {{-- Manual Sections --}}
        <div class="manual-sections">
            {{-- General Operations --}}
            <a href="{{ route('frontend.operations.section', 'general-operations') }}" class="section-card general">
                <i class="section-icon fas fa-cog"></i>
                <h3 class="section-title">General Operations</h3>
                <p class="section-description">
                    Company overview, pilot requirements, code of conduct, and communication procedures for Air Jamaica Virtual operations.
                </p>
                <ul class="section-topics">
                    <li>Company Overview & Mission</li>
                    <li>Pilot Requirements & Certifications</li>
                    <li>Code of Conduct & Standards</li>
                    <li>Communication Procedures</li>
                </ul>
            </a>

            {{-- Flight Operations --}}
            <a href="{{ route('frontend.operations.section', 'flight-procedures') }}" class="section-card flight">
                <i class="section-icon fas fa-plane"></i>
                <h3 class="section-title">Flight Operations</h3>
                <p class="section-description">
                    Comprehensive flight procedures from pre-flight planning through post-flight documentation and PIREP filing.
                </p>
                <ul class="section-topics">
                    <li>Flight Planning & Weather</li>
                    <li>Pre-flight Procedures</li>
                    <li>Departure & En-route Operations</li>
                    <li>Approach & Landing Procedures</li>
                </ul>
            </a>

            {{-- Aircraft Procedures --}}
            <a href="{{ route('frontend.operations.section', 'aircraft-procedures') }}" class="section-card aircraft">
                <i class="section-icon fas fa-tools"></i>
                <h3 class="section-title">Aircraft Procedures</h3>
                <p class="section-description">
                    Type-specific procedures, performance data, and emergency procedures for each aircraft in the Air Jamaica Virtual fleet.
                </p>
                <ul class="section-topics">
                    <li>Normal Operating Procedures</li>
                    <li>Emergency Procedures</li>
                    <li>Performance Data & Limitations</li>
                    <li>Type-specific Checklists</li>
                </ul>
            </a>

            {{-- Route & Network --}}
            <a href="{{ route('frontend.operations.section', 'route-network') }}" class="section-card network">
                <i class="section-icon fas fa-globe-americas"></i>
                <h3 class="section-title">Route & Network</h3>
                <p class="section-description">
                    Caribbean route structure, airport information, special procedures, and international operations guidelines.
                </p>
                <ul class="section-topics">
                    <li>Hub & Route Structure</li>
                    <li>Caribbean Airport Procedures</li>
                    <li>Hurricane Season Operations</li>
                    <li>International Procedures</li>
                </ul>
            </a>

            {{-- Administrative --}}
            <a href="{{ route('frontend.operations.section', 'administrative') }}" class="section-card admin">
                <i class="section-icon fas fa-clipboard-list"></i>
                <h3 class="section-title">Administrative</h3>
                <p class="section-description">
                    PIREP requirements, scheduling procedures, promotion criteria, and administrative policies.
                </p>
                <ul class="section-topics">
                    <li>PIREP Requirements</li>
                    <li>Flight Scheduling System</li>
                    <li>Promotion Requirements</li>
                    <li>Leave & Absence Policies</li>
                </ul>
            </a>

            {{-- Quality Assurance --}}
            <a href="{{ route('frontend.operations.section', 'quality-assurance') }}" class="section-card quality">
                <i class="section-icon fas fa-award"></i>
                <h3 class="section-title">Quality Assurance</h3>
                <p class="section-description">
                    Flight standards, training requirements, audit procedures, and quality control measures.
                </p>
                <ul class="section-topics">
                    <li>Flight Standards & Criteria</li>
                    <li>Training Requirements</li>
                    <li>Quality Audit Procedures</li>
                    <li>Performance Monitoring</li>
                </ul>
            </a>

            {{-- Emergency Procedures --}}
            <a href="{{ route('frontend.operations.section', 'emergency-procedures') }}" class="section-card emergency" id="emergency">
                <i class="section-icon fas fa-exclamation-triangle"></i>
                <h3 class="section-title">Emergency Procedures</h3>
                <p class="section-description">
                    Comprehensive emergency procedures for weather diversions, medical emergencies, and system failures.
                </p>
                <ul class="section-topics">
                    <li>Weather Diversions</li>
                    <li>Medical Emergencies</li>
                    <li>Communication Failures</li>
                    <li>Security Procedures</li>
                </ul>
            </a>

            {{-- Technical Operations --}}
            <a href="{{ route('frontend.operations.section', 'technical-operations') }}" class="section-card technical">
                <i class="section-icon fas fa-desktop"></i>
                <h3 class="section-title">Technical Operations</h3>
                <p class="section-description">
                    Software requirements, ACARS procedures, multiplayer operations, and technical documentation requirements.
                </p>
                <ul class="section-topics">
                    <li>Software Requirements</li>
                    <li>ACARS & Flight Tracking</li>
                    <li>VATSIM/IVAO Operations</li>
                    <li>Documentation Requirements</li>
                </ul>
            </a>
        </div>

        {{-- Additional Information --}}
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="section-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-info-circle me-2" style="color: var(--jamaica-blue);"></i>
                        Manual Information
                    </h5>
                    <div class="text-muted">
                        <p><strong>Version:</strong> 2.1.0</p>
                        <p><strong>Last Updated:</strong> {{ now()->format('F j, Y') }}</p>
                        <p><strong>Effective Date:</strong> {{ now()->format('F j, Y') }}</p>
                        <p><strong>Next Review:</strong> {{ now()->addMonths(6)->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="section-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-headset me-2" style="color: var(--jamaica-green);"></i>
                        Support & Contact
                    </h5>
                    <div class="text-muted">
                        <p><strong>Operations Team:</strong> operations@airjamaicavirtual.com</p>
                        <p><strong>Training Dept:</strong> training@airjamaicavirtual.com</p>
                        <p><strong>Emergency Contact:</strong> emergency@airjamaicavirtual.com</p>
                        <p><strong>24/7 Support:</strong> Available via Discord</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection