@extends('layouts.seven.public_app')

@section('title', 'Our Partners - Air Jamaica Virtual Airlines')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>

<style>
    :root {
        --nasa-blue: #0B3D91;
        --nasa-red: #FC3D21;
        --nasa-white: #FFFFFF;
        --nasa-dark: #0A0A1A;
        --sky-gradient: linear-gradient(135deg, #1a73e8, #34a853);
        --jamaica-green: #00A651;
        --jamaica-yellow: #FED100;
        --jamaica-black: #000000;
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', 'NASA Sans', sans-serif;
    }
    
    body {
        background-color: var(--nasa-dark);
        color: var(--nasa-white);
        overflow-x: hidden;
    }
    
    #vanta-globe {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
    
    /* Hero Section */
    .partners-hero {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5%;
        position: relative;
        overflow: hidden;
    }
    
    .hero-content {
        text-align: center;
        max-width: 900px;
        z-index: 10;
        position: relative;
    }
    
    .hero-title {
        font-size: 5rem;
        font-weight: 900;
        margin-bottom: 2rem;
        background: linear-gradient(45deg, #ffffff, #b5d5ff, #00a651, #fed100);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        letter-spacing: 3px;
        text-shadow: 0 0 30px rgba(0, 166, 81, 0.3);
        animation: glow 3s ease-in-out infinite alternate;
    }
    
    @keyframes glow {
        from {
            text-shadow: 0 0 30px rgba(0, 166, 81, 0.3);
        }
        to {
            text-shadow: 0 0 40px rgba(0, 166, 81, 0.6), 0 0 60px rgba(254, 209, 0, 0.3);
        }
    }
    
    .hero-subtitle {
        font-size: 1.4rem;
        color: #a0a0a0;
        margin-bottom: 3rem;
        line-height: 1.8;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        font-weight: 300;
    }
    
    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-top: 3rem;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #00a651;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* Partners Section */
    .partners-section {
        padding: 6rem 5%;
        background: rgba(15, 20, 25, 0.8);
        position: relative;
        backdrop-filter: blur(10px);
    }
    
    .section-header {
        text-align: center;
        margin-bottom: 4rem;
    }
    
    .section-title {
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
        background: linear-gradient(45deg, #ffffff, #00a651);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        letter-spacing: 1px;
    }
    
    .section-subtitle {
        font-size: 1.1rem;
        color: #a0a0a0;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }
    
    .partners-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .partners-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        margin: 3rem 0;
    }
    
    .partner-card {
        background: rgba(26, 31, 46, 0.9);
        border-radius: 16px;
        padding: 2rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(42, 47, 62, 0.5);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    
    .partner-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #00a651, #fed100, #00a651);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .partner-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        border-color: #00a651;
    }
    
    .partner-card:hover::before {
        transform: scaleX(1);
    }
    
    .partner-logo-container {
        text-align: center;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .partner-logo {
        max-height: 100px;
        width: auto;
        object-fit: contain;
        transition: all 0.3s ease;
        filter: brightness(0.9) contrast(1.1);
        border-radius: 8px;
        padding: 0.8rem;
        background: rgba(255, 255, 255, 0.05);
    }
    
    .partner-card:hover .partner-logo {
        transform: scale(1.05);
        filter: brightness(1.1) contrast(1.1);
        box-shadow: 0 4px 15px rgba(0, 166, 81, 0.2);
    }
    
    .partner-name {
        font-size: 1.4rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 1rem;
        text-align: center;
        letter-spacing: 0.5px;
    }
    
    .partner-description {
        font-size: 0.95rem;
        color: #b0b0b0;
        line-height: 1.5;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .partner-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #00a651, #34a853);
        color: white;
        padding: 0.7rem 1.2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 100%;
        justify-content: center;
        font-size: 0.9rem;
    }
    
    .partner-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 166, 81, 0.3);
        color: white;
    }
    
    /* Partnership Inquiry Section */
    .partnership-section {
        padding: 6rem 5%;
        background: linear-gradient(135deg, rgba(26, 31, 46, 0.9) 0%, rgba(10, 10, 26, 0.9) 100%);
        position: relative;
        backdrop-filter: blur(15px);
    }
    
    .partnership-container {
        width: 100%;
        margin: 0 auto;
        text-align: center;
    }
    
    .partnership-card {
        background: rgba(26, 31, 46, 0.95);
        border-radius: 20px;
        padding: 3rem 5%;
        border: 1px solid rgba(42, 47, 62, 0.6);
        backdrop-filter: blur(15px);
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        width: 100%;
    }
    
    .partnership-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #ffffff;
        letter-spacing: 1px;
    }
    
    .partnership-description {
        font-size: 1.1rem;
        color: #a0a0a0;
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }
    
    .partnership-buttons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #00a651, #34a853);
        color: white;
        padding: 1rem 2rem;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(0, 166, 81, 0.3);
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 166, 81, 0.4);
        color: white;
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .partners-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 3rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .hero-stats {
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .partners-section {
            padding: 4rem 1rem;
        }
        
        .partnership-section {
            padding: 4rem 1rem;
        }
        
        .partners-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .partner-card {
            padding: 1.5rem;
        }
        
        .partnership-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .partnership-card {
            padding: 2rem;
        }
        
        .section-title {
            font-size: 2.2rem;
        }
        
        .partnership-title {
            font-size: 2rem;
        }
    }
</style>
@endsection

@section('content')
<div id="vanta-globe"></div>

