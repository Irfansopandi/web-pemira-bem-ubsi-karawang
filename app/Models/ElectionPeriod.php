<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectionPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'name',
        'start_time',
        'end_time',
        'status',
        'is_result_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'published_at' => 'datetime',
            'is_result_published' => 'boolean',
        ];
    }

    public function candidatePairs(): HasMany
    {
        return $this->hasMany(CandidatePair::class)->orderBy('candidate_number');
    }

    public function voterParticipations(): HasMany
    {
        return $this->hasMany(VoterParticipation::class);
    }

    public function ballots(): HasMany
    {
        return $this->hasMany(Ballot::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && now()->between($this->start_time, $this->end_time);
    }
}

