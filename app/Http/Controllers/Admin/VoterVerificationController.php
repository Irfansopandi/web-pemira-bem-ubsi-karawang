<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionPeriod;
use App\Models\VoterParticipation;
use App\Services\ElectionService;
use App\Services\VoterVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VoterVerificationController extends Controller
{
    public function __construct(
        protected VoterVerificationService $verificationService,
        protected ElectionService $electionService,
    ) {}

    /**
     * Tampilkan antarmuka Verifikasi Kehadiran Pemilih (Meja Registrasi TPS Onsite).
     */
    public function index(Request $request): View
    {
        if ($request->has('period_id')) {
            session(['admin_selected_period_id' => $request->period_id]);
        }

        $period = $this->electionService->getSelectedPeriod();
        $allPeriods = ElectionPeriod::orderByDesc('is_active')->orderByDesc('id')->get();
        $summary = $period ? $this->verificationService->getSummary($period) : null;
        $admin = Auth::guard('admin')->user();

        return view('admin.verification.index', compact('period', 'allPeriods', 'summary', 'admin'));
    }

    /**
     * Endpoint AJAX: Cari identitas mahasiswa berdasarkan NIM.
     */
    public function lookup(Request $request): JsonResponse
    {
        $nim = trim((string) $request->input('nim', ''));
        $period = $this->electionService->getSelectedPeriod();

        if (! $period) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada periode pemilihan yang aktif saat ini.',
            ], 422);
        }

        $result = $this->verificationService->lookup($nim, $period);

        if ($result['status'] === 'empty' || $result['status'] === 'not_found') {
            return response()->json($result, 404);
        }

        // Susun payload respons lengkap dengan data mahasiswa
        $student = $result['student'] ?? null;
        $participation = $result['participation'] ?? null;

        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
            'student' => $student ? [
                'nim' => $student->nim,
                'name' => $student->name,
                'study_program' => $student->study_program,
                'class_name' => $student->class_name,
                'semester' => $student->semester,
                'asal_kampus' => $student->asal_kampus,
                'email_kampus' => $student->email_kampus,
            ] : null,
            'participation' => $participation ? [
                'has_voted' => (bool) $participation->has_voted,
                'voted_at' => $participation->voted_at?->translatedFormat('d M Y, H:i') . ' WIB',
                'verified_at' => $participation->verified_at?->translatedFormat('d M Y, H:i') . ' WIB',
                'signature_url' => $participation->signature_url,
                'tps_location' => $participation->tps_location,
            ] : null,
        ]);
    }

    /**
     * Endpoint AJAX: Simpan verifikasi kehadiran & tanda tangan digital pemilih.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nim' => 'required|string',
            'signature' => 'required|string',
            'tps_location' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
        ], [
            'nim.required' => 'NIM mahasiswa wajib diisi.',
            'signature.required' => 'Tanda tangan digital wajib dibubuhkan di canvas.',
            'tps_location.required' => 'Asal kampus wajib dipilih (Karawang atau Cikampek).',
        ]);

        $period = $this->electionService->getSelectedPeriod();

        if (! $period) {
            return response()->json([
                'success' => false,
                'message' => 'Periode pemilihan tidak aktif.',
            ], 422);
        }

        $admin = Auth::guard('admin')->user();
        $tpsLocation = $request->input('tps_location');

        $result = $this->verificationService->verify(
            nim: $request->input('nim'),
            signatureData: $request->input('signature'),
            adminId: $admin?->id,
            tpsLocation: $tpsLocation,
            period: $period,
            phone: $request->input('phone'),
            email: $request->input('email'),
            ipAddress: $request->ip(),
            userAgent: $request->userAgent()
        );

        if (! $result['success']) {
            return response()->json($result, 422);
        }

        $summary = $this->verificationService->getSummary($period);

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'participation' => [
                'student_nim' => $result['participation']->student_nim,
                'student_name' => $result['participation']->student->name,
                'study_program' => $result['participation']->student->study_program,
                'verified_at' => $result['participation']->verified_at?->format('H:i:s'),
                'tps_location' => $result['participation']->tps_location,
                'signature_url' => $result['participation']->signature_url,
            ],
            'summary' => $summary,
        ]);
    }

    /**
     * Batalkan status verifikasi pemilih jika salah input.
     */
    public function cancel(VoterParticipation $participation, Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $result = $this->verificationService->cancel(
            participation: $participation,
            adminId: $admin->id,
            ipAddress: $request->ip()
        );

        if (! $result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }
}
