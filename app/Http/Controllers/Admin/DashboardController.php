<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ElectionPeriod;
use App\Models\VoterParticipation;
use App\Services\ElectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected ElectionService $electionService
    ) {}

    public function index(Request $request): View
    {
        $period = null;
        if ($request->has('period_id')) {
            $period = ElectionPeriod::find($request->period_id);
        }
        $period = $period ?? $this->electionService->getActivePeriod() ?? $this->electionService->getLatestPeriod();

        $allPeriods = ElectionPeriod::orderByDesc('id')->get();
        $stats = $period ? $this->electionService->getParticipationStats($period) : null;
        $tally = $period ? $this->electionService->getTally($period) : collect();

        $participations = $period
            ? VoterParticipation::with('student', 'verifiedBy')
                ->where('election_period_id', $period->id)
                ->when($request->status === 'voted', fn($q) => $q->where('has_voted', true))
                ->when($request->status === 'not_voted', fn($q) => $q->where('has_voted', false))
                ->latest('updated_at')
                ->paginate(15)
                ->withQueryString()
            : null;

        $recentLogs = AuditLog::with('admin')->latest('id')->limit(8)->get();

        return view('admin.dashboard', compact('period', 'allPeriods', 'stats', 'tally', 'participations', 'recentLogs'));
    }

    public function togglePublish(ElectionPeriod $period): RedirectResponse
    {
        $newState = ! $period->is_result_published;
        $period->update([
            'is_result_published' => $newState,
            'published_at' => $newState ? now() : null,
        ]);

        AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => $newState ? 'publish_election_results' : 'unpublish_election_results',
            'entity_type' => ElectionPeriod::class,
            'entity_id' => $period->id,
            'new_values' => ['is_result_published' => $newState],
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);

        return back()->with('success', $newState ? 'Hasil rekapitulasi suara resmi berhasil dipublikasikan ke publik!' : 'Publikasi hasil suara telah ditarik kembali.');
    }

    public function toggleStatus(Request $request, ElectionPeriod $period): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:draft,active,closed,archived'],
        ]);

        $oldStatus = $period->status;
        $newStatus = $request->input('status');

        if ($newStatus === 'active') {
            ElectionPeriod::where('id', '!=', $period->id)
                ->where('status', 'active')
                ->update(['status' => 'closed']);
        }

        $period->update(['status' => $newStatus]);

        AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'update_period_status',
            'entity_type' => ElectionPeriod::class,
            'entity_id' => $period->id,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $newStatus],
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return back()->with('success', "Status periode pemilihan berhasil diperbarui menjadi {$newStatus}.");
    }
}
