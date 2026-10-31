@extends('layouts.seven.public_app')
@section('title', 'Training Module')

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
        'title' => 'Training Center',
        'url' => route('frontend.training.index'),
        'icon' => 'bi bi-mortarboard'
    ],
    [
        'title' => 'Training Courses',
        'url' => route('frontend.training.courses'),
        'icon' => 'bi bi-book'
    ],
    [
        'title' => isset($course) ? $course->title : 'Course Details',
        'url' => isset($course) ? route('frontend.training.course', $course) : '#',
        'icon' => 'bi bi-journal-text'
    ],
    [
        'title' => 'Module ' . ($moduleId ?? '1'),
        'icon' => 'bi bi-file-text'
    ]
];
@endphp
@include('layouts.seven.components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

@section('css')
    {{-- Air Jamaica Virtual Dashboard Styles --}}
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
            padding-top: 76px !important; /* Account for fixed navbar */
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

        /* Dashboard Container */
        .jamaica-dashboard {
            padding: 100px 0 3rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Module Content */
        .module-content {
            background: rgba(15, 20, 25, 0.4);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            color: var(--jamaica-white);
        }

        .module-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            background: rgba(26, 31, 46, 0.6);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .module-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--jamaica-yellow);
            margin-bottom: 0.5rem;
        }

        .module-subtitle {
            color: #a0a0a0;
            font-size: 1.1rem;
        }

        .content-section {
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--jamaica-green);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(0, 166, 81, 0.3);
        }

        .content-text {
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1.5rem;
        }

        /* Navigation Buttons */
        .module-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-nav {
            background: linear-gradient(45deg, var(--jamaica-green), #2ECC71);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-nav:hover {
            background: linear-gradient(45deg, #2ECC71, var(--jamaica-green));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-nav.disabled {
            background: rgba(108, 117, 125, 0.5);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-nav.disabled:hover {
            background: rgba(108, 117, 125, 0.5);
            transform: none;
            box-shadow: none;
        }

        /* Progress Indicator */
        .progress-indicator {
            text-align: center;
            background: rgba(26, 31, 46, 0.6);
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
        }

        .progress-bar-container {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            height: 8px;
            margin: 1rem 0;
            overflow: hidden;
        }

        .progress-bar-fill {
            background: linear-gradient(90deg, var(--jamaica-green), var(--jamaica-yellow));
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .progress-text {
            color: var(--jamaica-yellow);
            font-weight: 600;
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('content')
<div class="jamaica-dashboard">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="module-content">
                    {{-- Module Header --}}
                    <div class="module-header">
                        <h1 class="module-title">Module {{ $moduleId ?? '1' }}: Introduction</h1>
                        <p class="module-subtitle">Welcome to your training journey with Air Jamaica Virtual</p>
                    </div>

                    {{-- Progress Indicator --}}
                    <div class="progress-indicator">
                        <div class="progress-text">Module Progress: 0%</div>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" style="width: 0%"></div>
                        </div>
                    </div>

                    {{-- Module Content --}}
                    <div class="content-section">
                        <h2 class="section-title">Learning Objectives</h2>
                        <div class="content-text">
                            <p>By the end of this module, you will be able to:</p>
                            <ul>
                                <li>Understand the core values and mission of Air Jamaica Virtual</li>
                                <li>Navigate the pilot portal and essential systems</li>
                                <li>Identify key operational procedures and standards</li>
                                <li>Recognize safety protocols and reporting procedures</li>
                            </ul>
                        </div>
                    </div>

                    <div class="content-section">
                        <h2 class="section-title">Introduction to Air Jamaica Virtual</h2>
                        <div class="content-text">
                            <p>Welcome to Air Jamaica Virtual, where aviation passion meets professional excellence. As a virtual airline, we strive to provide the most realistic and engaging flight simulation experience while maintaining the highest standards of professionalism and safety.</p>
                            
                            <p>Our commitment to excellence extends beyond simulation - we foster a community of aviation enthusiasts who share knowledge, experience, and a genuine love for flying. Whether you're a seasoned virtual pilot or just beginning your journey, Air Jamaica Virtual provides the platform and support to help you grow.</p>
                            
                            <p>Throughout this orientation course, you'll learn about our operational procedures, fleet information, route network, and the tools available to help you succeed as a member of our virtual airline family.</p>
                        </div>
                    </div>

                    <div class="content-section">
                        <h2 class="section-title">Our Mission & Values</h2>
                        <div class="content-text">
                            <p><strong>Mission:</strong> To provide a realistic, professional, and enjoyable virtual aviation experience that promotes learning, skill development, and camaraderie among aviation enthusiasts worldwide.</p>
                            
                            <p><strong>Core Values:</strong></p>
                            <ul>
                                <li><strong>Professionalism:</strong> Maintaining high standards in all operations</li>
                                <li><strong>Safety:</strong> Prioritizing safety in every aspect of virtual flight</li>
                                <li><strong>Excellence:</strong> Striving for continuous improvement and quality</li>
                                <li><strong>Community:</strong> Building lasting relationships with fellow aviators</li>
                                <li><strong>Education:</strong> Promoting learning and skill development</li>
                            </ul>
                        </div>
                    </div>

                    <div class="content-section">
                        <h2 class="section-title">Getting Started</h2>
                        <div class="content-text">
                            <p>As a new pilot with Air Jamaica Virtual, you'll have access to:</p>
                            <ul>
                                <li>Comprehensive training programs</li>
                                <li>Detailed aircraft documentation and procedures</li>
                                <li>Route planning tools and resources</li>
                                <li>Community forums and support channels</li>
                                <li>Regular events and group flights</li>
                                <li>Progress tracking and achievement system</li>
                            </ul>
                            
                            <p>Take your time to explore these resources and don't hesitate to reach out to our training team or experienced pilots if you have any questions.</p>
                        </div>
                    </div>

                    {{-- Module Navigation --}}
                    <div class="module-navigation">
                        <a href="{{ isset($course) ? route('frontend.training.course', $course) : route('frontend.training.courses') }}" class="btn-nav">
                            <i class="fas fa-arrow-left"></i>
                            Back to Course
                        </a>
                        
                        <span class="text-muted">Module 1 of 6</span>
                        
                        <a href="#" class="btn-nav">
                            Next Module
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection