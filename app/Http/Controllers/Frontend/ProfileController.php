<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Events\ProfileUpdated;
use App\Models\User;
use App\Models\UserField;
use App\Models\UserFieldValue;
use App\Repositories\AirlineRepository;
use App\Repositories\AirportRepository;
use App\Repositories\UserRepository;
use App\Support\Countries;
use App\Support\Timezonelist;
use App\Support\Utils;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use Laracasts\Flash\Flash;
use Nwidart\Modules\Facades\Module;
use Prettus\Validator\Exceptions\ValidatorException;

class ProfileController extends Controller
{
    /**
     * ProfileController constructor.
     */
    public function __construct(
        private readonly AirlineRepository $airlineRepo,
        private readonly AirportRepository $airportRepo,
        private readonly UserRepository $userRepo
    ) {}

    /**
     * Return whether the vmsACARS module is enabled or not
     */
    private function acarsEnabled(): bool
    {
        // Is the ACARS module enabled?
        $acars_enabled = false;
        $acars = Module::find('VMSAcars');
        if ($acars) {
            $acars_enabled = $acars->isEnabled();
        }

        return $acars_enabled;
    }

    /**
     * Redirect to show() since only a single page gets shown and the template controls
     * the other items that are/aren't shown
     */
    public function index(): View
    {
        return $this->show(Auth::user()->id);
    }

    public function show(int $id): RedirectResponse|View
    {
        $with = [
            'airline',
            'awards',
            'current_airport',
            'fields.field',
            'home_airport',
            'last_pirep',
            'rank',
            'typeratings',
            'journal', // Add financial data
            'bids',    // Add current bids
        ];
        /** @var ?User $user */
        $user = User::with($with)->where('id', $id)->first();

        if (!$user) {
            Flash::error('User not found!');

            return redirect(route('frontend.dashboard.index'));
        }

        $userFields = $this->userRepo->getUserFields($user, true);

        // Get additional profile data
        $profileData = $this->getEnhancedProfileData($user);

        return view('profile.index', [
            'user'       => $user,
            'userFields' => $userFields,
            'acars'      => $this->acarsEnabled(),
            'recent_pireps' => $profileData['recent_pireps'],
            'flight_stats' => $profileData['flight_stats'],
            'career_progress' => $profileData['career_progress'],
        ]);
    }

    /**
     * Get enhanced data for profile display
     */
    private function getEnhancedProfileData(User $user): array
    {
        // Get recent PIREPs
        $recent_pireps = $user->pireps()
            ->with(['flight', 'aircraft', 'dpt_airport', 'arr_airport'])
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get();

        // Calculate flight statistics
        $flight_stats = [
            'avg_score' => $user->pireps()->where('score', '>', 0)->avg('score') ?? 0,
            'total_distance' => $user->pireps()->sum('distance') ?? 0,
            'avg_landing_rate' => $user->pireps()
                ->whereNotNull('landing_rate')
                ->where('landing_rate', '>', -1000)
                ->avg('landing_rate') ?? 0,
            'countries_visited' => $user->pireps()
                ->join('airports as arr', 'pireps.arr_airport_id', '=', 'arr.id')
                ->distinct('arr.country')
                ->count('arr.country') ?? 0,
        ];

        // Career progression data
        $career_progress = [
            'next_rank' => $this->getNextRank($user),
            'rank_progress' => $this->calculateRankProgress($user),
            'recent_achievements' => $user->awards()
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get(),
        ];

        return [
            'recent_pireps' => $recent_pireps,
            'flight_stats' => $flight_stats,
            'career_progress' => $career_progress,
        ];
    }

    /**
     * Get the next rank for progression
     */
    private function getNextRank(User $user)
    {
        $currentRank = $user->rank;
        if (!$currentRank) {
            return null;
        }

        return \App\Models\Rank::where('hours', '>', $currentRank->hours)
            ->orderBy('hours', 'asc')
            ->first();
    }

