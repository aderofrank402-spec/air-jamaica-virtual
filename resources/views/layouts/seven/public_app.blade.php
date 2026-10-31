<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />

    <title>@yield('title') - {{ config('app.name') }}</title>
    <script>
        // Check for saved user preference, if any, on initial load
        (function () {
            if (localStorage.getItem('theme') === 'dark' || ((!localStorage.getItem('theme') || localStorage.getItem(
                'theme') === 'system') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-bs-theme', "dark")
            }
        })();
    </script>

    {{-- Start of required lines block. DON'T REMOVE THESE LINES! They're required or might break things --}}
    <meta name="base-url" content="/">
    <meta name="api-key" content="{!! Auth::check() ? Auth::user()->api_key : '' !!}">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    {{-- End the required lines block --}}

    <link rel="icon" type="image/png" href="{{ public_asset('/assets/img/air%20jamaica%20logo%20copy.png') }}" />
    <link rel="shortcut icon" type="image/png" href="{{ public_asset('/assets/img/air%20jamaica%20logo%20copy.png') }}" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent/3.1.1/cookieconsent.min.css"
        integrity="sha512-LQ97camar/lOliT/MqjcQs5kWgy6Qz/cCRzzRzUCfv0fotsCTC9ZHXaPQmJV8Xu/PVALfJZ7BDezl5lW3/qBxg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
    <link href="{{ public_asset('/assets/vendor/tomselect/tom-select.bootstrap5.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Start of the required files in the head block --}}
    @yield('css')
    @yield('scripts_head')
    @stack('styles')
    {{-- End of the required stuff in the head block --}}

    <style>
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

        body {
            padding-top: 76px;
            /* Account for fixed navbar */
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
            }

            body .offcanvas .nav-link:hover {
                color: #FED100 !important;
                transform: translateX(5px) !important;
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



        /* Professional Footer Styling */
        .footer {
            background: linear-gradient(135deg, #0a0a1a 0%, #1a1a2e 100%);
            color: #ffffff;
            padding: 4rem 0 0 0;
            border-top: 1px solid rgba(0, 166, 81, 0.3);
            position: relative;
            z-index: 2;
            margin-top: 3rem;
            /* Add space above the footer */
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
            font-size: 0.9rem;
        }

        .footer-bottom-links {
            display: flex;
            gap: 2rem;
        }

        .footer-bottom-links a {
            color: #b0b0b0;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }

        .footer-bottom-links a:hover {
            color: #00a651;
        }

        @media (max-width: 1024px) {
            .footer-content {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }

            .footer-section:first-child {
                grid-column: span 2;
            }
        }

        @media (max-width: 768px) {
            .footer {
                padding: 3rem 0 0 0;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-section:first-child {
                grid-column: span 1;
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
    </style>
</head>

<body>


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

            <div class="collapse navbar-collapse" id="desktopNavbarPublic">
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                                id="navbarDropdownPublic" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ public_asset('/assets/img/air jamaica logo copy.png') }}" height="32"
                                    alt="Logo">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0"
                                aria-labelledby="navbarDropdownPublic" style="background: #1a1a2e; min-width: 200px;">
                                <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3"
                                        href="{{ route('frontend.dashboard.index') }}">
                                        <i class="bi bi-speedometer2 text-success"></i> Pilot Center
                                    </a></li>
                                <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3"
                                        href="{{ route('frontend.profile.index') }}">
                                        <i class="bi bi-person text-success"></i> Profile
                                    </a></li>
                                <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3"
                                        href="{{ route('frontend.flights.index') }}">
                                        <i class="bi bi-airplane text-success"></i> Flights
                                    </a></li>
                                <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3"
                                        href="{{ route('frontend.pireps.index') }}">
                                        <i class="bi bi-check2-square text-success"></i> PIREPs
                                    </a></li>
                                <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3"
                                        href="{{ route('frontend.flights.bids') }}">
                                        <i class="bi bi-bookmark text-success"></i> My Bids
                                    </a></li>
                                @if(Auth::user()->hasAdminAccess())
                                    <li>
                                        <hr class="dropdown-divider border-secondary opacity-25">
                                    </li>
                                    <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3"
                                            href="{{ url('/admin') }}">
                                            <i class="bi bi-gear text-success"></i> Admin
                                        </a></li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider border-secondary opacity-25">
                                </li>
                                <li><a class="dropdown-item py-2 px-3 text-danger d-flex align-items-center gap-3"
                                        href="{{ url('/logout') }}">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content-wrapper">
        @yield('content_header')
        @yield('content')
    </main>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="publicOffcanvas" aria-labelledby="publicOffcanvasLabel"
        data-bs-backdrop="true">
        <div class="offcanvas-header"
            style="background: #0056b3 !important; border-bottom: 1px solid rgba(255,255,255,0.2) !important;">
            <h5 class="offcanvas-title" id="publicOffcanvasLabel">
                <img src="{{ public_asset('/assets/img/air jamaica logo copy.png') }}" alt="Logo"
                    style="height: 40px !important;">
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" style="background: #0056b3 !important; padding: 2rem !important;">
            <ul class="navbar-nav"
                style="display: flex !important; flex-direction: column !important; gap: 1rem !important; margin: 0 !important; padding: 0 !important;">
                <li class="nav-item" style="display: block !important; width: 100% !important;">
                    <a class="nav-link" href="{{ url('/') }}"
                        style="color: #ffffff !important; font-size: 1.2rem !important; display: flex !important; align-items: center !important; opacity: 1 !important; visibility: visible !important;">
                        <i class="fas fa-home" style="color: #00A651 !important; width: 30px !important;"></i> Home
                    </a>
                </li>
                <li class="nav-item" style="display: block !important; width: 100% !important;">
                    <a class="nav-link" href="{{ route('frontend.about') }}"
                        style="color: #ffffff !important; font-size: 1.2rem !important; display: flex !important; align-items: center !important; opacity: 1 !important; visibility: visible !important;">
                        <i class="bi bi-info-circle" style="color: #00A651 !important; width: 30px !important;"></i>
                        About Us
                    </a>
                </li>
                <li class="nav-item" style="display: block !important; width: 100% !important;">
                    <a class="nav-link" href="{{ route('frontend.contact') }}"
                        style="color: #ffffff !important; font-size: 1.2rem !important; display: flex !important; align-items: center !important; opacity: 1 !important; visibility: visible !important;">
                        <i class="bi bi-envelope" style="color: #00A651 !important; width: 30px !important;"></i>
                        Contact
                    </a>
                </li>
                <li class="nav-item" style="display: block !important; width: 100% !important;">
                    <a class="nav-link" href="{{ route('frontend.partners') }}"
                        style="color: #ffffff !important; font-size: 1.2rem !important; display: flex !important; align-items: center !important; opacity: 1 !important; visibility: visible !important;">
                        <i class="bi bi-handshake" style="color: #00A651 !important; width: 30px !important;"></i>
                        Partners
                    </a>
                </li>
                <li class="nav-item" style="display: block !important; width: 100% !important;">
                    <a class="nav-link" href="{{ route('frontend.livemap.index') }}"
                        style="color: #ffffff !important; font-size: 1.2rem !important; display: flex !important; align-items: center !important; opacity: 1 !important; visibility: visible !important;">
                        <i class="bi bi-globe" style="color: #00A651 !important; width: 30px !important;"></i> Live Map
                    </a>
                </li>
                <li class="nav-item" style="display: block !important; width: 100% !important;">
                    <a class="nav-link" href="{{ route('frontend.pilots.index') }}"
                        style="color: #ffffff !important; font-size: 1.2rem !important; display: flex !important; align-items: center !important; opacity: 1 !important; visibility: visible !important;">
                        <i class="bi bi-people" style="color: #00A651 !important; width: 30px !important;"></i> Pilots
                    </a>
                </li>
                @if (!Auth::check())
                    <li class="nav-item" style="display: block !important; width: 100% !important;">
                        <a class="nav-link" href="{{ url('/register') }}"
                            style="color: #ffffff !important; font-size: 1.2rem !important; display: flex !important; align-items: center !important; opacity: 1 !important; visibility: visible !important;">
                            <i class="bi bi-person-vcard" style="color: #00A651 !important; width: 30px !important;"></i>
                            Register
                        </a>
                    </li>
                    <li class="nav-item"
                        style="display: block !important; width: 100% !important; margin-top: 1rem !important;">
                        <a class="nav-link btn btn-outline-light" href="{{ url('/login') }}"
                            style="color: #ffffff !important; border-color: #FED100 !important; font-size: 1.2rem !important; justify-content: center !important;">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                @else
                    <li class="nav-item" style="display: block !important; width: 100% !important;">
                        <a class="nav-link" href="{{ route('frontend.dashboard.index') }}"
                            style="color: #ffffff !important; font-size: 1.1rem !important; display: flex !important; align-items: center !important;">
                            <i class="bi bi-speedometer2" style="color: #00A651 !important; width: 30px !important;"></i>
                            Pilot Center
                        </a>
                    </li>
                    <li class="nav-item" style="display: block !important; width: 100% !important;">
                        <a class="nav-link" href="{{ route('frontend.profile.index') }}"
                            style="color: #ffffff !important; font-size: 1.1rem !important; display: flex !important; align-items: center !important;">
                            <i class="bi bi-person" style="color: #00A651 !important; width: 30px !important;"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item" style="display: block !important; width: 100% !important;">
                        <a class="nav-link" href="{{ route('frontend.flights.index') }}"
                            style="color: #ffffff !important; font-size: 1.1rem !important; display: flex !important; align-items: center !important;">
                            <i class="bi bi-airplane" style="color: #00A651 !important; width: 30px !important;"></i>
                            Flights
                        </a>
                    </li>
                    <li class="nav-item" style="display: block !important; width: 100% !important;">
                        <a class="nav-link" href="{{ route('frontend.pireps.index') }}"
                            style="color: #ffffff !important; font-size: 1.1rem !important; display: flex !important; align-items: center !important;">
                            <i class="bi bi-check2-square" style="color: #00A651 !important; width: 30px !important;"></i>
                            PIREPs
                        </a>
                    </li>
                    <li class="nav-item" style="display: block !important; width: 100% !important;">
                        <a class="nav-link" href="{{ route('frontend.flights.bids') }}"
                            style="color: #ffffff !important; font-size: 1.1rem !important; display: flex !important; align-items: center !important;">
                            <i class="bi bi-bookmark" style="color: #00A651 !important; width: 30px !important;"></i> My
                            Bids
                        </a>
                    </li>
                    @if(Auth::user()->hasAdminAccess())
                        <li class="nav-item" style="display: block !important; width: 100% !important;">
                            <a class="nav-link" href="{{ url('/admin') }}"
                                style="color: #ffffff !important; font-size: 1.1rem !important; display: flex !important; align-items: center !important;">
                                <i class="bi bi-gear" style="color: #FED100 !important; width: 30px !important;"></i> Admin
                                Panel
                            </a>
                        </li>
                    @endif
                    <li class="nav-item"
                        style="display: block !important; width: 100% !important; margin-top: 1rem !important;">
                        <a class="nav-link btn btn-outline-danger" href="{{ url('/logout') }}"
                            style="color: #ffffff !important; border-color: #ff4444 !important; font-size: 1.2rem !important; justify-content: center !important;">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                @endif
            </ul>
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

    {{-- External Redirects Modal --}}
    @include('external_redirect_modal')

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    </script>



    {{-- Start of the required tags block. Don't remove these or things will break!! --}}
    @vite(['resources/js/entrypoint.js', 'resources/js/frontend/app.js'])
    @yield('scripts')
    @stack('scripts')

    {{-- This is the color theme switcher --}}
    @include('scripts.bs_theme')

    {{-- Cookie consent --}}
    <script>
        window.addEventListener("load", function () {
            window.cookieconsent.initialise({
                palette: {
                    popup: {
                        background: "#edeff5",
                        text: "#838391"
                    },
                    button: {
                        "background": "#067ec1"
                    }
                },
                position: "top",
            })
        });
    </script>
    {{-- End the required tags block --}}

    {{-- Google Analytics tracking code --}}
    @php
        $gtag = setting('general.google_analytics_id');
    @endphp
    @if ($gtag)
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtag }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', '{{ $gtag }}');
        </script>
    @endif
    {{-- End of the Google Analytics code --}}
</body>

</html>