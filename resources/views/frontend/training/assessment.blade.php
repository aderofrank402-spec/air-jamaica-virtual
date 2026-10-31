@extends('layouts.seven.public_app')
@section('title', 'Course Assessment')

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
        'title' => 'Final Assessment',
        'icon' => 'bi bi-clipboard-check'
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

        /* Dashboard Container */
        .jamaica-dashboard {
            padding: 100px 0 3rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Assessment Container */
        .assessment-container {
            background: rgba(15, 20, 25, 0.4);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            color: var(--jamaica-white);
        }

        .assessment-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            background: rgba(26, 31, 46, 0.6);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .assessment-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--jamaica-yellow);
            margin-bottom: 0.5rem;
        }

        .assessment-subtitle {
            color: #a0a0a0;
            font-size: 1.1rem;
        }

        /* Quiz Section */
        .quiz-container {
            background: rgba(26, 31, 46, 0.6);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
        }

        .question-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .question-number {
            background: var(--jamaica-green);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 1rem;
        }

        .question-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--jamaica-white);
            flex-grow: 1;
        }

        .question-progress {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        .options-container {
            margin-bottom: 2rem;
        }

        .option-item {
            background: rgba(15, 20, 25, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .option-item:hover {
            background: rgba(0, 166, 81, 0.1);
            border-color: rgba(0, 166, 81, 0.4);
        }

        .option-item.selected {
            background: rgba(0, 166, 81, 0.2);
            border-color: var(--jamaica-green);
        }

        .option-radio {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            margin-right: 1rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .option-item.selected .option-radio {
            border-color: var(--jamaica-green);
        }

        .option-item.selected .option-radio::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background: var(--jamaica-green);
            border-radius: 50%;
        }

        .option-text {
            color: var(--jamaica-white);
            font-size: 1rem;
        }

        /* Navigation Buttons */
        .assessment-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 2rem;
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
            cursor: pointer;
        }

        .btn-nav:hover {
            background: linear-gradient(45deg, #2ECC71, var(--jamaica-green));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-nav.secondary {
            background: rgba(108, 117, 125, 0.5);
        }

        .btn-nav.secondary:hover {
            background: rgba(108, 117, 125, 0.7);
        }

        .btn-nav.submit {
            background: linear-gradient(45deg, var(--jamaica-yellow), #FFD700);
            color: var(--jamaica-dark);
        }

        .btn-nav.submit:hover {
            background: linear-gradient(45deg, #FFD700, var(--jamaica-yellow));
            color: var(--jamaica-dark);
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

        /* Assessment Info */
        .assessment-info {
            background: rgba(26, 31, 46, 0.6);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            text-align: center;
        }

        .info-item {
            padding: 1rem;
            background: rgba(15, 20, 25, 0.6);
            border-radius: 10px;
        }

        .info-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--jamaica-green);
            margin-bottom: 0.25rem;
        }

        .info-label {
            font-size: 0.8rem;
            color: #a0a0a0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
@endsection

@section('content')
<div class="jamaica-dashboard">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="assessment-container">
                    {{-- Assessment Header --}}
                    <div class="assessment-header">
                        <h1 class="assessment-title">Final Assessment</h1>
                        <p class="assessment-subtitle">{{ isset($course) ? $course->title : 'New Pilot Orientation' }}</p>
                    </div>

                    {{-- Assessment Info --}}
                    <div class="assessment-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-value">10</div>
                                <div class="info-label">Questions</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">30 min</div>
                                <div class="info-label">Time Limit</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">80%</div>
                                <div class="info-label">Pass Score</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">3</div>
                                <div class="info-label">Attempts</div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Indicator --}}
                    <div class="progress-indicator">
                        <div class="progress-text">Question 1 of 10</div>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" style="width: 10%"></div>
                        </div>
                    </div>

                    {{-- Quiz Container --}}
                    <div class="quiz-container">
                        <div class="question-header">
                            <div class="question-number">1</div>
                            <div class="question-text">What is the primary mission of Air Jamaica Virtual?</div>
                            <div class="question-progress">1 / 10</div>
                        </div>

                        <div class="options-container">
                            <div class="option-item" onclick="selectOption(this)">
                                <div class="option-radio"></div>
                                <div class="option-text">To provide the fastest virtual flights possible</div>
                            </div>

                            <div class="option-item" onclick="selectOption(this)">
                                <div class="option-radio"></div>
                                <div class="option-text">To provide a realistic, professional, and enjoyable virtual aviation experience</div>
                            </div>

                            <div class="option-item" onclick="selectOption(this)">
                                <div class="option-radio"></div>
                                <div class="option-text">To compete with other virtual airlines</div>
                            </div>

                            <div class="option-item" onclick="selectOption(this)">
                                <div class="option-radio"></div>
                                <div class="option-text">To make profits from virtual flying</div>
                            </div>
                        </div>
                    </div>

                    {{-- Assessment Navigation --}}
                    <div class="assessment-navigation">
                        <a href="{{ isset($course) ? route('frontend.training.course', $course) : route('frontend.training.courses') }}" class="btn-nav secondary">
                            <i class="fas fa-arrow-left"></i>
                            Back to Course
                        </a>
                        
                        <span class="text-muted">Make sure to answer all questions before submitting</span>
                        
                        <button class="btn-nav" onclick="nextQuestion()">
                            Next Question
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectOption(element) {
    // Remove selected class from all options
    document.querySelectorAll('.option-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Add selected class to clicked option
    element.classList.add('selected');
}

function nextQuestion() {
    // Check if an option is selected
    const selected = document.querySelector('.option-item.selected');
    if (!selected) {
        alert('Please select an answer before proceeding.');
        return;
    }
    
    // In a real implementation, this would advance to the next question
    alert('This would advance to the next question in a real implementation.');
}
</script>
@endsection