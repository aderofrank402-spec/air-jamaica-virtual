@extends('layouts.seven.auth.auth_layout')
@section('title', __('login'))

@push('css')
<style>
  /* Vanta.js Globe Container */
  #vanta-globe {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
  }

  /* Hero Section – explicit white so Edge/Bootstrap dark theme cannot override */
  .hero-section {
    padding: 2rem 1rem;
    color: #ffffff !important;
    text-align: center;
    margin-bottom: 3rem; /* Added space below hero */
  }

  .hero-title {
    font-size: 3.5rem; /* Increased font size */
    font-weight: 800;
    margin-bottom: 1.5rem; /* Increased margin */
    color: #ffffff !important;
    text-shadow: 0 0 15px rgba(0, 166, 81, 0.5), 0 0 25px rgba(0, 166, 81, 0.3); /* Enhanced glow */
  }

  @keyframes heroGlow {
    from {
      text-shadow: 0 0 30px rgba(0, 166, 81, 0.3);
    }
    to {
      text-shadow: 0 0 50px rgba(0, 166, 81, 0.6), 0 0 60px rgba(254, 209, 0, 0.3);
    }
  }

  .hero-subtitle {
    font-size: 1.2rem;
    font-weight: 300;
    max-width: 650px;
    margin: 0 auto;
    color: #ffffff !important; /* White for visibility in all browsers (Chrome + Edge) */
    line-height: 1.7; /* Improved readability */
    letter-spacing: 0.5px; /* Improved readability */
  }

  /* Login Card */
  .login-card {
    background: rgba(10, 10, 20, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(0, 166, 81, 0.2);
    border-radius: 20px;
    padding: 3rem 2.5rem;
    box-shadow: 0 20px 60px rgba(0, 166, 81, 0.1),
      0 0 0 1px rgba(255, 255, 255, 0.05);
    position: relative;
    overflow: hidden;
    max-width: 480px;
    width: 100%;
    margin: 0 auto; /* Changed from 2rem auto */
  }

  .login-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--jamaica-green), var(--jamaica-yellow), var(--jamaica-green));
    border-radius: 20px 20px 0 0;
  }

  /* Login Header */
  .login-header {
    text-align: center;
    margin-bottom: 2.5rem;
  }

  .login-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: linear-gradient(45deg, #ffffff, #b5d5ff, #00a651);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    text-shadow: 0 0 30px rgba(0, 166, 81, 0.3);
    animation: titleGlow 3s ease-in-out infinite alternate;
  }

  @keyframes titleGlow {
    from {
      text-shadow: 0 0 20px rgba(0, 166, 81, 0.2);
    }

    to {
      text-shadow: 0 0 30px rgba(0, 166, 81, 0.4), 0 0 40px rgba(254, 209, 0, 0.2);
    }
  }

  .login-subtitle {
    color: #a0a0a0;
    font-size: 1rem;
    margin-bottom: 0;
    font-weight: 300;
  }

  /* Form Styling */
  .login-form {
    position: relative;
  }

  .form-group {
    margin-bottom: 2rem;
    position: relative;
  }

  .form-label {
    display: block;
    margin-bottom: 0.8rem;
    color: #ffffff;
    font-weight: 500;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
  }

  .form-control {
    width: 100%;
    padding: 1rem 1.25rem;
    background: rgba(15, 15, 25, 0.7);
    border: 1px solid rgba(0, 166, 81, 0.3);
    border-radius: 12px;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
  }

  .form-control:focus {
    outline: none;
    border-color: var(--jamaica-green);
    background: rgba(15, 15, 25, 0.9);
    box-shadow: 0 0 0 3px rgba(0, 166, 81, 0.1), 0 8px 25px rgba(0, 166, 81, 0.15);
    transform: translateY(-2px);
  }

  .form-control::placeholder {
    color: #666;
  }

  /* Button Styling */
  .btn-group {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
  }

  .btn-primary {
    flex: 1;
    background: linear-gradient(135deg, var(--jamaica-green), #0a8f4a);
    border: none;
    border-radius: 12px;
    padding: 1rem 2rem;
    color: #ffffff;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
  }

  .btn-primary:hover {
    background: linear-gradient(135deg, #0a8f4a, var(--jamaica-green));
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0, 166, 81, 0.3);
  }

  .btn-primary:hover::before {
    left: 100%;
  }

  .btn-secondary {
    flex: 1;
    background: rgba(30, 30, 45, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 1rem 2rem;
    color: #ffffff;
    font-weight: 500;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .btn-secondary:hover {
    background: rgba(50, 50, 65, 0.8);
    border-color: var(--jamaica-yellow);
    color: #ffffff;
    transform: translateY(-2px);
    text-decoration: none;
  }

  /* OAuth Buttons */
  .oauth-divider {
    text-align: center;
    margin: 2.5rem 0 2rem 0;
    position: relative;
  }

  .oauth-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  }

  .oauth-divider span {
    background: rgba(10, 10, 20, 0.9);
    padding: 0 1.5rem;
    color: #a0a0a0;
    font-size: 0.9rem;
    font-weight: 300;
  }

  .oauth-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
  }

  .btn-oauth {
    width: 100%;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 12px;
    color: #ffffff;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    text-decoration: none;
    position: relative;
    overflow: hidden;
  }

  .btn-oauth::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s;
  }

  .btn-oauth:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    color: #ffffff;
    text-decoration: none;
  }

  .btn-oauth:hover::before {
    left: 100%;
  }

  .btn-discord {
    background: linear-gradient(135deg, #738ADB, #5865F2);
  }

  .btn-ivao {
    background: linear-gradient(135deg, #0d2c99, #1e40af);
  }

  .btn-vatsim {
    background: linear-gradient(135deg, #29B473, #10B981);
  }

  /* Forgot Password Link */
  .forgot-password {
    text-align: center;
    margin-top: 1.5rem;
  }

  .forgot-password a {
    color: var(--jamaica-green);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    position: relative;
  }

  .forgot-password a::after {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--jamaica-green);
    transition: width 0.3s ease;
  }

  .forgot-password a:hover {
    color: #0a8f4a;
    text-decoration: none;
  }

  .forgot-password a:hover::after {
    width: 100%;
  }

  /* Register Link */
  .register-link {
    text-align: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
  }

  .register-link p {
    margin: 0;
    color: #a0a0a0;
  }

  .register-link a {
    color: var(--jamaica-yellow);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .register-link a:hover {
    color: #ffffff;
    text-decoration: underline;
  }

  /* Error Alert */
  .alert-danger {
    background: rgba(252, 61, 33, 0.1);
    border: 1px solid rgba(252, 61, 33, 0.3);
    border-radius: 12px;
    color: #ff6b6b;
    padding: 1rem 1.25rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .login-card {
      padding: 2rem 1.5rem;
      margin: 1rem;
    }

    .login-title {
      font-size: 2rem;
    }

    .btn-group {
      flex-direction: column;
    }
  }

  
</style>
@endpush

@section('content')
<div id="vanta-globe"></div>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-12">
      <div class="hero-section" style="color: #ffffff;">
        <h1 class="hero-title" style="color: #ffffff !important;">Welcome to Air Jamaica Virtual – Your Gateway to the Skies</h1>
        <p class="hero-subtitle" style="color: #ffffff !important;">Join a global community of pilots and embark on a journey of virtual aviation, exploring new horizons and connecting the world with every flight.</p>
      </div>
      <div class="login-card">
        <div class="login-header">
          <h1 class="login-title">Log In</h1>
          <p class="login-subtitle">Ready for takeoff?</p>
        </div>

        <form method="post" action="{{ url('/login') }}" class="login-form">
          @csrf

          <div class="form-group">
            <label for="email" class="form-label">Email or Pilot ID</label>
            <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email or pilot ID">
            @if ($errors->has('email'))
            <span class="invalid-feedback">
              <strong>{{ $errors->first('email') }}</strong>
            </span>
            @endif
          </div>

          <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Enter your password">
            @if ($errors->has('password'))
            <span class="invalid-feedback">
              <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
          </div>

          @php
          $captchaEnabled = (bool) setting('captcha.enabled', false);
          $captchaSiteKey = setting('captcha.site_key', '');
          @endphp

          @if($captchaEnabled && !empty($captchaSiteKey))
          <div class="form-group">
            <label for="g-recaptcha" class="form-label">@lang('auth.fillcaptcha')</label>
            <div class="g-recaptcha" data-sitekey="{{ $captchaSiteKey }}" id="recaptcha-container"></div>
            <div id="recaptcha-status" style="color: #ffcc00; font-size: 0.8rem; margin-top: 5px; display: none;">
              Loading reCAPTCHA...
            </div>
            @error('g-recaptcha-response')
            <span class="invalid-feedback" style="display: block; color: #ff6b6b;">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              // Check reCAPTCHA loading status
              var captchaContainer = document.getElementById('recaptcha-container');
              var statusMessage = document.getElementById('recaptcha-status');
              
              if (statusMessage) {
                statusMessage.style.display = 'block';
                
                // Set a timer to check if reCAPTCHA loaded
                setTimeout(function() {
                  if (captchaContainer && !captchaContainer.querySelector('iframe')) {
                    // If no iframe, reCAPTCHA didn't load properly
                    statusMessage.innerHTML = 'reCAPTCHA not loading. Attempting to reload...';
                    statusMessage.style.color = '#ff6b6b';
                    
                    // Try to load reCAPTCHA manually
                    var script = document.createElement('script');
                    script.src = "https://www.google.com/recaptcha/api.js";
                    script.async = true;
                    script.defer = true;
                    document.head.appendChild(script);
                    
                    script.onload = function() {
                      statusMessage.innerHTML = 'reCAPTCHA loaded successfully!';
                      statusMessage.style.color = '#4caf50';
                      setTimeout(function() { 
                        statusMessage.style.display = 'none'; 
                      }, 2000);
                    };
                  } else {
                    // reCAPTCHA loaded successfully
                    statusMessage.style.display = 'none';
                  }
                }, 2000);
              }
            });
          </script>
          @endif

          <div class="btn-group">
            <button type="submit" class="btn btn-primary w-100">Log In</button>
          </div>

          @if(config('oauth.enabled'))
          <div class="oauth-divider"><span>OR</span></div>
          <div class="oauth-buttons">
            @foreach(config('oauth.providers') as $provider => $config)
            @if($config['enabled'])
            <a href="{{ url('/oauth/'.$provider) }}" class="btn btn-oauth btn-{{ $provider }}">
              <i class="fab fa-{{ $provider }}"></i>
              <span>Login with {{ ucfirst($provider) }}</span>
            </a>
            @endif
            @endforeach
          </div>
          @endif

          <div class="forgot-password">
            <a href="{{ url('/password/reset') }}">Forgot Password?</a>
          </div>

          <div class="register-link">
            <p>Don't have an account? <a href="{{ url('/register') }}">Sign Up</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
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
      color: 0x00a651,
      color2: 0xffd700,
      backgroundColor: 0x0b1426,
      size: 1.2
    });

    // Check if reCAPTCHA is loaded properly
    setTimeout(function() {
      var recaptchaContainer = document.getElementById('recaptcha-container');
      var statusMessage = document.getElementById('recaptcha-status');
      
      if (recaptchaContainer && !recaptchaContainer.querySelector('iframe') && statusMessage) {
        console.log("reCAPTCHA not loaded - attempting to reload");
        
        // Try direct script injection if needed
        if (typeof grecaptcha === 'undefined') {
          var recaptchaScript = document.createElement('script');
          recaptchaScript.src = "https://www.google.com/recaptcha/api.js";
          recaptchaScript.async = true;
          recaptchaScript.defer = true;
          document.head.appendChild(recaptchaScript);
          
          statusMessage.innerHTML = "Loading reCAPTCHA directly... Please wait.";
        }
      } else if (recaptchaContainer && recaptchaContainer.querySelector('iframe') && statusMessage) {
        // Hide the status message if reCAPTCHA loaded successfully
        statusMessage.style.display = "none";
      }
    }, 2000);
  });
</script>
@endpush