<!-- Hero Section -->
<section class="partners-hero">
    <div class="hero-content">
        <h1 class="hero-title">Our Partners</h1>
        <p class="hero-subtitle">
            Collaborating with industry leaders to deliver exceptional virtual aviation experiences. 
            Our partnerships enable us to provide the most realistic and comprehensive flight simulation environment.
        </p>
        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-number">10+</div>
                <div class="stat-label">Partners</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Support</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100%</div>
                <div class="stat-label">Quality</div>
            </div>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section class="partners-section">
    <div class="partners-container">
        <div class="section-header">
            <h2 class="section-title">Industry Leaders</h2>
            <p class="section-subtitle">
                We partner with the world's leading aviation and simulation companies to provide 
                the most authentic and comprehensive virtual flying experience.
            </p>
        </div>

        <div class="partners-grid">
            <!-- VATSIM -->
            <div class="partner-card">
                <div class="partner-logo-container">
                    <img 
                        src="{{ public_asset('/assets/img/vatsim-logo.jpg') }}" 
                        alt="VATSIM" 
                        class="partner-logo"
                        onerror="this.style.display='none'" 
                    >
                </div>
                <h3 class="partner-name">VATSIM</h3>
                <p class="partner-description">Virtual Air Traffic Simulation Network - Our primary network for online flying with real-time ATC.</p>
                <a href="https://www.vatsim.net/" target="_blank" class="partner-link">
                    <i class="fas fa-external-link-alt"></i>
                    Visit Website
                </a>
            </div>

            <!-- FLIGHT SIM -->
            <div class="partner-card">
                <div class="partner-logo-container">
                    <img 
                        src="{{ public_asset('/assets/img/fs.png') }}" 
                        alt="Flight Sim" 
                        class="partner-logo"
                        onerror="this.style.display='none'"
                    >
                </div>
                <h3 class="partner-name">Flight Sim</h3>
                <p class="partner-description">International Virtual Aviation Organisation - Alternative network for virtual flights and training.</p>
                <a href="https://www.flightsim.com/" target="_blank" class="partner-link">
                    <i class="fas fa-external-link-alt"></i>
                    Visit Website
                </a>
            </div>

            <!-- FLIGHT SIM LAB -->
            <div class="partner-card">
                <div class="partner-logo-container">
                    <img 
                        src="{{ public_asset('/assets/img/fsl.png') }}" 
                        alt="Flight Sim Lab" 
                        class="partner-logo"
                        onerror="this.style.display='none'"
                    >
                </div>
                <h3 class="partner-name">Flight Sim Lab</h3>
                <p class="partner-description">Premium aircraft add-ons and simulation software for the most realistic flying experience.</p>
                <a href="https://www.flightsimlabs.com/" target="_blank" class="partner-link">
                    <i class="fas fa-external-link-alt"></i>
                    Visit Website
                </a>
            </div>

            <!-- AEROSOFT -->
            <div class="partner-card">
                <div class="partner-logo-container">
                    <img 
                        src="{{ public_asset('/assets/img/aerosoft.png') }}" 
                        alt="aerosoft" 
                        class="partner-logo"
                        onerror="this.style.display='none'"
                    >
                </div>
                <h3 class="partner-name">Aerosoft</h3>
                <p class="partner-description">Leading developer of high-quality aircraft, airports, and simulation software.</p>
                <a href="https://www.aerosoft.com/en/" target="_blank" class="partner-link">
                    <i class="fas fa-external-link-alt"></i>
                    Visit Website
                </a>
            </div>

            <!-- PMDG -->
            <div class="partner-card">
                <div class="partner-logo-container">
                    <img 
                        src="{{ public_asset('/assets/img/pmdg.png') }}" 
                        alt="pmdg" 
                        class="partner-logo"
                        onerror="this.style.display='none'"
                    >
                </div>
                <h3 class="partner-name">PMDG</h3>
                <p class="partner-description">Ultra-realistic aircraft simulations with study-level systems and procedures.</p>
                <a href="https://pmdg.com/" target="_blank" class="partner-link">
                    <i class="fas fa-external-link-alt"></i>
                    Visit Website
                </a>
            </div>

            <!-- PREPAR3D -->
            <div class="partner-card">
                <div class="partner-logo-container">
                    <img 
                        src="{{ public_asset('/assets/img/prepar3d.png') }}" 
                        alt="prepar3d" 
                        class="partner-logo"
                        onerror="this.style.display='none'"
                    >
                </div>
                <h3 class="partner-name">Prepar3D</h3>
                <p class="partner-description">Professional flight simulation platform for training and entertainment.</p>
                <a href="https://www.prepar3d.com/" target="_blank" class="partner-link">
                    <i class="fas fa-external-link-alt"></i>
                    Visit Website
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Partnership Inquiry Section -->
<section class="partnership-section">
    <div class="partnership-container">
        <div class="partnership-card">
            <h2 class="partnership-title">Become a Partner</h2>
            <p class="partnership-description">
                Interested in partnering with Air Jamaica Virtual? We're always looking to collaborate with organizations 
                that share our passion for aviation and commitment to excellence. Join our network of industry leaders 
                and help us create the most immersive virtual aviation experience.
            </p>
            <div class="partnership-buttons">
                <a href="{{ url('/contact') }}" class="btn-primary-custom">
                    <i class="fas fa-handshake"></i>
                    Contact Us for Partnership
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    VANTA.GLOBE({
        el: "#vanta-globe",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 200.00,
        minWidth: 200.00,
        scale: 1.00,
        scaleMobile: 1.00,
        color: 0x00A651,
        backgroundColor: 0x0A0A1A,
        size: 1.00
    });
});
</script>
@endsection