<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'type',
        'content',
        'resources',
        'estimated_minutes',
        'sort_order',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'resources' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(TrainingAssessment::class, 'module_id');
    }

    // Module type options
    public static function getTypeOptions(): array
    {
        return [
            'reading' => 'Reading Material',
            'video' => 'Video Content',
            'interactive' => 'Interactive Content',
            'assessment' => 'Assessment/Quiz',
            'practical' => 'Practical Exercise',
            'simulator' => 'Simulator Training',
        ];
    }

    // Scope for active modules
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for required modules
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    // Check if user has completed this module
    public function isCompletedByUser(User $user): bool
    {
        $enrollment = TrainingEnrollment::where('user_id', $user->id)
            ->where('course_id', $this->course_id)
            ->first();

        if (!$enrollment || !$enrollment->module_progress) {
            return false;
        }

        return $enrollment->module_progress[$this->id]['completed'] ?? false;
    }

    // Get user's progress on this module
    public function getUserProgress(User $user): array
    {
        $enrollment = TrainingEnrollment::where('user_id', $user->id)
            ->where('course_id', $this->course_id)
            ->first();

        if (!$enrollment || !$enrollment->module_progress) {
            return [
                'completed' => false,
                'started_at' => null,
                'completed_at' => null,
                'time_spent' => 0,
            ];
        }

        return $enrollment->module_progress[$this->id] ?? [
            'completed' => false,
            'started_at' => null,
            'completed_at' => null,
            'time_spent' => 0,
        ];
    }
}