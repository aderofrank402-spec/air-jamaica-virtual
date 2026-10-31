@extends('app')
@section('title', __('auth.suspended'))

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card border-orange-bottom">
        <div class="content">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="text-center">
                <div class="d-flex justify-content-center mb-4">
                  <div class="bg-warning text-white rounded-circle p-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-ban fa-2x"></i>
                  </div>
                </div>

                <h2 class="mb-4">{{ __('Account Suspended') }}</h2>

                <div class="alert alert-warning" role="alert">
                  <h5 class="alert-heading">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('Your account has been suspended') }}
                  </h5>
                  <p class="mb-3">
                    {{ __('Your account with') }} <strong>{{ config('app.name') }}</strong> {{ __('has been temporarily suspended.') }}
                  </p>
                  <p class="mb-3">
                    {{ __('This suspension may be due to a violation of our terms of service or community guidelines.') }}
                  </p>
                  <hr>
                  <p class="mb-0">
                    <small>
                      <i class="fas fa-envelope"></i>
                      {{ __('Check your email for more details about this suspension.') }}
                    </small>
                  </p>
                </div>

                <div class="card mt-4">
                  <div class="card-header">
                    <h5 class="mb-0">{{ __('Next Steps') }}</h5>
                  </div>
                  <div class="card-body text-left">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item d-flex align-items-center">
                        <span class="text-warning mr-3"><i class="fas fa-envelope"></i></span>
                        {{ __('Contact administration to understand the reason for suspension') }}
                      </li>
                      <li class="list-group-item d-flex align-items-center">
                        <span class="text-warning mr-3"><i class="fas fa-clock"></i></span>
                        {{ __('Wait for the suspension period to end (if temporary)') }}
                      </li>
                      <li class="list-group-item d-flex align-items-center">
                        <span class="text-warning mr-3"><i class="fas fa-handshake"></i></span>
                        {{ __('Follow any provided instructions to resolve the issue') }}
                      </li>
                    </ul>
                  </div>
                </div>

                <div class="mt-4">
                  <a href="{{ route('frontend.index') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> {{ __('Return to Homepage') }}
                  </a>
                </div>

                <div class="mt-4 text-muted">
                  <small>
                    <i class="fas fa-info-circle"></i>
                    {{ __('For immediate assistance, please contact our administration team.') }}
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection