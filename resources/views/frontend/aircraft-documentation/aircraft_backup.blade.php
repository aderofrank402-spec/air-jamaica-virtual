@extends('layouts.seven.public_app')

@section('title', $aircraftType->display_name . ' - Aircraft Documentation')

@section('content')
<div class="aircraft-documentation-aircraft">
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="page-header-content">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb text-white">
                                <li class="breadcrumb-item"><a href="{{ route('frontend.aircraft-documentation.index') }}" class="text-white">Aircraft Documentation</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $aircraftType->display_name }}</li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <i class="fas fa-plane text-primary me-2"></i>
                            {{ $aircraftType->display_name }}
                        </h1>
                        <p class="page-description">
                            {{ $aircraftType->description }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    @if($aircraftType->image_url)
                    <div class="aircraft-image">
                        <img src="{{ $aircraftType->image_url }}" alt="{{ $aircraftType->display_name }}" class="img-fluid rounded">
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="main-content py-5">
        <div class="container">
            <!-- Aircraft Specifications -->
            @if($aircraftType->specifications)
            <div class="specifications-section mb-5">
                <h2 class="section-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Aircraft Specifications
                </h2>
                <div class="specifications-grid">
                    <div class="row">
                        @foreach($aircraftType->specifications as $key => $value)
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="spec-card">
                                <div class="spec-label">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                                <div class="spec-value">{{ $value }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Document Categories -->
            <div class="documents-section">
                <h2 class="section-title">
                    <i class="fas fa-folder-open me-2"></i>
                    Documentation Categories
                </h2>
                
                @foreach($categories as $category)
                    @if(isset($documents[$category->name]) && $documents[$category->name]->isNotEmpty())
                    <div class="category-section mb-5">
                        <div class="category-header">
                            <div class="category-title-row">
                                <h3 class="category-name">
                                    <i class="{{ $category->icon ?? 'fas fa-folder' }}" style="color: {{ $category->color }};"></i>
                                    {{ $category->name }}
                                    <span class="badge bg-secondary ms-2">{{ $documents[$category->name]->count() }}</span>
                                </h3>
                                <p class="category-description">{{ $category->description }}</p>
                            </div>
                        </div>
                        
                        <div class="documents-grid">
                            <div class="row">
                                @foreach($documents[$category->name] as $document)
                                <div class="col-lg-6 col-xl-4 mb-4">
                                    <div class="document-card h-100 {{ $document->is_featured ? 'featured' : '' }}">
                                        <div class="document-header">
                                            <div class="document-type">
                                                <i class="fas fa-file-pdf text-danger"></i>
                                                {{ strtoupper($document->file_type) }}
                                            </div>
                                            <div class="document-badges">
                                                @if($document->is_featured)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-star"></i>
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
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <small>{{ $document->effective_date->format('M d, Y') }}</small>
                                                </div>
                                                <div class="meta-item">
                                                    <i class="fas fa-download me-1"></i>
                                                    <small>{{ $document->download_count }} downloads</small>
                                                </div>
                                                <div class="meta-item">
                                                    <i class="fas fa-file-alt me-1"></i>
                                                    <small>{{ $document->file_size_formatted }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="document-footer">
                                            <div class="document-actions">
                                                <a href="{{ route('frontend.aircraft-documentation.view', [$aircraftType->slug, $document->category->slug, $document->slug]) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>
                                                    View
                                                </a>
                                                <a href="{{ route('frontend.aircraft-documentation.download', [$aircraftType->slug, $document->category->slug, $document->slug]) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-download me-1"></i>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach

                @if($documents->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4>No Documents Available</h4>
                    <p>Documentation for {{ $aircraftType->display_name }} is currently being prepared.</p>
                    <a href="{{ route('frontend.aircraft-documentation.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to Documentation
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.aircraft-documentation-aircraft {
    background: #f8f9fa;
    min-height: 100vh;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4rem 0;
}

.breadcrumb a {
    text-decoration: none;
}

.aircraft-image img {
    max-height: 200px;
    width: 100%;
    object-fit: cover;
}

.specifications-grid {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.spec-card {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    height: 100%;
}

.spec-label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.spec-value {
    font-size: 1.25rem;
    font-weight: bold;
    color: #1f2937;
}

.category-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.category-header {
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 1rem;
    margin-bottom: 2rem;
}

.category-name {
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.category-description {
    color: #6b7280;
    margin-bottom: 0;
}

.document-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.document-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.document-card.featured {
    border-color: #fbbf24;
}

.document-header {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.document-badges {
    display: flex;
    gap: 0.5rem;
}

.document-body {
    padding: 1.5rem;
}

.document-title {
    color: #1f2937;
    margin-bottom: 0.75rem;
    font-size: 1.1rem;
}

.document-description {
    color: #6b7280;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.document-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.meta-item {
    color: #9ca3af;
    font-size: 0.875rem;
}

.document-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #f1f5f9;
    background: #f8f9fa;
}

.document-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.section-title {
    margin-bottom: 2rem;
    color: #333;
    font-weight: 600;
}

@media (max-width: 768px) {
    .document-actions {
        flex-direction: column;
    }
    
    .document-meta {
        font-size: 0.8rem;
    }
    
    .specifications-grid {
        padding: 1rem;
    }
}
</style>
@endsection