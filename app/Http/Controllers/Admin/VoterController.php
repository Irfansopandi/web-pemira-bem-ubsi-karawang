<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionPeriod;
use App\Models\VoterParticipation;
use App\Services\ElectionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VoterController extends Controller
{
    public function __construct(
        protected ElectionService $electionService
    ) {}

    public function index(Request $request): View
    {
        if ($request->has('period_id')) {
            session(['admin_selected_period_id' => $request->period_id]);
        }
        $period = $this->electionService->getSelectedPeriod();

        $allPeriods = ElectionPeriod::orderByDesc('is_active')->orderByDesc('id')->get();
        $stats = $period ? $this->electionService->getParticipationStats($period) : null;

        $query = VoterParticipation::with('student', 'verifiedBy');

        if ($period) {
            $query->where('voter_participations.election_period_id', $period->id);
        }

        // Filter status kehadiran
        if ($request->status === 'voted') {
            $query->where('voter_participations.has_voted', true);
        } elseif ($request->status === 'not_voted') {
            $query->where('voter_participations.has_voted', false);
        } elseif ($request->status === 'present') {
            $query->where(function ($q) {
                $q->whereNotNull('voter_participations.verified_at')
                  ->orWhere('voter_participations.has_voted', true);
            });
        }

        // Pencarian NIM atau Nama
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filter program studi
        if ($request->filled('study_program')) {
            $prodi = $request->input('study_program');
            $query->whereHas('student', function ($q) use ($prodi) {
                $q->where('study_program', $prodi);
            });
        }

        // Pilihan Sortir / Pengurutan
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'nim_asc' => $query->orderBy('voter_participations.student_nim', 'asc'),
            'nim_desc' => $query->orderBy('voter_participations.student_nim', 'desc'),
            'name_asc' => $query->join('students', 'voter_participations.student_nim', '=', 'students.nim')
                                ->orderBy('students.name', 'asc')
                                ->select('voter_participations.*'),
            'voted_desc' => $query->orderByRaw('voter_participations.voted_at IS NULL, voter_participations.voted_at DESC'),
            default => $query->latest('voter_participations.updated_at'),
        };

        $participations = $query->paginate(10)->withQueryString();

        return view('admin.voters.index', compact('period', 'allPeriods', 'stats', 'participations'));
    }
}
