<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class VoterParticipation extends Model
{
    use HasFactory;

    protected $table = 'voter_participations';

    protected $fillable = [
        'election_period_id',
        'student_nim',
        'has_voted',
        'voted_at',
        'encrypted_vote_receipt',
        'verified_by_admin_id',
        'verified_at',
        'signature_path',
        'phone',
        'tps_location',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'has_voted' => 'boolean',
            'voted_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function electionPeriod(): BelongsTo
    {
        return $this->belongsTo(ElectionPeriod::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_nim', 'nim');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'verified_by_admin_id');
    }

    /**
     * Cek apakah pemilih sudah terverifikasi hadir di Meja Registrasi TPS.
     */
    public function isVerified(): bool
    {
        return ! is_null($this->verified_at);
    }

    /**
     * URL tanda tangan digital pemilih.
     */
    public function getSignatureUrlAttribute(): ?string
    {
        if (empty($this->signature_path)) {
            return null;
        }

        return asset('storage/' . $this->signature_path);
    }

    /**
     * Get decrypted candidate pair id for post-voting summary.
     */
    public function getDecryptedCandidatePairId(): ?int
    {
        if (empty($this->encrypted_vote_receipt)) {
            return null;
        }

        try {
            return (int) Crypt::decryptString($this->encrypted_vote_receipt);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
