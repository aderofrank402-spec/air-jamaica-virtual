<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'enrolled_at',
        'started_at',
        'completed_at',
        'expires_at',
        'progress_percentage',
        'final_score',
        'attempts',
        'module_progress',
        'notes',
        'instructor_id',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
        'progress_percentage' => 'decimal:2',
        'final_score' => 'decimal:2',
        'module_progress' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // Status options
    public static function getStatusOptions(): array
    {
        return [
            'enrolled' => 'Enrolled',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'expired' => 'Expired',
            'withdrawn' => 'Withdrawn',
        ];
    }

    // Check if enrollment is active
    public function isActive(): bool
    {
        return in_array($this->status, ['enrolled', 'in_progress']);
    }

    // Check if enrollment is completed
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    // Check if enrollment has expired
    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
               ($this->expires_at && now()->isAfter($this->expires_at));
    }

    // Update module progress
    public function updateModuleProgress(int $moduleId, array $progressData): void
    {
        $moduleProgress = $this->module_progress ?? [];
        $moduleProgress[$moduleId] = array_merge(
            $moduleProgress[$moduleId] ?? [],
            $progressData
        );
        
        $this->update(['module_progress' => $moduleProgress]);
        $this->recalculateProgress();
    }

    // Mark module as completed
    public function completeModule(int $moduleId, int $timeSpent = 0): void
    {
        $this->updateModuleProgress($moduleId, [
            'completed' => true,
            'completed_at' => now()->toISOString(),
            'time_spent' => $timeSpent,
        ]);
    }

    // Recalculate overall progress percentage
    public function recalculateProgress(): void
    {
        $totalModules = $this->course->modules()->where('is_required', true)->count();
        
        if ($totalModules === 0) {
            $this->update(['progress_percentage' => 100]);
            return;
        }

        $completedModules = 0;
        $moduleProgress = $this->module_progress ?? [];

        foreach ($this->course->modules()->where('is_required', true)->get() as $module) {
            if (($moduleProgress[$module->id]['completed'] ?? false)) {
                $completedModules++;
            }
        }

        $progressPercentage = round(($completedModules / $totalModules) * 100, 2);
        $this->update(['progress_percentage' => $progressPercentage]);

        // Auto-complete if all modules done and passing score achieved
        if ($progressPercentage >= 100 && $this->final_score >= $this->course->passing_score) {
            $this->markAsCompleted();
        }
    }

    // Mark enrollment as completed
    public function markAsCompleted(float $finalScore = null): void
    {
        $updateData = [
            'status' => 'completed',
            'completed_at' => now(),
            'progress_percentage' => 100,
        ];

        if ($finalScore !== null) {
            $updateData['final_score'] = $finalScore;
        }

        // Set expiration if course has validity period
        if ($this->course->validity_months) {
            $updateData['expires_at'] = now()->addMonths($this->course->validity_months);
        }

        $this->update($updateData);

        // Create certification if passed
        if ($this->final_score >= $this->course->passing_score) {
            $this->createCertification();
        }
    }

    // Create certification upon completion
    protected function createCertification(): void
    {
        TrainingCertification::create([
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'certification_number' => $this->generateCertificationNumber(),
            'title' => $this->course->title,
            'aircraft_type' => $this->course->aircraft_type,
            'issued_at' => now(),
            'expires_at' => $this->expires_at,
            'status' => 'active',
            'issued_by' => $this->instructor_id ?? 1, // System or instructor
            'final_score' => $this->final_score,
        ]);
    }

    // Generate unique certification number
    protected function generateCertificationNumber(): string
    {
        $prefix = strtoupper(substr($this->course->category, 0, 3));
        $userId = str_pad($this->user_id, 4, '0', STR_PAD_LEFT);
        $courseId = str_pad($this->course_id, 3, '0', STR_PAD_LEFT);
        $timestamp = now()->format('Ymd');
        
        return "{$prefix}-{$userId}-{$courseId}-{$timestamp}";
    }

    // Scope for active enrollments
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['enrolled', 'in_progress']);
    }

    // Scope for completed enrollments
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Scope for expired enrollments
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere(function ($q) {
                        $q->whereNotNull('expires_at')
                          ->where('expires_at', '<', now());
                    });
    }
}