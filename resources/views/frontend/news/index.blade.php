@extends('layouts.seven.public_app')

@section('title', 'Company News')

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
        'title' => __('common.dashboard'),
        'url' => route('frontend.dashboard.index'),
        'icon' => 'bi bi-speedometer2'
    ],
    [
        'title' => 'Company News',
        'icon' => 'bi bi-newspaper'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

@section('content')
<div class="jamaica-dashboard">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-section" style="background: linear-gradient(135deg, rgba(44, 62, 80, 0.95) 0%, rgba(52, 73, 94, 0.95) 100%); border: 2px solid var(--jamaica-yellow); border-radius: 15px; position: relative; overflow: hidden;">
                    <div class="hero-content" style="padding: 3rem 2rem; text-align: center;">
                        <h1 class="hero-title" style="color: var(--jamaica-yellow); font-weight: bold; font-size: 2.5rem; margin-bottom: 15px;">
                            <i class="fas fa-newspaper me-3"></i>Air Jamaica Virtual News
                        </h1>
                        <p class="hero-subtitle" style="color: var(--jamaica-white); font-size: 1.2rem; margin-bottom: 0; opacity: 0.9;">
                            Stay updated with the latest airline announcements and aviation news
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Articles -->
        @if($news->count() > 0)
            <div class="row">
                @foreach($news as $article)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 jamaica-card" style="background: linear-gradient(135deg, rgba(34, 49, 63, 0.95) 0%, rgba(44, 62, 80, 0.95) 100%); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 15px; transition: all 0.3s ease; position: relative; overflow: hidden;">
                        <!-- Card Header -->
                        <div class="card-header" style="background: linear-gradient(135deg, var(--jamaica-yellow) 0%, #ffdb4d 100%); border-bottom: 2px solid var(--jamaica-yellow); border-radius: 15px 15px 0 0;">
                            <h5 style="color: black; margin: 0; font-weight: 600;">
                                <i class="fas fa-bullhorn me-2"></i>{{ $article->subject }}
                            </h5>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body d-flex flex-column" style="padding: 1.5rem;">
                            <div class="flex-grow-1">
                                <div style="color: var(--jamaica-white); margin-bottom: 20px; line-height: 1.6; font-size: 0.95rem;">
                                    {!! Str::limit(strip_tags($article->body), 200) !!}
                                </div>
                            </div>
                            
                            <!-- Article Meta -->
                            <div class="mt-auto">
                                <hr style="border-color: rgba(255, 193, 7, 0.3); margin: 1rem 0;">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <small style="color: rgba(255, 193, 7, 0.8);">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ $article->created_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        @if($article->user)
                                        <small style="color: var(--jamaica-yellow);">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $article->user->name }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <small style="color: rgba(255, 255, 255, 0.6);">
                                            <i class="fas fa-clock me-1"></i>
                                            Published {{ $article->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Read Full Article Button -->
                        <div class="card-footer" style="background: rgba(44, 62, 80, 0.8); border-top: 1px solid rgba(255, 193, 7, 0.3); border-radius: 0 0 15px 15px;">
                            <button class="btn w-100 jamaica-btn" 
                                    style="background: linear-gradient(135deg, var(--jamaica-green) 0%, #00b359 100%); color: white; border: none; font-weight: 500; transition: all 0.3s ease;" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#fullArticleModal"
                                    data-article-id="{{ $article->id }}"
                                    data-article-title="{{ htmlspecialchars($article->subject, ENT_QUOTES) }}"
                                    data-article-content="{{ htmlspecialchars(strip_tags($article->body), ENT_QUOTES) }}"
                                    data-article-date="{{ $article->created_at->format('M d, Y H:i') }}"
                                    data-article-author="{{ $article->user ? htmlspecialchars($article->user->name, ENT_QUOTES) : 'System' }}">
                                <i class="fas fa-eye me-2"></i>Read Full Article
                            </button>
                        </div>
                        
                        <!-- Hover Effect Overlay -->
                        <div class="card-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(0, 166, 81, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%); opacity: 0; transition: opacity 0.3s ease; pointer-events: none; border-radius: 15px;"></div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <div class="jamaica-pagination">
                    {{ $news->links() }}
                </div>
            </div>
        @else
            <!-- No News State -->
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="card jamaica-card" style="background: linear-gradient(135deg, rgba(34, 49, 63, 0.95) 0%, rgba(44, 62, 80, 0.95) 100%); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 15px;">
                            <div class="card-body py-5">
                                <i class="fas fa-newspaper" style="font-size: 64px; color: rgba(255, 193, 7, 0.5); margin-bottom: 20px;"></i>
                                <h3 style="color: var(--jamaica-white); margin-bottom: 15px;">No News Available</h3>
                                <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                                    There are currently no news articles to display. Check back later for updates from Air Jamaica Virtual management.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Full Article Modal -->
<div class="modal fade" id="fullArticleModal" tabindex="-1" aria-labelledby="fullArticleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: linear-gradient(135deg, rgba(26, 26, 26, 0.98) 0%, rgba(45, 45, 45, 0.98) 100%); border: 2px solid var(--jamaica-yellow); border-radius: 15px;">
            <div class="modal-header" style="border-bottom: 2px solid var(--jamaica-yellow); background: linear-gradient(135deg, rgba(44, 62, 80, 0.95) 0%, rgba(52, 73, 94, 0.95) 100%); border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="fullArticleModalLabel" style="color: var(--jamaica-white);">
                    <i class="fas fa-newspaper me-2" style="color: var(--jamaica-yellow);"></i>
                    <span id="articleTitle">Article Title</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: rgba(44, 62, 80, 0.95); color: var(--jamaica-white); padding: 2rem;">
                <!-- Article Meta -->
                <div class="mb-4 p-3" style="background: rgba(255, 193, 7, 0.1); border-radius: 10px; border: 1px solid rgba(255, 193, 7, 0.3);">
                    <div class="row">
                        <div class="col-md-6">
                            <small style="color: var(--jamaica-yellow); font-weight: 500;">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <span id="articleDate">Date</span>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small style="color: var(--jamaica-yellow); font-weight: 500;">
                                <i class="fas fa-user me-1"></i>
                                <span id="articleAuthor">Author</span>
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Article Content -->
                <div id="articleContent" style="line-height: 1.8; font-size: 1.05rem; color: var(--jamaica-white);">
                    Article content will be loaded here...
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid var(--jamaica-yellow); background: linear-gradient(135deg, rgba(44, 62, 80, 0.95) 0%, rgba(52, 73, 94, 0.95) 100%); border-radius: 0 0 15px 15px;">
                <button type="button" class="btn" data-bs-dismiss="modal" style="background: rgba(108, 117, 125, 0.8); color: white; border: none; border-radius: 8px;">
                    <i class="fas fa-times me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Jamaica Theme Variables */
:root {
    --jamaica-green: #00a651;
    --jamaica-yellow: #ffc107;
    --jamaica-white: #ffffff;
    --jamaica-dark: #1a1f2e;
}

/* Card Hover Effects */
.jamaica-card:hover {
    transform: translateY(-8px);
    border-color: var(--jamaica-yellow) !important;
    box-shadow: 0 15px 35px rgba(255, 193, 7, 0.2);
}

.jamaica-card:hover .card-overlay {
    opacity: 1;
}

/* Button Hover Effects */
.jamaica-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 166, 81, 0.3);
    background: linear-gradient(135deg, #00b359 0%, var(--jamaica-green) 100%) !important;
}

/* Pagination Styling */
.jamaica-pagination .pagination .page-link {
    background-color: rgba(44, 62, 80, 0.8);
    border: 1px solid rgba(255, 193, 7, 0.3);
    color: var(--jamaica-white);
    border-radius: 8px;
    margin: 0 2px;
    transition: all 0.3s ease;
}

.jamaica-pagination .pagination .page-link:hover {
    background-color: var(--jamaica-yellow);
    border-color: var(--jamaica-yellow);
    color: black;
    transform: translateY(-2px);
}

.jamaica-pagination .pagination .page-item.active .page-link {
    background-color: var(--jamaica-yellow);
    border-color: var(--jamaica-yellow);
    color: black;
}

/* Hero Section Animation */
.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 193, 7, 0.1), transparent);
    animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Modal Enhancements */
.modal-content {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem !important;
    }
    
    .hero-subtitle {
        font-size: 1rem !important;
    }
    
    .card-body {
        padding: 1rem !important;
    }
}
</style>

<script>
// Handle modal show event to populate content (same pattern as dashboard modals)
document.getElementById('fullArticleModal').addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    const button = event.relatedTarget;
    
    // Extract info from data-* attributes
    const title = button.getAttribute('data-article-title');
    const content = button.getAttribute('data-article-content');
    const date = button.getAttribute('data-article-date');
    const author = button.getAttribute('data-article-author');
    
    // Update modal content
    document.getElementById('articleTitle').textContent = title;
    document.getElementById('articleDate').textContent = date;
    document.getElementById('articleAuthor').textContent = author;
    document.getElementById('articleContent').textContent = content;
});
</script>
</script>
</script>
@endsection