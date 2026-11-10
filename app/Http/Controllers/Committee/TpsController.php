<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\VoterParticipation;
use App\Services\ElectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TpsController extends Controller
{
    public function __construct(
        protected ElectionService $electionService
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::guard('admin')->user();
        $activePeriod = $this->electionService->getActivePeriod();

        $search = $request->input('search');

        $participations = null;
        if ($activePeriod) {
            $participations = VoterParticipation::with('student', 'verifiedBy')
                ->where('election_period_id', $activePeriod->id)
                ->when($search, function ($q) use ($search) {
                    $q->whereHas('student', function ($sq) use ($search) {
                        $sq->where('nim', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
                })
                ->latest('updated_at')
                ->paginate(20)
                ->withQueryString();
        }

        return view('committee.tps', compact('user', 'activePeriod', 'participations', 'search'));
    }

    public function verify(Request $request, VoterParticipation $participation): RedirectResponse
    {
        $user = Auth::guard('admin')->user();

        $participation->update([
            'verified_by_admin_id' => $user->id,
            'verified_at' => now(),
            'tps_location' => $user->tps_location ?? 'TPS Kampus UBSI Karawang',
        ]);

        AuditLog::create([
            'admin_id' => $user->id,
            'action' => 'verify_voter_onsite',
            'entity_type' => VoterParticipation::class,
            'entity_id' => $participation->id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return back()->with('success', "Mahasiswa NIM {$participation->student_nim} ({$participation->student->name}) berhasil diverifikasi kehadiran fisiknya di TPS.");
    }
}
