<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand " href="{{ url('/') }}">
            <img src="{{ public_asset('/assets/img/logo_blue_bg.svg') }}" width="135px"
                alt="Air Jamaica Virtual Logo" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#dashboardOffcanvas"
            aria-controls="dashboardOffcanvas" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="offcanvas offcanvas-end" tabindex="-1" id="dashboardOffcanvas" aria-labelledby="dashboardOffcanvasLabel" data-bs-backdrop="true">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="dashboardOffcanvasLabel">
            <img src="{{ public_asset('/assets/img/logo_blue_bg.svg') }}" width="135px" alt="Logo">
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            @if (Auth::check())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.dashboard.index') }}">
                        <i class="bi bi-speedometer2"></i>
                        @lang('common.dashboard')
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link" href="{{ route('frontend.livemap.index') }}">
                    <i class="bi bi-globe"></i>
                    @lang('common.livemap')
                </a>
            </li>

            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.pilots.index') }}">
                        <i class="bi bi-people"></i>
                        {{ trans_choice('common.pilot', 2) }}
                    </a>
                </li>
            @endauth

            {{-- Show the module links that don't require being logged in --}}
            @foreach ($moduleSvc->getFrontendLinks($logged_in = false) as &$link)
                <li class="nav-item">
                    <a class="nav-link" href="{{ url($link['url']) }}">
                        <i class="{{ $link['icon'] }}"></i>
                        {{ $link['title'] }}
                    </a>
                </li>
            @endforeach

            @foreach ($page_links as $page)
                <li class="nav-item">
                    <a class="nav-link" href="{{ $page->url }}"
                        target="{{ $page->new_window ? '_blank' : '_self' }}">
                        <i class="{{ $page['icon'] }}"></i>
                        {{ $page['name'] }}
                    </a>
                </li>
            @endforeach

            @if (!Auth::check())
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/register') }}">
                        <i class="bi bi-person-vcard"></i>
                        @lang('common.register')
                    </a>
                </li>
                <li class="nav-item" style="margin-top: 1rem !important;">
                    <a class="nav-link btn btn-outline-light" href="{{ url('/login') }}">
                        <i class="bi bi-box-arrow-in-right"></i>
                        @lang('common.login')
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.flights.index') }}">
                        <i class="bi bi-airplane"></i>
                        {{ trans_choice('common.flight', 2) }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.downloads.index') }}">
                        <i class="bi bi-download"></i>
                        {{ trans_choice('common.download', 2) }}
                    </a>
                </li>

                {{-- Show the module links for being logged in --}}
                @foreach ($moduleSvc->getFrontendLinks($logged_in = true) as &$link)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url($link['url']) }}">
                            <i class="{{ $link['icon'] }}"></i>
                            {{ $link['title'] }}
                        </a>
                    </li>
                @endforeach

                <li class="nav-item" style="margin-top: 1rem !important;">
                    <a class="nav-link btn btn-outline-danger text-white" href="{{ url('/logout') }}">
                        <i class="bi bi-box-arrow-right"></i>&nbsp;&nbsp;@lang('common.logout')
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>