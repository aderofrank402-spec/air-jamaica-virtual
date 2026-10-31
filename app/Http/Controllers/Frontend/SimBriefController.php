<?php

namespace App\Http\Controllers\Frontend;

use App\Exceptions\AssetNotFound;
use App\Models\Aircraft;
use App\Models\Bid;
use App\Models\Enums\AircraftState;
use App\Models\Enums\AircraftStatus;
use App\Models\Enums\AirframeSource;
use App\Models\Enums\FareType;
use App\Models\Enums\FlightType;
use App\Models\Fare;
use App\Models\Flight;
use App\Models\SimBrief;
use App\Models\SimBriefLayout;
use App\Models\Subfleet;
use App\Models\User;
use App\Repositories\FlightRepository;
use App\Services\FareService;
use App\Services\ModuleService;
use App\Services\SimBriefService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\View\View;

class SimBriefController
{
    public function __construct(
        private readonly FareService $fareSvc,
        private readonly FlightRepository $flightRepo,
        private readonly ModuleService $moduleSvc,
        private readonly SimBriefService $simBriefSvc,
        private readonly UserService $userSvc
    ) {}

    /**
     * Show the main OFP form
     *
     *
     * @throws Exception
     */
    public function generate(Request $request): RedirectResponse|View
    {
        /** @var User $user */
        $user = Auth::user();

        $flight_id = $request->input('flight_id');
        $aircraft_id = $request->input('aircraft_id');

        /** @var Flight $flight */
        $flight = $this->flightRepo->with(['airline', 'arr_airport', 'dpt_airport', 'fares', 'subfleets'])->find($flight_id);

        if (!$flight) {
            flash()->error('Unknown flight');

            return redirect(route('frontend.flights.index'));
        }

        $apiKey = setting('simbrief.api_key');
        if (empty($apiKey)) {
            flash()->error('Invalid SimBrief API key!');

            return redirect(route('frontend.flights.index'));
        }

        // Generate SimBrief Static ID
        $static_id = $user->ident.'_'.$flight->id;

        // No aircraft selected, try to find one from a bid
        if (!$aircraft_id) {
            $bid = Bid::where(['user_id' => $user->id, 'flight_id' => $flight_id])->first();
            if ($bid) {
                $aircraft_id = $bid->aircraft_id;
            }
        }

        // No aircraft selected, show selection form
        if (!$aircraft_id) {
            // Get user's allowed subfleets and intersect it with flight subfleets
            // so we will have a proper list which the user is allowed to fly
            $user_subfleets = $this->userSvc->getAllowableSubfleets($user)->pluck('id')->toArray();
            $flight_subfleets = $flight->subfleets->pluck('id')->toArray();

            if ((blank($flight_subfleets) || count($flight_subfleets) === 0) && setting('flights.only_company_aircraft', false)) {
                $flight_subfleets = Subfleet::where(['airline_id' => $flight->airline_id])->pluck('id')->toArray();
            }

            $subfleet_ids = filled($flight_subfleets) ? array_intersect($user_subfleets, $flight_subfleets) : $user_subfleets;

            // Prepare variables for single aircraft query
            $where = [];
            $where['state'] = AircraftState::PARKED;
            $where['status'] = AircraftStatus::ACTIVE;

            if (setting('pireps.only_aircraft_at_dpt_airport')) {
                $where['airport_id'] = $flight->dpt_airport_id;
            }

            $withCount = ['simbriefs' => function ($query) {
                $query->whereNull('pirep_id');
            }];

            // Build proper aircraft collection considering all possible settings
            // Flight subfleets, user subfleet restrictions, pirep restrictions, simbrief blocking etc
            $aircraft = Aircraft::withCount($withCount)->with(['sbaircraft', 'sbairframes'])->where($where)
                ->when(setting('simbrief.block_aircraft'), function ($query) {
                    return $query->having('simbriefs_count', 0);
                })->whereIn('subfleet_id', $subfleet_ids)
                ->orderby('icao')->orderby('registration')
                ->get();

            return view('flights.simbrief_aircraft', [
                'flight'    => $flight,
                'aircrafts' => $aircraft,
            ]);
        }

        // Check if a Simbrief profile already exists
        $simbrief = SimBrief::select('id')->where([
            'flight_id' => $flight_id,
            'user_id'   => $user->id,
        ])->first();

        if ($simbrief) {
            return redirect(route('frontend.simbrief.briefing', [$simbrief->id]));
        }

        // SimBrief profile does not exists and everything else is ok
        // Select aircraft which will be used for calculations and details
        /** @var Aircraft $aircraft */
        $aircraft = Aircraft::with(['airline', 'sbaircraft', 'sbairframes'])->where('id', $aircraft_id)->first();

        // Figure out the proper fares to use for this flight/aircraft
        $all_fares = $this->fareSvc->getFareWithOverrides($aircraft->subfleet->fares, $flight->fares);

        // TODO: Reconcile the fares for this aircraft w/ proper for the flight/subfleet

        // Get passenger and baggage weights with failsafe defaults
        if ($flight->flight_type === FlightType::CHARTER_PAX_ONLY) {
            $pax_weight = setting('simbrief.charter_pax_weight', 168);
            $bag_weight = setting('simbrief.charter_baggage_weight', 28);
        } else {
            $pax_weight = setting('simbrief.noncharter_pax_weight', 185);
            $bag_weight = setting('simbrief.noncharter_baggage_weight', 35);
        }

        // Get the load factors with failsafe for loadmax if nothing is defined
        $lfactor = $flight->load_factor ?? setting('flights.default_load_factor');
        $lfactorv = $flight->load_factor_variance ?? setting('flights.load_factor_variance');

        $loadmin = $lfactor - $lfactorv;
        $loadmin = max($loadmin, 0);

        $loadmax = $lfactor + $lfactorv;
        $loadmax = min($loadmax, 100);

        if ($loadmax === 0) {
            $loadmax = 100;
        }

        if (setting('flights.use_cargo_load_factor ', false)) {
            $cgolfactor = $flight->load_factor ?? setting('flights.default_cargo_load_factor');
            $cgolfactorv = $flight->load_factor_variance ?? setting('flights.cargo_load_factor_variance');

            $cgoloadmin = $cgolfactor - $cgolfactorv;
            $cgoloadmin = max($cgoloadmin, 0);

            $cgoloadmax = $cgolfactor + $cgolfactorv;
            $cgoloadmax = min($cgoloadmax, 100);

            if ($cgoloadmax === 0) {
                $cgoloadmax = 100;
            }
        } else {
            $cgoloadmin = $loadmin;
            $cgoloadmax = $loadmax;
        }

        // Load fares for passengers

        $loaddist = []; // The load distribution string

        $pax_load_sheet = [];
        $tpaxfig = 0;
        $acd_maxpax = 0;

        /** @var Fare $fare */
        foreach ($all_fares as $fare) {
            if ($fare->type !== FareType::PASSENGER || empty($fare->capacity)) {
                continue;
            }

            $acd_maxpax += $fare->capacity;
            $count = floor(($fare->capacity * rand($loadmin, $loadmax)) / 100);
            $tpaxfig += $count;
            $pax_load_sheet[] = [
                'id'       => $fare->id,
                'code'     => $fare->code,
                'name'     => $fare->name,
                'type'     => $fare->type,
                'capacity' => (int) $fare->capacity,
                'count'    => $count,
            ];

            $loaddist[] = $fare->code.' '.$count;
        }

        // Calculate and convert weights according to SimBrief requirements
        if (setting('units.weight') === 'kg') {
            $tpaxload = round(($pax_weight * $tpaxfig) / 2.205);
            $tbagload = round(($bag_weight * $tpaxfig) / 2.205);
        } else {
            $tpaxload = round($pax_weight * $tpaxfig);
            $tbagload = round($bag_weight * $tpaxfig);
        }

        // Load up fares for cargo
        $tcargoload = 0;
        $cargo_load_sheet = [];
        foreach ($all_fares as $fare) {
            if ($fare->type !== FareType::CARGO || empty($fare->capacity)) {
                continue;
            }

            $count = ceil((($fare->capacity - $tbagload) * rand($cgoloadmin, $cgoloadmax)) / 100);
            $tcargoload += $count;
            $cargo_load_sheet[] = [
                'id'       => $fare->id,
                'code'     => $fare->code,
                'name'     => $fare->name,
                'type'     => $fare->type,
                'capacity' => $fare->capacity,
                'count'    => $count,
            ];

            $loaddist[] = $fare->code.' '.$count;
        }

        $tpayload = $tpaxload + $tbagload + $tcargoload;

        $request->session()->put('simbrief_fares', array_merge($pax_load_sheet, $cargo_load_sheet));

        // Prepare SimBrief layouts, acdata json and actype code
        $layouts = SimBriefLayout::get();

        $acdata = [
            // Passenger and Baggage Weights needs to pounds, integer like 185
            'paxwgt' => $pax_weight,
            'bagwgt' => $bag_weight,
            // Airframe Weights needs to be thousands of pounds, with 3 digit precision like 85.715
            'mzfw'    => (filled($aircraft->zfw) && $aircraft->zfw->internal(0) > 0) ? round($aircraft->zfw->internal(0) / 1000, 3) : null,
            'mtow'    => (filled($aircraft->mtow) && $aircraft->mtow->internal(0) > 0) ? round($aircraft->mtow->internal(0) / 1000, 3) : null,
            'mlw'     => (filled($aircraft->mlw) && $aircraft->mlw->internal(0) > 0) ? round($aircraft->mlw->internal(0) / 1000, 3) : null,
            'hexcode' => filled($aircraft->hex_code) ? $aircraft->hex_code : null,
            'maxpax'  => $acd_maxpax,
        ];

        $actype = (filled($aircraft->simbrief_type)) ? $aircraft->simbrief_type : ((filled(optional($aircraft->subfleet)->simbrief_type)) ? $aircraft->subfleet->simbrief_type : $aircraft->icao);
        $sbaircraft = filled($aircraft->sbaircraft) ? collect(json_decode($aircraft->sbaircraft->details)) : null;
        $sbairframes = (setting('simbrief.use_custom_airframes', false)) ? $aircraft->sbairframes->where('source', AirframeSource::INTERNAL) : $aircraft->sbairframes;

        // Store flight_id and aircraft_id in session for callback
        $request->session()->put('simbrief_flight_id', $flight_id);
        $request->session()->put('simbrief_aircraft_id', $aircraft_id);

        // Show the main simbrief form
        return view('flights.simbrief_form', [
            'user'             => $user,
            'flight'           => $flight,
            'aircraft'         => $aircraft,
            'pax_weight'       => $pax_weight,
            'bag_weight'       => $bag_weight,
            'loadmin'          => $loadmin,
            'loadmax'          => $loadmax,
            'pax_load_sheet'   => $pax_load_sheet,
            'cargo_load_sheet' => $cargo_load_sheet,
            'tpaxfig'          => $tpaxfig,
            'tpaxload'         => $tpaxload,
            'tbagload'         => $tbagload,
            'tpayload'         => $tpayload,
            'tcargoload'       => $tcargoload,
            'loaddist'         => implode(' ', $loaddist).' BAG '.$tbagload,
            'static_id'        => $static_id,
            'sbaircraft'       => $sbaircraft,
            'sbairframes'      => filled($sbairframes) ? $sbairframes : null,
            'acdata'           => json_encode($acdata),
            'actype'           => $actype,
            'layouts'          => $layouts,
        ]);
    }

