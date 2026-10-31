<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Models\Aircraft;
use App\Models\Bid;
use App\Models\Enums\FlightType;
use App\Models\Enums\PirepState;
use App\Models\Flight;
use App\Models\Pirep;
use App\Models\Typerating;
use App\Models\User;
use App\Repositories\AirlineRepository;
use App\Repositories\AirportRepository;
use App\Repositories\Criteria\WhereCriteria;
use App\Repositories\FlightRepository;
use App\Repositories\SubfleetRepository;
use App\Repositories\UserRepository;
use App\Services\FlightService;
use App\Services\GeoService;
use App\Services\ModuleService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Laracasts\Flash\Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class FlightController extends Controller
{
    public function __construct(
        private readonly AirlineRepository $airlineRepo,
        private readonly AirportRepository $airportRepo,
        private readonly FlightRepository $flightRepo,
        private readonly FlightService $flightSvc,
        private readonly GeoService $geoSvc,
        private readonly ModuleService $moduleSvc,
        private readonly SubfleetRepository $subfleetRepo,
        private readonly UserRepository $userRepo,
        private readonly UserService $userSvc
    ) {}

    /**
     * @throws RepositoryException
     */
    public function index(Request $request): View
    {
        return $this->search($request);
    }

    /**
     * Make a search request using the Repository search
     *
     *
     * @throws RepositoryException
     */
    public function search(Request $request): View
    {
        $where = [
            'active'  => true,
            'visible' => true,
        ];

        /** @var User $user */
        $user = Auth::user();
        $user->loadMissing(['current_airport', 'typeratings']);

        if (setting('pilots.restrict_to_company')) {
            $where['airline_id'] = $user->airline_id;
        }

        // default restrictions on the flights shown. Handle search differently
        if (setting('pilots.only_flights_from_current')) {
            $where['dpt_airport_id'] = $user->curr_airport_id;
        }

        $this->flightRepo->resetCriteria();

        try {
            $this->flightRepo->searchCriteria($request);
            $this->flightRepo->pushCriteria(new WhereCriteria($request, $where, [
                'airline' => ['active' => true],
            ]));

            $this->flightRepo->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            Log::emergency($e);
        }

        // Filter flights according to user capabilities (by rank or by type rating etc)
        $filter_by_user = setting('pireps.restrict_aircraft_to_rank', true) || setting('pireps.restrict_aircraft_to_typerating', false);

        if ($filter_by_user) {
            // Get allowed subfleets for the user
            $user_subfleets = $this->userSvc->getAllowableSubfleets($user)->pluck('id')->toArray();
            // Get flight_id's from relationships (group by flight id to reduce the array size)
            $user_flights = DB::table('flight_subfleet')
                ->select('flight_id')
                ->whereIn('subfleet_id', $user_subfleets)
                ->groupBy('flight_id')
                ->pluck('flight_id')
                ->toArray();
            // Get flight_id's of open (non restricted) flights
            $open_flights = Flight::withCount('subfleets')
                ->whereNull('user_id')
                ->groupBy('id')
                ->having('subfleets_count', 0)
                ->pluck('id')
                ->toArray();
            $allowed_flights = array_merge($user_flights, $open_flights);
            // Build aircraft icao codes by considering allowed subfleets
            $icao_codes = Aircraft::whereIn('subfleet_id', $user_subfleets)->groupBy('icao')->orderBy('icao')->pluck('icao')->toArray();
            // Build type ratings collection by considering user's capabilities
            $type_ratings = $user->typeratings;
        } else {
            $allowed_flights = [];
            // Build aircraft icao codes array from complete fleet
            $icao_codes = Aircraft::groupBy('icao')->orderBy('icao')->pluck('icao')->toArray();
            // Build type ratings collection from all active ratings
            $type_ratings = Typerating::where('active', 1)->select('id', 'name', 'type')->orderBy('type')->get();
        }

        // Get only used Flight Types for the search form
        // And filter according to settings
        $usedtypes = Flight::select('flight_type')
            ->where($where)
            ->groupby('flight_type')
            ->orderby('flight_type')
            ->get();

        // Build collection with type codes and labels
        $flight_types = collect('');
        foreach ($usedtypes as $ftype) {
            $flight_types->put($ftype->flight_type, FlightType::label($ftype->flight_type));
        }

        // Handle per page parameter
        $perPage = $request->get('per_page', 25);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 25;

        // Handle quick filters
        $query = $this->flightRepo->searchCriteria($request)
            ->with([
                'airline',
                'alt_airport',
                'arr_airport',
                'dpt_airport',
                'subfleets.airline',
                'simbrief' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
            ])
            ->when($filter_by_user, function ($query) use ($allowed_flights) {
                return $query->whereIn('id', $allowed_flights);
            });

        // Apply quick filters
        if ($request->get('today')) {
            $query->whereDate('created_at', today());
        }

        if ($request->get('available_aircraft')) {
            // Filter flights with available aircraft (this would need more complex logic)
            $query->whereHas('subfleets.aircraft', function ($q) {
                $q->where('status', 'A'); // Assuming 'A' means available
            });
        }

        if ($request->get('max_flight_time')) {
            $query->where('flight_time', '<=', $request->get('max_flight_time'));
        }

        if ($request->get('min_flight_time')) {
            $query->where('flight_time', '>=', $request->get('min_flight_time'));
        }

        $flights = $query->sortable('flight_number')->orderBy('route_code')->orderBy('route_leg')
            ->paginate($perPage);

        $saved_flights = [];
        $bids = Bid::where('user_id', Auth::id())->get();
        foreach ($bids as $bid) {
            if (!$bid->flight) {
                $bid->delete();

                continue;
            }

            $saved_flights[$bid->flight_id] = $bid->id;
        }

        return view('flights.index', [
            'user'          => $user,
            'airlines'      => $this->airlineRepo->selectBoxList(true),
            'airports'      => $this->airportRepo->selectBoxList(true),
            'flights'       => $flights,
            'saved'         => $saved_flights,
            'subfleets'     => $this->subfleetRepo->selectBoxList(true),
            'flight_number' => $request->input('flight_number'),
            'flight_types'  => $flight_types,
            'flight_type'   => $request->input('flight_type'),
            'arr_icao'      => $request->input('arr_icao'),
            'dep_icao'      => $request->input('dep_icao'),
            'subfleet_id'   => $request->input('subfleet_id'),
            'simbrief'      => true, // Enable SimBrief features for development/demo
            'simbrief_bids' => setting('simbrief.only_bids'),
            'acars_plugin'  => $this->moduleSvc->isModuleActive('VMSAcars'),
            'icao_codes'    => $icao_codes,
            'type_ratings'  => $type_ratings,
        ]);
    }

    /**
     * Find the user's bids and display them
     */
    public function bids(Request $request): View
    {
        $user = Auth::user();

        // Fetch bids with simple relationships to avoid recursive loading loops
        $bids = Bid::with(['flight.airline', 'flight.dpt_airport', 'flight.arr_airport', 'aircraft'])
            ->where('user_id', $user->id)
            ->get();

        $flights = collect();
        $saved_flights = [];

        foreach ($bids as $bid) {
            if (!$bid->flight) {
                $bid->delete();
                continue;
            }

            $flight = $bid->flight;
            // Manually associate the aircraft if it exists on the bid
            if ($bid->aircraft) {
                $flight->setRelation('aircraft', $bid->aircraft);
            }

            $flights->add($flight);
            $saved_flights[$bid->flight_id] = $bid->id;
        }

        // A bid isn't removed until its PIREP is filed (see BidEventHandler::onPirepFiled), so a flight
        // the pilot is actively flying via SmartCARS still shows up here. Surface that PIREP's id so the
        // banner can poll its live position instead of sitting static until the flight is filed.
        $active_pireps = Pirep::where('user_id', $user->id)
            ->whereIn('flight_id', $flights->pluck('id'))
            ->where('state', PirepState::IN_PROGRESS)
            ->pluck('id', 'flight_id');

        return view('flights.bids', [
            'user'          => $user,
            'flights'       => $flights,
            'saved'         => $saved_flights,
            'active_pireps' => $active_pireps,
            'simbrief'      => true,
            'simbrief_bids' => setting('simbrief.only_bids'),
            'acars_plugin'  => $this->moduleSvc->isModuleActive('VMSAcars'),
        ]);
    }

    /**
     * Show the flight information page
     */
    public function show(string $id): View
    {
        $user = Auth::user();
        // Support retrieval of deleted relationships
        $with_flight = [
            'airline' => function ($query) {
                return $query->withTrashed();
            },
            'alt_airport' => function ($query) {
                return $query->withTrashed();
            },
            'arr_airport' => function ($query) {
                return $query->withTrashed();
            },
            'dpt_airport' => function ($query) {
                return $query->withTrashed();
            },
            'subfleets.airline',
            'simbrief' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            },
        ];

        $flight = $this->flightRepo->with($with_flight)->find($id);
        if (empty($flight)) {
            Flash::error('Flight not found!');

            return redirect(route('frontend.dashboard.index'));
        }

        if (setting('flights.only_company_aircraft', false)) {
            $flight = $this->flightSvc->filterSubfleets($user, $flight);
        }

        $map_features = $this->geoSvc->flightGeoJson($flight);

        // See if the user has a bid for this flight
        $bid = Bid::where(['user_id' => $user->id, 'flight_id' => $flight->id])->first();

        return view('flights.show', [
            'flight'       => $flight,
            'map_features' => $map_features,
            'bid'          => $bid,
            'acars_plugin' => $this->moduleSvc->isModuleActive('VMSAcars'),
        ]);
    }
}
