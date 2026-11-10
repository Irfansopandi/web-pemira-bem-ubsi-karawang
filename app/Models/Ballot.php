<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ballot extends Model
{
    use HasFactory;

    public $timestamps = false; // Only created_at is stored

    protected $fillable = [
        'election_period_id',
        'candidate_pair_id',
        'ballot_hash',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function electionPeriod(): BelongsTo
    {
        return $this->belongsTo(ElectionPeriod::class);
    }

    public function candidatePair(): BelongsTo
    {
        return $this->belongsTo(CandidatePair::class);
    }
}

