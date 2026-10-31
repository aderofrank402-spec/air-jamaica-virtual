<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Training Courses Table
        if (!Schema::hasTable('training_courses')) {
            Schema::create('training_courses', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description');
                $table->text('objectives')->nullable();
                $table->enum('category', [
                    'orientation', 'aircraft_systems', 'type_rating', 'route_specific',
                    'advanced', 'instructor', 'safety', 'recurrent'
                ]);
                $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced', 'expert']);
                $table->integer('estimated_hours')->default(0);
                $table->decimal('passing_score', 5, 2)->default(80.00);
                $table->boolean('is_mandatory')->default(false);
                $table->boolean('requires_instructor')->default(false);
                $table->json('prerequisites')->nullable();
                $table->integer('validity_months')->nullable();
                $table->boolean('is_active')->default(true);
                $table->string('aircraft_type')->nullable();
                $table->text('completion_criteria')->nullable();
                $table->timestamps();
                
                $table->index(['category', 'is_active']);
                $table->index(['aircraft_type']);
            });
        }

        // Training Modules Table
        if (!Schema::hasTable('training_modules')) {
            Schema::create('training_modules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id');
                $table->string('title');
                $table->string('slug');
                $table->text('description')->nullable();
                $table->enum('type', ['reading', 'video', 'interactive', 'assessment', 'practical', 'simulator']);
                $table->text('content')->nullable();
                $table->json('resources')->nullable();
                $table->integer('estimated_minutes')->default(0);
                $table->integer('sort_order')->default(0);
                $table->boolean('is_required')->default(true);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index(['course_id', 'sort_order']);
                $table->unique(['course_id', 'slug']);
            });
        }

        // Training Enrollments Table
        if (!Schema::hasTable('training_enrollments')) {
            Schema::create('training_enrollments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('course_id');
                $table->enum('status', ['enrolled', 'in_progress', 'completed', 'failed', 'expired', 'withdrawn'])->default('enrolled');
                $table->timestamp('enrolled_at');
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->decimal('progress_percentage', 5, 2)->default(0.00);
                $table->decimal('final_score', 5, 2)->nullable();
                $table->integer('attempts')->default(0);
                $table->json('module_progress')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('instructor_id')->nullable();
                $table->timestamps();
                
                $table->unique(['user_id', 'course_id']);
                $table->index(['status', 'expires_at']);
            });
        }

        // Training Assessments Table
        if (!Schema::hasTable('training_assessments')) {
            Schema::create('training_assessments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id');
                $table->unsignedBigInteger('module_id')->nullable();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('type', ['multiple_choice', 'true_false', 'practical', 'simulator', 'oral_exam', 'checkride']);
                $table->json('questions');
                $table->integer('time_limit_minutes')->nullable();
                $table->integer('max_attempts')->default(3);
                $table->decimal('passing_score', 5, 2)->default(80.00);
                $table->boolean('randomize_questions')->default(true);
                $table->boolean('show_correct_answers')->default(true);
                $table->boolean('is_final_exam')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index(['course_id', 'is_final_exam']);
            });
        }

        // Training Assessment Attempts Table
        if (!Schema::hasTable('training_assessment_attempts')) {
            Schema::create('training_assessment_attempts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('assessment_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('enrollment_id');
                $table->json('responses');
                $table->decimal('score', 5, 2);
                $table->boolean('passed');
                $table->timestamp('started_at');
                $table->timestamp('completed_at');
                $table->integer('time_taken_minutes');
                $table->text('feedback')->nullable();
                $table->timestamps();
                
                $table->index(['user_id', 'assessment_id']);
                $table->index(['enrollment_id', 'passed']);
            });
        }

        // Training Certifications Table
        if (!Schema::hasTable('training_certifications')) {
            Schema::create('training_certifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('course_id');
                $table->string('certification_number')->unique();
                $table->string('title');
                $table->string('aircraft_type')->nullable();
                $table->timestamp('issued_at');
                $table->timestamp('expires_at')->nullable();
                $table->enum('status', ['active', 'expired', 'suspended', 'revoked'])->default('active');
                $table->unsignedBigInteger('issued_by');
                $table->decimal('final_score', 5, 2);
                $table->text('notes')->nullable();
                $table->json('competencies')->nullable();
                $table->timestamps();
                
                $table->index(['user_id', 'status']);
                $table->index(['aircraft_type', 'status']);
                $table->index(['expires_at']);
            });
        }

        // Training Sessions Table
        if (!Schema::hasTable('training_sessions')) {
            Schema::create('training_sessions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id');
                $table->unsignedBigInteger('instructor_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->timestamp('scheduled_at');
                $table->integer('duration_minutes');
                $table->integer('max_participants')->default(1);
                $table->enum('type', ['one_on_one', 'group', 'practical', 'simulator']);
                $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
                $table->string('meeting_link')->nullable();
                $table->text('session_notes')->nullable();
                $table->timestamps();
                
                $table->index(['instructor_id', 'scheduled_at']);
                $table->index(['course_id', 'status']);
            });
        }

        // Training Session Participants Table
        if (!Schema::hasTable('training_session_participants')) {
            Schema::create('training_session_participants', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('session_id');
                $table->unsignedBigInteger('user_id');
                $table->enum('status', ['registered', 'attended', 'absent', 'cancelled'])->default('registered');
                $table->decimal('performance_score', 5, 2)->nullable();
                $table->text('instructor_feedback')->nullable();
                $table->timestamps();
                
                $table->unique(['session_id', 'user_id']);
            });
        }

        // Add foreign keys with error handling
        $this->addForeignKeysWithErrorHandling();
    }

    private function addForeignKeysWithErrorHandling()
    {
        $foreignKeys = [
            ['training_modules', 'course_id', 'training_courses', 'id', 'cascade'],
            ['training_enrollments', 'user_id', 'users', 'id', 'cascade'],
            ['training_enrollments', 'course_id', 'training_courses', 'id', 'cascade'],
            ['training_enrollments', 'instructor_id', 'users', 'id', null],
            ['training_assessments', 'course_id', 'training_courses', 'id', 'cascade'],
            ['training_assessments', 'module_id', 'training_modules', 'id', 'cascade'],
            ['training_assessment_attempts', 'assessment_id', 'training_assessments', 'id', 'cascade'],
            ['training_assessment_attempts', 'user_id', 'users', 'id', 'cascade'],
            ['training_assessment_attempts', 'enrollment_id', 'training_enrollments', 'id', 'cascade'],
            ['training_certifications', 'user_id', 'users', 'id', 'cascade'],
            ['training_certifications', 'course_id', 'training_courses', 'id', 'cascade'],
            ['training_certifications', 'issued_by', 'users', 'id', null],
            ['training_sessions', 'course_id', 'training_courses', 'id', 'cascade'],
            ['training_sessions', 'instructor_id', 'users', 'id', null],
            ['training_session_participants', 'session_id', 'training_sessions', 'id', 'cascade'],
            ['training_session_participants', 'user_id', 'users', 'id', 'cascade'],
        ];

        foreach ($foreignKeys as [$table, $column, $refTable, $refColumn, $onDelete]) {
            try {
                Schema::table($table, function (Blueprint $table) use ($column, $refTable, $refColumn, $onDelete) {
                    $foreign = $table->foreign($column)->references($refColumn)->on($refTable);
                    if ($onDelete) {
                        $foreign->onDelete($onDelete);
                    }
                });
            } catch (\Exception $e) {
                // Foreign key creation failed, continue
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('training_session_participants');
        Schema::dropIfExists('training_sessions');
        Schema::dropIfExists('training_certifications');
        Schema::dropIfExists('training_assessment_attempts');
        Schema::dropIfExists('training_assessments');
        Schema::dropIfExists('training_enrollments');
        Schema::dropIfExists('training_modules');
        Schema::dropIfExists('training_courses');
    }
};