@extends('layouts.seven.auth.auth_layout')
@section('title', __('auth.register'))

@section('head')
@if (isset($captcha) && $captcha['enabled'] && !empty($captcha['site_key']))
<script src="https://www.google.com/recaptcha/api.js"></script>
@endif
@endsection

@section('css')
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

  /* Hero Section */
  .hero-section {
    padding: 2rem 1rem;
    color: #ffffff;
    text-align: center;
    margin-bottom: 3rem;
  }

  .hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    color: #ffffff;
    text-shadow: 0 0 15px rgba(0, 166, 81, 0.5), 0 0 25px rgba(0, 166, 81, 0.3);
  }

  .hero-subtitle {
    font-size: 1.2rem;
    font-weight: 300;
    max-width: 650px;
    margin: 0 auto;
    color: #d1d1d1;
    line-height: 1.7;
    letter-spacing: 0.5px;
  }

  /* Registration Card */
  .register-card {
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
    width: 100%;
    margin: 0 auto;
  }

  .register-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--jamaica-green), var(--jamaica-yellow), var(--jamaica-green));
    border-radius: 20px 20px 0 0;
  }

  /* Header */
  .register-header {
    text-align: center;
    margin-bottom: 2.5rem;
  }

  .register-title {
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
    from { text-shadow: 0 0 20px rgba(0, 166, 81, 0.2); }
    to { text-shadow: 0 0 30px rgba(0, 166, 81, 0.4), 0 0 40px rgba(254, 209, 0, 0.2); }
  }

  .register-subtitle {
    color: #a0a0a0;
    font-size: 1rem;
    margin-bottom: 0;
    font-weight: 300;
  }

  /* Form Styling */
  .form-group {
    margin-bottom: 1.5rem;
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

  .form-control, .form-select {
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
  
  .form-control:focus, .form-select:focus {
    outline: none;
    border-color: var(--jamaica-green);
    background: rgba(15, 15, 25, 0.9);
    box-shadow: 0 0 0 3px rgba(0, 166, 81, 0.1), 0 8px 25px rgba(0, 166, 81, 0.15);
    transform: translateY(-2px);
  }

  .form-control::placeholder { color: #666; }

  /* Tom Select / Select2 */
  .ts-control {
    background: rgba(15, 15, 25, 0.7) !important;
    border: 1px solid rgba(0, 166, 81, 0.3) !important;
    border-radius: 12px !important;
    color: #ffffff !important;
  }
  .ts-control .item { color: #ffffff !important; }
  .ts-dropdown {
    background: #0f172a !important;
    border: 1px solid var(--jamaica-green) !important;
  }
  .ts-dropdown .option { color: #ffffff !important; }
  .ts-dropdown .active { background-color: var(--jamaica-green) !important; }

  /* Button */
  .btn-primary {
    width: 100%;
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
  }

  .btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0, 166, 81, 0.3);
  }

  /* Terms and Captcha */
  .form-check-label { color: #a0a0a0; }
  .form-check-input:checked {
    background-color: var(--jamaica-green);
    border-color: var(--jamaica-green);
  }

  /* Login Link */
  .login-link {
    text-align: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
  }
  .login-link p { margin: 0; color: #a0a0a0; }
  .login-link a {
    color: var(--jamaica-yellow);
    text-decoration: none;
    font-weight: 600;
  }
  .login-link a:hover { color: #ffffff; text-decoration: underline; }

</style>
@endsection

@section('content')
<div id="vanta-globe"></div>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-12">
      <div class="hero-section">
        <h1 class="hero-title">Create Your Account</h1>
        <p class="hero-subtitle">Join our community of virtual pilots and explore the world from your cockpit.</p>
      </div>
      <div class="register-card">
        <div class="register-header">
          <h1 class="register-title">Pilot Registration</h1>
          <p class="register-subtitle">Fill out the form below to begin your journey.</p>
        </div>
                <!-- reCAPTCHA Debug -->
        @if (isset($captcha) && $captcha['enabled'] === true)
        <div style="background:#1a1a1a; border:1px solid #333; color:#ccc; padding:10px; font-size:12px; line-height:1.3; border-radius:8px; margin-bottom:20px;">
            <strong>reCAPTCHA Status:</strong><br>
            Enabled: {{ $captcha['enabled'] ? 'Yes' : 'No' }}<br>
            Site Key: {{ !empty($captcha['site_key']) ? 'Present ('.substr($captcha['site_key'], 0, 6).'...)' : 'Missing' }}<br>
            Secret Key: {{ !empty($captcha['secret_key']) ? 'Present ('.strlen($captcha['secret_key']).' chars)' : 'Missing' }}<br>
        </div>
        @endif
        @include('layouts.seven.auth.register')
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
<script>
  // Debug reCAPTCHA
  window.addEventListener('load', function() {
    console.log('reCAPTCHA Debug: Checking status');
    setTimeout(function() {
      var captchaDiv = document.querySelector('.g-recaptcha');
      if (captchaDiv) {
        console.log('reCAPTCHA Debug: Container found');
        if (!captchaDiv.querySelector('iframe')) {
          console.log('reCAPTCHA Debug: No iframe found, attempting to reload');
          // Try to reload reCAPTCHA
          var recaptchaScript = document.createElement('script');
          recaptchaScript.src = 'https://www.google.com/recaptcha/api.js';
          document.head.appendChild(recaptchaScript);
          
          // Add status message
          var statusDiv = document.getElementById('recaptcha-status');
          if (statusDiv) {
            statusDiv.innerHTML = 'Loading reCAPTCHA... If it doesn\'t appear, please refresh the page.';
            statusDiv.style.color = '#ffcc00';
          }
        } else {
          console.log('reCAPTCHA Debug: reCAPTCHA widget loaded successfully');
          var statusDiv = document.getElementById('recaptcha-status');
          if (statusDiv) {
            statusDiv.style.display = 'none';
          }
        }
      } else {
        console.log('reCAPTCHA Debug: Container not found');
      }
    }, 2000);
  });
</script>
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

    // Initialize TomSelect for form fields
    if (window.TomSelect) {
      new TomSelect('#airline_id');
      new TomSelect('#country');
      new TomSelect('#timezone');
      
      // Airport search select with API integration
      document.querySelectorAll('select.airport_search').forEach(function(element) {
        if (!element.tomselect) {
          new TomSelect(element, {
            valueField: 'id',
            labelField: 'description',
            searchField: 'description',
            load: function(query, callback) {
              var url = new URL('{{ Config::get("app.url") }}/api/airports/search');
              var params = {
                search: query,
                hubs: element.classList.contains('hubs_only') ? 1 : 0,
                page: 1,
                orderBy: 'id',
                sortedBy: 'asc'
              };
              Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
              fetch(url)
                .then(response => response.json())
                .then(json => callback(json.data))
                .catch(() => callback());
            }
          });
        }
      });
    }
  });
</script>
@include('scripts.airport_search')
@endsection
