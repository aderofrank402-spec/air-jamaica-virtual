<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AircraftType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'manufacturer',
        'model',
        'variant',
        'description',
        'image_url',
        'specifications',
        'is_active',
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_active' => 'boolean',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(AircraftDocument::class);
    }

    public function activeDocuments(): HasMany
    {
        return $this->hasMany(AircraftDocument::class)->where('is_active', true);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->manufacturer . ' ' . $this->model . ($this->variant ? ' ' . $this->variant : '');
    }
}
