<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CandidatePair extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_period_id',
        'candidate_number',
        'chairman_name',
        'chairman_nim',
        'chairman_study_program',
        'vice_chairman_name',
        'vice_chairman_nim',
        'vice_chairman_study_program',
        'photo_url',
        'vision',
        'mission',
        'work_programs',
    ];

    protected function casts(): array
    {
        return [
            'candidate_number' => 'integer',
            'mission' => 'array',
            'work_programs' => 'array',
        ];
    }

    public function electionPeriod(): BelongsTo
    {
        return $this->belongsTo(ElectionPeriod::class);
    }

    public function ballots(): HasMany
    {
        return $this->hasMany(Ballot::class);
    }
}

