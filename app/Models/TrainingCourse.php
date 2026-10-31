<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'objectives',
        'category',
        'difficulty_level',
        'estimated_hours',
        'passing_score',
        'is_mandatory',
        'requires_instructor',
        'prerequisites',
        'validity_months',
        'is_active',
        'aircraft_type',
        'completion_criteria',
    ];

    protected $casts = [
        'prerequisites' => 'array',
        'is_mandatory' => 'boolean',
        'requires_instructor' => 'boolean',
        'is_active' => 'boolean',
        'passing_score' => 'decimal:2',
    ];

    public function modules(): HasMany
    {
        return $this->hasMany(TrainingModule::class, 'course_id')->orderBy('sort_order');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(TrainingEnrollment::class, 'course_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(TrainingAssessment::class, 'course_id');
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(TrainingCertification::class, 'course_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(TrainingSession::class, 'course_id');
    }

    public function activeEnrollments(): HasMany
    {
        return $this->enrollments()->whereIn('status', ['enrolled', 'in_progress']);
    }

    public function completedEnrollments(): HasMany
    {
        return $this->enrollments()->where('status', 'completed');
    }

    // Check if user has completed prerequisites
    public function hasCompletedPrerequisites(User $user): bool
    {
        if (empty($this->prerequisites)) {
            return true;
        }

        $completedCourses = $user->trainingEnrollments()
            ->where('status', 'completed')
            ->pluck('course_id')
            ->toArray();

        return empty(array_diff($this->prerequisites, $completedCourses));
    }

    // Get completion rate
    public function getCompletionRateAttribute(): float
    {
        $totalEnrollments = $this->enrollments()->count();
        if ($totalEnrollments === 0) {
            return 0;
        }

        $completedEnrollments = $this->completedEnrollments()->count();
        return round(($completedEnrollments / $totalEnrollments) * 100, 2);
    }

    // Get average score
    public function getAverageScoreAttribute(): float
    {
        return $this->completedEnrollments()->avg('final_score') ?? 0;
    }

    // Category options for forms
    public static function getCategoryOptions(): array
    {
        return [
            'orientation' => 'Orientation & Welcome',
            'aircraft_systems' => 'Aircraft Systems',
            'type_rating' => 'Type Rating',
            'route_specific' => 'Route Specific Training',
            'advanced' => 'Advanced Training',
            'instructor' => 'Instructor Training',
            'safety' => 'Safety Training',
            'recurrent' => 'Recurrent Training',
        ];
    }

    // Difficulty level options
    public static function getDifficultyOptions(): array
    {
        return [
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
            'expert' => 'Expert',
        ];
    }

    // Scope for active courses
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for mandatory courses
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    // Scope by category
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // Scope by aircraft type
    public function scopeByAircraftType($query, string $aircraftType)
    {
        return $query->where('aircraft_type', $aircraftType);
    }
}