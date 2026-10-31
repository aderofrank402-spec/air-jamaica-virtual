@extends('layouts.seven.public_app')
@section('title', __('common.dashboard'))

@section('css')
    {{-- Enhanced Air Jamaica Virtual Dashboard Styles --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Air Jamaica Virtual Enhanced Branding Colors */
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

        /* Enhanced Body Styling with Animated Background */
        body {
            padding-top: 0 !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #0f1419 100%) !important;
            background-attachment: fixed;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            color: #fff;
            overflow-x: hidden;
            position: relative;
        }

        /* Beautiful Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(0, 166, 81, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 215, 0, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(30, 60, 114, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 60% 60%, rgba(0, 166, 81, 0.1) 0%, transparent 50%);
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

        /* Dashboard Container */
        .jamaica-dashboard {
            padding: 100px 0 3rem 0;
            min-height: calc(100vh - 100px);
            position: relative;
            z-index: 1;
        }

        /* Navigation Integration */
        .public-nav {
            background: linear-gradient(135deg, var(--jamaica-green) 0%, var(--jamaica-blue) 100%) !important;
            backdrop-filter: blur(10px) !important;
            border-bottom: 3px solid var(--jamaica-yellow) !important;
        }

        /* Enhanced Hero Section */
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

        /* Enhanced Statistics Grid */
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
            background: rgba(15, 20, 25, 0.4);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .operation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
            border-color: rgba(0, 166, 81, 0.3);
        }

        .operation-header {
            margin-bottom: 1.5rem;
        }

        .operation-title {
            color: var(--jamaica-white);
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .operation-title i {
            color: var(--jamaica-green);
            font-size: 1.4rem;
        }

        .operation-body {
            color: #b0b0b0;
        }

        .operation-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            text-decoration: none;
            color: #b0b0b0;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
            position: relative;
        }

        .operation-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            text-decoration: none;
            border-color: rgba(0, 166, 81, 0.3);
            transform: translateX(5px);
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
            font-size: 1.2rem;
        }

        .operation-icon.flight {
            background: linear-gradient(45deg, var(--jamaica-green), #20c997);
            color: var(--jamaica-black);
        }

        .operation-icon.manage {
            background: linear-gradient(45deg, var(--jamaica-blue), #007bff);
            color: var(--jamaica-white);
        }

        .operation-icon.tools {
            background: linear-gradient(45deg, var(--jamaica-yellow), #ffc107);
            color: var(--jamaica-black);
        }

        .operation-content h5 {
            margin: 0 0 0.3rem 0;
            color: var(--jamaica-white);
            font-weight: 600;
        }

        .operation-content small {
            color: #a0a0a0;
        }

        .operation-badge {
            background: var(--jamaica-green);
            color: var(--jamaica-black);
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: auto;
        }

        /* Enhanced Button Styles */
        .btn-jamaica {
            background: linear-gradient(45deg, var(--jamaica-green), #20c997);
            color: var(--jamaica-black);
            border: none;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-jamaica:hover {
            background: linear-gradient(45deg, #009f4a, #1db584);
            color: var(--jamaica-black);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 166, 81, 0.3);
        }

        .btn-outline-jamaica {
            background: transparent;
            color: var(--jamaica-green);
            border: 2px solid var(--jamaica-green);
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-outline-jamaica:hover {
            background: var(--jamaica-green);
            color: var(--jamaica-black);
            border-color: var(--jamaica-green);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 166, 81, 0.3);
        }

        .alert-jamaica {
            background: rgba(0, 166, 81, 0.1);
            border: 1px solid rgba(0, 166, 81, 0.3);
            color: var(--jamaica-white);
            border-radius: 15px;
        }

        /* Widget Integration Styles */
        .widget {
            background: rgba(15, 20, 25, 0.4);
            border-radius: 15px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            color: #b0b0b0;
        }

        .widget h5, .widget h6 {
            color: var(--jamaica-white);
        }

        /* API Key Section */
        .api-key-section {
            background: rgba(26, 31, 46, 0.6);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            margin: 2rem 0;
            border: 1px solid rgba(42, 47, 62, 0.3);
            backdrop-filter: blur(10px);
        }

        .api-key-icon {
            font-size: 2.2rem;
            color: var(--jamaica-yellow);
            margin-bottom: 1rem;
        }

        .api-key-input {
            background: rgba(42, 47, 62, 0.7);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.8rem;
            font-size: 1rem;
            margin-bottom: 1rem;
            width: 60%;
        }

        /* Enhanced Mobile Responsiveness */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
            }
            
            .operations-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .operation-card {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Enhanced Widget Specific Styles */
        .dispatch-center-widget .stat-card {
            background: rgba(26, 31, 46, 0.8);
            border: 1px solid rgba(0, 166, 81, 0.3);
        }

        .notam-widget .notam-item {
            background: rgba(26, 31, 46, 0.6);
            border-left: 4px solid;
        }

        /* Performance and Financial Widget Styles */
        .performance-widget, .financial-widget {
            background: rgba(15, 20, 25, 0.6);
            border-radius: 15px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .metric-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .metric-item:last-child {
            border-bottom: none;
        }

        .metric-label {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        .metric-value {
            color: var(--jamaica-white);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .metric-trend {
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }

        .metric-trend.positive {
            color: var(--jamaica-green);
        }

        .metric-trend.negative {
            color: #ff6b6b;
        }

        /* Enhanced Widgets Container */
        .widgets-container {
            margin: 3rem 0;
        }

        .widget-container {
            background: transparent;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .widget-container:hover {
            transform: translateY(-3px);
        }

        /* Additional Widget Integration */
        .widget-container .widget {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
    </style>
@endsection

@section('content')
<div class="jamaica-dashboard">
    <div class="container">
        {{-- On Leave Alert --}}
        @if (Auth::user()->state === \App\Models\Enums\UserState::ON_LEAVE)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-jamaica d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-3" style="color: var(--jamaica-yellow); font-size: 1.5rem;"></i>
                    <div>
                        <strong>On Leave Status Active</strong><br>
                        You're currently marked as on leave. File a PIREP to return to active duty.
                        <a href="{{ route('frontend.pireps.create') }}" class="btn btn-jamaica btn-sm ms-3">Return to Active</a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Enhanced Hero Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-section">
                    <div class="hero-content">
                        <h1 class="hero-title">Welcome back, {{ Auth::user()->name_private }}</h1>
                        <p class="hero-subtitle">
                            {{ Auth::user()->rank ? Auth::user()->rank->name : 'Pilot' }} • 
                            Air Jamaica Virtual Flight Operations Center
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enhanced Statistics Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="stats-container">
                    <div class="stats-header">
                        <h2 class="stats-title">Your Flight Statistics</h2>
                        <p class="stats-subtitle">Real-time performance metrics and achievements</p>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-plane"></i>
                            </div>
                            <div class="stat-value">{{ Auth::user()->flights }}</div>
                            <div class="stat-label">Total Flights</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: {{ min(100, (Auth::user()->flights / 100) * 100) }}%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-value">{{ number_format(Auth::user()->flight_time / 60, 1) }}</div>
                            <div class="stat-label">Flight Hours</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: {{ min(100, (Auth::user()->flight_time / 6000) * 100) }}%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-value">{{ Auth::user()->rank ? Auth::user()->rank->name : 'Student' }}</div>
                            <div class="stat-label">Current Rank</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: 75%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="stat-value">${{ number_format(Auth::user()->balance ?? 0) }}</div>
                            <div class="stat-label">Account Balance</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: 85%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="stat-value">{{ Auth::user()->awards->count() }}</div>
                            <div class="stat-label">Awards Earned</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: {{ min(100, (Auth::user()->awards->count() / 10) * 100) }}%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-bookmark"></i>
                            </div>
                            <div class="stat-value">{{ isset($current_bids) ? $current_bids->count() : 0 }}</div>
                            <div class="stat-label">Active Bids</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: {{ isset($current_bids) ? min(100, ($current_bids->count() / 5) * 100) : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="stat-value">{{ $current_airport }}</div>
                            <div class="stat-label">Current Hub</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: 100%"></div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-value">94.5%</div>
                            <div class="stat-label">On-Time Performance</div>
                            <div class="stat-progress">
                                <div class="stat-progress-bar" style="width: 94.5%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- API Key Section --}}
        @if(Auth::check())
        <div class="row mb-4">
            <div class="col-12">
                <div class="api-key-section">
                    <div class="api-key-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h5 class="text-white mb-3">SmartCARS API Key</h5>
                    <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
                        <input type="text" readonly value="{{ Auth::user()->api_key }}" id="apiKeyInput" class="api-key-input">
                        <button class="btn btn-jamaica" onclick="navigator.clipboard.writeText(document.getElementById('apiKeyInput').value)">
                            <i class="fas fa-copy me-2"></i>Copy Key
                        </button>
                    </div>
                    <p class="text-muted small mb-0">Use this key as your password when logging in to SmartCARS</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Operations Cards Section --}}
        <div class="row">
            <div class="col-12">
                <div class="operations-grid">
                    
                    {{-- Flight Operations Card --}}
                    <div class="operation-card">
                        <div class="operation-header">
                            <h3 class="operation-title">
                                <i class="fas fa-plane-departure"></i>
                                Flight Operations
                            </h3>
                        </div>
                        <div class="operation-body">
                            <a href="{{ route('frontend.pireps.create') }}" class="operation-item">
                                <div class="operation-icon flight">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>File PIREP</h5>
                                    <small>Submit your flight report and log hours</small>
                                </div>
                            </a>

                            <a href="{{ route('frontend.flights.index') }}" class="operation-item">
                                <div class="operation-icon flight">
                                    <i class="fas fa-route"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Browse Flights</h5>
                                    <small>Find and book available routes</small>
                                </div>
                            </a>

                            @if ($last_pirep === null)
                            <div class="text-center mt-3">
                                <a href="{{ route('frontend.pireps.create') }}" class="btn btn-jamaica">
                                    <i class="fas fa-plus me-2"></i>File Your First PIREP
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Flight Management Card --}}
                    <div class="operation-card">
                        <div class="operation-header">
                            <h3 class="operation-title">
                                <i class="fas fa-clipboard-list"></i>
                                Flight Management
                            </h3>
                        </div>
                        <div class="operation-body">
                            <a href="{{ route('frontend.flights.bids') }}" class="operation-item">
                                <div class="operation-icon manage">
                                    <i class="fas fa-bookmark"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>My Bids</h5>
                                    <small>Manage your booked flights</small>
                                </div>
                                @if(isset($current_bids) && $current_bids->count() > 0)
                                    <span class="operation-badge">{{ $current_bids->count() }}</span>
                                @endif
                            </a>

                            @if(isset($current_bids) && $current_bids->count() > 0)
                                @foreach($current_bids->take(2) as $bid)
                                <div class="operation-item" style="background: rgba(0, 166, 81, 0.1); border-color: var(--jamaica-green);">
                                    <div class="operation-icon manage" style="width: 40px; height: 40px; font-size: 1rem;">
                                        <i class="fas fa-plane"></i>
                                    </div>
                                    <div class="operation-content">
                                        <h5>
                                            @if($bid->flight)
                                                {{ $bid->flight->ident }}
                                            @else
                                                Flight {{ $bid->flight_id }}
                                            @endif
                                        </h5>
                                        <small>{{ $bid->flight->dpt_airport_id ?? 'Unknown' }} → {{ $bid->flight->arr_airport_id ?? 'Unknown' }}</small>
                                    </div>
                                    <a href="{{ route('frontend.flights.show', $bid->flight_id) }}" class="btn btn-outline-jamaica btn-sm">View</a>
                                </div>
                                @endforeach
                            @endif

                            @if ($last_pirep !== null)
                            <div class="operation-item" style="background: rgba(255, 255, 255, 0.02);">
                                <div class="operation-icon manage">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Last Flight: {{ $last_pirep->flight->ident ?? 'N/A' }}</h5>
                                    <small>{{ $last_pirep->dpt_airport_id }} → {{ $last_pirep->arr_airport_id }} • {{ $last_pirep->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tools & Information Card --}}
                    <div class="operation-card">
                        <div class="operation-header">
                            <h3 class="operation-title">
                                <i class="fas fa-tools"></i>
                                Tools & Information
                            </h3>
                        </div>
                        <div class="operation-body">
                            <a href="{{ route('frontend.downloads.index') }}" class="operation-item">
                                <div class="operation-icon tools">
                                    <i class="fas fa-download"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>ACARS Downloads</h5>
                                    <small>Flight tracking and simulation tools</small>
                                </div>
                            </a>

                            <div class="operation-item" style="background: rgba(0, 123, 255, 0.05);">
                                <div class="operation-icon tools">
                                    <i class="fas fa-cloud-sun"></i>
                                </div>
                                <div class="operation-content">
                                    <h5>Weather - {{ $current_airport }}</h5>
                                    <small>Current conditions at your hub</small>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="{{ route('frontend.pireps.index') }}" class="btn btn-outline-jamaica btn-sm w-100">
                                            <i class="fas fa-history me-1"></i>History
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('frontend.profile.index') }}" class="btn btn-outline-jamaica btn-sm w-100">
                                            <i class="fas fa-user me-1"></i>Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Dispatch Center & Operations Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="operation-card">
                    <div class="operation-header">
                        <h3 class="operation-title">
                            <i class="fas fa-tower-control"></i>
                            Dispatch & Operations Center
                        </h3>
                    </div>
                    <div class="operation-body">
                        <div class="widget dispatch-center-widget">
                            {{ Widget::dispatchCenter([
                                'hub_icao' => $current_airport,
                                'show_active_flights' => true,
                                'show_weather' => true,
                                'show_notams' => true,
                                'show_pending_departures' => true,
                                'refresh_interval' => 30
                            ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enhanced News, NOTAMs & Activity Section --}}
        <div class="row">
            <div class="col-lg-4">
                <div class="operation-card">
                    <div class="operation-header">
                        <h3 class="operation-title">
                            <i class="fas fa-exclamation-triangle"></i>
                            Active NOTAMs
                        </h3>
                    </div>
                    <div class="operation-body">
                        <div class="widget notam-widget">
                            {{ Widget::activeNotams([
                                'count' => 5,
                                'icao' => null,
                                'show_critical_only' => false,
                                'show_priority_badges' => true,
                                'show_airports' => true
                            ]) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="operation-card">
                    <div class="operation-header">
                        <h3 class="operation-title">
                            <i class="fas fa-newspaper"></i>
                            Latest News & Updates
                        </h3>
                    </div>
                    <div class="operation-body">
                        <div class="widget">
                            {{ Widget::latestNews(['count' => 5]) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="operation-card">
                    <div class="operation-header">
                        <h3 class="operation-title">
                            <i class="fas fa-history"></i>
                            Recent Activity
                        </h3>
                    </div>
                    <div class="operation-body">
                        <div class="mb-4">
                            <h6 style="color: var(--jamaica-green); margin-bottom: 1rem;">
                                <i class="fas fa-file-alt me-2"></i>Recent PIREPs
                            </h6>
                            <div class="widget">
                                {{ Widget::latestPireps(['count' => 3]) }}
                            </div>
                        </div>
                        
                        <div>
                            <h6 style="color: var(--jamaica-green); margin-bottom: 1rem;">
                                <i class="fas fa-cloud-sun me-2"></i>Weather - {{ $current_airport }}
                            </h6>
                            <div class="widget">
                                {{ Widget::Weather(['icao' => $current_airport]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enhanced Widgets Section --}}
        <div class="widgets-container">
            <div class="row g-4">
                {{-- Performance Metrics Widget --}}
                <div class="col-lg-6">
                    <div class="widget-container">
                        {{ Widget::PerformanceMetrics() }}
                    </div>
                </div>

                {{-- Financial Dashboard Widget --}}
                <div class="col-lg-6">
                    <div class="widget-container">
                        {{ Widget::FinancialDashboard() }}
                    </div>
                </div>
            </div>

            {{-- Quick Flight Finder Widget --}}
            <div class="row g-4 mt-2">
                <div class="col-12">
                    <div class="widget-container">
                        {{ Widget::QuickFlightFinder() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection