<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class AircraftDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'aircraft_type_id',
        'category_id',
        'title',
        'slug',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'version',
        'effective_date',
        'expiry_date',
        'access_level',
        'required_ratings',
        'download_count',
        'is_featured',
        'is_active',
        'uploaded_by',
    ];

    protected $casts = [
        'required_ratings' => 'array',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'download_count' => 'integer',
        'file_size' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function aircraftType(): BelongsTo
    {
        return $this->belongsTo(AircraftType::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AircraftDocumentCategory::class, 'category_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(AircraftDocumentVersion::class, 'document_id');
    }

    public function latestVersion(): HasOne
    {
        return $this->hasOne(AircraftDocumentVersion::class, 'document_id')
            ->orderBy('version_number', 'desc');
    }

    public function accessLogs(): HasMany
    {
        return $this->hasMany(AircraftDocumentAccessLog::class, 'document_id');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(AircraftDocumentBookmark::class, 'document_id');
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return 'Unknown';
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }
}
