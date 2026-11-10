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
    public function getActivePeriod(): ?ElectionPeriod
    {
        return ElectionPeriod::with(['candidatePairs' => function ($q) {
            $q->orderBy('candidate_number');
        }, 'announcements' => function ($q) {
            $q->where('is_active', true)->latest();
        }])
        ->where('status', 'active')
        ->first();
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
     * Get anonymous tally directly from ballots table.
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

        return $candidates->map(function ($candidate) use ($ballotCounts, $totalVotes) {
            $votes = (int) ($ballotCounts[$candidate->id] ?? 0);
            $percentage = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 1) : 0;

            return [
                'id' => $candidate->id,
                'candidate_number' => $candidate->candidate_number,
                'chairman_name' => $candidate->chairman_name,
                'chairman_study_program' => $candidate->chairman_study_program,
                'vice_chairman_name' => $candidate->vice_chairman_name,
                'vice_chairman_study_program' => $candidate->vice_chairman_study_program,
                'photo_url' => $candidate->photo_url,
                'total_votes' => $votes,
                'percentage' => $percentage,
            ];
        });
    }

    public function getParticipationStats(ElectionPeriod $period): array
    {
        $totalRegisteredStudents = Student::where('is_active', true)->count();
        $totalVoted = VoterParticipation::where('election_period_id', $period->id)
            ->where('has_voted', true)
            ->count();
        $totalNotVoted = max(0, $totalRegisteredStudents - $totalVoted);
        $turnoutPercentage = $totalRegisteredStudents > 0 ? round(($totalVoted / $totalRegisteredStudents) * 100, 1) : 0;

        return [
            'total_students' => $totalRegisteredStudents,
            'total_voted' => $totalVoted,
            'total_not_voted' => $totalNotVoted,
            'turnout_percentage' => $turnoutPercentage,
        ];
    }
}

