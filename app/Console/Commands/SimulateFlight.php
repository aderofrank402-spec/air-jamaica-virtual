<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pirep;
use App\Models\Acars;
use App\Models\Airport;
use App\Models\Enums\PirepState;
use App\Models\Enums\PirepStatus;
use App\Models\Enums\PirepSource;
use App\Models\Enums\AcarsType;
use App\Models\User;
use App\Models\Airline;
use App\Models\Aircraft;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SimulateFlight extends Command
{
    protected $signature = 'simulate:flight
        {--cleanup : Remove all simulated flights from the DB}
        {--snapshot : Insert one mid-flight snapshot then exit (no live loop)}';

    protected $description = 'Simulate a live flight for the map. Runs a live position loop by default.';

    // MKJP (Kingston) → KMIA (Miami) route
    private array $waypoints = [
        ['name' => 'MKJP',  'lat' => 17.9356, 'lon' => -76.7875],
        ['name' => 'TELES', 'lat' => 19.2500, 'lon' => -77.5000],
        ['name' => 'OPIKA', 'lat' => 20.8000, 'lon' => -78.2000],
        ['name' => 'CAMGI', 'lat' => 22.5000, 'lon' => -79.5000],
        ['name' => 'URSUS', 'lat' => 24.0000, 'lon' => -80.0000],
        ['name' => 'KMIA',  'lat' => 25.7959, 'lon' => -80.2901],
    ];

    public function handle(): void
    {
        if ($this->option('cleanup')) {
            $deleted = Pirep::where('route_code', 'SIM')->delete();
            $this->info("Cleaned up {$deleted} simulated PIREP(s).");
            return;
        }

        $this->ensureAirports();

        $user     = User::first();
        $airline  = Airline::first();
        $aircraft = Aircraft::where('status', 'A')->first() ?? Aircraft::first();

        if (!$user || !$airline || !$aircraft) {
            $this->error('Need at least one user, airline, and aircraft in the DB.');
            return;
        }

        $pirep = $this->createPirep($user, $airline, $aircraft);
        $this->insertRouteWaypoints($pirep);
        $this->insertAcarsLog($pirep, 'Pushback complete. Engines starting.');
        $this->insertAcarsLog($pirep, 'Taxiing to runway 12.');
        $this->insertAcarsLog($pirep, 'Takeoff roll initiated.');

        $this->info("PIREP created: {$pirep->id}");
        $this->info("Route: MKJP → KMIA");
        $this->info('Open the Live Map now: /livemap');

        if ($this->option('snapshot')) {
            // Insert a single mid-flight position and exit
            $midLat = 21.5;
            $midLon = -78.8;
            $this->insertPosition($pirep, $midLat, $midLon, 330, 35000, 450);
            $this->insertAcarsLog($pirep, 'Cruising FL350. On track.');
            $pirep->updated_at = Carbon::now();
            $pirep->save();
            $this->info("Snapshot inserted at {$midLat}, {$midLon}. Refresh the map.");
            return;
        }

        // Live loop: move the aircraft along the route in real time
        $this->info('Running live simulation. Press Ctrl+C to stop.');

        $startLat = $this->waypoints[0]['lat'];
        $startLon = $this->waypoints[0]['lon'];
        $endLat   = $this->waypoints[count($this->waypoints) - 1]['lat'];
        $endLon   = $this->waypoints[count($this->waypoints) - 1]['lon'];

        $steps = 80;
        $logMessages = [
            10 => 'Climbing through FL180.',
            20 => 'Reached cruise altitude FL350.',
            30 => 'Passing abeam TELES. On track.',
            40 => 'Turbulence reported. Seat belt sign ON.',
            50 => 'Turbulence clear. Seat belt sign OFF.',
            60 => 'Beginning descent into KMIA.',
            70 => 'Intercepting ILS runway 08R.',
        ];

        for ($i = 0; $i <= $steps; $i++) {
            $progress = $i / $steps;
            $lat = $startLat + ($endLat - $startLat) * $progress;
            $lon = $startLon + ($endLon - $startLon) * $progress;

            // Altitude profile: climb to 35000, cruise, descend
            if ($progress < 0.15) {
                $alt = (int) (35000 * ($progress / 0.15));
            } elseif ($progress > 0.75) {
                $alt = (int) (35000 * (1 - ($progress - 0.75) / 0.25));
                $alt = max($alt, 1000);
            } else {
                $alt = 35000;
            }

            // Ground speed: climb slower, cruise fast, descend medium
            if ($progress < 0.15) {
                $gs = (int) (250 + 200 * ($progress / 0.15));
            } elseif ($progress > 0.75) {
                $gs = (int) (450 - 150 * (($progress - 0.75) / 0.25));
            } else {
                $gs = 450;
            }

            $this->insertPosition($pirep, $lat, $lon, 330, $alt, $gs);

            if (isset($logMessages[$i])) {
                $this->insertAcarsLog($pirep, $logMessages[$i]);
            }

            $pirep->updated_at = Carbon::now();
            $pirep->save();

            $this->line(sprintf(
                "[%d/%d] Pos: %.4f, %.4f | Alt: %d ft | GS: %d kts",
                $i, $steps, $lat, $lon, $alt, $gs
            ));

            sleep(5);
        }

        $this->info('Simulation complete.');
    }

    private function ensureAirports(): void
    {
        $airports = [
            [
                'id'        => 'MKJP',
                'iata'      => 'KIN',
                'icao'      => 'MKJP',
                'name'      => 'Norman Manley International Airport',
                'location'  => 'Kingston',
                'country'   => 'JM',
                'timezone'  => 'America/Jamaica',
                'hub'       => 1,
                'lat'       => 17.9356,
                'lon'       => -76.7875,
                'elevation' => 10,
            ],
            [
                'id'        => 'KMIA',
                'iata'      => 'MIA',
                'icao'      => 'KMIA',
                'name'      => 'Miami International Airport',
                'location'  => 'Miami, FL',
                'country'   => 'US',
                'timezone'  => 'America/New_York',
                'hub'       => 0,
                'lat'       => 25.7959,
                'lon'       => -80.2901,
                'elevation' => 8,
            ],
        ];

        foreach ($airports as $data) {
            Airport::firstOrCreate(['id' => $data['id']], $data);
        }

        $this->info('Airports ready: MKJP, KMIA.');
    }

    private function createPirep($user, $airline, $aircraft): Pirep
    {
        return Pirep::create([
            'user_id'             => $user->id,
            'airline_id'          => $airline->id,
            'aircraft_id'         => $aircraft->id,
            'flight_number'       => 'SIM' . rand(100, 999),
            'route_code'          => 'SIM',
            'route_leg'           => '1',
            'dpt_airport_id'      => 'MKJP',
            'arr_airport_id'      => 'KMIA',
            'level'               => 35000,
            'distance'            => 730,
            'planned_distance'    => 730,
            'flight_time'         => 0,
            'planned_flight_time' => 120,
            'zfw'                 => 120000,
            'block_fuel'          => 20000,
            'fuel_used'           => 0,
            'route'               => 'MKJP TELES OPIKA CAMGI URSUS KMIA',
            'notes'               => 'Simulated flight — live map test',
            'source'              => PirepSource::ACARS,
            'state'               => PirepState::IN_PROGRESS,
            'status'              => PirepStatus::ENROUTE,
            'submitted_at'        => Carbon::now(),
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
        ]);
    }

    private function insertRouteWaypoints(Pirep $pirep): void
    {
        foreach ($this->waypoints as $idx => $wp) {
            Acars::create([
                'id'       => Str::orderedUuid()->toString(),
                'pirep_id' => $pirep->id,
                'type'     => AcarsType::ROUTE,
                'order'    => $idx,
                'name'     => $wp['name'],
                'lat'      => $wp['lat'],
                'lon'      => $wp['lon'],
                'status'   => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        $this->info('Route waypoints inserted: ' . implode(' → ', array_column($this->waypoints, 'name')));
    }

    private function insertPosition(Pirep $pirep, float $lat, float $lon, int $heading, int $alt, int $gs): void
    {
        Acars::create([
            'id'           => Str::orderedUuid()->toString(),
            'pirep_id'     => $pirep->id,
            'type'         => AcarsType::FLIGHT_PATH,
            'lat'          => $lat,
            'lon'          => $lon,
            'heading'      => $heading,
            'altitude_msl' => $alt,
            'altitude_agl' => $alt,
            'gs'           => $gs,
            'vs'           => 0,
            'status'       => PirepStatus::ENROUTE,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
    }

    private function insertAcarsLog(Pirep $pirep, string $message): void
    {
        Acars::create([
            'id'        => Str::orderedUuid()->toString(),
            'pirep_id'  => $pirep->id,
            'type'      => AcarsType::LOG,
            'log'       => $message,
            'status'    => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
