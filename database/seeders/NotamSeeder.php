<?php

namespace Database\Seeders;

use App\Models\Notam;
use App\Models\Airport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample airports
        $airports = Airport::take(5)->get();
        $admin = User::where('email', 'admin@phpvms.net')->first();
        
        if ($airports->isEmpty()) {
            $this->command->warn('No airports found. Please seed airports first.');
            return;
        }

        $sampleNotams = [
            [
                'identifier' => 'A0001/24',
                'title' => 'Runway 09/27 Closed for Maintenance',
                'description' => 'Runway 09/27 closed for scheduled maintenance. All operations redirected to Runway 15/33. Expect delays during peak hours.',
                'priority' => 'HIGH',
                'effective_from' => Carbon::now()->subHours(2),
                'effective_until' => Carbon::now()->addDays(3),
            ],
            [
                'identifier' => 'A0002/24',
                'title' => 'Temporary Tower Frequency Change',
                'description' => 'Due to equipment maintenance, tower frequency temporarily changed from 118.1 to 119.3 MHz. Effective immediately.',
                'priority' => 'CRITICAL',
                'effective_from' => Carbon::now()->subHour(),
                'effective_until' => Carbon::now()->addHours(8),
            ],
            [
                'identifier' => 'A0003/24',
                'title' => 'Construction Work Near Taxiway A',
                'description' => 'Construction equipment operating near Taxiway A. Pilots advised to exercise caution and follow ground control instructions.',
                'priority' => 'NORMAL',
                'effective_from' => Carbon::now()->subDays(1),
                'effective_until' => Carbon::now()->addDays(7),
            ],
            [
                'identifier' => 'A0004/24',
                'title' => 'Instrument Landing System Out of Service',
                'description' => 'ILS for Runway 09 is out of service for calibration. Visual approaches only. Weather minimums increased.',
                'priority' => 'HIGH',
                'effective_from' => Carbon::now()->subHours(6),
                'effective_until' => Carbon::now()->addDays(2),
            ],
            [
                'identifier' => 'A0005/24',
                'title' => 'Bird Activity Reported',
                'description' => 'Increased bird activity reported in the vicinity of the airport. Pilots are advised to exercise caution, especially during takeoff and landing.',
                'priority' => 'NORMAL',
                'effective_from' => Carbon::now()->subHours(3),
                'effective_until' => Carbon::now()->addHours(12),
            ],
            [
                'identifier' => 'A0006/24',
                'title' => 'Emergency Generator Test',
                'description' => 'Monthly emergency generator test scheduled. Brief lighting disruptions possible. Backup systems will be online.',
                'priority' => 'LOW',
                'effective_from' => Carbon::now()->addHours(2),
                'effective_until' => Carbon::now()->addHours(4),
            ],
            [
                'identifier' => 'A0007/24',
                'title' => 'CRITICAL: Fuel System Malfunction',
                'description' => 'URGENT: Fuel distribution system experiencing intermittent issues. Limited fuel availability. Pilots must confirm fuel status before departure.',
                'priority' => 'CRITICAL',
                'effective_from' => Carbon::now()->subMinutes(30),
                'effective_until' => null, // Indefinite until resolved
            ],
            [
                'identifier' => 'A0008/24',
                'title' => 'Noise Abatement Procedures',
                'description' => 'New noise abatement procedures effective immediately. Preferential runway usage during certain hours. See AIP for details.',
                'priority' => 'NORMAL',
                'effective_from' => Carbon::now()->subDays(5),
                'effective_until' => Carbon::now()->addDays(30),
            ],
        ];

        foreach ($sampleNotams as $index => $notamData) {
            // Cycle through available airports
            $airport = $airports[$index % $airports->count()];
            
            Notam::create([
                'identifier' => $notamData['identifier'],
                'icao_code' => $airport->icao,
                'title' => $notamData['title'],
                'description' => $notamData['description'],
                'priority' => $notamData['priority'],
                'active' => true,
                'effective_from' => $notamData['effective_from'],
                'effective_until' => $notamData['effective_until'],
                'user_id' => $admin->id ?? null,
            ]);
        }

        $this->command->info('Created ' . count($sampleNotams) . ' sample NOTAMs');
    }
}