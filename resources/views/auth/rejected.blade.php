@extends('app')
@section('title', __('auth.registrationdenied'))

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card border-red-bottom">
        <div class="content">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="text-center">
                <div class="d-flex justify-content-center mb-4">
                  <div class="bg-danger text-white rounded-circle p-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times fa-2x"></i>
                  </div>
                </div>

                <h2 class="mb-4">{{ __('Registration Rejected') }}</h2>

                <div class="alert alert-danger" role="alert">
                  <h5 class="alert-heading">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('Your registration has been rejected') }}
                  </h5>
                  <p class="mb-3">
                    {{ __('Unfortunately, your registration with') }} <strong>{{ config('app.name') }}</strong> {{ __('has been rejected by our administrators.') }}
                  </p>
                  <p class="mb-3">
                    {{ __('If you believe this is an error or would like more information about the decision, please contact our administration team.') }}
                  </p>
                  <hr>
                  <p class="mb-0">
                    <small>
                      <i class="fas fa-envelope"></i>
                      {{ __('You may have received an email with additional details.') }}
                    </small>
                  </p>
                </div>

                <div class="card mt-4">
                  <div class="card-header">
                    <h5 class="mb-0">{{ __('What can you do?') }}</h5>
                  </div>
                  <div class="card-body text-left">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item d-flex align-items-center">
                        <span class="text-danger mr-3"><i class="fas fa-envelope"></i></span>
                        {{ __('Contact our administration team for clarification') }}
                      </li>
                      <li class="list-group-item d-flex align-items-center">
                        <span class="text-danger mr-3"><i class="fas fa-user-plus"></i></span>
                        {{ __('Review our registration requirements and try again later') }}
                      </li>
                      <li class="list-group-item d-flex align-items-center">
                        <span class="text-danger mr-3"><i class="fas fa-question-circle"></i></span>
                        {{ __('Ask for feedback on what needs to be improved') }}
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
                    {{ __('If you have questions about this decision, please don\'t hesitate to reach out to our team.') }}
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