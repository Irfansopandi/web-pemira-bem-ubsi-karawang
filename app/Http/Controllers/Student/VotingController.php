<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CandidatePair;
use App\Models\VoterParticipation;
use App\Services\ElectionService;
use App\Services\VotingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use RuntimeException;

class VotingController extends Controller
{
    public function __construct(
        protected ElectionService $electionService,
        protected VotingService $votingService
    ) {}

    public function index(): View|RedirectResponse
    {
        $student = Auth::guard('student')->user();
        $activePeriod = $this->electionService->getActivePeriod();

        if (! $activePeriod) {
            $latestPeriod = $this->electionService->getLatestPeriod();
            if ($latestPeriod) {
                $hasVoted = VoterParticipation::where('election_period_id', $latestPeriod->id)
                    ->where('student_nim', $student->nim)
                    ->where('has_voted', true)
                    ->exists();

                if ($hasVoted) {
                    return redirect()->route('student.receipt');
                }
            }

            Auth::guard('student')->logout();
            return redirect('/login')
                ->with('status', 'Saat ini belum ada periode pemilihan PEMIRA yang sedang aktif.');
        }

        // Check if student has already voted in this period
        $participation = VoterParticipation::where('election_period_id', $activePeriod->id)
            ->where('student_nim', $student->nim)
            ->first();

        if ($participation && $participation->has_voted) {
            return redirect()->route('student.receipt');
        }

        // Check if voting booth is closed
        if ($activePeriod->isVotingClosed()) {
            return view('student.closed', compact('student', 'activePeriod'));
        }

        $candidates = $activePeriod->candidatePairs;

        return view('student.voting', compact('student', 'activePeriod', 'candidates', 'participation'));
    }

    public function showCandidate(CandidatePair $candidate): View|RedirectResponse
    {
        $student = Auth::guard('student')->user();
        $activePeriod = $this->electionService->getActivePeriod();

        if (! $activePeriod || $candidate->election_period_id !== $activePeriod->id || $activePeriod->isVotingClosed()) {
            return redirect()->route('student.voting');
        }

        $participation = VoterParticipation::where('election_period_id', $activePeriod->id)
            ->where('student_nim', $student->nim)
            ->first();

        if ($participation && $participation->has_voted) {
            return redirect()->route('student.receipt');
        }

        return view('student.candidate-detail', compact('student', 'activePeriod', 'candidate', 'participation'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'candidate_pair_id' => ['required', 'exists:candidate_pairs,id'],
        ], [
            'candidate_pair_id.required' => 'Silakan tentukan pilihan pasangan calon sebelum mengirim suara.',
            'candidate_pair_id.exists' => 'Pasangan calon yang dipilih tidak valid.',
        ]);

        $student = Auth::guard('student')->user();

        try {
            $participation = $this->votingService->castVote(
                $student,
                (int) $request->input('candidate_pair_id'),
                $request
            );

            return redirect()->route('student.completed')
                ->with('voting_success', true);
        } catch (RuntimeException $e) {
            return redirect()->route('student.voting')
                ->with('error', $e->getMessage());
        }
    }

    public function completed(): View|RedirectResponse
    {
        $student = Auth::guard('student')->user();
        $activePeriod = $this->electionService->getActivePeriod();

        if (! $activePeriod) {
            return redirect('/login');
        }

        $participation = VoterParticipation::where('election_period_id', $activePeriod->id)
            ->where('student_nim', $student->nim)
            ->first();

        if (! $participation || ! $participation->has_voted) {
            return redirect()->route('student.voting');
        }

        $chosenCandidateId = $participation->getDecryptedCandidatePairId();
        $chosenCandidate = $chosenCandidateId ? CandidatePair::find($chosenCandidateId) : null;

        return view('student.completed', compact('student', 'activePeriod', 'participation', 'chosenCandidate'));
    }

    public function receipt(): View|RedirectResponse
    {
        $student = Auth::guard('student')->user();
        $activePeriod = $this->electionService->getActivePeriod();

        // 1. Coba cari partisipasi yang sudah memilih di periode aktif
        $participation = null;
        if ($activePeriod) {
            $participation = VoterParticipation::where('election_period_id', $activePeriod->id)
                ->where('student_nim', $student->nim)
                ->where('has_voted', true)
                ->first();
        }

        // 2. Jika tidak ada di periode aktif, cari dari periode manapun di mana mahasiswa ini sudah pernah voting
        if (! $participation) {
            $participation = VoterParticipation::where('student_nim', $student->nim)
                ->where('has_voted', true)
                ->latest('voted_at')
                ->first();

            if ($participation) {
                $activePeriod = ElectionPeriod::find($participation->election_period_id);
            }
        }

        if (! $participation || ! $participation->has_voted) {
            return redirect()->route('student.voting')
                ->with('info', 'Anda belum menggunakan hak suara.');
        }

        $chosenCandidateId = $participation->getDecryptedCandidatePairId();
        $chosenCandidate = $chosenCandidateId ? CandidatePair::find($chosenCandidateId) : null;

        return view('student.receipt', compact('student', 'activePeriod', 'participation', 'chosenCandidate'));
    }
}
