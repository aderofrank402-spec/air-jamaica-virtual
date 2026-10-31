<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TrainingCourse;
use App\Models\TrainingModule;
use App\Models\TrainingEnrollment;
use App\Models\User;

class SeedTrainingData extends Command
{
    protected $signature = 'training:seed';
    protected $description = 'Seed sample training data for testing';

    public function handle()
    {
        $this->info('Seeding training data...');

        // Clear existing data if it exists
        TrainingEnrollment::truncate();
        TrainingModule::truncate();
        TrainingCourse::truncate();
        $this->info('Cleared existing training data...');

        // Create sample courses
        $courses = [
            [
                'title' => 'Boeing 737-800 Type Rating',
                'slug' => 'boeing-737-800-type-rating',
                'description' => 'Complete type rating course for Boeing 737-800 aircraft',
                'category' => 'type_rating',
                'difficulty_level' => 'advanced',
                'estimated_hours' => 120,
                'passing_score' => 80.00,
                'is_active' => true,
                'is_mandatory' => true,
                'aircraft_type' => 'Boeing 737-800',
            ],
            [
                'title' => 'Basic Flight Training',
                'slug' => 'basic-flight-training',
                'description' => 'Fundamental flight training for new pilots',
                'category' => 'orientation',
                'difficulty_level' => 'beginner',
                'estimated_hours' => 40,
                'passing_score' => 75.00,
                'is_active' => true,
                'is_mandatory' => true,
            ],
            [
                'title' => 'Emergency Procedures',
                'slug' => 'emergency-procedures',
                'description' => 'Emergency procedures and safety training',
                'category' => 'safety',
                'difficulty_level' => 'intermediate',
                'estimated_hours' => 16,
                'passing_score' => 85.00,
                'is_active' => true,
                'is_mandatory' => true,
            ],
        ];

        foreach ($courses as $courseData) {
            $course = TrainingCourse::create($courseData);
            $this->info("Created course: {$course->title}");

            // Create modules for each course
            $modules = [
                [
                    'title' => 'Introduction to ' . $course->title,
                    'slug' => 'introduction-' . strtolower(str_replace(' ', '-', $course->title)),
                    'description' => 'Overview and introduction module',
                    'type' => 'reading',
                    'sort_order' => 1,
                    'estimated_minutes' => 60,
                    'content' => 'This module covers the basic introduction to the course.',
                    'is_required' => true,
                    'is_active' => true,
                ],
                [
                    'title' => 'Theory and Fundamentals',
                    'slug' => 'theory-fundamentals-' . strtolower(str_replace(' ', '-', $course->title)),
                    'description' => 'Theoretical knowledge and fundamentals',
                    'type' => 'reading',
                    'sort_order' => 2,
                    'estimated_minutes' => 120,
                    'content' => 'Detailed theoretical content and fundamentals.',
                    'is_required' => true,
                    'is_active' => true,
                ],
                [
                    'title' => 'Practical Application',
                    'slug' => 'practical-application-' . strtolower(str_replace(' ', '-', $course->title)),
                    'description' => 'Hands-on practical training',
                    'type' => 'practical',
                    'sort_order' => 3,
                    'estimated_minutes' => 180,
                    'content' => 'Practical exercises and real-world scenarios.',
                    'is_required' => true,
                    'is_active' => true,
                ],
            ];

            foreach ($modules as $moduleData) {
                $moduleData['course_id'] = $course->id;
                $module = TrainingModule::create($moduleData);
                $this->info("  - Created module: {$module->title}");
            }
        }

        // Create sample enrollments for existing users
        $users = User::limit(3)->get();
        $courses = TrainingCourse::all();

        foreach ($users as $user) {
            foreach ($courses->take(2) as $course) {
                $enrollment = TrainingEnrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'status' => 'enrolled',
                    'progress_percentage' => rand(0, 100),
                    'enrolled_at' => now(),
                ]);
                $this->info("Enrolled {$user->name} in {$course->title}");
            }
        }

        $this->info('Training data seeded successfully!');
        $this->info('You can now access the admin panel at: http://127.0.0.1:8000/admin');
        $this->info('Training Courses: http://127.0.0.1:8000/admin/training-courses');
        $this->info('Training Enrollments: http://127.0.0.1:8000/admin/training-enrollments');
        $this->info('Training Modules: http://127.0.0.1:8000/admin/training-modules');
    }
}