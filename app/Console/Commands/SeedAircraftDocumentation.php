<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AircraftType;
use App\Models\AircraftDocumentCategory;
use App\Models\AircraftDocument;
use App\Models\User;

class SeedAircraftDocumentation extends Command
{
    protected $signature = 'aircraft-docs:seed';
    protected $description = 'Seed sample aircraft documentation data';

    public function handle()
    {
        $this->info('Seeding aircraft documentation data...');

        // Clear existing data
        AircraftDocument::truncate();
        AircraftDocumentCategory::truncate();
        AircraftType::truncate();
        $this->info('Cleared existing aircraft documentation data...');

        // Create aircraft types
        $aircraftTypes = [
            [
                'name' => 'Boeing 737-800',
                'slug' => 'boeing-737-800',
                'manufacturer' => 'Boeing',
                'model' => '737-800',
                'variant' => '800',
                'description' => 'Boeing 737-800 is a narrow-body aircraft that can seat between 162 to 189 passengers.',
                'image_url' => 'https://via.placeholder.com/400x200/1976d2/ffffff?text=Boeing+737-800',
                'specifications' => [
                    'max_weight' => '79,010 kg',
                    'range' => '5,765 km',
                    'cruise_speed' => '842 km/h',
                    'engines' => '2x CFM56-7B',
                    'length' => '39.5 m',
                    'wingspan' => '35.8 m'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Airbus A320-200',
                'slug' => 'airbus-a320-200',
                'manufacturer' => 'Airbus',
                'model' => 'A320-200',
                'variant' => '200',
                'description' => 'Airbus A320-200 is a narrow-body aircraft with seating for 150 to 186 passengers.',
                'image_url' => 'https://via.placeholder.com/400x200/059669/ffffff?text=Airbus+A320-200',
                'specifications' => [
                    'max_weight' => '78,000 kg',
                    'range' => '6,150 km',
                    'cruise_speed' => '828 km/h',
                    'engines' => '2x V2500 or CFM56-5',
                    'length' => '37.6 m',
                    'wingspan' => '35.8 m'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Boeing 777-300ER',
                'slug' => 'boeing-777-300er',
                'manufacturer' => 'Boeing',
                'model' => '777-300ER',
                'variant' => '300ER',
                'description' => 'Boeing 777-300ER is a long-range wide-body aircraft with seating for 350-400+ passengers.',
                'image_url' => 'https://via.placeholder.com/400x200/dc2626/ffffff?text=Boeing+777-300ER',
                'specifications' => [
                    'max_weight' => '351,530 kg',
                    'range' => '14,685 km',
                    'cruise_speed' => '905 km/h',
                    'engines' => '2x GE90-115B',
                    'length' => '73.9 m',
                    'wingspan' => '64.8 m'
                ],
                'is_active' => true,
            ],
        ];

        foreach ($aircraftTypes as $typeData) {
            $aircraftType = AircraftType::create($typeData);
            $this->info("Created aircraft type: {$aircraftType->name}");
        }

        // Create document categories
        $categories = [
            [
                'name' => 'Performance Data',
                'slug' => 'performance-data',
                'description' => 'Weight & balance charts, performance tables, and operating limitations',
                'icon' => 'fas fa-chart-line',
                'color' => '#3b82f6',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Standard Operating Procedures',
                'slug' => 'standard-operating-procedures',
                'description' => 'Normal, abnormal, and emergency procedures',
                'icon' => 'fas fa-clipboard-list',
                'color' => '#059669',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Aircraft Systems',
                'slug' => 'aircraft-systems',
                'description' => 'Powerplant, flight controls, avionics, and electrical systems',
                'icon' => 'fas fa-cogs',
                'color' => '#dc2626',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Quick Reference',
                'slug' => 'quick-reference',
                'description' => 'QRH, memory items, and cockpit reference cards',
                'icon' => 'fas fa-bookmark',
                'color' => '#7c2d12',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Maintenance Manual',
                'slug' => 'maintenance-manual',
                'description' => 'Maintenance procedures and technical specifications',
                'icon' => 'fas fa-wrench',
                'color' => '#4338ca',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Training Materials',
                'slug' => 'training-materials',
                'description' => 'Training guides, simulators manuals, and instructional content',
                'icon' => 'fas fa-graduation-cap',
                'color' => '#7c3aed',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = AircraftDocumentCategory::create($categoryData);
            $this->info("Created category: {$category->name}");
        }

        // Create sample documents
        $user = User::first();
        $aircraftTypes = AircraftType::all();
        $categories = AircraftDocumentCategory::all();

        $documents = [
            // Boeing 737-800 Documents
            [
                'aircraft_type' => 'boeing-737-800',
                'category' => 'performance-data',
                'title' => 'Weight and Balance Manual',
                'slug' => 'weight-and-balance-manual',
                'description' => 'Complete weight and balance calculations for Boeing 737-800',
                'file_path' => 'aircraft-docs/boeing-737-800/performance/weight-balance-manual.pdf',
                'file_type' => 'pdf',
                'file_size' => 2457600, // 2.4 MB
                'version' => '2.1',
                'effective_date' => '2024-01-15',
                'access_level' => 'public',
                'is_featured' => true,
            ],
            [
                'aircraft_type' => 'boeing-737-800',
                'category' => 'standard-operating-procedures',
                'title' => 'Normal Procedures Checklist',
                'slug' => 'normal-procedures-checklist',
                'description' => 'Standard operating procedures for normal flight operations',
                'file_path' => 'aircraft-docs/boeing-737-800/procedures/normal-procedures.pdf',
                'file_type' => 'pdf',
                'file_size' => 1843200, // 1.8 MB
                'version' => '3.0',
                'effective_date' => '2024-02-01',
                'access_level' => 'public',
                'is_featured' => true,
            ],
            [
                'aircraft_type' => 'boeing-737-800',
                'category' => 'aircraft-systems',
                'title' => 'Engine Systems Manual',
                'slug' => 'engine-systems-manual',
                'description' => 'CFM56-7B engine systems and operations',
                'file_path' => 'aircraft-docs/boeing-737-800/systems/engine-systems.pdf',
                'file_type' => 'pdf',
                'file_size' => 3276800, // 3.2 MB
                'version' => '1.5',
                'effective_date' => '2024-01-20',
                'access_level' => 'restricted',
            ],
            [
                'aircraft_type' => 'boeing-737-800',
                'category' => 'quick-reference',
                'title' => 'Emergency Procedures QRH',
                'slug' => 'emergency-procedures-qrh',
                'description' => 'Quick Reference Handbook for emergency situations',
                'file_path' => 'aircraft-docs/boeing-737-800/qrh/emergency-procedures.pdf',
                'file_type' => 'pdf',
                'file_size' => 1048576, // 1 MB
                'version' => '4.2',
                'effective_date' => '2024-03-01',
                'access_level' => 'public',
                'is_featured' => true,
            ],

            // Airbus A320-200 Documents
            [
                'aircraft_type' => 'airbus-a320-200',
                'category' => 'performance-data',
                'title' => 'Flight Performance Tables',
                'slug' => 'flight-performance-tables',
                'description' => 'Takeoff, landing and cruise performance data',
                'file_path' => 'aircraft-docs/airbus-a320-200/performance/flight-performance.pdf',
                'file_type' => 'pdf',
                'file_size' => 2048000, // 2 MB
                'version' => '1.8',
                'effective_date' => '2024-02-15',
                'access_level' => 'public',
                'is_featured' => true,
            ],
            [
                'aircraft_type' => 'airbus-a320-200',
                'category' => 'standard-operating-procedures',
                'title' => 'Standard Operating Procedures',
                'slug' => 'standard-operating-procedures',
                'description' => 'Complete SOP manual for A320-200 operations',
                'file_path' => 'aircraft-docs/airbus-a320-200/procedures/sop-manual.pdf',
                'file_type' => 'pdf',
                'file_size' => 4194304, // 4 MB
                'version' => '2.3',
                'effective_date' => '2024-01-10',
                'access_level' => 'public',
            ],
            [
                'aircraft_type' => 'airbus-a320-200',
                'category' => 'aircraft-systems',
                'title' => 'Fly-by-Wire Systems Guide',
                'slug' => 'fly-by-wire-systems-guide',
                'description' => 'Complete guide to A320 fly-by-wire flight controls',
                'file_path' => 'aircraft-docs/airbus-a320-200/systems/fbw-systems.pdf',
                'file_type' => 'pdf',
                'file_size' => 2867200, // 2.8 MB
                'version' => '1.2',
                'effective_date' => '2024-01-25',
                'access_level' => 'restricted',
                'is_featured' => true,
            ],

            // Boeing 777-300ER Documents
            [
                'aircraft_type' => 'boeing-777-300er',
                'category' => 'performance-data',
                'title' => 'Long Range Performance Manual',
                'slug' => 'long-range-performance-manual',
                'description' => 'Extended range performance calculations and fuel planning',
                'file_path' => 'aircraft-docs/boeing-777-300er/performance/long-range-performance.pdf',
                'file_type' => 'pdf',
                'file_size' => 3584000, // 3.5 MB
                'version' => '1.0',
                'effective_date' => '2024-03-15',
                'access_level' => 'public',
            ],
        ];

        foreach ($documents as $docData) {
            $aircraftType = $aircraftTypes->where('slug', $docData['aircraft_type'])->first();
            $category = $categories->where('slug', $docData['category'])->first();

            if ($aircraftType && $category) {
                $document = AircraftDocument::create([
                    'aircraft_type_id' => $aircraftType->id,
                    'category_id' => $category->id,
                    'title' => $docData['title'],
                    'slug' => $docData['slug'],
                    'description' => $docData['description'],
                    'file_path' => $docData['file_path'],
                    'file_type' => $docData['file_type'],
                    'file_size' => $docData['file_size'],
                    'version' => $docData['version'],
                    'effective_date' => $docData['effective_date'],
                    'access_level' => $docData['access_level'],
                    'download_count' => rand(10, 500),
                    'is_featured' => $docData['is_featured'] ?? false,
                    'is_active' => true,
                    'uploaded_by' => $user->id,
                ]);

                $this->info("Created document: {$document->title} for {$aircraftType->name}");
            }
        }

        $this->info('Aircraft documentation data seeded successfully!');
        $this->info('You can now access the aircraft documentation at: http://127.0.0.1:8000/aircraft-documentation');
        $this->info('Admin panel: http://127.0.0.1:8000/admin/aircraft-types');
        $this->info('Admin panel: http://127.0.0.1:8000/admin/aircraft-document-categories');
        $this->info('Admin panel: http://127.0.0.1:8000/admin/aircraft-documents');
    }
}
