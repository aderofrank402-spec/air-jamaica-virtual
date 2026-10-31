@extends('layouts.seven.public_app')
@section('title', $sectionTitle . ' - Operations Manual')

{{-- Breadcrumb Navigation --}}
@php
$breadcrumbs = [
    [
        'title' => 'Pilot Center',
        'url' => Auth::check() ? route('frontend.dashboard.index') : route('frontend.home'),
        'icon' => Auth::check() ? 'fas fa-tachometer-alt' : 'fas fa-home'
    ],
    [
        'title' => 'Operations Manual',
        'url' => route('frontend.operations.index'),
        'icon' => 'fas fa-book'
    ],
    [
        'title' => $sectionTitle,
        'icon' => 'fas fa-file-alt'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])

@section('css')
    {{-- Air Jamaica Virtual Operations Manual Section Styles --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css" rel="stylesheet">

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
            --gradient-gold: linear-gradient(135deg, #FFD700, #FFA500);
            --gradient-green: linear-gradient(135deg, #00A651, #20c997);
            --gradient-blue: linear-gradient(135deg, #0056b3, #3399ff);
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

        /* Animated Background Effects */
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

        /* Operations Manual Container */
        .jamaica-operations {
            padding: 120px 0 3rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Breadcrumb positioning */
        .breadcrumb {
            position: relative;
            z-index: 1000;
            margin-top: 80px;
            margin-bottom: 2rem;
        }

        /* Content Layout */
        .manual-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
            align-items: start;
        }

        /* Table of Contents */
        .toc-sidebar {
            position: sticky;
            top: 100px;
            background: rgba(26, 31, 46, 0.8);
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(15px);
            max-height: calc(100vh - 150px);
            overflow-y: auto;
        }

        .toc-title {
            color: var(--jamaica-white);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--jamaica-green);
        }

        .toc-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .toc-item {
            margin-bottom: 0.5rem;
        }

        .toc-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 8px;
            display: block;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .toc-link:hover,
        .toc-link.active {
            background: rgba(0, 166, 81, 0.2);
            color: var(--jamaica-white);
            text-decoration: none;
        }

        /* Content Area */
        .content-area {
            background: rgba(26, 31, 46, 0.8);
            border-radius: 20px;
            padding: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(15px);
            color: var(--jamaica-white);
            line-height: 1.7;
        }

        .content-area h1 {
            color: var(--jamaica-white);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid var(--jamaica-green);
        }

        .content-area h2 {
            color: var(--jamaica-white);
            font-size: 1.8rem;
            font-weight: 600;
            margin: 2.5rem 0 1.5rem 0;
            padding-left: 1rem;
            border-left: 4px solid var(--jamaica-yellow);
        }

        .content-area h3 {
            color: var(--jamaica-white);
            font-size: 1.4rem;
            font-weight: 600;
            margin: 2rem 0 1rem 0;
        }

        .content-area h4 {
            color: var(--jamaica-white);
            font-size: 1.2rem;
            font-weight: 600;
            margin: 1.5rem 0 1rem 0;
        }

        .content-area p {
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 1.5rem;
        }

        .content-area ul,
        .content-area ol {
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }

        .content-area li {
            margin-bottom: 0.5rem;
        }

        /* Special Boxes */
        .info-box {
            background: rgba(0, 123, 255, 0.1);
            border-left: 4px solid #007bff;
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 0 12px 12px 0;
        }

        .warning-box {
            background: rgba(255, 193, 7, 0.1);
            border-left: 4px solid #ffc107;
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 0 12px 12px 0;
        }

        .danger-box {
            background: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 0 12px 12px 0;
        }

        .success-box {
            background: rgba(40, 167, 69, 0.1);
            border-left: 4px solid #28a745;
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 0 12px 12px 0;
        }

        .box-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Code Blocks */
        .code-block {
            background: #2d3748;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            overflow-x: auto;
            color: #e2e8f0;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        }

        /* Tables */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            overflow: hidden;
        }

        .content-table th {
            background: var(--jamaica-green);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .content-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.85);
        }

        .content-table tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Navigation */
        .section-nav {
            display: flex;
            justify-content: space-between;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            background: var(--gradient-green);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
            color: white;
            text-decoration: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .manual-layout {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .toc-sidebar {
                position: relative;
                top: 0;
                max-height: none;
            }
            
            .content-area {
                padding: 2rem 1.5rem;
            }
            
            .content-area h1 {
                font-size: 2rem;
            }
        }
    </style>
@endsection

@section('content')

<div class="jamaica-operations">
    <div class="container">
        <div class="manual-layout">
            {{-- Table of Contents Sidebar --}}
            <div class="toc-sidebar">
                <h4 class="toc-title">
                    <i class="fas fa-list me-2"></i>Contents
                </h4>
                <ul class="toc-list" id="tocList">
                    {{-- TOC will be generated by JavaScript --}}
                </ul>
                
                <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                    <a href="{{ route('frontend.operations.index') }}" class="nav-btn" style="background: var(--gradient-blue); width: 100%; justify-content: center;">
                        <i class="fas fa-arrow-left"></i>
                        Back to Manual
                    </a>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="content-area">
                @if($section === 'general-operations')
                    @include('operations.sections.general-operations')
                @elseif($section === 'flight-procedures')
                    @include('operations.sections.flight-procedures')
                @elseif($section === 'aircraft-procedures')
                    @include('operations.sections.aircraft-procedures')
                @elseif($section === 'route-network')
                    @include('operations.sections.route-network')
                @elseif($section === 'administrative')
                    @include('operations.sections.administrative')
                @elseif($section === 'quality-assurance')
                    @include('operations.sections.quality-assurance')
                @elseif($section === 'emergency-procedures')
                    @include('operations.sections.emergency-procedures')
                @elseif($section === 'technical-operations')
                    @include('operations.sections.technical-operations')
                @else
                    <h1>Section Not Found</h1>
                    <p>The requested section could not be found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Table of Contents --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate Table of Contents
    const tocList = document.getElementById('tocList');
    const headings = document.querySelectorAll('.content-area h2, .content-area h3');
    
    headings.forEach((heading, index) => {
        // Create anchor link
        const id = 'heading-' + index;
        heading.id = id;
        
        // Create TOC item
        const li = document.createElement('li');
        li.className = 'toc-item';
        
        const link = document.createElement('a');
        link.href = '#' + id;
        link.className = 'toc-link';
        link.textContent = heading.textContent;
        
        if (heading.tagName === 'H3') {
            link.style.paddingLeft = '1.5rem';
            link.style.fontSize = '0.8rem';
        }
        
        li.appendChild(link);
        tocList.appendChild(li);
    });
    
    // Smooth scrolling for TOC links
    document.querySelectorAll('.toc-link').forEach(link => {
        link.addEventListener('click', function(e) {
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
    
    // Highlight active section
    function updateActiveToc() {
        const scrollPos = window.scrollY + 150;
        const tocLinks = document.querySelectorAll('.toc-link');
        
        tocLinks.forEach(link => {
            link.classList.remove('active');
            const target = document.querySelector(link.getAttribute('href'));
            if (target && target.offsetTop <= scrollPos && (target.offsetTop + target.offsetHeight) > scrollPos) {
                link.classList.add('active');
            }
        });
    }
    
    window.addEventListener('scroll', updateActiveToc);
    updateActiveToc();
});
</script>

@endsection