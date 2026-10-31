@extends('layouts.seven.app')

@section('title', 'Recent Activity')

@section('css')
    <style>
        /* Jamaica Color Scheme */
        :root {
            --jamaica-green: #00a651;
            --jamaica-yellow: #ffd700;
            --jamaica-blue: #001f5f;
            --jamaica-white: #ffffff;
            --jamaica-dark: #1a1f2e;
            --jamaica-gray: #6c757d;
            --jamaica-light: #f8f9fa;
            --card-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            --card-shadow-hover: 0 12px 35px rgba(0, 0, 0, 0.25);
        }

        /* Dark animated background from sample file - Fixed for stability */
        body {
            background: linear-gradient(-45deg, #0f1419, #1a1f2e, #0f1419, #2a2f3e);
            background-size: 400% 400%;
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
        }

        /* Background animation container - separate from body */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(-45deg, #0f1419, #1a1f2e, #0f1419, #2a2f3e);
            background-size: 400% 400%;
            animation: backgroundShift 20s ease-in-out infinite;
            z-index: -2;
        }

        /* Floating Particles Effect from Sample File */
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
                opacity: 0.8;
            }
            50% { 
                opacity: 1;
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

        /* Page Container */
        .activity-page {
            padding: 100px 0 3rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Navigation Integration - Fixed positioning */
        .public-nav {
            background: linear-gradient(135deg, var(--jamaica-green) 0%, var(--jamaica-blue) 100%) !important;
            backdrop-filter: blur(10px) !important;
            border-bottom: 3px solid var(--jamaica-yellow) !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1050 !important;
        }

        /* Page Header */
        .page-header {
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

        .page-header::before {
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

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #ffffff, #00a651);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 1px;
        }

        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
            margin-bottom: 0;
            color: #a0a0a0;
        }

        /* Activity Container */
        .activity-container {
            background: rgba(15, 20, 25, 0.4);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
        }

        .activity-section {
            margin-bottom: 3rem;
        }

        .activity-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            color: var(--jamaica-green);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--jamaica-white);
            text-decoration: none;
            background: rgba(0, 166, 81, 0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            border: 1px solid rgba(0, 166, 81, 0.3);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .back-button:hover {
            background: rgba(0, 166, 81, 0.3);
            color: var(--jamaica-white);
            text-decoration: none;
            transform: translateX(-5px);
        }
    </style>
@endsection

@section('content')
<div class="activity-page">
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">Recent Activity</h1>
                    <p class="page-subtitle">
                        Latest flight operations and pilot activity across the network
                    </p>
                </div>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('frontend.dashboard.index') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        {{-- Recent PIREPs --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="activity-container">
                    <div class="activity-section">
                        <h2 class="section-title">
                            <i class="fas fa-file-alt"></i>
                            Recent PIREPs
                        </h2>
                        {{ Widget::latestPireps(['count' => 15]) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Weather Information --}}
        <div class="row">
            <div class="col-12">
                <div class="activity-container">
                    <div class="activity-section">
                        <h2 class="section-title">
                            <i class="fas fa-cloud-sun"></i>
                            Weather Information
                        </h2>
                        <div style="background: rgba(42, 47, 62, 0.7); padding: 2rem; border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.1);">
                            <div class="text-center">
                                <i class="fas fa-cloud-sun fa-3x text-primary mb-3"></i>
                                <h5 class="text-white mb-2">Weather Service</h5>
                                <p class="text-muted mb-0">Current weather conditions and forecasts</p>
                                <small class="text-muted">Check individual airport weather on the airports page</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection