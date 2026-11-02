<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Controller;
use App\Exceptions\PilotIdNotFound;
use App\Models\Enums\UserState;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect after logging in
     */
    protected mixed $redirectTo = '/dashboard';

    private string $loginFieldValue;

    /**
     * LoginController constructor.
     */
    public function __construct(
        private readonly UserService $userSvc
    ) {
        $this->redirectTo = config('ajv.login_redirect');
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get the needed authorization credentials from the request.
     * Overriding the value from the trait
     *
     * @override
     */
    protected function credentials(Request $request): array
    {
        return [
            'email'    => $this->loginFieldValue,
            'password' => $request->input('password'),
        ];
    }

    /**
     * Validate the user login request.
     *
     * @override
     */
    protected function validateLogin(Request $request): void
    {
        $id_field = $request->input('email');
        $validations = ['required', 'string'];

        /*
         * Trying to login by email or not?
         *
         * If not, run a validation rule which attempts to split the user by their VA and ID
         * Then inject that user's email into the request
         */
        if (strpos($id_field, '@') !== false) {
            $validations[] = 'email';
            $this->loginFieldValue = $request->input('email');
        } else {
            $validations[] = function ($attr, $value, $fail) use ($request) {
                try {
                    $user = $this->userSvc->findUserByPilotId($value);
                } catch (PilotIdNotFound $ex) {
                    Log::warning('Error logging in, pilot_id not found, id='.$value);
                    $fail(__('auth.failed'));

                    return;
                }

                $request->email = $user->email;
                $this->loginFieldValue = $user->email;
            };
        }

        $validationRules = [
            'email'    => $validations,
            'password' => 'required|string',
        ];

        // Add reCAPTCHA validation if enabled
        $captchaEnabled = (bool) setting('captcha.enabled', false);
        $captchaSecretKey = setting('captcha.secret_key', '');

        
        if ($captchaEnabled && !empty($captchaSecretKey)) {
            $validationRules['g-recaptcha-response'] = [
                'required',
                function ($attribute, $value, $fail) use ($captchaSecretKey) {
                    if (empty($value)) {
                        return $fail(__('validation.custom.g-recaptcha-response.required') ?? 'Captcha is required.');
                    }
                    try {
                        // Create a custom HTTP client with more flexible settings
                        $client = new \GuzzleHttp\Client([
                            'timeout' => 10,
                            'connect_timeout' => 10,
                            'verify' => true, // Try true first
                            'http_errors' => false,
                        ]);
                        
                        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                            'form_params' => [
                                'secret'   => $captchaSecretKey,
                                'response' => $value,
                                'remoteip' => request()->ip(),
                            ],
                        ]);
                        
                        $result = json_decode((string)$response->getBody(), true);
                        
                        if (!$result || !isset($result['success'])) {
                            Log::error('Captcha response parsing error', ['response' => (string)$response->getBody()]);
                            return $fail('Could not verify captcha. Please try again.');
                        }
                        
                        if ($result['success'] !== true) {
                            $codes = $result['error-codes'] ?? [];
                            Log::error('Captcha failed', ['google_response' => $result]);
                            if (config('app.debug') && !empty($codes)) {
                                return $fail('Captcha failed: '.implode(', ', (array) $codes));
                            }
                            $fail('Captcha verification failed, please try again.');
                        }
                    } catch (\Exception $e) {
                        Log::error('Captcha HTTP error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                        
                        // Try again with SSL verification disabled if that might be the issue
                        try {
                            $client = new \GuzzleHttp\Client([
                                'timeout' => 10,
                                'connect_timeout' => 10,
                                'verify' => false, // Disable SSL verification as fallback
                                'http_errors' => false,
                            ]);
                            
                            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                                'form_params' => [
                                    'secret'   => $captchaSecretKey,
                                    'response' => $value,
                                    'remoteip' => request()->ip(),
                                ],
                            ]);
                            
                            $result = json_decode((string)$response->getBody(), true);
                            
                            if (!$result || !isset($result['success'])) {
                                Log::error('Captcha response parsing error (fallback)', ['response' => (string)$response->getBody()]);
                                return $fail('Could not verify captcha. Please try again.');
                            }
                            
                            if ($result['success'] !== true) {
                                Log::error('Captcha failed (fallback)', ['google_response' => $result]);
                                return $fail('Captcha verification failed, please try again.');
                            }
                            
                            // If we got here, the fallback worked
                            Log::info('Captcha verification succeeded using fallback method');
                            return;
                        } catch (\Exception $e2) {
                            Log::error('Captcha HTTP error (fallback attempt)', ['error' => $e2->getMessage()]);
                            return $fail('Could not connect to verification service. Please try again later.');
                        }
                    }
                },
            ];
        }

        $request->validate($validationRules);
    }

    protected function sendLoginResponse(Request $request): RedirectResponse|View
    {
        /** @var User $user */
        $user = Auth::user();

        if (setting('general.record_user_ip', true)) {
            $user->last_ip = $request->ip();
            $user->lastlogin_at = Carbon::now();
            $user->save();
        }

        if ($user->state !== UserState::ACTIVE && $user->state !== UserState::ON_LEAVE) {
            Log::info('Trying to login '.$user->ident.', state '.UserState::label($user->state));

            // Log them out
            $this->guard()->logout();
            $request->session()->invalidate();

            // Redirect to one of the error pages
            if ($user->state === UserState::PENDING) {
                return view('auth.pending');
            }

            if ($user->state === UserState::REJECTED) {
                return view('auth.rejected');
            }

            if ($user->state === UserState::SUSPENDED) {
                return view('auth.suspended');
            }
        }

        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        return redirect()->intended($this->redirectPath());
    }
}
