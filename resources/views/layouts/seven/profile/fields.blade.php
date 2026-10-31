<div class="row">
    <div class="col-md-6 form-group">
        <label for="name" class="form-label">
            <i class="fas fa-user me-2 text-primary"></i>{{ __('common.name') }}
        </label>
        <input type="text" name="name" id="name"
            class="form-control {{ $errors->has('name') ? ' is-invalid' : ' ' }}" 
            value="{{ $user->name }}" 
            placeholder="Enter your full name" />
        @if ($errors->has('name'))
            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="email" class="form-label">
            <i class="fas fa-envelope me-2 text-primary"></i>{{ __('common.email') }}
        </label>
        <input type="email" name="email" id="email"
            class="form-control {{ $errors->has('email') ? ' is-invalid' : ' ' }}"
            value="{{ $user->email }}" 
            placeholder="Enter your email address" />
        @if ($errors->has('email'))
            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-6 form-group">
        <label for="airline_id" class="form-label">
            <i class="fas fa-building me-2 text-primary"></i>{{ __('common.airline') }}
        </label>
        <select id="airline_id" name="airline_id" class="form-select" autocomplete="off">
            @foreach ($airlines as $airline_id => $airline_label)
                <option value="{{ $airline_id }}" @if ($user->airline_id === $airline_id) selected @endif>
                    {{ $airline_label }}</option>
            @endforeach
        </select>
        @if ($errors->has('airline_id'))
            <div class="invalid-feedback">{{ $errors->first('airline_id') }}</div>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="home_airport_id" class="form-label">
            <i class="fas fa-home me-2 text-primary"></i>{{ __('airports.home') }}
        </label>
        <select id="home_airport_id" name="home_airport_id" class="form-select" autocomplete="off">
            @foreach ($airports as $airport_id => $airport_label)
                <option value="{{ $airport_id }}" @if($user->home_airport_id == $airport_id) selected @endif>{{ $airport_label }}</option>
            @endforeach
        </select>
        @if ($errors->has('home_airport_id'))
            <div class="invalid-feedback">{{ $errors->first('home_airport_id') }}</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-6 form-group">
        <label for="country" class="form-label">
            <i class="fas fa-flag me-2 text-primary"></i>{{ __('common.country') }}
        </label>
        <select id="country" name="country" class="form-select" autocomplete="off">
            @foreach ($countries as $country_id => $country_label)
                <option value="{{ $country_id }}" @if ($user->country === $country_id) selected @endif>
                    {{ $country_label }}</option>
            @endforeach
        </select>
        @if ($errors->has('country'))
            <div class="invalid-feedback">{{ $errors->first('country') }}</div>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="timezone" class="form-label">
            <i class="fas fa-clock me-2 text-primary"></i>{{ __('common.timezone') }}
        </label>
        <select id="timezone" name="timezone" class="form-select" autocomplete="off">
            @foreach ($timezones as $group_name => $group_timezones)
                <optgroup label="{{ $group_name }}">
                    @foreach ($group_timezones as $timezone_id => $timezone_label)
                        <option value="{{ $timezone_id }}" @if ($timezone_id === $user->timezone) selected @endif>
                            {!! $timezone_label !!}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        @if ($errors->has('timezone'))
            <div class="invalid-feedback">{{ $errors->first('timezone') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-6 form-group">
        <label for="avatar" class="form-label">
            <i class="fas fa-image me-2 text-primary"></i>{{ __('profile.avatar') }}
        </label>
        
        {{-- Current Avatar Preview --}}
        @if($user->avatar || $user->getAttributes()['avatar'] ?? null)
            <div class="current-avatar-preview mb-3">
                <label class="form-label small text-muted">Current Avatar:</label>
                <div class="avatar-preview-container">
                    @php
                        try {
                            $currentAvatarPath = $user->getAttributes()['avatar'] ?? null;
                            $avatarExists = $currentAvatarPath && file_exists(public_path('uploads/' . $currentAvatarPath));
                            $hasError = false;
                        } catch (\Exception $e) {
                            $avatarExists = false;
                            $hasError = true;
                        }
                    @endphp
                    
                    @if($avatarExists && !$hasError)
                        <img src="{{ $user->resolveAvatarUrl() }}" 
                             alt="Current Avatar" 
                             class="img-thumbnail current-avatar-img"
                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                        <div class="small text-success mt-1">
                            <i class="fas fa-check-circle me-1"></i>Avatar loaded successfully
                        </div>
                    @else
                        <div class="avatar-placeholder bg-light border rounded d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user text-muted"></i>
                        </div>
                        @if($hasError)
                            <div class="small text-danger mt-1">
                                <i class="fas fa-times-circle me-1"></i>Error loading avatar
                            </div>
                        @else
                            <div class="small text-warning mt-1">
                                <i class="fas fa-exclamation-triangle me-1"></i>Using default avatar (file missing)
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @endif
        
        <input type="file" name="avatar" id="avatar"
            class="form-control {{ $errors->has('avatar') ? ' is-invalid' : ' ' }}" 
            accept="image/jpeg,image/jpg,image/png,image/gif" />
        <div class="form-text enhanced-help-text">
            <i class="fas fa-info-circle me-1 text-primary"></i>
            <strong>{{ __('profile.avatarresize', [
                'width' => config('phpvms.avatar.width', 200),
                'height' => config('phpvms.avatar.height', 200),
            ]) }}</strong>
            <br>
            <small class="text-muted">Supported formats: JPEG, PNG, GIF • Max size: {{ ini_get('upload_max_filesize') }}</small>
        </div>
        @if ($errors->has('avatar'))
            <div class="invalid-feedback">{{ $errors->first('avatar') }}</div>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="opt_in" class="form-label">
            <i class="fas fa-envelope-open me-2 text-primary"></i>{{ __('profile.opt-in') }}
        </label>
        <select id="opt_in" name="opt_in" class="form-select">
            <option value="1" @if ($user->opt_in === true) selected @endif>{{ __('common.yes') }}</option>
            <option value="0" @if ($user->opt_in === false) selected @endif>{{ __('common.no') }}</option>
        </select>
        <div class="form-text enhanced-help-text">
            <i class="fas fa-info-circle me-1 text-primary"></i>
            <strong>{{ __('profile.opt-in-descrip') }}</strong>
        </div>
        @if ($errors->has('opt_in'))
            <div class="invalid-feedback">{{ $errors->first('opt_in') }}</div>
        @endif
    </div>
</div>
{{-- Custom Fields --}}
@if($userFields && $userFields->count() > 0)
<div class="row">
    <div class="col-12">
        <h6 class="text-primary mb-3">
            <i class="fas fa-list me-2"></i>Additional Fields
        </h6>
    </div>
    @foreach ($userFields as $field)
        <div class="col-md-6 form-group">
            <label for="field_{{ $field->slug }}" class="form-label">
                <i class="fas fa-tag me-2 text-muted"></i>{{ $field->name }}
                @if ($field->required === true)
                    <span class="text-danger">*</span>
                @endif
            </label>
            @if ($field->type === 'select')
                <select id="field_{{ $field->slug }}" name="field_{{ $field->slug }}" class="form-select"
                    autocomplete="off">
                    @foreach ($field->options as $option_id => $option_label)
                        <option value="{{ $option_id }}"
                            @if ($field->value === $option_id) selected @endif>
                            {{ $option_label }}
                        </option>
                    @endforeach
                </select>
            @else
                <input type="text" name="field_{{ $field->slug }}" id="field_{{ $field->slug }}"
                    class="form-control" value="{{ $field->value }}" 
                    placeholder="Enter {{ strtolower($field->name) }}" />
            @endif
            @if ($errors->has('field_' . $field->slug))
                <div class="invalid-feedback">
                    {{ $errors->first('field_' . $field->slug) }}
                </div>
            @endif
        </div>
            @endforeach
</div>
@endif

{{-- Password Change Section --}}
<div class="row">
    <div class="col-12">
        <h6 class="text-primary mb-3">
            <i class="fas fa-lock me-2"></i>{{ __('profile.changepassword') }}
        </h6>
        <p class="text-muted small mb-3">Leave blank to keep current password</p>
    </div>
    <div class="col-md-6 form-group">
        <label for="password" class="form-label">
            <i class="fas fa-key me-2 text-primary"></i>{{ __('profile.newpassword') }}
        </label>
        <input type="password" name="password" id="password"
            class="form-control {{ $errors->has('password') ? ' is-invalid' : ' ' }}" 
            placeholder="Enter new password" />
        @if ($errors->has('password'))
            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label for="password_confirmation" class="form-label">
            <i class="fas fa-check-circle me-2 text-primary"></i>{{ __('passwords.confirm') }}
        </label>
        <input type="password" name="password_confirmation" id="password_confirmation"
            class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : ' ' }}" 
            placeholder="Confirm new password" />
        @if ($errors->has('password_confirmation'))
            <div class="invalid-feedback">
                {{ $errors->first('password_confirmation') }}
            </div>
        @endif
    </div>
</div>