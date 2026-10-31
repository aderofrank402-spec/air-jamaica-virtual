@extends('layouts.seven.public_app')
@section('title', 'Aircraft Documentation')

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
        'title' => 'Aircraft Documentation',
        'icon' => 'bi bi-file-earmark-pdf'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

@section('css')
    {{-- Air Jamaica Virtual Aircraft Documentation Styles --}}
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
            padding-top: 0 !important;
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

        /* Navigation Integration */
        .public-nav {
            background: linear-gradient(135deg, var(--jamaica-green) 0%, var(--jamaica-blue) 100%) !important;
            backdrop-filter: blur(10px) !important;
            border-bottom: 3px solid var(--jamaica-yellow) !important;
        }

        /* Breadcrumb Styling */
        .breadcrumb-nav {
            margin-bottom: 0;
            animation: slideInFromTop 0.6s ease-out;
        }

        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .jamaica-breadcrumb {
            background: rgba(26, 31, 46, 0.6);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 0;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(42, 47, 62, 0.3);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .jamaica-breadcrumb::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--jamaica-green), var(--jamaica-yellow), var(--jamaica-blue));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .jamaica-breadcrumb:hover::before {
            opacity: 1;
        }

        .jamaica-breadcrumb:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
            border-color: rgba(0, 166, 81, 0.4);
        }

        .jamaica-breadcrumb .breadcrumb-item {
            font-size: 0.95rem;
            font-weight: 500;
            position: relative;
        }

        .jamaica-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: var(--jamaica-green);
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .jamaica-breadcrumb:hover .breadcrumb-item + .breadcrumb-item::before {
            color: var(--jamaica-yellow);
            transform: scale(1.1);
        }

        .breadcrumb-link {
            color: #a0a0a0;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            position: relative;
            overflow: hidden;
        }

        .breadcrumb-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 166, 81, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .breadcrumb-link:hover::before {
            left: 100%;
        }

        .breadcrumb-link:hover {
            color: var(--jamaica-green);
            text-decoration: none;
            transform: translateX(3px);
            background: rgba(0, 166, 81, 0.1);
        }

        .breadcrumb-link i {
            color: var(--jamaica-green);
            transition: all 0.3s ease;
            margin-right: 0.5rem;
        }

        .breadcrumb-link:hover i {
            color: var(--jamaica-yellow);
            transform: scale(1.1);
        }

        .jamaica-breadcrumb .breadcrumb-item.active {
            color: var(--jamaica-white);
            font-weight: 600;
            display: flex;
            align-items: center;
            background: rgba(0, 166, 81, 0.15);
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            border: 1px solid rgba(0, 166, 81, 0.3);
        }

        .jamaica-breadcrumb .breadcrumb-item.active i {
            color: var(--jamaica-green);
            margin-right: 0.5rem;
        }

        /* Dashboard Container */
        .jamaica-dashboard {
            padding: 100px 0 3rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Hero Section with Dashboard Styling */
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

        /* Statistics Grid */
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

        /* Enhanced Widgets Container */
        .widget-container {
            background: transparent;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .widget-container:hover {
            transform: translateY(-3px);
        }

        .widget-container .card {
            background: rgba(26, 31, 46, 0.6);
            border: 1px solid rgba(42, 47, 62, 0.3);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            color: var(--jamaica-white);
        }

        .card-header {
            background: rgba(42, 47, 62, 0.5);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
        }

        .card-title-container {
            display: flex;
            align-items: center;
        }

        .card-title {
            color: var(--jamaica-white);
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-text {
            color: #a0a0a0;
        }

        .card-subtitle {
            color: #a0a0a0;
            font-size: 0.875rem;
        }

        /* Button Styling */
        .btn-jamaica {
            background: linear-gradient(135deg, var(--jamaica-green), var(--jamaica-blue));
            border: none;
            color: var(--jamaica-white);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-jamaica:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 166, 81, 0.3);
            color: var(--jamaica-white);
        }

        /* Form Styling */
        .form-control, .form-select {
            background: rgba(42, 47, 62, 0.7);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(42, 47, 62, 0.9);
            border-color: var(--jamaica-green);
            box-shadow: 0 0 0 0.2rem rgba(0, 166, 81, 0.25);
            color: #fff;
        }

        .form-control::placeholder {
            color: #a0a0a0;
        }

        .form-label {
            color: var(--jamaica-white);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* Aircraft and Category Cards */
        .aircraft-card, .category-card, .quick-access-card {
            background: rgba(26, 31, 46, 0.6);
            border: 1px solid rgba(42, 47, 62, 0.3);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .aircraft-card:hover, .category-card:hover, .quick-access-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
            border-color: rgba(0, 166, 81, 0.4);
        }

        .aircraft-image {
            height: 200px;
            overflow: hidden;
            background: rgba(42, 47, 62, 0.5);
        }

        .aircraft-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .aircraft-card:hover .aircraft-image img {
            transform: scale(1.05);
        }

        /* Text Colors for Dark Theme */
        .text-white {
            color: var(--jamaica-white) !important;
        }

        .text-muted {
            color: #a0a0a0 !important;
        }
        .aircraft-documentation-index {
            padding: 100px 0 3rem 0;
            min-height: calc(100vh - 100px);
            position: relative;
            z-index: 1;
        }

        /* Welcome Section - Dashboard Hero Style */
        .welcome-section {
            background: rgba(15, 20, 25, 0.3);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 3rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            color: var(--jamaica-white);
        }

        .welcome-section::before {
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

        .welcome-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #ffffff, #00a651);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 1px;
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
            margin-bottom: 2rem;
            color: #a0a0a0;
        }

        /* Stats Section - Dashboard Style */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
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

        .stat-card::before {
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

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
            border-color: rgba(0, 166, 81, 0.4);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }

        .stat-card.aircraft::before { --gradient: linear-gradient(90deg, var(--jamaica-green), #2ecc71); }
        .stat-card.categories::before { --gradient: linear-gradient(90deg, var(--jamaica-yellow), #f39c12); }
        .stat-card.documents::before { --gradient: linear-gradient(90deg, var(--jamaica-blue), #3498db); }

        .stat-icon {
            font-size: 2rem;
            color: var(--jamaica-green);
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--jamaica-white);
            margin-bottom: 0.5rem;
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #a0a0a0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        /* Section Styles - Dashboard Style */
        .section {
            background: rgba(26, 31, 46, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(42, 47, 62, 0.3);
            transition: all 0.3s ease;
        }

        .section:hover {
            transform: translateY(-3px);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(42, 47, 62, 0.3);
        }

        .section-icon {
            font-size: 2rem;
            margin-right: 1rem;
            color: var(--jamaica-green);
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--jamaica-white);
            margin: 0;
        }

        /* Search Section - Dashboard Style */
        .search-form {
            background: rgba(42, 47, 62, 0.7);
            border-radius: 16px;
            padding: 1.5rem;
        }

        .search-form .form-label {
            color: var(--jamaica-white);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .search-form .form-control,
        .search-form .form-select {
            background: rgba(42, 47, 62, 0.7);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .search-form .form-control:focus,
        .search-form .form-select:focus {
            background: rgba(42, 47, 62, 0.9);
            border-color: var(--jamaica-green);
            box-shadow: 0 0 0 0.2rem rgba(0, 166, 81, 0.25);
            color: #fff;
        }

        .search-form .form-control::placeholder {
            color: #a0a0a0;
        }

        .search-btn {
            background: linear-gradient(135deg, var(--jamaica-green), #2ecc71);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 166, 81, 0.3);
        }

        /* Card Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        /* Base Card Styles - Dashboard Style */
        .base-card {
            background: rgba(26, 31, 46, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(42, 47, 62, 0.3);
            transition: all 0.3s ease;
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .base-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
            border-color: rgba(0, 166, 81, 0.4);
        }

        /* Aircraft Cards */
        .aircraft-card .card-image {
            height: 200px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            position: relative;
            overflow: hidden;
        }

        .aircraft-card .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .aircraft-card:hover .card-image img {
            transform: scale(1.05);
        }

        .aircraft-card .card-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--jamaica-green);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        /* Category Cards */
        .category-card {
            border-left: 4px solid var(--category-color, var(--jamaica-blue));
        }

        .category-card .card-header {
            background: rgba(var(--category-rgb), 0.1);
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .category-icon {
            font-size: 2.5rem;
            color: var(--category-color);
            margin-bottom: 1rem;
        }

        /* Card Content */
        .card-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--jamaica-white);
            margin-bottom: 0.5rem;
        }

        .card-subtitle {
            color: #a0a0a0;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .card-description {
            color: #a0a0a0;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .card-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: #a0a0a0;
        }

        .card-footer {
            padding: 1rem 1.5rem;
            background: rgba(42, 47, 62, 0.5);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--jamaica-green), #2ecc71);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 166, 81, 0.3);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid var(--jamaica-green);
            color: var(--jamaica-green);
            background: transparent;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline-custom:hover {
            background: var(--jamaica-green);
            color: white;
            transform: translateY(-2px);
        }

        /* Featured Badge */
        .featured-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: linear-gradient(135deg, var(--jamaica-yellow), #f39c12);
            color: var(--jamaica-dark);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            z-index: 2;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .aircraft-documentation-index {
                padding: 80px 1rem 2rem 1rem;
            }

            .welcome-section {
                padding: 2rem 1.5rem;
                margin: 1rem 0;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .section {
                margin: 1rem 0;
                padding: 1.5rem;
            }

            .cards-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        /* Animation */
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Scroll Enhancement */
        html {
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--jamaica-green), var(--jamaica-blue));
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #2ecc71, #3498db);
        }
    </style>
@endsection

@section('content')
<div class="jamaica-dashboard">
    <div class="container-fluid">

        {{-- Breadcrumbs --}}
        <div class="row mb-3">
            <div class="col-12">
                <nav aria-label="breadcrumb" class="breadcrumb-nav">
                    <ol class="breadcrumb jamaica-breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('frontend.dashboard.index') }}" class="breadcrumb-link">
                                <i class="bi bi-house-door me-1"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            Aircraft Documentation
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Hero Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-section">
                    <div class="hero-content">
                        <h1 class="hero-title">
                            <i class="bi bi-file-earmark-pdf me-3" style="color: var(--jamaica-green);"></i>
                            Aircraft Documentation Center
                        </h1>
                        <p class="hero-subtitle">
                            Access comprehensive aircraft manuals, procedures, and technical documentation
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="bi bi-airplane"></i>
                        </div>
                        <div class="stat-value">{{ $stats['aircraft_types'] ?? 0 }}</div>
                        <div class="stat-label">Aircraft Types</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="bi bi-folder2"></i>
                        </div>
                        <div class="stat-value">{{ $stats['categories'] ?? 0 }}</div>
                        <div class="stat-label">Document Categories</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="stat-value">{{ $stats['documents'] ?? 0 }}</div>
                        <div class="stat-label">Total Documents</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="widget-container">
                    <div class="card widget-card h-100">
                        <div class="card-header">
                            <div class="card-title-container">
                                <i class="bi bi-search me-2" style="color: var(--jamaica-green);"></i>
                                <h3 class="card-title">Search Documentation</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('frontend.aircraft-documentation.search') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="aircraft_type" class="form-label">Aircraft Type</label>
                                        <select name="aircraft_type" id="aircraft_type" class="form-select">
                                            <option value="">All Aircraft Types</option>
                                            @foreach($aircraftTypes as $type)
                                                <option value="{{ $type->id }}" {{ request('aircraft_type') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category" id="category" class="form-select">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="search" class="form-label">Search Terms</label>
                                        <input type="text" name="search" id="search" class="form-control" 
                                               placeholder="Search documents..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-jamaica">
                                        <i class="bi bi-search me-2"></i>
                                        Search Documentation
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aircraft Fleet Section --}}
        @if($aircraftTypes->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="widget-container">
                    <div class="card widget-card h-100">
                        <div class="card-header">
                            <div class="card-title-container">
                                <i class="bi bi-airplane me-2" style="color: var(--jamaica-green);"></i>
                                <h3 class="card-title">Aircraft Fleet</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($aircraftTypes as $aircraft)
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card aircraft-card h-100">
                                        @if($aircraft->image_url)
                                        <div class="card-img-top aircraft-image">
                                            <img src="{{ $aircraft->image_url }}" alt="{{ $aircraft->name }}" class="img-fluid">
                                        </div>
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title text-white">{{ $aircraft->name }}</h5>
                                            <p class="card-subtitle">{{ $aircraft->manufacturer }} {{ $aircraft->model }}</p>
                                            @if($aircraft->description)
                                                <p class="card-text">{{ Str::limit($aircraft->description, 100) }}</p>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <small class="text-muted">
                                                    <i class="bi bi-file-earmark-text me-1"></i>
                                                    {{ $aircraft->documents_count ?? 0 }} Documents
                                                </small>
                                                <a href="{{ route('frontend.aircraft-documentation.aircraft', $aircraft->slug) }}" 
                                                   class="btn btn-jamaica btn-sm">
                                                    View Documents
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Document Categories Section --}}
        @if($categories->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="widget-container">
                    <div class="card widget-card h-100">
                        <div class="card-header">
                            <div class="card-title-container">
                                <i class="bi bi-folder2 me-2" style="color: var(--jamaica-green);"></i>
                                <h3 class="card-title">Document Categories</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $categoryColors = [
                                        '#00A651', // Jamaica Green
                                        '#FED100', // Jamaica Yellow  
                                        '#0056b3', // Jamaica Blue
                                        '#e74c3c', // Red
                                        '#9b59b6', // Purple
                                        '#f39c12', // Orange
                                    ];
                                    $categoryIcons = [
                                        'bi-book',
                                        'bi-tools', 
                                        'bi-shield-check',
                                        'bi-gear',
                                        'bi-clipboard-check',
                                        'bi-file-earmark-text'
                                    ];
                                @endphp
                                
                                @foreach($categories as $index => $category)
                                @php
                                    $colorIndex = $index % count($categoryColors);
                                    $iconIndex = $index % count($categoryIcons);
                                    $color = $categoryColors[$colorIndex];
                                    $icon = $categoryIcons[$iconIndex];
                                @endphp
                                
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card category-card h-100" style="border-left: 4px solid {{ $color }};">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="{{ $icon }} me-3" style="font-size: 2rem; color: {{ $color }};"></i>
                                                <h5 class="card-title text-white mb-0">{{ $category->name }}</h5>
                                            </div>
                                            @if($category->description)
                                                <p class="card-text">{{ $category->description }}</p>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <small class="text-muted">
                                                    <i class="bi bi-file-earmark-text me-1"></i>
                                                    {{ $category->documents_count ?? 0 }} Documents
                                                </small>
                                                <a href="{{ route('frontend.aircraft-documentation.category', $category->slug) }}" 
                                                   class="btn btn-jamaica btn-sm">
                                                    Browse Category
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Quick Access Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="widget-container">
                    <div class="card widget-card h-100">
                        <div class="card-header">
                            <div class="card-title-container">
                                <i class="bi bi-link-45deg me-2" style="color: var(--jamaica-green);"></i>
                                <h3 class="card-title">Quick Access</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card quick-access-card h-100 text-center">
                                        <div class="card-body">
                                            <i class="bi bi-search mb-3" style="font-size: 3rem; color: var(--jamaica-green);"></i>
                                            <h5 class="card-title text-white">Advanced Search</h5>
                                            <p class="card-text">Find specific documents with detailed filters</p>
                                            <a href="{{ route('frontend.aircraft-documentation.search') }}" class="btn btn-jamaica">
                                                Search Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                @auth
                                <div class="col-md-4 mb-3">
                                    <div class="card quick-access-card h-100 text-center">
                                        <div class="card-body">
                                            <i class="bi bi-bookmark mb-3" style="font-size: 3rem; color: var(--jamaica-blue);"></i>
                                            <h5 class="card-title text-white">My Bookmarks</h5>
                                            <p class="card-text">Access your saved documents quickly</p>
                                            <a href="{{ route('frontend.aircraft-documentation.index') }}?bookmarked=1" class="btn btn-jamaica">
                                                View Bookmarks
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endauth
                                
                                <div class="col-md-4 mb-3">
                                    <div class="card quick-access-card h-100 text-center">
                                        <div class="card-body">
                                            <i class="bi bi-question-circle mb-3" style="font-size: 3rem; color: var(--jamaica-yellow);"></i>
                                            <h5 class="card-title text-white">Help & Support</h5>
                                            <p class="card-text">Need assistance? Contact our support team</p>
                                            <a href="mailto:support@airjamaicavirtual.com" class="btn btn-jamaica">
                                                Contact Support
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Animated Background (matching dashboard) -->
<div class="flying-particles"></div>

<script>
    // Document ready functions
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips if available
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Create floating particles animation
        createFloatingParticles();

        // Smooth scroll for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading states to buttons
        document.querySelectorAll('.btn-jamaica').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!this.disabled) {
                    this.style.opacity = '0.8';
                    setTimeout(() => {
                        this.style.opacity = '1';
                    }, 200);
                }
            });
        });

        // Enhanced search functionality
        const searchInput = document.getElementById('search');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    // Add visual feedback
                    this.style.borderColor = 'var(--jamaica-green)';
                    setTimeout(() => {
                        this.style.borderColor = '';
                    }, 300);
                }, 500);
            });
        }

        // Add hover effects to cards
        document.querySelectorAll('.aircraft-card, .category-card, .quick-access-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });

    // Create floating particles (matching dashboard animation)
    function createFloatingParticles() {
        const particlesContainer = document.querySelector('.flying-particles');
        if (!particlesContainer) return;

        const particleCount = 30;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            // Random positioning
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            
            // Random animation delay
            particle.style.animationDelay = Math.random() * 10 + 's';
            particle.style.animationDuration = (Math.random() * 20 + 15) + 's';
            
            particlesContainer.appendChild(particle);
        }
    }

    // Additional utility functions
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            background: rgba(26, 31, 46, 0.9);
            border: 1px solid var(--jamaica-green);
            color: var(--jamaica-white);
        `;
        
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Page transition effects
    function smoothPageTransition() {
        document.body.style.opacity = '0';
        setTimeout(() => {
            document.body.style.transition = 'opacity 0.3s ease';
            document.body.style.opacity = '1';
        }, 50);
    }
</script>

<style>
    /* Flying Particles Animation */
    .flying-particles {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
    }

    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: rgba(0, 166, 81, 0.6);
        border-radius: 50%;
        animation: float 20s linear infinite;
        opacity: 0.7;
        box-shadow: 0 0 10px rgba(0, 166, 81, 0.4);
    }

    .particle:nth-child(odd) {
        background: rgba(255, 215, 0, 0.6);
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.4);
    }

    .particle:nth-child(3n) {
        background: rgba(0, 86, 179, 0.6);
        box-shadow: 0 0 10px rgba(0, 86, 179, 0.4);
        width: 3px;
        height: 3px;
    }

    @keyframes float {
        0% {
            transform: translateY(100vh) translateX(0px) rotate(0deg);
            opacity: 0;
        }
        10% {
            opacity: 0.7;
        }
        90% {
            opacity: 0.7;
        }
        100% {
            transform: translateY(-100vh) translateX(50px) rotate(360deg);
            opacity: 0;
        }
    }

    /* Page Load Animation */
    @keyframes pageLoad {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .jamaica-dashboard {
        animation: pageLoad 0.6s ease-out;
    }

    /* Enhanced Card Animations */
    .widget-container {
        animation: pageLoad 0.8s ease-out backwards;
    }

    .widget-container:nth-child(1) { animation-delay: 0.1s; }
    .widget-container:nth-child(2) { animation-delay: 0.2s; }
    .widget-container:nth-child(3) { animation-delay: 0.3s; }
    .widget-container:nth-child(4) { animation-delay: 0.4s; }

    /* Responsive adjustments */
    @media (max-width: 768px) {
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
        
        .stat-item {
            padding: 1rem;
        }
        
        .jamaica-dashboard {
            padding: 80px 0 2rem 0;
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .hero-section {
            padding: 2rem 1rem;
        }
    }
</style>

@endsection