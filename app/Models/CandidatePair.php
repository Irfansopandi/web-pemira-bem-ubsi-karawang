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
        $defaultPhoto = 'images/paslon-0' . $this->candidate_number . '.png';

        if (! empty($this->photo_url)) {
            // Full HTTP/HTTPS URL
            if (str_starts_with($this->photo_url, 'http://') || str_starts_with($this->photo_url, 'https://')) {
                return $this->photo_url;
            }

            $cleanPath = ltrim($this->photo_url, '/');

            // If path exists directly in public
            if (file_exists(public_path($cleanPath))) {
                return asset($cleanPath);
            }

            // Path starting with storage/ or /storage/
            $storageRelative = preg_replace('#^storage/#', '', $cleanPath);
            if (file_exists(storage_path('app/public/' . $storageRelative))) {
                return asset('storage/' . $storageRelative);
            }
        }

        // Fallback to public images (e.g. public/images/paslon-01.png)
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

