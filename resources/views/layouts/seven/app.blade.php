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

    <link rel="icon" type="image/png" href="/assets/img/air%20jamaica%20logo%20copy.png" />
    <link rel="shortcut icon" type="image/png" href="/assets/img/air%20jamaica%20logo%20copy.png" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent/3.1.1/cookieconsent.min.css"
        integrity="sha512-LQ97camar/lOliT/MqjcQs5kWgy6Qz/cCRzzRzUCfv0fotsCTC9ZHXaPQmJV8Xu/PVALfJZ7BDezl5lW3/qBxg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ public_asset('/assets/vendor/tomselect/tom-select.bootstrap5.css') }}" rel="stylesheet">

    {{-- Start of the required files in the head block --}}
    {{--
    <link href="{{ public_mix('/assets/global/css/vendor.css') }}" rel="stylesheet" /> --}}
    @yield('css')
    @yield('scripts_head')
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
                background: rgba(255, 255, 255, 0.05) !important;
                border-radius: 8px !important;
            }

            body .offcanvas .nav-link:hover {
                color: #FED100 !important;
                background: rgba(255, 255, 255, 0.1) !important;
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



        /* Professional Footer Styling */
        .footer {
            background: linear-gradient(135deg, #0a0a1a 0%, #1a1a2e 100%);
            color: #ffffff;
            padding: 4rem 0 0 0;
            border-top: 1px solid rgba(0, 166, 81, 0.3);
            position: relative;
            z-index: 2;
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
            color: #cbd5e1;
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
            color: #cbd5e1;
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
            color: #cbd5e1;
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
            color: #cbd5e1;
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

        :root {
            --bs-primary: #067ec1 !important;


        }

        [data-bs-theme=light] {
            --bs-primary: #067ec1 !important;
        }

        .bg-primary {
            background-color: #067ec1 !important;
        }

        .btn-primary {
            background-color: #067ec1 !important;
            border-color: #067ec1 !important;
        }
    </style>
</head>

<body>


    <!-- Navbar -->
    <div class="wrapper d-flex flex-column min-vh-100">
        @include('layouts.seven.public_nav')
        <div class="body container flex-grow-1 pt-4">
            {{-- These should go where you want your content to show up --}}
            @include('flash.message')
            @yield('content')
            {{-- End the above block --}}

        </div>
        @include('layouts.seven.footer')
    </div>

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

    {{-- This is the color theme switcher --}}
    @include('scripts.bs_theme')

    {{--
    It's probably safe to keep this to ensure you're in compliance
    with the EU Cookie Law https://privacypolicies.com/blog/eu-cookie-law
    --}}
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

    {{--
    Google Analytics tracking code. Only active if an ID has been entered
    You can modify to any tracking code and re-use that settings field, or
    just remove it completely. Only added as a convenience factor
    --}}
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