    /**
     * Calculate rank progression percentage
     */
    private function calculateRankProgress(User $user): int
    {
        $currentRank = $user->rank;
        $nextRank = $this->getNextRank($user);
        
        if (!$currentRank || !$nextRank) {
            return 100; // Max rank achieved
        }

        $currentHours = $user->flight_time / 60; // Convert to hours
        $rankRange = $nextRank->hours - $currentRank->hours;
        $progress = ($currentHours - $currentRank->hours) / $rankRange;
        
        return min(100, max(0, (int)($progress * 100)));
    }

    /**
     * Show the edit for form the user's profile
     *
     *
     * @throws Exception
     */
    public function edit(Request $request, User $profile): RedirectResponse|View
    {
        // Security check: users can only edit their own profile
        if ($profile->id !== Auth::id()) {
            Flash::error('You can only edit your own profile!');
            return redirect(route('frontend.profile.index'));
        }

        Log::info('Profile edit page requested for user: '.$profile->id);

        /** @var User $user */
        $user = User::with('fields.field', 'home_airport')->where('id', $profile->id)->first();

        if (empty($user)) {
            Flash::error('User not found!');
            Log::error('User not found in profile edit: '.$profile->id);
            return redirect(route('frontend.dashboard.index'));
        }

        // Get all airports for the dropdown - check if only hubs should be shown
        $hubs_only = setting('pilots.home_hubs_only');
        if ($hubs_only) {
            $airports = $this->airportRepo->whereOrder(['hub' => true], 'id')->pluck('id', 'id')->toArray();
            $airports = array_map(function($icao) {
                $airport = $this->airportRepo->find($icao);
                return $airport ? $icao . ' - ' . $airport->name : $icao;
            }, $airports);
        } else {
            $airports = $this->airportRepo->selectBoxList(true);
        }

        $airlines = $this->airlineRepo->selectBoxList();
        $userFields = $this->userRepo->getUserFields($user);

        Log::info('Rendering profile.edit view for user: '.$user->id);

        return view('profile.edit', [
            'user'       => $user,
            'airlines'   => $airlines,
            'airports'   => $airports,
            'hubs_only'  => setting('pilots.home_hubs_only'),
            'countries'  => Countries::getSelectList(),
            'timezones'  => Timezonelist::toArray(),
            'userFields' => $userFields,
        ]);
    }

