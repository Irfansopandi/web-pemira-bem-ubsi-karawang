<?php

namespace App\Services;

use App\Models\Ballot;
use App\Models\CandidatePair;
use App\Models\ElectionPeriod;
use App\Models\Student;
use App\Models\VoterParticipation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ElectionService
{
    /**
     * Mengambil Periode Aktif Sistem untuk pelaksanaan pemilihan saat ini (Bilik Suara Mahasiswa).
     */
    public function getActivePeriod(): ?ElectionPeriod
    {
        return ElectionPeriod::with(['candidatePairs' => function ($q) {
            $q->orderBy('candidate_number');
        }, 'announcements' => function ($q) {
            $q->where('is_active', true)->latest();
        }])
        ->where(function ($q) {
            $q->where('is_active', true)->orWhere('status', 'active');
        })
        ->first();
    }

    /**
     * Mengambil Periode yang sedang dipilih / dikelola oleh Admin (Konteks Panel Admin).
     * Memprioritaskan: Query parameter -> Session admin -> Periode Aktif -> Periode Terbaru.
     */
    public function getSelectedPeriod(): ?ElectionPeriod
    {
        $selectedId = request('period_id') ?? session('admin_selected_period_id');

        if ($selectedId) {
            $period = ElectionPeriod::with(['candidatePairs' => function ($q) {
                $q->orderBy('candidate_number');
            }])->find($selectedId);

            if ($period) {
                session(['admin_selected_period_id' => $period->id]);
                return $period;
            }
        }

        // Fallback 1: Periode aktif sistem
        $active = $this->getActivePeriod();
        if ($active) {
            session(['admin_selected_period_id' => $active->id]);
            return $active;
        }

        // Fallback 2: Periode terbaru
        $latest = $this->getLatestPeriod();
        if ($latest) {
            session(['admin_selected_period_id' => $latest->id]);
            return $latest;
        }

        return null;
    }

    public function getLatestPeriod(): ?ElectionPeriod
    {
        return ElectionPeriod::with(['candidatePairs' => function ($q) {
            $q->orderBy('candidate_number');
        }])
        ->latest('id')
        ->first();
    }

    /**
     * Get anonymous tally directly from ballots table for the specific period.
     */
    public function getTally(ElectionPeriod $period): Collection
    {
        $candidates = CandidatePair::where('election_period_id', $period->id)
            ->orderBy('candidate_number')
            ->get();

        $ballotCounts = Ballot::where('election_period_id', $period->id)
            ->select('candidate_pair_id', DB::raw('count(*) as total_votes'))
            ->groupBy('candidate_pair_id')
            ->pluck('total_votes', 'candidate_pair_id');

        $totalVotes = Ballot::where('election_period_id', $period->id)->count();

        // Opsi C: Dihitung berdasarkan total pemilih yang hadir/terverifikasi di TPS
        $totalPresent = VoterParticipation::where('election_period_id', $period->id)
            ->where(function ($query) {
                $query->whereNotNull('verified_at')
                    ->orWhere('has_voted', true);
            })
            ->count();

        $baseAttendance = max($totalPresent, $totalVotes);

        return $candidates->map(function ($candidate) use ($ballotCounts, $totalVotes, $baseAttendance) {
            $votes = (int) ($ballotCounts[$candidate->id] ?? 0);
            $percentage = $baseAttendance > 0 ? round(($votes / $baseAttendance) * 100, 1) : 0;
            $percentageOfVoted = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 1) : 0;

            return [
                'id' => $candidate->id,
                'candidate_number' => $candidate->candidate_number,
                'chairman_name' => $candidate->chairman_name,
                'chairman_study_program' => $candidate->chairman_study_program,
                'vice_chairman_name' => $candidate->vice_chairman_name,
                'vice_chairman_study_program' => $candidate->vice_chairman_study_program,
                'photo_url' => $candidate->getPhotoUrl(),
                'total_votes' => $votes,
                'percentage' => $percentage,
                'percentage_of_voted' => $percentageOfVoted,
                'total_present' => $baseAttendance,
                'total_voted' => $totalVotes,
            ];
        });
    }

    public function getParticipationStats(ElectionPeriod $period): array
    {
        // 1. Total DPT dihitung dari daftar pemilih yang terdaftar pada periode bersangkutan
        $totalDpt = VoterParticipation::where('election_period_id', $period->id)->count();

        // 2. Total Suara Masuk: dihitung dari surat suara sah di kotak suara (ballots) pada periode ini
        $totalBallots = Ballot::where('election_period_id', $period->id)->count();
        $totalParticipated = VoterParticipation::where('election_period_id', $period->id)
            ->where('has_voted', true)
            ->count();
        $totalVoted = max($totalBallots, $totalParticipated);

        $totalNotVoted = max(0, $totalDpt - $totalVoted);
        $turnoutPercentage = $totalDpt > 0 ? round(($totalVoted / $totalDpt) * 100, 1) : 0;

        $totalPresent = VoterParticipation::where('election_period_id', $period->id)
            ->where(function ($query) {
                $query->whereNotNull('verified_at')
                    ->orWhere('has_voted', true);
            })
            ->count();

        return [
            'total_students' => $totalDpt,
            'total_voted' => $totalVoted,
            'total_not_voted' => $totalNotVoted,
            'total_present' => max($totalPresent, $totalVoted),
            'turnout_percentage' => $turnoutPercentage,
        ];
    }
}

