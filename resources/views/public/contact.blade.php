@extends('layouts.seven.public_app')

@section('title', 'Contact Us - Air Jamaica Virtual Airlines')

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
    
    /* Contact Hero Section */
    .contact-hero {
        height: 45vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
        overflow: hidden;
        padding: 2rem 5%;
    }
    
    .contact-hero-content {
        max-width: 800px;
        z-index: 10;
    }
    
    .contact-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        background: linear-gradient(45deg, #fff, #b5d5ff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-weight: 700;
    }
    
    .contact-hero .subtitle {
        font-size: 1.3rem;
        margin-bottom: 2rem;
        opacity: 0.9;
        line-height: 1.6;
    }
    
    /* Contact Section */
    .contact-section {
        padding: 5rem 5%;
        background: rgba(10, 10, 20, 0.8);
        position: relative;
    }
    
    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: start;
    }
    
    /* Contact Information */
    .contact-info-section {
        z-index: 10;
    }
    
    .contact-info-title {
        font-size: 2.5rem;
        margin-bottom: 2rem;
        background: linear-gradient(45deg, var(--jamaica-green), var(--jamaica-yellow));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-weight: 700;
    }
    
    .contact-info-text {
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 3rem;
        opacity: 0.9;
    }
    
    .contact-info-grid {
        display: grid;
        gap: 2rem;
    }
    
    .contact-info-card {
        background: rgba(30, 30, 45, 0.8);
        border-radius: 15px;
        padding: 2rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(11, 61, 145, 0.3);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
    }
    
    .contact-info-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(11, 61, 145, 0.2);
        border-color: var(--jamaica-green);
    }
    
    .contact-info-icon {
        font-size: 2rem;
        color: var(--jamaica-green);
        flex-shrink: 0;
        margin-top: 0.5rem;
    }
    
    .contact-info-content h3 {
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
        color: var(--nasa-white);
        font-weight: 600;
    }
    
    .contact-info-content p {
        font-size: 1rem;
        opacity: 0.8;
        line-height: 1.6;
        margin-bottom: 0;
    }
    
    .contact-info-content a {
        color: var(--jamaica-green);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .contact-info-content a:hover {
        color: var(--jamaica-yellow);
    }
    
    /* Social Media Section */
    .social-section {
        margin-top: 3rem;
    }
    
    .social-title {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--nasa-white);
        font-weight: 600;
    }
    
    .social-grid {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .social-link {
        width: 50px;
        height: 50px;
        background: rgba(0, 166, 81, 0.2);
        border: 1px solid rgba(0, 166, 81, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--jamaica-green);
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1.2rem;
    }
    
    .social-link:hover {
        background: var(--jamaica-green);
        color: var(--nasa-white);
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 166, 81, 0.3);
    }
    
    /* Contact Form */
    .contact-form-section {
        background: rgba(30, 30, 45, 0.8);
        border-radius: 20px;
        padding: 3rem;
        border: 1px solid rgba(11, 61, 145, 0.3);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
    }
    
    .contact-form-title {
        font-size: 2rem;
        margin-bottom: 1rem;
        background: linear-gradient(45deg, var(--jamaica-green), var(--jamaica-yellow));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-weight: 700;
        text-align: center;
    }
    
    .contact-form-subtitle {
        text-align: center;
        font-size: 1.1rem;
        margin-bottom: 2.5rem;
        opacity: 0.8;
    }
    
    .form-group {
        margin-bottom: 2rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.8rem;
        font-weight: 600;
        color: var(--nasa-white);
        font-size: 1rem;
    }
    
    .form-control {
        width: 100%;
        padding: 1rem 1.2rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: var(--nasa-white);
        font-size: 1rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--jamaica-green);
        box-shadow: 0 0 0 3px rgba(0, 166, 81, 0.1);
        background: rgba(255, 255, 255, 0.08);
    }
    
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
    
    .submit-btn {
        width: 100%;
        padding: 1.2rem;
        background: linear-gradient(135deg, var(--jamaica-green), #34a853);
        border: none;
        border-radius: 10px;
        color: var(--nasa-white);
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 166, 81, 0.3);
        background: linear-gradient(135deg, #34a853, var(--jamaica-green));
    }
    
    /* Alert Styling */
    .alert {
        border-radius: 10px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        border: none;
        backdrop-filter: blur(10px);
    }
    
    .alert-success {
        background: rgba(0, 166, 81, 0.1);
        color: #00a651;
        border-left: 4px solid #00a651;
    }
    
    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border-left: 4px solid #dc3545;
    }
    
    .alert ul {
        margin-bottom: 0;
        padding-left: 1.5rem;
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .contact-container {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
        
        .contact-hero h1 {
            font-size: 3rem;
        }
    }
    
    @media (max-width: 768px) {
        .contact-hero {
            height: 50vh;
            padding: 1rem 5%;
        }
        
        .contact-hero h1 {
            font-size: 2.5rem;
        }
        
        .contact-hero .subtitle {
            font-size: 1.1rem;
        }
        
        .contact-section {
            padding: 3rem 1rem;
        }
        
        .contact-form-section {
            padding: 2rem;
        }
        
        .contact-info-title,
        .contact-form-title {
            font-size: 2rem;
        }
        
        .social-grid {
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<!-- NASA-Inspired 3D Globe Background -->
<div id="vanta-globe"></div>

<!-- Contact Hero Section -->
<section class="contact-hero">
    <div class="contact-hero-content">
        <h1>GET IN TOUCH</h1>
        <p class="subtitle">Connect with our global virtual aviation platform. Whether you're interested in flight simulation, need technical support, or have questions about our services, our team is ready to assist you.</p>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="contact-container">
        <!-- Contact Information -->
        <div class="contact-info-section">
            <h2 class="contact-info-title">Contact Information</h2>
            <p class="contact-info-text">Reach out to us through any of the following channels. Our support team is dedicated to providing professional assistance to our global virtual aviation community.</p>
            
            <div class="contact-info-grid">
                <div class="contact-info-card">
                    <div class="contact-info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-info-content">
                        <h3>Email Us</h3>
                        <p><a href="mailto:support@airjamaicavirtual.com">support@airjamaicavirtual.com</a></p>
                    </div>
                </div>
                
                <div class="contact-info-card">
                    <div class="contact-info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="contact-info-content">
                        <h3>Response Time</h3>
                        <p>We typically respond within<br>24-48 business hours</p>
                    </div>
                </div>
                
                <div class="contact-info-card">
                    <div class="contact-info-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="contact-info-content">
                        <h3>Community Support</h3>
                        <p>Join our community platform for<br>real-time support and discussions</p>
                    </div>
                </div>
            </div>
            
            <div class="social-section">
                <h3 class="social-title">Follow Us</h3>
                <div class="social-grid">
                    <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div class="contact-form-section">
            <h2 class="contact-form-title">Send Us a Message</h2>
            <p class="contact-form-subtitle">Complete the form below and our support team will respond within 24-48 hours</p>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="#" class="needs-validation" novalidate>
                @csrf
                
                <div class="form-group">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" 
                           id="name" name="name" placeholder="Enter your full name" 
                           value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" 
                           id="email" name="email" placeholder="Enter your email address" 
                           value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" class="form-control" 
                           id="subject" name="subject" placeholder="What is this regarding?" 
                           value="{{ old('subject') }}" required>
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">Your Message</label>
                    <textarea class="form-control" 
                              id="message" name="message" placeholder="Tell us how we can help you..." 
                              required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i>
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // NASA-Inspired 3D Globe Background
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
    
    // Smooth scrolling for anchor links
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
    
    // Form validation enhancement
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
</script>
@endsection