    /**
     * @throws ValidatorException
     */
    public function update(Request $request): RedirectResponse
    {
        $id = Auth::user()->id;
        $user = $this->userRepo->findWithoutFail($id);

        $rules = [
            'name'       => 'required',
            'email'      => 'required|unique:users,email,'.$id,
            'airline_id' => 'required',
            'password'   => 'confirmed',
            'avatar'     => 'nullable|mimes:jpeg,png,jpg',
        ];

        $userFields = UserField::where(
            ['show_on_registration' => true, 'required' => true, 'internal' => false]
        )->get();
        foreach ($userFields as $field) {
            $rules['field_'.$field->slug] = 'required';
        }

        $validator = Validator::make($request->toArray(), $rules);

        if ($validator->fails()) {
            Log::info('validator failed for user '.$user->ident);
            Log::info($validator->errors()->toArray());

            return redirect(route('frontend.profile.edit', $id))
                ->withErrors($validator)
                ->withInput();
        }

        $req_data = $request->all();
        if (!$request->filled('password')) {
            unset($req_data['password']);
        } else {
            $req_data['password'] = Hash::make($req_data['password']);
        }

        if ($request->hasFile('avatar')) {
            try {
                // Delete old avatar if it exists
                if ($user->avatar !== null) {
                    $oldAvatarPath = public_path('uploads/'.$user->avatar);
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath);
                        Log::info('Deleted old avatar: '.$oldAvatarPath);
                    }
                }

                $avatar = $request->file('avatar');
                
                // Add timestamp to filename for cache-busting and uniqueness
                $timestamp = time();
                $extension = $avatar->getClientOriginalExtension();
                $file_name = $user->ident . '_' . $timestamp . '.' . $extension;
                
                // Ensure the avatars directory exists
                $avatarsDir = public_path('uploads/avatars');
                if (!is_dir($avatarsDir)) {
                    if (!mkdir($avatarsDir, 0755, true)) {
                        throw new \Exception('Could not create avatars directory. Please check permissions.');
                    }
                }

                // Check if directory is writable
                if (!is_writable($avatarsDir)) {
                    throw new \Exception('Avatars directory is not writable. Please check permissions for: ' . $avatarsDir);
                }

                // Create the avatar, resizing it and keeping the aspect ratio.
                $w = config('ajv.avatar.width', 200);
                $h = config('ajv.avatar.height', 200);

                $canvas = Image::canvas($w, $h);
                $image = Image::make($avatar)->resize($w, $h, static function ($constraint) {
                    $constraint->aspectRatio();
                });

                $canvas->insert($image);
                Log::info('Processing avatar for upload to: '.$avatarsDir);
                
                $fullPath = $avatarsDir.'/'.$file_name;
                
                // Check available disk space (at least 10MB)
                $freeSpace = disk_free_space($avatarsDir);
                if ($freeSpace !== false && $freeSpace < 10 * 1024 * 1024) {
                    throw new \Exception('Insufficient disk space for avatar upload.');
                }
                
                // Save the processed image
                $canvas->save($fullPath);
                
                // Verify the file was created successfully
                if (!file_exists($fullPath)) {
                    throw new \Exception('Avatar file was not created successfully.');
                }
                
                // Set proper file permissions
                chmod($fullPath, 0644);
                
                // Also try to save to production paths if they exist (for shared hosting)
                $productionPaths = [
                    base_path('../public_html/uploads/avatars/'.$file_name),
                    base_path('../../public_html/uploads/avatars/'.$file_name),
                ];
                
                foreach ($productionPaths as $prodPath) {
                    $prodDir = dirname($prodPath);
                    if (is_dir($prodDir) && is_writable($prodDir)) {
                        try {
                            $canvas->save($prodPath);
                            chmod($prodPath, 0644);
                            Log::info('Avatar also saved to production path: ' . $prodPath);
                        } catch (\Exception $e) {
                            Log::warning('Could not save avatar to production path: ' . $prodPath . ' - ' . $e->getMessage());
                        }
                    }
                }

                // Store just the path relative to uploads directory
                $req_data['avatar'] = "avatars/$file_name";
                Log::info('Avatar uploaded successfully: '.$fullPath);
                flash()->success('Avatar uploaded successfully!');
                
            } catch (\Exception $e) {
                Log::error('Avatar upload failed: '.$e->getMessage());
                flash()->error('Avatar upload failed: '.$e->getMessage().'. Please contact support if this issue persists.');
                return redirect()->back()->withInput();
            }
        }

        // User needs to verify their new email address
        if ($user->email != $request->input('email')) {
            $req_data['email_verified_at'] = null;
        }

        // Log the avatar data being saved for debugging
        if (isset($req_data['avatar'])) {
            Log::info('Saving avatar to database: ' . $req_data['avatar'] . ' for user: ' . $user->ident);
        }

        // CRITICAL FIX: Use direct Eloquent update instead of repository for avatar persistence
        // This ensures the same update mechanism that works for admins
        if (isset($req_data['avatar'])) {
            // Update avatar field directly using Eloquent (not repository)
            User::where('id', $id)->update(['avatar' => $req_data['avatar']]);
            Log::info('Avatar updated directly via Eloquent for user: ' . $user->ident);
            
            // Remove avatar from $req_data to prevent repository from overriding it
            $avatarValue = $req_data['avatar'];
            unset($req_data['avatar']);
            
            // Update other fields via repository (if any)
            if (!empty($req_data)) {
                $this->userRepo->update($req_data, $id);
            }
            
            // Force refresh the user model to get the latest data from database
            $user = User::find($id); // Direct Eloquent query for fresh data
            
            // Verify the avatar was saved correctly
            $savedAvatar = $user->getAttributes()['avatar'];
            if ($savedAvatar === $avatarValue) {
                Log::info('Avatar successfully persisted: ' . $savedAvatar . ' for user: ' . $user->ident);
            } else {
                Log::error('Avatar persistence failed! Expected: ' . $avatarValue . ', Got: ' . ($savedAvatar ?? 'NULL'));
            }
        } else {
            // No avatar update, use repository normally
            $this->userRepo->update($req_data, $id);
            // Refresh user for consistency
            $user = $this->userRepo->findWithoutFail($id);
        }
        
        // Update the currently authenticated user in session to reflect changes immediately
        if (Auth::user() && Auth::user()->id == $user->id) {
            Auth::setUser($user);
            Log::info('Authenticated user session updated for user: ' . $user->ident);
        }
        
        // Final verification: Double-check avatar persistence with a fresh database query
        if (isset($avatarValue)) {
            $verificationUser = User::select('avatar')->where('id', $id)->first();
            $dbAvatarValue = $verificationUser->getAttributes()['avatar'];
            
            if ($dbAvatarValue === $avatarValue) {
                Log::info('✓ AVATAR PERSISTENCE VERIFIED: ' . $dbAvatarValue . ' for user: ' . $user->ident);
                flash()->success('Avatar updated successfully and verified!');
            } else {
                Log::error('✗ AVATAR PERSISTENCE VERIFICATION FAILED! Expected: ' . $avatarValue . ', Database has: ' . ($dbAvatarValue ?? 'NULL'));
                flash()->warning('Avatar uploaded but persistence verification failed. Please refresh the page.');
            }
        }

        // Email verification removed

        // Save all of the user fields
        $userFields = UserField::where('internal', false)->get();
        foreach ($userFields as $field) {
            $field_name = 'field_'.$field->slug;
            UserFieldValue::updateOrCreate([
                'user_field_id' => $field->id,
                'user_id'       => $id,
            ], ['value' => $request->get($field_name)]);
        }

        // Dispatch event including whether an avatar has been updated
        ProfileUpdated::dispatch($user, $request->hasFile('avatar'));

        Flash::success('Profile updated successfully!');

        return redirect(route('frontend.profile.index'));
    }

    /**
     * Regenerate the user's API key
     */
    public function regen_apikey(Request $request): RedirectResponse
    {
        $user = User::find(Auth::user()->id);
        Log::info('Regenerating API key "'.$user->ident.'"');

        $user->api_key = Utils::generateApiKey();
        $user->save();

        flash('New API key generated!')->success();

        return redirect(route('frontend.profile.index'));
    }

    /**
     * Generate the ACARS config and send it to download
     */
    public function acars(Request $request): Response
    {
        /** @var User $user */
        $user = Auth::user();
        $domain = Utils::getRootDomain(config('app.url'));

        $config = json_encode([
            'ApiKey' => $user->api_key,
            'Domain' => $domain,
            'Name'   => config('app.name'),
            'Url'    => config('app.url'),
        ], JSON_PRETTY_PRINT);

        return response($config)->withHeaders([
            'Content-Type'        => 'application/json',
            'Content-Length'      => strlen($config),
            'Cache-Control'       => 'no-store, no-cache',
            'Content-Disposition' => 'attachment; filename="'.$domain.'.json"',
        ]);
    }

    /**
     * Show the awards page for the authenticated user
     */
    public function awards(): View
    {
        $user = Auth::user();
        
        // Get user awards with pagination
        $awards = $user->awards()
            ->orderBy('user_awards.created_at', 'desc')
            ->paginate(15);

        // Get all available awards that user hasn't earned yet
        $availableAwards = \App\Models\Award::whereNotIn('id', 
            $user->awards()->pluck('award_id')
        )->orderBy('name')->get();

        // Get award statistics
        $award_stats = [
            'total_awards' => $user->awards()->count(),
            'recent_awards' => $user->awards()
                ->where('user_awards.created_at', '>=', now()->subDays(30))
                ->count(),
            'categories' => \App\Models\Award::distinct('ref_model')->count('ref_model'),
            'available_awards' => $availableAwards->count(),
        ];

        // Get achievement timeline data (last 12 months)
        $dbDriver = config('database.default');
        if ($dbDriver === 'sqlite' || $dbDriver === 'testing' || $dbDriver === 'memory') {
            // SQLite uses strftime
            $timelineData = $user->awards()
                ->selectRaw("strftime('%Y-%m', user_awards.created_at) as month, COUNT(*) as count")
                ->where('user_awards.created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } else {
            // MySQL/MariaDB uses DATE_FORMAT
            $timelineData = $user->awards()
                ->selectRaw("DATE_FORMAT(user_awards.created_at, '%Y-%m') as month, COUNT(*) as count")
                ->where('user_awards.created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        // Get recent community awards (last 7 days, anonymized) - check if UserAward model exists
        try {
            $recentCommunityAwards = \App\Models\UserAward::with(['award', 'user'])
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            // If UserAward model doesn't exist or has issues, create empty collection
            $recentCommunityAwards = collect();
        }

        // Calculate award rarity (how many users have each award)
        $awardRarity = [];
        try {
            foreach($user->awards as $userAward) {
                $awardId = $userAward->id; // Now userAward is the Award model directly
                $totalUsers = \App\Models\UserAward::where('award_id', $awardId)->count();
                $awardRarity[$awardId] = $totalUsers;
            }
        } catch (\Exception $e) {
            // If there's an issue with award rarity calculation, provide empty array
            $awardRarity = [];
        }

        // Get performance metrics from pireps (check if relationship exists)
        $performanceMetrics = [
            'total_flights' => method_exists($user, 'pireps') ? $user->pireps()->count() : 0,
            'total_distance' => method_exists($user, 'pireps') ? ($user->pireps()->sum('distance') ?? 0) : 0,
            'avg_landing_rate' => method_exists($user, 'pireps') ? ($user->pireps()->avg('landing_rate') ?? 0) : 0,
            'total_flight_time' => $user->flight_time ?? 0,
        ];

        // Get pilot ranking based on awards (handle potential errors)
        try {
            $pilotRanking = \App\Models\User::withCount('awards')
                ->orderBy('awards_count', 'desc')
                ->get()
                ->search(function($pilot) use ($user) {
                    return $pilot->id === $user->id;
                }) + 1;
        } catch (\Exception $e) {
            $pilotRanking = 1; // Default ranking if query fails
        }

        // Get award categories with counts
        $awardCategories = \App\Models\Award::selectRaw('
                ref_model as category,
                COUNT(*) as total_awards,
                SUM(CASE WHEN user_awards.user_id = ? THEN 1 ELSE 0 END) as earned_awards
            ')
            ->leftJoin('user_awards', function($join) use ($user) {
                $join->on('awards.id', '=', 'user_awards.award_id')
                     ->where('user_awards.user_id', '=', $user->id);
            })
            ->groupBy('ref_model')
            ->setBindings([$user->id])
            ->get();

        return view('profile.awards', [
            'user' => $user,
            'userAwards' => $awards,
            'user_awards' => $awards, // Keep for backward compatibility
            'awards' => $awards, // Keep for backward compatibility
            'award_stats' => $award_stats,
            
            // Individual variables for the new view
            'totalAwards' => $award_stats['total_awards'],
            'recentAwards' => $award_stats['recent_awards'],
            'categoryCount' => $award_stats['categories'],
            'availableAwards' => $award_stats['available_awards'],
            'availableAwardsData' => $availableAwards,
            
            // Additional data
            'timelineData' => $timelineData,
            'recentCommunityAwards' => $recentCommunityAwards,
            'awardRarity' => $awardRarity,
            'performanceMetrics' => $performanceMetrics,
            'pilotRanking' => $pilotRanking,
            'awardCategories' => $awardCategories,
            'leaderboard' => \App\Models\User::withCount('awards')->orderBy('awards_count', 'desc')->limit(10)->get(),
        ]);
    }
}
