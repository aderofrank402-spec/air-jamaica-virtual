<footer class="footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">
                    <h3>Air Jamaica Virtual Airlines</h3>
                    <p>Experience the pinnacle of global virtual aviation excellence</p>
                </div>
                <div class="footer-social">
                    <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('frontend.about') }}">About Us</a></li>
                    <li><a href="{{ route('frontend.contact') }}">Contact</a></li>
                    <li><a href="{{ route('frontend.livemap.index') }}">Live Map</a></li>
                    <li><a href="{{ url('/register') }}">Join Us</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Pilot Portal</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('frontend.partners') }}">Partners</a></li>
                    <li><a href="{{ route('frontend.pilots.index') }}">Pilots</a></li>
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                    @if (Auth::check())
                        <li><a href="{{ route('frontend.dashboard.index') }}">Dashboard</a></li>
                    @endif
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Contact Info</h4>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@airjamaicavirtual.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-globe"></i>
                        <span>www.airjamaicavirtual.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Houston, Texas</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p>&copy; {{ date('Y') }} Air Jamaica Virtual Airlines. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="{{ route('frontend.privacy') }}">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>
