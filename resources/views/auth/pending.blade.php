@extends('layouts.seven.auth.auth_layout')
@section('title', 'Registration Pending Approval')

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

  /* Pending Card */
  .pending-card {
    background: rgba(10, 10, 20, 0.9);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(0, 166, 81, 0.3);
    border-radius: 20px;
    padding: 3rem 2.5rem;
    box-shadow: 0 20px 60px rgba(0, 166, 81, 0.1),
      0 0 0 1px rgba(255, 255, 255, 0.05);
    position: relative;
    overflow: hidden;
    color: white;
  }

  .pending-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--jamaica-green), var(--jamaica-yellow), var(--jamaica-green));
    border-radius: 20px 20px 0 0;
  }

  .status-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--jamaica-yellow), #ffa500);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(254, 209, 0, 0.4); }
    70% { box-shadow: 0 0 0 20px rgba(254, 209, 0, 0); }
    100% { box-shadow: 0 0 0 0 rgba(254, 209, 0, 0); }
  }

  .pending-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: linear-gradient(45deg, #ffffff, #b5d5ff, #00a651);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    text-align: center;
  }

  .alert-custom {
    background: rgba(254, 209, 0, 0.1);
    border: 1px solid rgba(254, 209, 0, 0.3);
    border-radius: 15px;
    padding: 1.5rem;
    margin: 2rem 0;
  }

  .step-item {
    background: rgba(0, 166, 81, 0.1);
    border: 1px solid rgba(0, 166, 81, 0.2);
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
  }

  .step-number {
    background: var(--jamaica-green);
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 1rem;
    flex-shrink: 0;
  }

  .btn-custom {
    background: linear-gradient(135deg, var(--jamaica-green), #0a8f4a);
    border: none;
    border-radius: 12px;
    padding: 0.75rem 2rem;
    color: white;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    margin: 0.5rem;
  }

  .btn-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 166, 81, 0.3);
    color: white;
    text-decoration: none;
  }

  .btn-outline-custom {
    background: transparent;
    border: 2px solid var(--jamaica-green);
    border-radius: 12px;
    padding: 0.75rem 2rem;
    color: var(--jamaica-green);
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    margin: 0.5rem;
  }

  .btn-outline-custom:hover {
    background: var(--jamaica-green);
    color: white;
    text-decoration: none;
  }
</style>
@endsection

@section('content')
<div id="vanta-globe"></div>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-12">
      <div class="pending-card">
        <div class="text-center">
          <div class="status-icon">
            <i class="fas fa-clock fa-3x text-white"></i>
          </div>
          
          <h1 class="pending-title">Registration Pending</h1>
          
          <div class="alert-custom">
            <h4 class="mb-3" style="color: var(--jamaica-yellow);">
              <i class="fas fa-paper-plane"></i> 
              Application Submitted Successfully!
            </h4>
            <p class="mb-3" style="font-size: 1.1rem;">
              Thank you for registering with <strong>Air Jamaica Virtual Airlines</strong>!
            </p>
            <p class="mb-3">
              Your pilot application has been received and is currently under review by our administrative team. 
              You will receive an email notification once your account has been reviewed.
            </p>
            <hr style="border-color: rgba(254, 209, 0, 0.3);">
            <p class="mb-0">
              <i class="fas fa-envelope" style="color: var(--jamaica-green);"></i> 
              A confirmation email has been sent to your registered email address.
            </p>
          </div>

          <div class="mt-4">
            <h5 class="mb-3" style="color: var(--jamaica-green);">What happens next?</h5>
            
            <div class="step-item">
              <div class="step-number">1</div>
              <div>Our administrators will carefully review your application and pilot credentials</div>
            </div>
            
            <div class="step-item">
              <div class="step-number">2</div>
              <div>You will receive an email notification with our decision within 24-48 hours</div>
            </div>
            
            <div class="step-item">
              <div class="step-number">3</div>
              <div>If approved, you can log in and start your virtual aviation journey with us</div>
            </div>
          </div>

          <div class="mt-4 text-center">
            <a href="{{ url('/') }}" class="btn-custom">
              <i class="fas fa-home"></i> Return to Homepage
            </a>
            
            <a href="{{ route('login') }}" class="btn-outline-custom">
              <i class="fas fa-sign-in-alt"></i> Try Login
            </a>
          </div>

          <div class="mt-4" style="color: #a0a0a0;">
            <small>
              <i class="fas fa-info-circle"></i>
              This process typically takes 24-48 hours. If you have questions, contact support@airjamaicavirtual.com
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
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
  });
</script>
@endsection