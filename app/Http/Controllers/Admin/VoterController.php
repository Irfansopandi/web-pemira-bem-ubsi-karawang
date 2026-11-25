<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ElectionPeriod;
use App\Models\Student;
use App\Models\VoterParticipation;
use App\Services\ElectionService;
use App\Services\StudentImportService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VoterController extends Controller
{
    public function __construct(
        protected ElectionService $electionService,
        protected StudentImportService $importService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->has('period_id')) {
            session(['admin_selected_period_id' => $request->period_id]);
        }
        $period = $this->electionService->getSelectedPeriod();

        $allPeriods = ElectionPeriod::orderByDesc('is_active')->orderByDesc('id')->get();
        $stats = $period ? $this->electionService->getParticipationStats($period) : null;

        $query = $this->buildFilteredQuery($request, $period);

        $participations = $query->paginate(10)->withQueryString();

        // Respon AJAX / Polling Background untuk Live Auto-Sync (jangan bajak navigasi SPA HTML)
        if ($request->has('live') || $request->wantsJson()) {
            $votersData = $participations->mapWithKeys(function ($part) {
                $isPresent = (bool) !empty($part->verified_at);
                $verifierText = 'Belum Mengisi Buku Tamu';
                if ($isPresent) {
                    if ($part->verifiedBy) {
                        $verifierText = 'Buku Tamu TPS (' . $part->verifiedBy->name . ')';
                    } elseif ($part->verified_at) {
                        $verifierText = 'Buku Tamu Internal';
                    }
                }

                $verifiedAtFormatted = $part->verified_at 
                    ? $part->verified_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' 
                    : '-';

                $votedAtFormatted = $part->voted_at 
                    ? $part->voted_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' 
                    : '-';

                return [(string) $part->student_nim => [
                    'nim' => (string) $part->student_nim,
                    'name' => (string) ($part->student->name ?? 'Mahasiswa'),
                    'study_program' => (string) ($part->student->study_program ?? '-'),
                    'class_name' => (string) ($part->student->class_name ?? '-'),
                    'semester' => (string) ($part->student->semester ?? '-'),
                    'asal_kampus' => (string) ($part->student->asal_kampus ?? $part->tps_location ?? '-'),
                    'email_kampus' => (string) ($part->student->email_kampus ?? '-'),
                    'phone' => (string) ($part->phone ?? '-'),
                    'has_voted' => (bool) $part->has_voted,
                    'voted_at' => (string) $votedAtFormatted,
                    'is_present' => (bool) $isPresent,
                    'verified_at' => (string) $verifiedAtFormatted,
                    'verifier' => (string) $verifierText,
                    'signature_url' => (string) ($part->signature_url ?? ''),
                    'tps_location' => (string) ($part->student->asal_kampus ?? $part->tps_location ?? 'UBSI Karawang'),
                ]];
            });

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'total' => $participations->total(),
                'count' => $participations->count(),
                'first_item' => $participations->firstItem() ?? 0,
                'last_item' => $participations->lastItem() ?? 0,
                'table_html' => view('admin.voters._table_rows', compact('participations'))->render(),
                'pagination_html' => $participations->hasPages() ? $participations->links()->render() : '',
                'voters_data' => $votersData,
                'timestamp' => now()->timezone('Asia/Jakarta')->format('H:i:s') . ' WIB',
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
              ->header('Pragma', 'no-cache')
              ->header('Expires', '0');
        }

        $classes = Student::query()
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->when($period, function ($q) use ($period) {
                $q->whereHas('participations', fn ($pq) => $pq->where('election_period_id', $period->id));
            })
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');

        return view('admin.voters.index', compact('period', 'allPeriods', 'stats', 'participations', 'classes'));
    }

    /**
     * Tampilkan halaman cetak Daftar Pemilih Tetap (DPT) & Kehadiran Mahasiswa.
     */
    public function print(Request $request): View
    {
        if ($request->has('period_id')) {
            session(['admin_selected_period_id' => $request->period_id]);
        }

        $period = $this->electionService->getSelectedPeriod();
        $stats = $period ? $this->electionService->getParticipationStats($period) : null;
        $participations = $this->buildFilteredQuery($request, $period)->get();

        return view('admin.voters.print', compact('period', 'stats', 'participations'));
    }

    /**
     * Bangun query daftar pemilih dengan filter dan pengurutan yang konsisten.
     */
    protected function buildFilteredQuery(Request $request, ?ElectionPeriod $period): Builder
    {
        $query = VoterParticipation::with('student', 'verifiedBy');

        if ($period) {
            $query->where('voter_participations.election_period_id', $period->id);
        }

        if ($request->status === 'voted') {
            $query->where('voter_participations.has_voted', true);
        } elseif ($request->status === 'not_voted') {
            $query->where('voter_participations.has_voted', false);
        } elseif ($request->status === 'present') {
            $query->whereNotNull('voter_participations.verified_at');
        } elseif ($request->status === 'not_present') {
            $query->whereNull('voter_participations.verified_at');
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('study_program')) {
            $prodi = $request->input('study_program');
            $query->whereHas('student', function ($q) use ($prodi) {
                $q->where('study_program', $prodi);
            });
        }

        if ($request->filled('class_name')) {
            $className = $request->input('class_name');
            $query->whereHas('student', function ($q) use ($className) {
                $q->where('class_name', $className);
            });
        }

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

        return $query;
    }

    /**
     * Download format template CSV / Excel untuk import data mahasiswa.
     */
    public function downloadTemplate(): StreamedResponse
    {
        return $this->importService->downloadTemplateCsv();
    }

    /**
     * Upload dan import file data mahasiswa (CSV, SQL, JSON) langsung ke DPT.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:256000'], // max 250MB
            'period_id' => ['nullable', 'exists:election_periods,id'],
            'update_existing' => ['nullable'],
        ], [
            'file.required' => 'Silakan pilih file data mahasiswa yang akan di-upload.',
            'file.file' => 'Upload harus berupa file yang valid.',
            'file.max' => 'Ukuran file maksimal adalah 250MB.',
        ]);

        $periodId = $request->filled('period_id') 
            ? (int) $request->period_id 
            : ($this->electionService->getSelectedPeriod()?->id ?? null);

        if (!$periodId) {
            return back()->with('error', 'Tidak ada periode pemilihan aktif yang dipilih untuk mendaftarkan DPT.');
        }

        $period = ElectionPeriod::find($periodId);
        $updateExisting = $request->boolean('update_existing', true);

        try {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            $result = $this->importService->importFile($file, $periodId, $updateExisting);

            // Catat log aktivitas admin
            AuditLog::create([
                'admin_id' => Auth::guard('admin')->id(),
                'action' => 'import_students_dpt',
                'entity_type' => 'Student',
                'entity_id' => $periodId,
                'new_values' => [
                    'file_name' => $fileName,
                    'period_name' => $period->name ?? $periodId,
                    'imported_new' => $result['imported'],
                    'updated_existing' => $result['updated'],
                    'participations_registered' => $result['participations'],
                    'total_rows_processed' => $result['total_rows'],
                ],
            ]);

            $msg = "Import berhasil! {$result['imported']} mahasiswa baru ditambahkan, {$result['updated']} diperbarui, dan {$result['participations']} didaftarkan ke DPT periode {$period->academic_year}.";
            
            if (!empty($result['errors'])) {
                $errorCount = count($result['errors']);
                $msg .= " (Catatan: Ada {$errorCount} baris yang dilewati).";
            }

            return back()->with('success', $msg);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses file import: ' . $e->getMessage());
        }
    }
}
