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

    /**
     * Resolves the full URL to the candidate pair photo.
     * Handles Storage uploads (public disk), legacy paths, and default fallbacks.
     */
    public function getPhotoUrl(): string
    {
        if (! empty($this->photo_url)) {
            // Full HTTP/HTTPS URL
            if (str_starts_with($this->photo_url, 'http://') || str_starts_with($this->photo_url, 'https://')) {
                return $this->photo_url;
            }

            // Path starting with /storage/ or storage/
            if (str_starts_with($this->photo_url, '/storage/')) {
                return asset(ltrim($this->photo_url, '/'));
            }

            if (str_starts_with($this->photo_url, 'storage/')) {
                return asset($this->photo_url);
            }

            // If it's a relative path in public_path (e.g. images/paslon-01.png)
            if (file_exists(public_path($this->photo_url))) {
                return asset($this->photo_url);
            }

            // Check if relative path inside public/storage (e.g. candidates/xyz.jpg)
            if (file_exists(public_path('storage/' . ltrim($this->photo_url, '/')))) {
                return asset('storage/' . ltrim($this->photo_url, '/'));
            }
        }

        // Check if candidate photo exists in storage/candidates/
        $storageCandidatePhoto = 'storage/candidates/paslon-0' . $this->candidate_number . '.png';
        if (file_exists(public_path($storageCandidatePhoto))) {
            return asset($storageCandidatePhoto);
        }

        // Fallback to public images
        $defaultPhoto = 'images/paslon-0' . $this->candidate_number . '.png';
        if (file_exists(public_path($defaultPhoto))) {
            return asset($defaultPhoto);
        }

        return asset('images/logo-bem-ubsi.png');
    }

    public function getPhotoAttribute(): string
    {
        return $this->getPhotoUrl();
    }
}

