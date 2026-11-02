<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Controller;
use App\Models\Enums\UserState;
use App\Models\Invite;
use App\Models\User;
use App\Models\UserField;
use App\Models\UserFieldValue;
use App\Notifications\Messages\AdminUserRegistered;
use App\Notifications\Messages\UserPending;
use App\Repositories\AirlineRepository;
use App\Repositories\AirportRepository;
use App\Services\UserService;
use App\Support\Countries;
use App\Support\HttpClient;
use App\Support\Timezonelist;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\View\View;
use RuntimeException;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     * Note: This is overridden in the register method to show pending page
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * RegisterController constructor.
     */
    public function __construct(
        private readonly AirlineRepository $airlineRepo,
        private readonly AirportRepository $airportRepo,
        private readonly HttpClient $httpClient,
        private readonly UserService $userService,
    ) {
        $this->middleware('guest');

        // Users will be redirected to dashboard after approval
        $this->redirectTo = '/dashboard';
    }

    public function showRegistrationForm(Request $request): View
    {
        if (setting('general.disable_registrations', false)) {
            abort(403, 'Registrations are disabled');
        }

        if (setting('general.invite_only_registrations', false)) {
            if (!$request->has('invite') && !$request->has('token')) {
                abort(403, 'Registrations are invite only');
            }

            $invite = Invite::find($request->get('invite'));
            if (!$invite || $invite->token !== $request->get('token')) {
                abort(403, 'Invalid invite');
            }

            if ($invite->usage_limit && $invite->usage_count >= $invite->usage_limit) {
                abort(403, 'Invite has been used too many times');
            }

            if ($invite->expires_at && $invite->expires_at->isPast()) {
                abort(403, 'Invite has expired');
            }
        }

        $airlines = $this->airlineRepo->selectBoxList();
        $userFields = UserField::where(['show_on_registration' => true, 'active' => true, 'internal' => false])->get();

        // Load airports - either all airports or only hubs based on setting
        $hubs_only = true; // Always show only hubs
        $airports = $this->airportRepo->selectBoxList(false, $hubs_only);
        
        // Added reCAPTCHA configuration
        $captchaEnabled = (bool) setting('captcha.enabled', false);
        $captchaSiteKey = setting('captcha.site_key', '');
        $captchaSecretKey = setting('captcha.secret_key', '');        return view('auth.register', [
            'airports'   => $airports,
            'airlines'   => $airlines,
            'countries'  => Countries::getSelectList(),
            'timezones'  => Timezonelist::toArray(),
            'userFields' => $userFields,
            'hubs_only'  => $hubs_only,
            'invite'     => $invite ?? null,
            'captcha'    => [
                'enabled'    => $captchaEnabled && !empty($captchaSiteKey),
                'site_key'   => $captchaSiteKey,
                'secret_key' => $captchaSecretKey,
            ],
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data): Validator
    {
        $rules = [
            'name'            => 'required|max:255',
            'email'           => 'required|email|max:255|unique:users,email',
            'airline_id'      => 'required',
            'home_airport_id' => 'required',
            'password'        => 'required|min:5|confirmed',
            'toc_accepted'    => 'accepted',
        ];

        // Dynamically add the required fields
        $userFields = UserField::where([
            'show_on_registration' => true,
            'required'             => true,
            'internal'             => false,
            'active'               => true,
        ])->get();

        foreach ($userFields as $field) {
            $rules['field_'.$field->slug] = 'required';
        }

        // ReCAPTCHA validation
        $captchaEnabled = (bool) setting('captcha.enabled', false);
        $captchaSecretKey = setting('captcha.secret_key', '');

        
        if ($captchaEnabled && !empty($captchaSecretKey)) {
            $rules['g-recaptcha-response'] = [
                'required',
                function ($attribute, $value, $fail) use ($captchaSecretKey) {
                    if (empty($value)) {
                        return $fail(__('validation.custom.g-recaptcha-response.required') ?? 'Captcha is required.');
                    }
                    try {
                        $response = Http::asForm()->timeout(10)->post(
                            'https://www.google.com/recaptcha/api/siteverify',
                            [
                                'secret'   => $captchaSecretKey,
                                'response' => $value,
                                'remoteip' => request()->ip(),
                            ]
                        );
                        $result = $response->json();

                        if (!$response->successful() || !is_array($result) || !array_key_exists('success', $result)) {
                            Log::error('Captcha response error', ['status' => $response->status()]);
                            return $fail('Could not verify captcha. Please try again.');
                        }

                        if ($result['success'] !== true) {
                            Log::warning('Captcha verification rejected', ['error_codes' => $result['error-codes'] ?? []]);
                            return $fail('Captcha verification failed, please try again.');
                        }
                    } catch (\Throwable $e) {
                        Log::error('Captcha verification unavailable', ['error' => $e->getMessage()]);
                        return $fail('Could not connect to verification service. Please try again later.');
                    }
                },
            ];
        }

        return ValidatorFacade::make($data, $rules);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @throws Exception
     * @throws RuntimeException
     */
    protected function create(Request $request): User
    {
        if (setting('general.disable_registrations', false)) {
            abort(403, 'Registrations are disabled');
        }

        if (setting('general.invite_only_registrations', false)) {
            if (!$request->has('invite') && !$request->has('invite_token')) {
                abort(403, 'Registrations are invite only');
            }

            $invite = Invite::find($request->get('invite'));
            if (!$invite || $invite->token !== base64_decode($request->get('invite_token'))) {
                abort(403, 'Invalid invite');
            }

            if ($invite->usage_limit && $invite->usage_count >= $invite->usage_limit) {
                abort(403, 'Invite has been used too many times');
            }

            if ($invite->expires_at && $invite->expires_at->isPast()) {
                abort(403, 'Invite has expired');
            }

            if ($invite->email && $invite->email !== $request->get('email')) {
                abort(403, 'Invite is for a different email address');
            }

            $invite->update([
                'usage_count' => $invite->usage_count + 1,
            ]);
        }

        // Default options
        $opts = $request->all();
        $opts['password'] = Hash::make($opts['password']);
        
        // Force user to be in PENDING state regardless of auto_accept setting
        $opts['state'] = UserState::PENDING;

        if (setting('general.record_user_ip', true)) {
            $opts['last_ip'] = $request->ip();
        }

        // Convert transfer hours into minutes
        if (isset($opts['transfer_time'])) {
            $opts['transfer_time'] *= 60;
        }

        $user = $this->userService->createUser($opts, [], UserState::PENDING);

        Log::info('User registered: ', $user->toArray());

        $userFields = UserField::where(['show_on_registration' => true, 'active' => true, 'internal' => false])->get();
        foreach ($userFields as $field) {
            $field_name = 'field_'.$field->slug;
            UserFieldValue::updateOrCreate([
                'user_field_id' => $field->id,
                'user_id'       => $user->id,
            ], ['value' => $opts[$field_name]]);
        }

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     *
     * @throws Exception
     */
    public function register(Request $request): RedirectResponse|View
    {
        $this->validator($request->all())->validate();

        // Create the user - the create method will ensure PENDING state
        $user = $this->create($request);
        
        // Notifications are handled automatically by NotificationEventsHandler
        // when the Registered event is fired by UserService->createUser()
        
        Log::info('User registration completed', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_state' => UserState::label($user->state),
            'user_ident' => $user->ident
        ]);
        
        // Always show the pending page to notify user that their account requires approval
        return view('auth.pending');
    }
}