    /**
     * Show the briefing
     *
     * @param string $id The OFP ID
     */
    public function briefing(string $id): RedirectResponse|View
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var SimBrief $simbrief */
        $simbrief = SimBrief::with(['flight.airline', 'pirep.airline'])->find($id);
        if (!$simbrief) {
            flash()->error('SimBrief briefing not found');

            return redirect(route('frontend.flights.index'));
        }

        $str = $simbrief->xml->aircraft->equip;
        $wc = stripos($str, '-');
        $tr = stripos($str, '/');
        $wakecat = substr($str, 0, $wc);
        $equipment = substr($str, $wc + 1, $tr - 2);
        $transponder = substr($str, $tr + 1);

        // See if a bid exists for this flight
        $bid = Bid::where(['user_id' => $user->id, 'flight_id' => $simbrief->flight_id])->first();

        return view('flights.simbrief_briefing', [
            'user'         => $user,
            'simbrief'     => $simbrief,
            'wakecat'      => $wakecat,
            'equipment'    => $equipment,
            'transponder'  => $transponder,
            'bid'          => $bid,
            'flight'       => $simbrief->flight,
            'acars_plugin' => $this->moduleSvc->isModuleActive('VMSAcars'),
        ]);
    }

    /**
     * Remove the flight_id from the SimBrief Briefing (to a create a new one)
     * or if no pirep_id is attached to the briefing delete it completely
     *
     *
     * @throws Exception
     */
    public function generate_new(Request $request): RedirectResponse
    {
        $simbrief = SimBrief::find($request->id);

        // Invalid Simbrief ID/profile, go back to the main flight index
        if (!$simbrief) {
            return redirect(route('frontend.flights.index'));
        }

        // Cleanup the current Simbrief entry and redirect to the new generation form
        // If there isn't a PIREP ID, then delete the entry, otherwise, remove the flight
        $flight_id = $simbrief->flight_id;
        if (!$simbrief->pirep_id) {
            $simbrief->delete();
        } else {
            $simbrief->flight_id = null;
            $simbrief->save();
        }

        return redirect(route('frontend.simbrief.generate').'?flight_id='.$flight_id);
    }

    /**
     * Create a prefile of this PIREP with a given OFP. Then redirect the
     * user to the newly prefiled PIREP
     */
    public function prefile(Request $request): RedirectResponse
    {
        $sb = SimBrief::find($request->id);
        if (!$sb) {
            return redirect(route('frontend.flights.index'));
        }

        // Redirect to the prefile page, with the flight_id and a simbrief_id
        $rd = route('frontend.pireps.create').'?sb_id='.$sb->id;

        return redirect($rd);
    }

    /**
     * Cancel the SimBrief request
     */
    public function cancel(Request $request): RedirectResponse
    {
        $sb = SimBrief::find($request->id);
        if ($sb) {
            $sb->delete();
        }

        return redirect(route('frontend.simbrief.prefile', ['id' => $request->id]));
    }

    /**
     * Check whether the OFP was generated. Pass in two items, the flight_id and ofp_id
     * This does the actual "attachment" of the Simbrief to the flight
     */
    public function check_ofp(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $ofp_id = $request->input('ofp_id');
        $flight_id = $request->input('flight_id');
        $aircraft_id = $request->input('aircraft_id');
        $fares = $request->session()->get('simbrief_fares', []);

        $simbrief = $this->simBriefSvc->downloadOfp($user->id, $ofp_id, $flight_id, $aircraft_id, $fares);
        if ($simbrief === null) {
            $error = new AssetNotFound(new Exception('Simbrief OFP not found'));

            return $error->getResponse();
        }

        return response()->json([
            'id' => $simbrief->id,
        ]);
    }

    /**
     * Get the latest generated OFP. Pass in two additional items, the Simbrief userid and static_id
     * This will get the latest edited/regenerated of from Simbrief and update our records
     * We do not need to send the fares again, so used an empty array
     */
    public function update_ofp(Request $request): RedirectResponse|JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $ofp_id = $request->input('ofp_id');
        $flight_id = $request->input('flight_id');
        $aircraft_id = $request->input('aircraft_id');
        $sb_userid = $request->input('sb_userid');
        $sb_static_id = $request->input('sb_static_id');
        $fares = [];

        $simbrief = $this->simBriefSvc->downloadOfp($user->id, $ofp_id, $flight_id, $aircraft_id, $fares, $sb_userid, $sb_static_id);
        if ($simbrief === null) {
            $error = new AssetNotFound(new Exception('Simbrief OFP not found'));

            return $error->getResponse();
        }

        return redirect(route('frontend.simbrief.briefing', [$ofp_id]));
    }

    /**
     * Official SimBrief API endpoint handler
     * Handles both api_req (for API code generation) and js_url_check (for OFP file existence check)
     * This matches the official simbrief.apiv1.php file structure
     *
     * @throws Exception
     */
    public function api(Request $request)
    {
        // Handle js_url_check - check if OFP XML file exists
        if ($request->has('js_url_check')) {
            $varname = $request->input('var', 'fe_result');
            $ofp_id = $request->input('js_url_check');
            
            // Check if the OFP XML file exists on SimBrief servers
            $url = 'http://www.simbrief.com/ofp/flightplans/xml/' . $ofp_id . '.xml';
            
            // Use get_headers to check if file exists
            $headers = @get_headers($url);
            $exists = false;
            
            if ($headers && isset($headers[0]) && strpos($headers[0], '200') !== false) {
                $exists = true;
            }
            
            // Return JavaScript variable assignment (matching official API format)
            return response('var ' . $varname . ' = "' . ($exists ? 'true' : 'false') . '";', 200)
                ->header('Content-Type', 'application/javascript');
        }
        
        // Handle api_req - generate API code
        if ($request->has('api_req')) {
            $apiKey = setting('simbrief.api_key', null);
            if (empty($apiKey)) {
                \Log::warning('SimBrief API key is empty');
                return response('var api_code = "";', 200)
                    ->header('Content-Type', 'application/javascript');
            }
            
            $api_req = $request->input('api_req');
            if (empty($api_req)) {
                \Log::warning('api_req parameter is missing');
                return response('var api_code = "";', 200)
                    ->header('Content-Type', 'application/javascript');
            }
            
            $api_code = md5($apiKey . $api_req);
            
            \Log::info('API code generated', [
                'api_code_length' => strlen($api_code),
                'api_req_length' => strlen($api_req),
            ]);
            
            // Return JavaScript variable assignment (matching official API format)
            return response('var api_code = "' . $api_code . '";', 200)
                ->header('Content-Type', 'application/javascript');
        }
        
        // If neither parameter is present, return empty response
        return response('', 200)
            ->header('Content-Type', 'application/javascript');
    }

    /**
     * Generate the API code (legacy endpoint for backward compatibility)
     *
     * @throws Exception
     */
    public function api_code(Request $request): JsonResponse
    {
        try {
            \Log::info('api_code method called', ['request' => $request->all()]);

            $apiKey = setting('simbrief.api_key', null);
            if (empty($apiKey)) {
                \Log::warning('SimBrief API key is empty');
                return response()->json([
                    'error' => 'Invalid SimBrief API key!',
                ], 400);
            }

            $api_req = $request->input('api_req');
            if (empty($api_req)) {
                \Log::warning('api_req parameter is missing', ['request_data' => $request->all()]);
                return response()->json([
                    'error' => 'Missing api_req parameter!',
                ], 400);
            }

            $api_code = md5($apiKey . $api_req);
            
            \Log::info('API code generated successfully', [
                'api_code_length' => strlen($api_code),
                'api_req_length' => strlen($api_req),
            ]);

            return response()->json([
                'api_code' => $api_code,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in api_code method: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            
            return response()->json([
                'error' => 'An error occurred while generating API code: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Submit form data to SimBrief and generate flight plan
     * This replaces the client-side form submission to avoid redirects
     *
     * @throws Exception
     */
    public function submit(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $apiKey = setting('simbrief.api_key', null);
        if (empty($apiKey)) {
            return response()->json([
                'error' => 'Invalid SimBrief API key!',
            ], 400);
        }

        // Get all form parameters (excluding special fields)
        $formData = $request->except(['_token', 'flight_id', 'aircraft_id', 'api_code', 'api_req']);
        
        // Add ref parameter (where SimBrief should redirect back to)
        // Use a callback endpoint that will capture the ofp_id and process it
        $flight_id = $request->input('flight_id');
        $aircraft_id = $request->input('aircraft_id');
        $ref = url('/simbrief/callback') . '?flight_id=' . urlencode($flight_id) . '&aircraft_id=' . urlencode($aircraft_id);
        $formData['ref'] = $ref;
        
        // Build api_req query string (sorted keys) - this is what gets hashed
        // IMPORTANT: api_req should NOT include api_code or api_req itself
        $keys = array_keys($formData);
        sort($keys);
        $apiReqParts = [];
        foreach ($keys as $key) {
            $value = $formData[$key];
            // Skip null, empty strings, but keep '0' values
            if ($value !== null && $value !== '') {
                $apiReqParts[] = urlencode($key) . '=' . urlencode($value);
            }
        }
        $apiReq = implode('&', $apiReqParts);
        
        // Generate API code from api_req
        $api_code = md5($apiKey . $apiReq);
        
        // Add api_code and api_req to form data for submission to SimBrief
        $formData['api_code'] = $api_code;
        $formData['api_req'] = $apiReq;
        
        // Log for debugging (remove in production)
        \Log::info('SimBrief submission', [
            'api_req_length' => strlen($apiReq),
            'has_api_code' => isset($formData['api_code']),
            'form_fields' => array_keys($formData),
        ]);

        // Submit to SimBrief dispatch endpoint
        // SimBrief's dispatch.php processes the form and redirects with ofp_id
        $endpoint = 'https://www.simbrief.com/system/dispatch.php';
        
        $ofp_id = null;
        $error = null;

        // Use GuzzleClient which is already configured with SSL options in AppServiceProvider
        $guzzleClient = app(GuzzleClient::class);
        
        try {
            // Build form-encoded body (like HTML form submission)
            $formBody = http_build_query($formData);
            
            // Track the final URL through redirects
            $finalUrl = null;
            
            // Submit form and follow redirects to get the final URL
            // SimBrief redirects: dispatch.php -> dispatch.simbrief.com -> our callback URL
            $response = $guzzleClient->post($endpoint, [
                'body' => $formBody,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ],
                'timeout' => 30,
                'allow_redirects' => [
                    'max' => 5,
                    'strict' => false,
                    'referer' => true,
                    'track_redirects' => true,
                ],
                'on_stats' => function ($stats) use (&$finalUrl) {
                    // Get the effective URI from the transfer stats
                    $finalUrl = (string) $stats->getEffectiveUri();
                },
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            
            // Get redirect history
            $redirectHistory = $response->getHeader('X-Guzzle-Redirect-History');
            $allUrls = [];
            if (!empty($redirectHistory)) {
                $allUrls = is_array($redirectHistory) ? $redirectHistory : [$redirectHistory];
            }
            if ($finalUrl) {
                $allUrls[] = $finalUrl;
            }
            
            \Log::info('SimBrief response', [
                'status' => $statusCode,
                'final_url' => $finalUrl,
                'redirect_history' => $allUrls,
                'body_preview' => substr($body, 0, 500),
            ]);
            
            // Check all URLs in redirect chain for ofp_id
            foreach ($allUrls as $url) {
                if (preg_match('/[?&]ofp_id=(\d+)/i', $url, $matches)) {
                    $ofp_id = $matches[1];
                    \Log::info('Found ofp_id in redirect URL', ['ofp_id' => $ofp_id, 'url' => $url]);
                    break;
                } elseif (preg_match('/ofp_id[=:](\d+)/i', $url, $matches)) {
                    $ofp_id = $matches[1];
                    \Log::info('Found ofp_id in redirect URL (alternate format)', ['ofp_id' => $ofp_id, 'url' => $url]);
                    break;
                }
            }
            
            // Also check response body for ofp_id
            if (!$ofp_id) {
                if (preg_match('/ofp_id[=:](\d+)/i', $body, $matches)) {
                    $ofp_id = $matches[1];
                    \Log::info('Found ofp_id in response body', ['ofp_id' => $ofp_id]);
                } elseif (preg_match('/request_id[>:](\d+)/i', $body, $matches)) {
                    $ofp_id = $matches[1];
                    \Log::info('Found request_id in response body', ['ofp_id' => $ofp_id]);
                }
            }
            
            // If we still don't have ofp_id and the final URL is our callback, check query params
            if (!$ofp_id && $finalUrl && strpos($finalUrl, '/simbrief/callback') !== false) {
                $parsedUrl = parse_url($finalUrl);
                if (isset($parsedUrl['query'])) {
                    parse_str($parsedUrl['query'], $queryParams);
                    if (isset($queryParams['ofp_id'])) {
                        $ofp_id = $queryParams['ofp_id'];
                        \Log::info('Found ofp_id in callback URL query params', ['ofp_id' => $ofp_id]);
                    }
                }
            }
            
        } catch (ClientException $e) {
            // Client errors (4xx)
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $responseBody = $response ? $response->getBody()->getContents() : 'no response';
            \Log::error('SimBrief client error', [
                'status' => $statusCode,
                'message' => $e->getMessage(),
                'response_preview' => substr($responseBody, 0, 500),
            ]);
            $error = "SimBrief returned error $statusCode. Please check your form data and SimBrief API key.";
        } catch (ServerException $e) {
            // Server errors (5xx) - SimBrief returned 500, which means our request format is likely wrong
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $responseBody = $response ? $response->getBody()->getContents() : 'no response';
            
            // Log full details for debugging
            \Log::error('SimBrief server error (500)', [
                'status' => $statusCode,
                'message' => $e->getMessage(),
                'response_body' => $responseBody,
                'request_fields' => array_keys($formData),
                'api_req_preview' => substr($apiReq, 0, 200),
            ]);
            
            // Check if response body contains any useful error message
            if (preg_match('/error[^>]*>([^<]+)/i', $responseBody, $matches)) {
                $error = "SimBrief error: " . trim($matches[1]);
            } else {
                $error = "SimBrief returned a server error (500). This usually means the request format is incorrect. Please check your SimBrief API key and form data.";
            }
        } catch (\Exception $e) {
            \Log::error('SimBrief submission error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
            $error = $e->getMessage();
        }

        if (!$ofp_id) {
            return response()->json([
                'error' => $error ?: 'Failed to generate SimBrief flight plan. Could not retrieve OFP ID from SimBrief response. Please try again or check your SimBrief account.',
            ], 500);
        }

        // Now download and save the OFP
        $flight_id = $request->input('flight_id');
        $aircraft_id = $request->input('aircraft_id');
        $fares = $request->session()->get('simbrief_fares', []);

        $simbrief = $this->simBriefSvc->downloadOfp($user->id, $ofp_id, $flight_id, $aircraft_id, $fares);
        
        if ($simbrief === null) {
            return response()->json([
                'error' => 'Failed to download SimBrief flight plan.',
            ], 500);
        }

        return response()->json([
            'id' => $simbrief->id,
            'ofp_id' => $ofp_id,
        ]);
    }

    /**
     * Callback endpoint that SimBrief redirects to with ofp_id
     * This processes the OFP and redirects to the briefing page
     * The official API redirects here with ofp_id parameter after the popup closes
     */
    public function callback(Request $request): RedirectResponse|View
    {
        \Log::info('SimBrief callback called', [
            'params' => $request->all(),
            'referer' => $request->header('Referer'),
        ]);
        
        /** @var User $user */
        $user = Auth::user();
        
        $ofp_id = $request->input('ofp_id');
        $flight_id = $request->input('flight_id');
        $aircraft_id = $request->input('aircraft_id');
        
        // ofp_id is required (comes from SimBrief redirect)
        if (!$ofp_id) {
            \Log::warning('SimBrief callback missing ofp_id', [
                'ofp_id' => $ofp_id,
                'flight_id' => $flight_id,
                'aircraft_id' => $aircraft_id,
            ]);
            flash()->error('Missing OFP ID from SimBrief callback');
            return redirect(route('frontend.flights.index'));
        }
        
        // flight_id and aircraft_id should be in the outputpage URL, but if not, try to get from session
        if (!$flight_id || !$aircraft_id) {
            // Try to extract from session or previous request
            $flight_id = $request->input('flight_id') ?: session('simbrief_flight_id');
            $aircraft_id = $request->input('aircraft_id') ?: session('simbrief_aircraft_id');
        }
        
        if (!$flight_id || !$aircraft_id) {
            \Log::warning('SimBrief callback missing flight_id or aircraft_id', [
                'ofp_id' => $ofp_id,
                'flight_id' => $flight_id,
                'aircraft_id' => $aircraft_id,
            ]);
            flash()->error('Missing required parameters from SimBrief callback');
            return redirect(route('frontend.flights.index'));
        }
        
        \Log::info('SimBrief callback processing OFP', [
            'ofp_id' => $ofp_id,
            'flight_id' => $flight_id,
            'aircraft_id' => $aircraft_id,
        ]);
        
        // Download and save the OFP
        $fares = $request->session()->get('simbrief_fares', []);
        $simbrief = $this->simBriefSvc->downloadOfp($user->id, $ofp_id, $flight_id, $aircraft_id, $fares);
        
        if ($simbrief === null) {
            \Log::error('SimBrief callback failed to download OFP', [
                'ofp_id' => $ofp_id,
                'flight_id' => $flight_id,
            ]);
            flash()->error('Failed to download SimBrief flight plan');
            return redirect(route('frontend.flights.index'));
        }
        
        $briefingUrl = route('frontend.simbrief.briefing', [$simbrief->id]);
        
        \Log::info('SimBrief callback successful, redirecting to briefing', [
            'simbrief_id' => $simbrief->id,
            'briefing_url' => $briefingUrl,
        ]);
        
        // Redirect directly to the briefing page
        return redirect($briefingUrl);
    }
}
