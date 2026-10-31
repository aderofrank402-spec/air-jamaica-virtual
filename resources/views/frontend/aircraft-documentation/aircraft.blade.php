@extends('layouts.seven.public_app')

@section('title', $aircraftType->display_name . ' - Aircraft Documentation')

@push('head')
    <style>
        /* BULLETPROOF GRID SOLUTION - SENIOR DEVELOPER FIX */
        .operations-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            grid-gap: 30px !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .operations-grid .operation-card {
            width: 100% !important;
            margin: 0 !important;
            float: none !important;
            clear: none !important;
            display: block !important;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .operations-grid {
                grid-template-columns: 1fr !important;
            }
        }
        
        /* Tablet responsive */
        @media (min-width: 769px) and (max-width: 1024px) {
            .operations-grid {
                grid-template-columns: 1fr 1fr !important;
            }
        }
        
        /* Desktop responsive */
        @media (min-width: 1025px) {
            .operations-grid {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
            }
        }
    </style>
@endpush

@section('styles')
    <style>
        /* Air Jamaica Virtual CSS Variables */
        :root {
            --jamaica-green: #00a651;
            --jamaica-yellow: #fed100;
            --jamaica-blue: #0056b3;
            --jamaica-white: #ffffff;
        }

        /* Body and Background */
        body {
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            color: var(--jamaica-white);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
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

        /* Hero Section */
        .hero-section {
            background: rgba(15, 20, 25, 0.3);
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 3rem;
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
            margin-bottom: 1.5rem;
            color: #a0a0a0;
        }

        .aircraft-image {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            height: 300px;
            background: rgba(42, 47, 62, 0.5);
        }

        .aircraft-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .hero-section:hover .aircraft-image img {
            transform: scale(1.05);
        }

        /* Widget Container */
        .widget-container {
            background: transparent;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
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

        /* Operation Cards (Dashboard Style) */
        .operations-grid {
            /* Base styles - will be overridden by higher specificity above */
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .operation-card {
            background: rgba(26, 31, 46, 0.8);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
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

        .operation-card.aircraft-specs::before {
            background: linear-gradient(90deg, #00a651, #20c997);
        }

        .operation-card.documents::before {
            background: linear-gradient(90deg, #0066cc, #3399ff);
        }

        .operation-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }

        .operation-card:hover::before {
            opacity: 1;
        }

        .operation-header {
            padding: 2rem 2rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .operation-title {
            color: var(--jamaica-white);
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .operation-title i {
            color: var(--jamaica-green);
            margin-right: 0.75rem;
            font-size: 1.2em;
        }

        .operation-subtitle {
            color: #a0a0a0;
            font-size: 0.9rem;
            margin: 0;
        }

        .operation-body {
            padding: 1.5rem 2rem 2rem;
        }

        /* Documents Grid within Cards */
        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .card-header {
            background: rgba(42, 47, 62, 0.5);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
        }

        .card-title {
            color: var(--jamaica-white);
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .card-title i {
            color: var(--jamaica-green);
            margin-right: 0.75rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Specifications Grid */
        .specs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .spec-item {
            background: rgba(42, 47, 62, 0.6);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .spec-item::before {
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

        .spec-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
            border-color: rgba(0, 166, 81, 0.4);
        }

        .spec-item:hover::before {
            opacity: 1;
        }

        .spec-label {
            font-size: 0.9rem;
            color: #a0a0a0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .spec-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--jamaica-white);
        }

        /* Document Cards */
        .document-card {
            background: rgba(26, 31, 46, 0.6);
            border: 1px solid rgba(42, 47, 62, 0.3);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
        }

        .document-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
            border-color: rgba(0, 166, 81, 0.4);
        }

        .document-card.featured {
            border-color: rgba(255, 215, 0, 0.5);
        }

        .document-card.featured::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #ffd700, #00a651);
        }

        .document-header {
            padding: 1.5rem 1.5rem 0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .document-type {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--jamaica-green);
        }

        .document-type i {
            margin-right: 0.5rem;
            color: #dc3545;
        }

        .document-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
        }

        .bg-warning {
            background: linear-gradient(135deg, #ffd700, #ffed4e) !important;
            color: #000 !important;
        }

        .bg-primary {
            background: linear-gradient(135deg, var(--jamaica-blue), var(--jamaica-green)) !important;
        }

        .document-body {
            padding: 1.5rem;
        }

        .document-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--jamaica-white);
            margin-bottom: 1rem;
        }

        .document-description {
            color: #a0a0a0;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .document-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            color: #a0a0a0;
        }

        .meta-item i {
            color: var(--jamaica-green);
            margin-right: 0.5rem;
            width: 12px;
        }

        .document-footer {
            padding: 0 1.5rem 1.5rem;
            margin-top: auto;
        }

        .document-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
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

        .btn-outline-jamaica {
            background: transparent;
            border: 2px solid var(--jamaica-green);
            color: var(--jamaica-green);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-outline-jamaica:hover {
            background: var(--jamaica-green);
            color: var(--jamaica-white);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 166, 81, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(26, 31, 46, 0.6);
            border-radius: 15px;
            border: 1px solid rgba(42, 47, 62, 0.3);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--jamaica-green);
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }

        .empty-state h4 {
            color: var(--jamaica-white);
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #a0a0a0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }

            .operations-grid {
                grid-template-columns: 1fr !important;
                gap: 1.5rem;
            }
            
            .specs-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .spec-item {
                padding: 1rem;
            }
            
            .jamaica-dashboard {
                padding: 80px 0 2rem 0;
            }

            .document-actions {
                flex-direction: column;
            }
            
            .document-meta {
                font-size: 0.8rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .operations-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) !important;
                gap: 1.5rem;
            }
        }

        @media (min-width: 1025px) {
            .operations-grid {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
                gap: 2rem;
            }
        }

        @media (max-width: 576px) {
            .specs-grid {
                grid-template-columns: 1fr;
            }
            
            .hero-section {
                padding: 2rem 1rem;
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

        .widget-container {
            animation: pageLoad 0.8s ease-out backwards;
        }

        .widget-container:nth-child(1) { animation-delay: 0.1s; }
        .widget-container:nth-child(2) { animation-delay: 0.2s; }
        .widget-container:nth-child(3) { animation-delay: 0.3s; }
        .widget-container:nth-child(4) { animation-delay: 0.4s; }
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
                        <li class="breadcrumb-item">
                            <a href="{{ route('frontend.aircraft-documentation.index') }}" class="breadcrumb-link">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                Aircraft Documentation
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="bi bi-airplane me-1"></i>
                            {{ $aircraftType->display_name }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Hero Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-section">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="hero-content">
                                <h1 class="hero-title">
                                    <i class="bi bi-airplane me-3" style="color: var(--jamaica-green);"></i>
                                    {{ $aircraftType->display_name }}
                                </h1>
                                <p class="hero-subtitle">
                                    {{ $aircraftType->description ?: 'Comprehensive documentation and resources for this aircraft type' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            @if($aircraftType->image_url)
                            <div class="aircraft-image">
                                <img src="{{ $aircraftType->image_url }}" alt="{{ $aircraftType->display_name }}" class="img-fluid">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="container-fluid px-0">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; width: 100%; margin: 2rem 0;">
                    
                    {{-- Aircraft Specifications Card --}}
                    @if($aircraftType->specifications)
                    <div class="operation-card aircraft-specs" style="grid-column: 1;">
                        <div class="operation-header">
                            <h3 class="operation-title">
                                <i class="bi bi-info-circle"></i>
                                Aircraft Specifications
                            </h3>
                        </div>
                        <div class="operation-body">
                            <div class="specs-grid">
                                @foreach($aircraftType->specifications as $key => $value)
                                <div class="spec-item">
                                    <div class="spec-label">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                                    <div class="spec-value">{{ $value }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Document Categories Cards --}}
                    @if($documents->isNotEmpty())
                        @foreach($categories as $category)
                            @if(isset($documents[$category->name]) && $documents[$category->name]->isNotEmpty())
                            <div class="operation-card documents">
                                <div class="operation-header">
                                    <h3 class="operation-title">
                                        <i class="{{ $category->icon ?? 'bi bi-folder' }}" style="color: {{ $category->color ?? 'var(--jamaica-green)' }};"></i>
                                        {{ $category->name }}
                                        <span class="badge bg-primary ms-2">{{ $documents[$category->name]->count() }}</span>
                                    </h3>
                                    @if($category->description)
                                    <p class="operation-subtitle mt-2 mb-0 text-muted">{{ $category->description }}</p>
                                    @endif
                                </div>
                                <div class="operation-body">
                                    <div class="documents-grid">
                                        @foreach($documents[$category->name] as $document)
                                        <div class="document-card h-100 {{ $document->is_featured ? 'featured' : '' }}">
                                            <div class="document-header">
                                                <div class="document-type">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                    {{ strtoupper($document->file_type) }}
                                                </div>
                                                <div class="document-badges">
                                                    @if($document->is_featured)
                                                    <span class="badge bg-warning">
                                                        <i class="bi bi-star-fill"></i>
                                                        Featured
                                                    </span>
                                                    @endif
                                                    <span class="badge bg-primary">v{{ $document->version }}</span>
                                                </div>
                                            </div>
                                            <div class="document-body">
                                                <h5 class="document-title">{{ $document->title }}</h5>
                                                <p class="document-description">{{ Str::limit($document->description, 120) }}</p>
                                                
                                                <div class="document-meta">
                                                    <div class="meta-item">
                                                        <i class="bi bi-calendar-event"></i>
                                                        <small>{{ $document->effective_date->format('M d, Y') }}</small>
                                                    </div>
                                                    <div class="meta-item">
                                                        <i class="bi bi-download"></i>
                                                        <small>{{ $document->download_count }} downloads</small>
                                                    </div>
                                                    <div class="meta-item">
                                                        <i class="bi bi-file-text"></i>
                                                        <small>{{ $document->file_size_formatted }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="document-footer">
                                                <div class="document-actions">
                                                    <a href="{{ route('frontend.aircraft-documentation.view', [$aircraftType->slug, $document->category->slug, $document->slug]) }}" 
                                                       class="btn btn-outline-jamaica btn-sm">
                                                        <i class="bi bi-eye me-1"></i>
                                                        View
                                                    </a>
                                                    <a href="{{ route('frontend.aircraft-documentation.download', [$aircraftType->slug, $document->category->slug, $document->slug]) }}" 
                                                       class="btn btn-jamaica btn-sm">
                                                        <i class="bi bi-download me-1"></i>
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @else
                        {{-- Empty State Card --}}
                        <div class="operation-card documents">
                            <div class="operation-body">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                    <h4>No Documents Available</h4>
                                    <p>There are currently no documents available for this aircraft type. Check back later for updates.</p>
                                    <a href="{{ route('frontend.aircraft-documentation.index') }}" class="btn btn-jamaica mt-3">
                                        <i class="bi bi-arrow-left me-1"></i>
                                        Back to Documentation
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Additional Cards for Testing Horizontal Layout --}}
                    <div class="operation-card" style="grid-column: 2;">
                        <div class="operation-header">
                            <div class="operation-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <h3 class="operation-title">Flight Manuals</h3>
                        </div>
                        <div class="operation-body">
                            <div class="operation-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Documents</span>
                                    <span class="stat-value">5</span>
                                </div>
                            </div>
                            <div class="operation-description">
                                Aircraft flight operating manuals and procedures
                            </div>
                            <div class="document-actions">
                                <button class="action-btn">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Documents
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="operation-card" style="grid-column: 1; grid-row: 2;">
                        <div class="operation-header">
                            <div class="operation-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h3 class="operation-title">Maintenance</h3>
                        </div>
                        <div class="operation-body">
                            <div class="operation-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Documents</span>
                                    <span class="stat-value">8</span>
                                </div>
                            </div>
                            <div class="operation-description">
                                Maintenance manuals and service bulletins
                            </div>
                            <div class="document-actions">
                                <button class="action-btn">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Documents
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="operation-card" style="grid-column: 2; grid-row: 2;">
                        <div class="operation-header">
                            <div class="operation-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="operation-title">Performance</h3>
                        </div>
                        <div class="operation-body">
                            <div class="operation-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Documents</span>
                                    <span class="stat-value">3</span>
                                </div>
                            </div>
                            <div class="operation-description">
                                Performance charts and weight & balance data
                            </div>
                            <div class="document-actions">
                                <button class="action-btn">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Documents
                                </button>
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
        document.querySelectorAll('.btn-jamaica, .btn-outline-jamaica').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!this.disabled) {
                    this.style.opacity = '0.8';
                    setTimeout(() => {
                        this.style.opacity = '1';
                    }, 200);
                }
            });
        });

        // Add hover effects to cards (Dashboard Style)
        const cards = document.querySelectorAll('.operation-card, .document-card, .spec-item');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                if (this.classList.contains('operation-card')) {
                    this.style.transform = 'translateY(-8px)';
                } else {
                    this.style.transform = 'translateY(-5px)';
                }
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
</style>
@endsection