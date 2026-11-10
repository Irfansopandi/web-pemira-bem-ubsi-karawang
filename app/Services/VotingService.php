<?php

namespace App\Services;

use App\Models\Ballot;
use App\Models\CandidatePair;
use App\Models\ElectionPeriod;
use App\Models\Student;
use App\Models\VoterParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class VotingService
{
    /**
     * Cast an anonymous ballot securely and atomically.
     *
     * @throws RuntimeException
     */
    public function castVote(Student $student, int $candidatePairId, Request $request): VoterParticipation
    {
        // 1. Get current active election period
        $activePeriod = ElectionPeriod::where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        if (! $activePeriod) {
            throw new RuntimeException('Saat ini tidak ada periode pemilihan PEMIRA yang sedang aktif.');
        }

        // 2. Validate candidate pair belongs to active election period
        $candidatePair = CandidatePair::where('id', $candidatePairId)
            ->where('election_period_id', $activePeriod->id)
            ->first();

        if (! $candidatePair) {
            throw new RuntimeException('Pasangan calon yang dipilih tidak valid untuk periode ini.');
        }

        // 3. Atomically check and execute within DB Transaction with row locking
        return DB::transaction(function () use ($student, $activePeriod, $candidatePair, $request) {
            $participation = VoterParticipation::firstOrCreate(
                [
                    'election_period_id' => $activePeriod->id,
                    'student_nim' => $student->nim,
                ],
                [
                    'has_voted' => false,
                ]
            );

            // Row-level lock to prevent simultaneous double-click / race condition
            $lockedParticipation = VoterParticipation::where('id', $participation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedParticipation->has_voted) {
                throw new RuntimeException('Hak suara Anda telah digunakan sebelumnya. Setiap mahasiswa hanya memiliki 1 kali hak suara.');
            }

            // 4. Insert strictly anonymous ballot into ballots table (NO student_nim or student_id)
            $ballotHash = hash('sha256', Str::uuid()->toString() . '-' . $activePeriod->id . '-' . microtime(true) . '-' . config('app.key'));

            Ballot::create([
                'election_period_id' => $activePeriod->id,
                'candidate_pair_id' => $candidatePair->id,
                'ballot_hash' => $ballotHash,
                'created_at' => now(),
            ]);

            // 5. Update participation status & save encrypted receipt for student post-voting summary
            $lockedParticipation->update([
                'has_voted' => true,
                'voted_at' => now(),
                'encrypted_vote_receipt' => Crypt::encryptString((string) $candidatePair->id),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
            ]);

            return $lockedParticipation;
        });
    }
}
