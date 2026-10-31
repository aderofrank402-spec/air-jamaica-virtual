<?php

namespace Database\Seeders;

use App\Models\TrainingCourse;
use App\Models\TrainingModule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TrainingSystemSeeder extends Seeder
{
    public function run()
    {
        // 1. New Pilot Orientation Course
        $orientationCourse = TrainingCourse::create([
            'title' => 'Air Jamaica Virtual New Pilot Orientation',
            'slug' => 'new-pilot-orientation',
            'description' => 'Welcome to Air Jamaica Virtual! This comprehensive orientation course will introduce you to our virtual airline, covering everything from company culture to basic operational procedures.',
            'objectives' => 'Upon completion, pilots will understand AJV operations, communication protocols, and be ready to begin flight training.',
            'category' => 'orientation',
            'difficulty_level' => 'beginner',
            'estimated_hours' => 4,
            'passing_score' => 85.00,
            'is_mandatory' => true,
            'requires_instructor' => false,
            'validity_months' => null, // Permanent certification
            'is_active' => true,
            'completion_criteria' => '<p>Complete all modules and pass the final assessment with 85% or higher.</p>',
        ]);

        // Modules for Orientation Course
        $orientationModules = [
            [
                'title' => 'Welcome to Air Jamaica Virtual',
                'type' => 'reading',
                'content' => '<h2>Welcome to Air Jamaica Virtual Airlines!</h2>
                <p>We are excited to welcome you to our virtual airline family. Air Jamaica Virtual (AJV) is committed to providing realistic flight simulation experiences while maintaining the highest standards of professionalism and fun.</p>
                <h3>Our Mission</h3>
                <p>To provide an authentic and enjoyable virtual airline experience that captures the spirit of Caribbean aviation excellence.</p>
                <h3>Company Values</h3>
                <ul>
                <li><strong>Excellence:</strong> We strive for the highest standards in all aspects of our operations</li>
                <li><strong>Respect:</strong> We treat all members with dignity and professionalism</li>
                <li><strong>Safety:</strong> Safety is our top priority in all flight operations</li>
                <li><strong>Community:</strong> We foster a welcoming environment for pilots of all skill levels</li>
                </ul>',
                'estimated_minutes' => 30,
                'sort_order' => 1,
            ],
            [
                'title' => 'Virtual Airline Operations Overview',
                'type' => 'reading',
                'content' => '<h2>How Air Jamaica Virtual Works</h2>
                <p>As a virtual airline, we simulate real-world airline operations using flight simulation software. Here\'s what you need to know:</p>
                <h3>Flight Operations</h3>
                <ul>
                <li>Bid on scheduled flights from our route network</li>
                <li>File proper flight plans</li>
                <li>Complete flights using approved aircraft</li>
                <li>Submit PIREPs (Pilot Reports) after each flight</li>
                </ul>
                <h3>Rank Progression</h3>
                <p>Advance through pilot ranks by accumulating flight hours and maintaining high standards.</p>',
                'estimated_minutes' => 45,
                'sort_order' => 2,
            ],
            [
                'title' => 'Communication Protocols',
                'type' => 'reading',
                'content' => '<h2>Communication Standards</h2>
                <p>Professional communication is essential to our operations:</p>
                <h3>Discord Server</h3>
                <p>Join our Discord server for real-time communication with other pilots and staff.</p>
                <h3>Forums</h3>
                <p>Participate in our community forums for announcements and discussions.</p>
                <h3>PIREP Communication</h3>
                <p>Use professional language in all flight reports and communications.</p>',
                'estimated_minutes' => 20,
                'sort_order' => 3,
            ],
        ];

        foreach ($orientationModules as $moduleData) {
            TrainingModule::create(array_merge($moduleData, [
                'course_id' => $orientationCourse->id,
                'slug' => Str::slug($moduleData['title']),
                'is_required' => true,
                'is_active' => true,
            ]));
        }

        // 2. Boeing 737-800 Type Rating Course
        $b737Course = TrainingCourse::create([
            'title' => 'Boeing 737-800 Type Rating',
            'slug' => 'boeing-737-800-type-rating',
            'description' => 'Comprehensive type rating course for the Boeing 737-800, covering systems, procedures, and operational techniques.',
            'objectives' => 'Pilots will gain thorough knowledge of 737-800 systems and be certified to operate this aircraft type.',
            'category' => 'type_rating',
            'difficulty_level' => 'advanced',
            'estimated_hours' => 25,
            'passing_score' => 80.00,
            'is_mandatory' => false,
            'requires_instructor' => true,
            'validity_months' => 24,
            'is_active' => true,
            'aircraft_type' => 'Boeing 737-800',
            'prerequisites' => [$orientationCourse->id],
            'completion_criteria' => '<p>Complete all training modules, pass written examination, and complete practical checkride with certified instructor.</p>',
        ]);

        // 3. Caribbean Routes Familiarization
        $routesCourse = TrainingCourse::create([
            'title' => 'Caribbean Routes Familiarization',
            'slug' => 'caribbean-routes-familiarization',
            'description' => 'Specialized training for Caribbean region flying, including weather patterns, airport procedures, and route-specific considerations.',
            'objectives' => 'Understand unique aspects of Caribbean aviation including tropical weather, island airports, and regional procedures.',
            'category' => 'route_specific',
            'difficulty_level' => 'intermediate',
            'estimated_hours' => 8,
            'passing_score' => 85.00,
            'is_mandatory' => false,
            'requires_instructor' => false,
            'validity_months' => 36,
            'is_active' => true,
            'prerequisites' => [$orientationCourse->id],
            'completion_criteria' => '<p>Complete all modules and pass the final assessment covering Caribbean aviation knowledge.</p>',
        ]);

        // 4. Safety Management System (SMS) Training
        $smsCourse = TrainingCourse::create([
            'title' => 'Safety Management System (SMS) Training',
            'slug' => 'safety-management-system-training',
            'description' => 'Comprehensive safety training covering SMS principles, hazard identification, risk management, and safety culture.',
            'objectives' => 'Develop strong safety awareness and understand our safety management systems and reporting procedures.',
            'category' => 'safety',
            'difficulty_level' => 'intermediate',
            'estimated_hours' => 6,
            'passing_score' => 90.00,
            'is_mandatory' => true,
            'requires_instructor' => false,
            'validity_months' => 12,
            'is_active' => true,
            'prerequisites' => [$orientationCourse->id],
            'completion_criteria' => '<p>Complete all safety modules and achieve 90% or higher on the safety assessment.</p>',
        ]);

        // 5. Advanced Weather and Flight Planning
        $weatherCourse = TrainingCourse::create([
            'title' => 'Advanced Weather and Flight Planning',
            'slug' => 'advanced-weather-flight-planning',
            'description' => 'Advanced course covering complex weather interpretation, flight planning optimization, and decision-making in challenging conditions.',
            'objectives' => 'Master advanced weather analysis and flight planning techniques for safe and efficient operations.',
            'category' => 'advanced',
            'difficulty_level' => 'expert',
            'estimated_hours' => 12,
            'passing_score' => 85.00,
            'is_mandatory' => false,
            'requires_instructor' => true,
            'validity_months' => 18,
            'is_active' => true,
            'prerequisites' => [$orientationCourse->id, $routesCourse->id],
            'completion_criteria' => '<p>Complete advanced modules, demonstrate weather analysis skills, and pass comprehensive examination.</p>',
        ]);

        // 6. Instructor Certification Program
        $instructorCourse = TrainingCourse::create([
            'title' => 'Air Jamaica Virtual Instructor Certification',
            'slug' => 'instructor-certification-program',
            'description' => 'Comprehensive instructor training program for experienced pilots who wish to become certified training instructors.',
            'objectives' => 'Develop teaching skills and knowledge required to effectively train and evaluate other pilots within AJV.',
            'category' => 'instructor',
            'difficulty_level' => 'expert',
            'estimated_hours' => 20,
            'passing_score' => 90.00,
            'is_mandatory' => false,
            'requires_instructor' => true,
            'validity_months' => 24,
            'is_active' => true,
            'prerequisites' => [$orientationCourse->id, $smsCourse->id],
            'completion_criteria' => '<p>Complete instructor development modules, demonstrate teaching proficiency, and pass comprehensive instructor examination.</p>',
        ]);

        $this->command->info('Training system seeded with ' . TrainingCourse::count() . ' courses.');
    }
}