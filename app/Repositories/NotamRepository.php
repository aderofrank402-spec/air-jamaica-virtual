<?php

namespace App\Repositories;

use App\Contracts\Repository;
use App\Models\Notam;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class NotamRepository extends Repository
{
    protected $fieldSearchable = [
        'identifier',
        'icao_code',
        'title',
        'description',
        'priority',
    ];

    public function model(): string
    {
        return Notam::class;
    }

    /**
     * Get active NOTAMs for a specific airport
     */
    public function getActiveForAirport(string $icao): Collection
    {
        return $this->scopeQuery(function ($query) use ($icao) {
            return $query->active()
                ->forAirport($icao)
                ->orderBy('priority', 'desc')
                ->orderBy('effective_from', 'desc');
        })->all();
    }

    /**
     * Get all active NOTAMs
     */
    public function getAllActive(): Collection
    {
        return $this->scopeQuery(function ($query) {
            return $query->active()
                ->with(['user', 'airport'])
                ->orderBy('priority', 'desc')
                ->orderBy('effective_from', 'desc');
        })->all();
    }

    /**
     * Get NOTAMs by priority
     */
    public function getByPriority(string $priority): Collection
    {
        return $this->scopeQuery(function ($query) use ($priority) {
            return $query->active()
                ->byPriority($priority)
                ->with(['user', 'airport'])
                ->orderBy('effective_from', 'desc');
        })->all();
    }

    /**
     * Get recent NOTAMs
     */
    public function getRecent(int $count = 10): Collection
    {
        return $this->scopeQuery(function ($query) use ($count) {
            return $query->active()
                ->with(['user', 'airport'])
                ->orderBy('created_at', 'desc')
                ->limit($count);
        })->all();
    }

    /**
     * Get NOTAMs for multiple airports
     */
    public function getForAirports(array $icaoCodes): Collection
    {
        return $this->scopeQuery(function ($query) use ($icaoCodes) {
            return $query->active()
                ->whereIn('icao_code', array_map('strtoupper', $icaoCodes))
                ->with(['user', 'airport'])
                ->orderBy('priority', 'desc')
                ->orderBy('effective_from', 'desc');
        })->all();
    }

    /**
     * Get critical NOTAMs
     */
    public function getCritical(): Collection
    {
        return $this->getByPriority(Notam::PRIORITY_CRITICAL);
    }

    /**
     * Get expiring NOTAMs (within next 24 hours)
     */
    public function getExpiringSoon(): Collection
    {
        return $this->scopeQuery(function ($query) {
            return $query->active()
                ->whereNotNull('effective_until')
                ->whereBetween('effective_until', [
                    Carbon::now(),
                    Carbon::now()->addDay()
                ])
                ->with(['user', 'airport'])
                ->orderBy('effective_until', 'asc');
        })->all();
    }

    /**
     * Search NOTAMs by text
     */
    public function searchByText(string $searchTerm): Collection
    {
        return $this->scopeQuery(function ($query) use ($searchTerm) {
            return $query->active()
                ->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhere('identifier', 'like', "%{$searchTerm}%")
                      ->orWhere('icao_code', 'like', "%{$searchTerm}%");
                })
                ->with(['user', 'airport'])
                ->orderBy('priority', 'desc')
                ->orderBy('effective_from', 'desc');
        })->all();
    }
}