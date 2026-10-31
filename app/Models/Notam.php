<?php

namespace App\Models;

use App\Contracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * @property int                             $id
 * @property string                          $identifier
 * @property string                          $icao_code
 * @property string                          $title
 * @property string                          $description
 * @property string                          $priority
 * @property bool                            $active
 * @property \Carbon\Carbon                  $effective_from
 * @property \Carbon\Carbon|null             $effective_until
 * @property int|null                        $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null      $user
 */
class Notam extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'notams';

    protected $fillable = [
        'identifier',
        'icao_code',
        'title',
        'description',
        'priority',
        'active',
        'effective_from',
        'effective_until',
        'user_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'effective_from' => 'datetime',
        'effective_until' => 'datetime',
    ];

    public static array $rules = [
        'identifier' => 'required|string|max:20',
        'icao_code' => 'required|string|size:4',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'priority' => 'required|in:LOW,NORMAL,HIGH,CRITICAL',
        'effective_from' => 'required|date',
        'effective_until' => 'nullable|date|after:effective_from',
    ];

    // Priority levels
    public const PRIORITY_LOW = 'LOW';
    public const PRIORITY_NORMAL = 'NORMAL';
    public const PRIORITY_HIGH = 'HIGH';
    public const PRIORITY_CRITICAL = 'CRITICAL';

    /**
     * Get the user who created the NOTAM
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the airport this NOTAM belongs to
     */
    public function airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'icao_code', 'icao');
    }

    /**
     * Scope for active NOTAMs
     */
    public function scopeActive($query)
    {
        return $query->where('active', true)
                    ->where('effective_from', '<=', Carbon::now())
                    ->where(function ($q) {
                        $q->whereNull('effective_until')
                          ->orWhere('effective_until', '>=', Carbon::now());
                    });
    }

    /**
     * Scope for specific airport
     */
    public function scopeForAirport($query, string $icao)
    {
        return $query->where('icao_code', strtoupper($icao));
    }

    /**
     * Scope for priority level
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Check if NOTAM is currently effective
     */
    public function isEffective(): bool
    {
        $now = Carbon::now();
        
        if (!$this->active || $this->effective_from > $now) {
            return false;
        }

        return !$this->effective_until || $this->effective_until >= $now;
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_CRITICAL => 'danger',
            self::PRIORITY_HIGH => 'warning',
            self::PRIORITY_NORMAL => 'info',
            self::PRIORITY_LOW => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Format identifier for display
     */
    public function getFormattedIdentifierAttribute(): string
    {
        return "NOTAM {$this->identifier}";
    }
}