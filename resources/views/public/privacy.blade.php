@extends('layouts.seven.public_app')

@section('title', 'Privacy Policy - Air Jamaica Virtual Airlines')

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
    
    /* Privacy Hero Section */
    .privacy-hero {
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
    
    .privacy-hero-content {
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
    
    .privacy-hero h1 {
        font-size: 2.2rem;
        margin-bottom: 0.8rem;
        background: linear-gradient(45deg, #ffffff, #00a651);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    .privacy-hero .subtitle {
        font-size: 1rem;
        margin-bottom: 1rem;
        color: #a0a0a0;
        line-height: 1.5;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Privacy Content Section */
    .privacy-section {
        padding: 4rem 5%;
        background: rgba(15, 20, 25, 0.4);
        position: relative;
        backdrop-filter: blur(10px);
        border-radius: 20px;
        margin-top: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .privacy-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .privacy-content {
        background: rgba(26, 31, 46, 0.6);
        border-radius: 15px;
        padding: 2.5rem;
        border: 1px solid rgba(42, 47, 62, 0.3);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    
    .section-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1rem;
        margin-top: 2rem;
        background: linear-gradient(45deg, #ffffff, #00a651);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        letter-spacing: 0.5px;
    }
    
    .section-title:first-child {
        margin-top: 0;
    }
    
    .section-content {
        font-size: 1rem;
        color: #b0b0b0;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .section-content p {
        margin-bottom: 1rem;
    }
    
    .last-updated {
        font-size: 0.9rem;
        color: #888;
        font-style: italic;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .privacy-hero {
            min-height: 20vh;
            padding: 1rem 1%;
        }
        
        .privacy-hero h1 {
            font-size: 1.8rem;
        }
        
        .privacy-hero .subtitle {
            font-size: 0.9rem;
        }
        
        .privacy-hero-content {
            padding: 1.5rem;
        }
        
        .privacy-section {
            padding: 2rem 0.5rem;
        }
        
        .privacy-content {
            padding: 1.2rem;
        }
        
        .section-title {
            font-size: 1.3rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Privacy Hero Section -->
<section class="privacy-hero">
    <div class="privacy-hero-content">
        <h1>Privacy Policy</h1>
        <p class="subtitle">
            Your privacy is important to us. This policy explains how we collect, use, and protect your personal information.
        </p>
    </div>
</section>

<!-- Privacy Content Section -->
<section class="privacy-section">
    <div class="privacy-container">
        <div class="privacy-content">
            <h2 class="section-title">1. Information We Collect</h2>
            <div class="section-content">
                <p>
                    We collect information that you provide directly to us when you register for an account, 
                    submit flight reports, or interact with our services. This may include:
                </p>
                <ul>
                    <li>Name, email address, and contact information</li>
                    <li>Account credentials and profile information</li>
                    <li>Flight data and pilot reports (PIREPs)</li>
                    <li>Communication preferences</li>
                </ul>
            </div>

            <h2 class="section-title">2. How We Use Your Information</h2>
            <div class="section-content">
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Provide and maintain our virtual airline services</li>
                    <li>Process and manage your flight reports and statistics</li>
                    <li>Communicate with you about your account and our services</li>
                    <li>Improve our platform and user experience</li>
                    <li>Ensure the security and integrity of our platform</li>
                </ul>
            </div>

            <h2 class="section-title">3. Information Sharing</h2>
            <div class="section-content">
                <p>
                    We do not sell, trade, or rent your personal information to third parties. We may share 
                    your information only in the following circumstances:
                </p>
                <ul>
                    <li>With your explicit consent</li>
                    <li>To comply with legal obligations</li>
                    <li>To protect our rights and safety</li>
                    <li>With service providers who assist in operating our platform (under strict confidentiality agreements)</li>
                </ul>
            </div>

            <h2 class="section-title">4. Data Security</h2>
            <div class="section-content">
                <p>
                    We implement appropriate technical and organizational measures to protect your personal 
                    information against unauthorized access, alteration, disclosure, or destruction. However, 
                    no method of transmission over the Internet is 100% secure, and we cannot guarantee 
                    absolute security.
                </p>
            </div>

            <h2 class="section-title">5. Your Rights</h2>
            <div class="section-content">
                <p>You have the right to:</p>
                <ul>
                    <li>Access and review your personal information</li>
                    <li>Request corrections to inaccurate information</li>
                    <li>Request deletion of your personal information</li>
                    <li>Opt-out of certain communications</li>
                    <li>Request a copy of your data</li>
                </ul>
            </div>

            <h2 class="section-title">6. Cookies and Tracking</h2>
            <div class="section-content">
                <p>
                    We use cookies and similar tracking technologies to enhance your experience, analyze 
                    usage patterns, and improve our services. You can control cookie preferences through 
                    your browser settings.
                </p>
            </div>

            <h2 class="section-title">7. Third-Party Links</h2>
            <div class="section-content">
                <p>
                    Our platform may contain links to third-party websites. We are not responsible for 
                    the privacy practices of these external sites. We encourage you to review their 
                    privacy policies.
                </p>
            </div>

            <h2 class="section-title">8. Children's Privacy</h2>
            <div class="section-content">
                <p>
                    Our services are not intended for individuals under the age of 13. We do not knowingly 
                    collect personal information from children under 13. If you believe we have collected 
                    such information, please contact us immediately.
                </p>
            </div>

            <h2 class="section-title">9. Changes to This Policy</h2>
            <div class="section-content">
                <p>
                    We may update this Privacy Policy from time to time. We will notify you of any changes 
                    by posting the new policy on this page and updating the "Last Updated" date. We encourage 
                    you to review this policy periodically.
                </p>
            </div>

            <h2 class="section-title">10. Contact Us</h2>
            <div class="section-content">
                <p>
                    If you have any questions about this Privacy Policy or our data practices, please contact us at:
                </p>
                <p>
                    <strong>Email:</strong> info@airjamaicavirtual.com<br>
                    <strong>Website:</strong> www.airjamaicavirtual.com
                </p>
            </div>

            <div class="last-updated">
                <p>Last Updated: {{ date('F j, Y') }}</p>
            </div>
        </div>
    </div>
</section>
@endsection

