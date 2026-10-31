<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'module_id',
        'title',
        'description',
        'type',
        'questions',
        'time_limit_minutes',
        'max_attempts',
        'passing_score',
        'randomize_questions',
        'show_correct_answers',
        'is_final_exam',
        'is_active',
    ];

    protected $casts = [
        'questions' => 'array',
        'randomize_questions' => 'boolean',
        'show_correct_answers' => 'boolean',
        'is_final_exam' => 'boolean',
        'is_active' => 'boolean',
        'passing_score' => 'decimal:2',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(TrainingAssessmentAttempt::class, 'assessment_id');
    }

    // Check if user can take this assessment
    public function canUserAttempt(User $user): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $attemptCount = $this->attempts()
            ->where('user_id', $user->id)
            ->count();

        return $attemptCount < $this->max_attempts;
    }

    // Get user's best attempt
    public function getUserBestAttempt(User $user): ?TrainingAssessmentAttempt
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->orderByDesc('score')
            ->first();
    }

    // Get user's last attempt
    public function getUserLastAttempt(User $user): ?TrainingAssessmentAttempt
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->latest()
            ->first();
    }

    // Scope for active assessments
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for final exams
    public function scopeFinalExams($query)
    {
        return $query->where('is_final_exam', true);
    }
}