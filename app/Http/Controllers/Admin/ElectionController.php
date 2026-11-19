<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionPeriod;
use App\Models\VoterParticipation;
use App\Services\ElectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ElectionController extends Controller
{
    public function __construct(
        protected ElectionService $electionService
    ) {}

    /**
     * Endpoint API JSON untuk Live Real-Time Auto-Polling (tanpa reload browser)
     */
    public function liveData(Request $request): JsonResponse
    {
        $period = null;
        if ($request->has('period_id') && !empty($request->period_id)) {
            $period = ElectionPeriod::find($request->period_id);
        }
        $period = $period ?? $this->electionService->getSelectedPeriod();

        if (!$period) {
            return response()->json([
                'status' => 'inactive',
                'period' => null,
                'stats' => [
                    'total_students' => 0,
                    'total_voted' => 0,
                    'total_not_voted' => 0,
                    'turnout_percentage' => 0,
                ],
                'tally' => [],
                'recent_participations' => [],
                'timestamp' => now()->timezone('Asia/Jakarta')->format('H:i:s') . ' WIB',
            ]);
        }

        $stats = $this->electionService->getParticipationStats($period);
        $tally = $this->electionService->getTally($period);

        $limit = max(1, min((int) $request->input('limit', 10), 20));

        $recentParticipations = VoterParticipation::with('student')
            ->where('election_period_id', $period->id)
            ->where('has_voted', true)
            ->latest('voted_at')
            ->limit($limit)
            ->get()
            ->map(function ($part) {
                return [
                    'name' => $part->student->name ?? 'Mahasiswa',
                    'nim' => $part->student_nim,
                    'study_program' => $part->student->study_program ?? '-',
                    'voted_at_diff' => $part->voted_at ? $part->voted_at->diffForHumans() : '-',
                ];
            });

        return response()->json([
            'status' => 'active',
            'period_id' => $period->id,
            'period_name' => $period->name,
            'stats' => $stats,
            'tally' => $tally,
            'recent_participations' => $recentParticipations,
            'timestamp' => now()->timezone('Asia/Jakarta')->format('H:i:s') . ' WIB',
        ]);
    }

    public function monitoring(Request $request): View
    {
        if ($request->has('period_id')) {
            session(['admin_selected_period_id' => $request->period_id]);
        }
        $period = $this->electionService->getSelectedPeriod();

        $allPeriods = ElectionPeriod::orderByDesc('is_active')->orderByDesc('id')->get();
        $stats = $period ? $this->electionService->getParticipationStats($period) : null;
        $tally = $period ? $this->electionService->getTally($period) : collect();

        // Data aktivitas pemilih terkini (10 entri terbaru)
        $recentVoters = $period
            ? VoterParticipation::with('student', 'verifiedBy')
                ->where('election_period_id', $period->id)
                ->where('has_voted', true)
                ->latest('voted_at')
                ->limit(10)
                ->get()
            : collect();

        return view('admin.elections.monitoring', compact('period', 'allPeriods', 'stats', 'tally', 'recentVoters'));
    }

    public function results(Request $request): View
    {
        if ($request->has('period_id')) {
            session(['admin_selected_period_id' => $request->period_id]);
        }
        $period = $this->electionService->getSelectedPeriod();

        $allPeriods = ElectionPeriod::orderByDesc('is_active')->orderByDesc('id')->get();
        $stats = $period ? $this->electionService->getParticipationStats($period) : null;
        $tally = $period ? $this->electionService->getTally($period) : collect();

        return view('admin.elections.results', compact('period', 'allPeriods', 'stats', 'tally'));
    }
}
