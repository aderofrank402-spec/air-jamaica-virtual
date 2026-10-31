<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />

    <title>@yield('title') - {{ config('app.name') }}</title>
    <script>
        // Check for saved user preference, if any, on initial load
        (function () {
            if (localStorage.getItem('theme') === 'dark' || ((!localStorage.getItem('theme') || localStorage.getItem('theme') === 'system') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-bs-theme', "dark")
            }
        })();
    </script>

    {{-- Start of required lines block. DON'T REMOVE THESE LINES! They're required or might break things --}}
    <meta name="base-url" content="/">
    <meta name="api-key" content="{!! Auth::check() ? Auth::user()->api_key : '' !!}">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    {{-- End the required lines block --}}

    <link rel="icon" type="image/png" href="/assets/img/air%20jamaica%20logo%20copy.png" />
    <link rel="shortcut icon" type="image/png" href="/assets/img/air%20jamaica%20logo%20copy.png" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
    <link href="{{ public_asset('/assets/vendor/tomselect/tom-select.bootstrap5.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    @yield('head')
    <style>
        :root {
            --jamaica-green: #00A651;
            --jamaica-yellow: #FFD700;
            --jamaica-black: #000000;
            --space-blue: #0B1426;
            --cosmic-purple: #2D1B69;
            --nebula-pink: #FF6B9D;
        }

        html,
        body {
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            margin: 0;
            padding: 0;
        }

        /* Public site navigation styling */
        .public-nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(0, 86, 179, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .public-nav .navbar-brand img {
            height: 50px;
            max-height: 50px;
        }

        .public-nav .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .public-nav .nav-link:hover {
            color: #00A651 !important;
            transform: translateY(-2px);
        }

        .public-nav .btn-outline-light {
            border-color: #00A651;
            color: #00A651;
        }

        .public-nav .btn-outline-light:hover {
            background-color: #00A651;
            border-color: #00A651;
            color: #ffffff;
        }

        /* Responsive Offcanvas Styling - EXTREME SPECIFICITY */
        @media (max-width: 991.98px) {
            body .offcanvas.offcanvas-end {
                width: 50vw !important;
                min-width: 50vw !important;
                max-width: 50vw !important;
                right: 0 !important;
                left: auto !important;
                background: #0056b3 !important;
                backdrop-filter: blur(15px) !important;
                border-left: 2px solid rgba(255, 255, 255, 0.2) !important;
                visibility: visible !important;
                z-index: 10000 !important;
                display: block !important;
            }

            body .offcanvas-header {
                background: #0056b3 !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
                padding: 1.5rem !important;
            }

            body .offcanvas-body {
                background: #0056b3 !important;
                padding: 2rem !important;
                display: block !important;
            }

            body .offcanvas .navbar-nav {
                width: 100% !important;
                display: flex !important;
                flex-direction: column !important;
                gap: 1.5rem !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            body .offcanvas .nav-link {
                color: #ffffff !important;
                font-size: 1.1rem !important;
                font-weight: 600 !important;
                text-transform: uppercase !important;
                letter-spacing: 1px !important;
                display: flex !important;
                align-items: center !important;
                opacity: 1 !important;
                visibility: visible !important;
                padding: 0.8rem 1rem !important;
                background: rgba(255,255,255,0.05) !important;
                border-radius: 8px !important;
            }

            body .offcanvas .nav-link:hover {
                color: #FED100 !important;
                background: rgba(255,255,255,0.1) !important;
                transform: translateX(5px) !important;
            }

            body .offcanvas .nav-link i {
                width: 30px !important;
                color: #00A651 !important;
                margin-right: 10px !important;
            }

            /* Disable ANY legacy theme sidebar or collapse conflicts */
            .sidebar-collapse,
            .navbar-collapse.collapse:not(.show),
            .navbar-collapse.collapse.show,
            .navbar-collapse.collapsing {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
            }
        }

        .bg-brand {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            position: relative;
            min-height: 100vh;
        }

        .bg-brand::before {
            display: none;
        }

        .main-content {
            min-height: calc(100vh - 400px);
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 100px;
            /* Add padding for fixed navbar */
        }

        @keyframes starfield {
            0% {
                transform: translateY(0px);
            }

            100% {
                transform: translateY(-60px);
            }
        }

        [data-bs-theme="dark"] .bg-brand {
            background: linear-gradient(135deg, var(--space-blue) 0%, var(--cosmic-purple) 50%, var(--space-blue) 100%);
        }

        .logo-light {
            display: block;
        }

        .logo-dark {
            display: none;
        }

        [data-bs-theme="dark"] .logo-light {
            display: none;
        }

        [data-bs-theme="dark"] .logo-dark {
            display: block;
        }

        /* Professional Footer Styling */
        .footer {
            background: linear-gradient(135deg, #0a0a1a 0%, #1a1a2e 100%);
            color: #ffffff;
            padding: 4rem 0 0 0;
            border-top: 1px solid rgba(0, 166, 81, 0.3);
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 5%;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h4 {
            color: #00a651;
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-logo h3 {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-logo p {
            color: #b0b0b0;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(0, 166, 81, 0.2);
            border: 1px solid rgba(0, 166, 81, 0.4);
            border-radius: 50%;
            color: #00a651;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: #00a651;
            color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 166, 81, 0.3);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: #b0b0b0;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.95rem;
        }

        .footer-links a:hover {
            color: #00a651;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            color: #b0b0b0;
            font-size: 0.95rem;
        }

        .contact-item i {
            color: #00a651;
            width: 16px;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.3);
            padding: 1.5rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-bottom-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 5%;
        }

        .footer-bottom p {
            color: #b0b0b0;
            margin: 0;
        }

        .footer-bottom-links {
            display: flex;
            gap: 2rem;
        }

        .footer-bottom-links a {
            color: #b0b0b0;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-bottom-links a:hover {
            color: #00a651;
        }

        /* Responsive footer */
        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-bottom-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .footer-bottom-links {
                justify-content: center;
            }
        }

        /* Login Page Specific Styles */
        #vanta-globe {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* Hero text on login page – force white in all browsers (overrides Bootstrap dark theme) */
        .main-content .hero-section,
        .main-content .hero-title,
        .main-content .hero-subtitle {
            color: #ffffff !important;
        }

        /* Login Card */
        .login-card {
            background: rgba(10, 10, 20, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 166, 81, 0.2);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 166, 81, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
            max-width: 450px;
            margin: 0 auto;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--jamaica-green), var(--jamaica-yellow), var(--jamaica-green));
            border-radius: 20px 20px 0 0;
        }

        /* Login Header */
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #ffffff, #b5d5ff, #00a651);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: loginTitleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes loginTitleGlow {
            from {
                filter: drop-shadow(0 0 20px rgba(0, 166, 81, 0.2));
            }

            to {
                filter: drop-shadow(0 0 30px rgba(0, 166, 81, 0.4)) drop-shadow(0 0 40px rgba(254, 209, 0, 0.2));
            }
        }

        .login-subtitle {
            color: #a0a0a0;
            font-size: 1rem;
            margin-bottom: 0;
            font-weight: 300;
        }

        /* Login Form Styling */
        .login-card .form-group {
            margin-bottom: 2rem;
            position: relative;
        }

        .login-card .form-label {
            display: block;
            margin-bottom: 0.8rem;
            color: #ffffff;
            font-weight: 500;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        .login-card .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            background: rgba(15, 15, 25, 0.7);
            border: 1px solid rgba(0, 166, 81, 0.3);
            border-radius: 12px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .login-card .form-control:focus {
            outline: none;
            border-color: var(--jamaica-green);
            background: rgba(15, 15, 25, 0.9);
            box-shadow: 0 0 0 3px rgba(0, 166, 81, 0.1),
                0 8px 25px rgba(0, 166, 81, 0.15);
            transform: translateY(-2px);
        }

        .login-card .form-control::placeholder {
            color: #666;
        }

        /* Button Styling */
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .login-card .btn-primary {
            flex: 1;
            background: linear-gradient(135deg, var(--jamaica-green), #0a8f4a);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            color: #ffffff;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .login-card .btn-primary:hover {
            background: linear-gradient(135deg, #0a8f4a, var(--jamaica-green));
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 166, 81, 0.3);
        }

        .login-card .btn-secondary {
            flex: 1;
            background: rgba(30, 30, 45, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1rem 2rem;
            color: #ffffff;
            font-weight: 500;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card .btn-secondary:hover {
            background: rgba(50, 50, 65, 0.8);
            border-color: var(--jamaica-yellow);
            color: #ffffff;
            transform: translateY(-2px);
            text-decoration: none;
        }

        /* OAuth Buttons */
        .oauth-divider {
            text-align: center;
            margin: 2.5rem 0 2rem 0;
            position: relative;
        }

        .oauth-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        }

        .oauth-divider span {
            background: rgba(10, 10, 20, 0.9);
            padding: 0 1.5rem;
            color: #a0a0a0;
            font-size: 0.9rem;
            font-weight: 300;
        }

        .oauth-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .btn-oauth {
            width: 100%;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-oauth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            color: #ffffff;
            text-decoration: none;
        }

        .btn-discord {
            background: linear-gradient(135deg, #738ADB, #5865F2);
        }

        .btn-ivao {
            background: linear-gradient(135deg, #0d2c99, #1e40af);
        }

        .btn-vatsim {
            background: linear-gradient(135deg, #29B473, #10B981);
        }

        /* Forgot Password Link */
        .forgot-password {
            text-align: center;
            margin-top: 1.5rem;
        }

        .forgot-password a {
            color: var(--jamaica-green);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .forgot-password a:hover {
            color: #0a8f4a;
            text-decoration: none;
        }

        /* Error Alert */
        .login-card .alert-danger {
            background: rgba(252, 61, 33, 0.1);
            border: 1px solid rgba(252, 61, 33, 0.3);
            border-radius: 12px;
            color: #ff6b6b;
            padding: 1rem 1.25rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        /* Responsive Design for Login */
        @media (max-width: 768px) {
            .login-card {
                padding: 2rem 1.5rem;
            }

            .login-title {
                font-size: 2rem;
            }

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
    @yield('css')
    @stack('css')
</head>

<body class="bg-brand">
    <!-- Public Navigation -->
    <nav class="navbar navbar-expand-lg public-nav">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand">
                <img src="{{ public_asset('/assets/img/air jamaica logo copy.png') }}"
                    alt="{{ config('app.name') }} Logo" />
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#publicOffcanvas"
                aria-controls="publicOffcanvas" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="desktopNavbarAuth">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.contact') }}">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.partners') }}">Partners</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.livemap.index') }}">Live Map</a>
                    </li>
                    @if (!Auth::check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/register') }}">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm ms-lg-3" href="{{ url('/login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.dashboard.index') }}">Dashboard</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="publicOffcanvas" aria-labelledby="publicOffcanvasLabel" data-bs-backdrop="true">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="publicOffcanvasLabel">
                <img src="{{ public_asset('/assets/img/air jamaica logo copy.png') }}" alt="Logo" style="height: 40px !important;">
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.about') }}">
                        <i class="bi bi-info-circle"></i> About Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.contact') }}">
                        <i class="bi bi-envelope"></i> Contact
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.partners') }}">
                        <i class="bi bi-handshake"></i> Partners
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.livemap.index') }}">
                        <i class="bi bi-globe"></i> Live Map
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.pilots.index') }}">
                        <i class="bi bi-people"></i> Pilots
                    </a>
                </li>
                @if (!Auth::check())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/register') }}">
                            <i class="bi bi-person-vcard"></i> Register
                        </a>
                    </li>
                    <li class="nav-item" style="margin-top: 1rem !important;">
                        <a class="nav-link btn btn-outline-light" href="{{ url('/login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.dashboard.index') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item" style="margin-top: 1rem !important;">
                        <a class="nav-link btn btn-outline-danger" href="{{ url('/logout') }}">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="container py-4">
            {{-- These should go where you want your content to show up --}}
            @include('flash.message')
            @yield('content')
            {{-- End the above block --}}
        </div>
    </div>

    <!-- Professional Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <h3>Air Jamaica Virtual Airlines</h3>
                        <p>Experience the pinnacle of global virtual aviation excellence</p>
                    </div>
                    <div class="footer-social">
                        <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('frontend.about') }}">About Us</a></li>
                        <li><a href="{{ route('frontend.contact') }}">Contact</a></li>
                        <li><a href="{{ route('frontend.livemap.index') }}">Live Map</a></li>
                        <li><a href="{{ url('/register') }}">Join Us</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Pilot Portal</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('frontend.partners') }}">Partners</a></li>
                        <li><a href="{{ route('frontend.pilots.index') }}">Pilots</a></li>
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                        @if (Auth::check())
                            <li><a href="{{ route('frontend.dashboard.index') }}">Dashboard</a></li>
                        @endif
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Contact Info</h4>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>info@airjamaicavirtual.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-globe"></i>
                            <span>www.airjamaicavirtual.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Houston, Texas</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p>&copy; {{ date('Y') }} Air Jamaica Virtual Airlines. All rights reserved.</p>
                    <div class="footer-bottom-links">
                        <a href="{{ route('frontend.privacy') }}">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ public_asset('/assets/global/js/jquery.js') }}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="{{ public_asset('/assets/vendor/tomselect/tom-select.complete.min.js') }}"></script>

    <!-- reCAPTCHA script added directly to ensure loading -->
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <!-- reCAPTCHA fix script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to inject reCAPTCHA
            function checkRecaptcha() {
                var recaptchaContainer = document.querySelector('.g-recaptcha');
                if (!recaptchaContainer) {
                    console.log("No reCAPTCHA container found");
                    return;
                }

                // If container exists but no iframe inside it (meaning reCAPTCHA hasn't rendered)
                if (!recaptchaContainer.querySelector('iframe')) {
                    console.log("Container found but no iframe - attempting to fix");

                    // Try to reload reCAPTCHA directly
                    var recaptchaScript = document.createElement('script');
                    recaptchaScript.src = 'https://www.google.com/recaptcha/api.js';
                    recaptchaScript.async = true;
                    recaptchaScript.defer = true;
                    document.head.appendChild(recaptchaScript);

                    // Add a status message
                    var statusDiv = document.getElementById('recaptcha-status');
                    if (statusDiv) {
                        statusDiv.innerHTML = 'Fixing reCAPTCHA... Please wait a moment.';
                    }
                }
            }

            // Call immediately and then again after a delay
            checkRecaptcha();
            setTimeout(checkRecaptcha, 2000);
        });
    </script>
</body>

@yield('scripts')

</html>