@extends('layouts.seven.public_app')

@section('title', 'About Us - Air Jamaica Virtual Airlines')

@section('css')
<style>
    body {
        background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 50%, #0f1419 100%);
        color: #fff;
        min-height: 100vh;
        overflow-x: hidden;
        position: relative;
    }
    
    /* Animated background overlay */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 20% 80%, rgba(0, 166, 81, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(30, 60, 114, 0.1) 0%, transparent 50%);
        z-index: -1;
        animation: backgroundShift 20s ease-in-out infinite;
    }
    
    @keyframes backgroundShift {
        0%, 100% { opacity: 0.7; }
        50% { opacity: 1; }
    }
    
    /* About Hero Section */
    .about-hero {
        background: transparent;
        min-height: 25vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
        padding: 1.5rem 2%;
        width: 100%;
    }
    
    .about-hero-content {
        max-width: 100%;
        width: 100%;
        z-index: 10;
        background: rgba(15, 20, 25, 0.3);
        padding: 2rem;
        border-radius: 15px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin: 0 auto;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
    
    .about-hero h1 {
        font-size: 2.2rem;
        margin-bottom: 0.8rem;
        background: linear-gradient(45deg, #ffffff, #00a651);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    .about-hero .subtitle {
        font-size: 1rem;
        margin-bottom: 1rem;
        color: #a0a0a0;
        line-height: 1.5;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* About Content Section */
    .about-section {
        padding: 4rem 5%;
        background: rgba(15, 20, 25, 0.4);
        position: relative;
        backdrop-filter: blur(10px);
        border-radius: 20px;
        margin-top: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .about-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .about-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin: 2rem 0;
    }
    
    .main-content, .sidebar {
        background: rgba(26, 31, 46, 0.6);
        border-radius: 15px;
        padding: 2.5rem;
        border: 1px solid rgba(42, 47, 62, 0.3);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    
    .sidebar {
        height: fit-content;
    }
    
    .section-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1rem;
        background: linear-gradient(45deg, #ffffff, #00a651);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        letter-spacing: 0.5px;
    }
    
    .section-content {
        font-size: 1rem;
        color: #b0b0b0;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .features-list {
        list-style: none;
        margin: 1.5rem 0;
        padding: 0;
    }
    
    .features-list li {
        padding: 0.8rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        gap: 0.8rem;
        font-size: 0.95rem;
        color: #b0b0b0;
    }
    
    .features-list li:last-child {
        border-bottom: none;
    }
    
    .features-list li::before {
        content: '✓';
        color: #00a651;
        font-weight: bold;
        font-size: 1rem;
        min-width: 16px;
    }
    
    .sidebar-title {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #ffffff;
        text-align: center;
    }
    
    .stats-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .stats-list li {
        padding: 0.8rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.95rem;
        color: #b0b0b0;
    }
    
    .stats-list li:last-child {
        border-bottom: none;
    }
    
    .stats-label {
        font-weight: 500;
        color: #ffffff;
    }
    
    .stats-value {
        color: #00a651;
        font-weight: 600;
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .about-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .main-content, .sidebar {
            padding: 2rem;
        }
    }
    
    @media (max-width: 768px) {
        .about-hero {
            min-height: 20vh;
            padding: 1rem 1%;
        }
        
        .about-hero h1 {
            font-size: 1.8rem;
        }
        
        .about-hero .subtitle {
            font-size: 0.9rem;
        }
        
        .about-hero-content {
            padding: 1.5rem;
        }
        
        .about-section {
            padding: 2rem 0.5rem;
        }
        
        .main-content, .sidebar {
            padding: 1.2rem;
        }
        
        .section-title {
            font-size: 1.3rem;
        }
        
        .features-list li, .stats-list li {
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<!-- About Hero Section -->
<section class="about-hero">
    <div class="about-hero-content">
        <h1>About Air Jamaica Virtual</h1>
        <p class="subtitle">
            A global virtual airline platform connecting aviation enthusiasts worldwide through 
            realistic flight simulation and professional virtual airline operations.
        </p>
    </div>
</section>

<!-- About Content Section -->
<section class="about-section">
    <div class="about-container">
        <div class="about-grid">
            <div class="main-content">
                <h2 class="section-title">Our Story</h2>
                <p class="section-content">
                    Air Jamaica Virtual is a virtual airline simulation platform that provides aviation enthusiasts 
                    with realistic flight experiences through advanced simulation technology. We are a community-driven 
                    platform focused on delivering professional virtual airline operations for pilots worldwide.
                </p>
                
                <h2 class="section-title">Our Mission</h2>
                <p class="section-content">
                    To create a premier virtual aviation platform that connects pilots globally, offering realistic 
                    flight simulation experiences, professional training opportunities, and a supportive community 
                    environment for aviation enthusiasts of all skill levels.
                </p>
                
                <h2 class="section-title">What We Provide</h2>
                <ul class="features-list">
                    <li>Professional virtual airline simulation platform</li>
                    <li>Global route network and destinations</li>
                    <li>Diverse fleet of simulated aircraft</li>
                    <li>Virtual pilot training and certification programs</li>
                    <li>International community of aviation enthusiasts</li>
                    <li>Organized virtual events and group flights</li>
                    <li>Realistic flight planning and operational procedures</li>
                    <li>Comprehensive online support system</li>
                </ul>
                
                <h2 class="section-title">Important Notice</h2>
                <p class="section-content">
                    Air Jamaica Virtual is a flight simulation platform designed for entertainment and educational 
                    purposes only. We are not affiliated with any real airline, including the former Air Jamaica. 
                    All flights are conducted in approved flight simulation software. No real aircraft operations 
                    are conducted through this platform.
                </p>
            </div>
            
            <div class="sidebar">
                <h3 class="sidebar-title">Platform Information</h3>
                <ul class="stats-list">
                    <li>
                        <span class="stats-label">Platform Type</span>
                        <span class="stats-value">Virtual Airline</span>
                    </li>
                    <li>
                        <span class="stats-label">Service Area</span>
                        <span class="stats-value">Global</span>
                    </li>
                    <li>
                        <span class="stats-label">Simulation Focus</span>
                        <span class="stats-value">Flight Training</span>
                    </li>
                    <li>
                        <span class="stats-label">Community</span>
                        <span class="stats-value">International</span>
                    </li>
                    <li>
                        <span class="stats-label">Support</span>
                        <span class="stats-value">Online</span>
                    </li>
                    <li>
                        <span class="stats-label">Platform Status</span>
                        <span class="stats-value">Active</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection