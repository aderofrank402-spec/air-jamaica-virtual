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

        <div class="collapse navbar-collapse" id="desktopNavbar">
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
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ public_asset('/assets/img/air jamaica logo copy.png') }}" height="32" alt="Logo">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="navbarDropdown" style="background: #1a1a2e; min-width: 200px;">
                            <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3" href="{{ route('frontend.dashboard.index') }}">
                                <i class="bi bi-speedometer2 text-success"></i> Pilot Center
                            </a></li>
                            <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3" href="{{ route('frontend.profile.index') }}">
                                <i class="bi bi-person text-success"></i> Profile
                            </a></li>
                            <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3" href="{{ route('frontend.flights.index') }}">
                                <i class="bi bi-airplane text-success"></i> Flights
                            </a></li>
                            <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3" href="{{ route('frontend.pireps.index') }}">
                                <i class="bi bi-check2-square text-success"></i> PIREPs
                            </a></li>
                            <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3" href="{{ route('frontend.flights.bids') }}">
                                <i class="bi bi-bookmark text-success"></i> My Bids
                            </a></li>
                            @if(Auth::user()->hasAdminAccess())
                                <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                <li><a class="dropdown-item py-2 px-3 text-white d-flex align-items-center gap-3" href="{{ url('/admin') }}">
                                    <i class="bi bi-gear text-success"></i> Admin
                                </a></li>
                            @endif
                            <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                            <li><a class="dropdown-item py-2 px-3 text-danger d-flex align-items-center gap-3" href="{{ url('/logout') }}">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a></li>
                        </ul>
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
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.pilots.index') }}">
                        <i class="bi bi-people"></i> Pilots
                    </a>
                </li>
            @endauth
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
                        <i class="bi bi-speedometer2"></i> Pilot Center
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.profile.index') }}">
                        <i class="bi bi-person"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.flights.index') }}">
                        <i class="bi bi-airplane"></i> Flights
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.pireps.index') }}">
                        <i class="bi bi-check2-square"></i> PIREPs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.flights.bids') }}">
                        <i class="bi bi-bookmark"></i> My Bids
                    </a>
                </li>
                @if(Auth::user()->hasAdminAccess())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/admin') }}">
                            <i class="bi bi-gear" style="color: #FED100 !important;"></i> Admin Panel
                        </a>
                    </li>
                @endif
                <li class="nav-item" style="margin-top: 1rem !important;">
                    <a class="nav-link btn btn-outline-danger" href="{{ url('/logout') }}">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>