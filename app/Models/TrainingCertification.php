<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'certification_number',
        'title',
        'aircraft_type',
        'issued_at',
        'expires_at',
        'status',
        'issued_by',
        'final_score',
        'notes',
        'competencies',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'final_score' => 'decimal:2',
        'competencies' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    // Status options
    public static function getStatusOptions(): array
    {
        return [
            'active' => 'Active',
            'expired' => 'Expired',
            'suspended' => 'Suspended',
            'revoked' => 'Revoked',
        ];
    }

    // Check if certification is active
    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    // Check if certification has expired
    public function isExpired(): bool
    {
        return $this->expires_at && now()->isAfter($this->expires_at);
    }

    // Check if certification is expiring soon (within 30 days)
    public function isExpiringSoon(): bool
    {
        return $this->expires_at && 
               now()->addDays(30)->isAfter($this->expires_at) && 
               !$this->isExpired();
    }

    // Get days until expiration
    public function getDaysUntilExpiration(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }

        return now()->diffInDays($this->expires_at, false);
    }

    // Scope for active certifications
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    // Scope for expired certifications
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere(function ($q) {
                        $q->whereNotNull('expires_at')
                          ->where('expires_at', '<', now());
                    });
    }

    // Scope for expiring soon
    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('status', 'active')
                    ->whereNotNull('expires_at')
                    ->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }

    // Scope by aircraft type
    public function scopeByAircraftType($query, string $aircraftType)
    {
        return $query->where('aircraft_type', $aircraftType);
    }
}