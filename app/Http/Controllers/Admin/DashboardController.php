<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ElectionPeriod;
use App\Models\VoterParticipation;
use App\Services\ElectionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
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
        $tally = $period ? $this->electionService->getTally($period) : collect();

        // Kilasan Partisipasi Terbaru (5 data terakhir untuk widget snapshot di dashboard)
        $recentParticipations = $period
            ? VoterParticipation::with('student', 'verifiedBy')
                ->where('election_period_id', $period->id)
                ->where('has_voted', true)
                ->latest('voted_at')
                ->limit(5)
                ->get()
            : collect();

        // Kilasan Log Aktivitas Sistem (5 data terakhir untuk widget snapshot di dashboard)
        $recentLogs = AuditLog::with('admin')->latest('id')->limit(5)->get();

        return view('admin.dashboard', compact('period', 'allPeriods', 'stats', 'tally', 'recentParticipations', 'recentLogs'));
    }
}

