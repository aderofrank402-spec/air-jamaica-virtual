<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingAssessmentAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'user_id',
        'enrollment_id',
        'responses',
        'score',
        'passed',
        'started_at',
        'completed_at',
        'time_taken_minutes',
        'feedback',
    ];

    protected $casts = [
        'responses' => 'array',
        'passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(TrainingAssessment::class, 'assessment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(TrainingEnrollment::class, 'enrollment_id');
    }

    // Get the grade letter based on score
    public function getGradeAttribute(): string
    {
        return match (true) {
            $this->score >= 95 => 'A+',
            $this->score >= 90 => 'A',
            $this->score >= 85 => 'B+',
            $this->score >= 80 => 'B',
            $this->score >= 75 => 'C+',
            $this->score >= 70 => 'C',
            $this->score >= 65 => 'D+',
            $this->score >= 60 => 'D',
            default => 'F',
        };
    }

    // Scope for passed attempts
    public function scopePassed($query)
    {
        return $query->where('passed', true);
    }

    // Scope for failed attempts
    public function scopeFailed($query)
    {
        return $query->where('passed', false);
    }
}