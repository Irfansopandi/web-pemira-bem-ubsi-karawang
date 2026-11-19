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
        'description',
        'start_time',
        'end_time',
        'status',
        'is_active',
        'voting_status',
        'is_result_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
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

    /**
     * Memeriksa apakah periode ini adalah Periode Aktif Pelaksanaan Sistem.
     */
    public function isSystemActive(): bool
    {
        return (bool) ($this->is_active || $this->status === 'active');
    }

    /**
     * Memeriksa apakah periode ini adalah Periode Arsip (bukan periode aktif).
     */
    public function isArchived(): bool
    {
        return ! $this->isSystemActive() || $this->status === 'archived';
    }

    /**
     * Memeriksa apakah bilik suara DIBUKA untuk pencoblosan mahasiswa.
     * Hanya periode aktif yang bilik suaranya dapat berstatus dibuka.
     */
    public function isVotingOpen(): bool
    {
        return $this->isSystemActive() && $this->voting_status === 'opened';
    }

    /**
     * Memeriksa apakah bilik suara DITUTUP.
     */
    public function isVotingClosed(): bool
    {
        return ! $this->isVotingOpen();
    }

    public function isActive(): bool
    {
        return $this->isSystemActive();
    }
}

