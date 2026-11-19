<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ElectionPeriod;
use App\Services\ElectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeriodController extends Controller
{
    public function __construct(
        protected ElectionService $electionService
    ) {}

    public function index(): View
    {
        $periods = ElectionPeriod::withCount(['candidatePairs', 'ballots', 'voterParticipations'])
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->get();

        $activePeriod = $this->electionService->getActivePeriod();
        $selectedPeriod = $this->electionService->getSelectedPeriod();

        return view('admin.elections.periods', compact('periods', 'activePeriod', 'selectedPeriod'));
    }

    /**
     * Tambah Periode Pemilihan Baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:election_periods,name'],
            'academic_year' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:1000'],
            'set_as_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama periode pemilihan wajib diisi.',
            'name.unique' => 'Nama periode pemilihan ini sudah ada.',
            'academic_year.required' => 'Tahun periode / akademik wajib diisi (misal: 2027/2028).',
        ]);

        $setAsActive = $request->boolean('set_as_active');

        $period = DB::transaction(function () use ($validated, $setAsActive, $request) {
            if ($setAsActive) {
                // Kunci dan arsipkan semua periode lain
                ElectionPeriod::lockForUpdate()->get();
                ElectionPeriod::query()->update([
                    'is_active' => false,
                    'status' => 'archived',
                    'voting_status' => 'closed',
                ]);
            }

            $newPeriod = ElectionPeriod::create([
                'name' => $validated['name'],
                'academic_year' => $validated['academic_year'],
                'description' => $validated['description'] ?? null,
                'status' => $setAsActive ? 'active' : 'archived',
                'is_active' => $setAsActive,
                'voting_status' => 'closed', // Selalu mulai dalam kondisi bilik tertutup
                'start_time' => now(),
                'end_time' => now()->addYear(),
            ]);

            // Jika dibuat sebagai aktif, otomatis jadikan konteks tampilan admin
            if ($setAsActive) {
                session(['admin_selected_period_id' => $newPeriod->id]);
            }

            AuditLog::create([
                'admin_id' => Auth::guard('admin')->id(),
                'action' => 'create_election_period',
                'entity_type' => ElectionPeriod::class,
                'entity_id' => $newPeriod->id,
                'new_values' => [
                    'name' => $newPeriod->name,
                    'academic_year' => $newPeriod->academic_year,
                    'is_active' => $newPeriod->is_active,
                ],
                'ip_address' => $request->ip(),
            ]);

            return $newPeriod;
        });

        $message = "Periode '{$period->name}' berhasil dibuat" . ($setAsActive ? " dan ditetapkan sebagai Periode Aktif!" : ".");
        return back()->with('success', $message);
    }

    /**
     * Edit Informasi Periode Pemilihan (Nama, Tahun, Deskripsi).
     */
    public function update(Request $request, ElectionPeriod $period): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:election_periods,name,' . $period->id],
            'academic_year' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Nama periode pemilihan wajib diisi.',
            'name.unique' => 'Nama periode pemilihan sudah digunakan oleh periode lain.',
            'academic_year.required' => 'Tahun periode wajib diisi.',
        ]);

        $oldValues = [
            'name' => $period->name,
            'academic_year' => $period->academic_year,
            'description' => $period->description,
        ];

        $period->update([
            'name' => $validated['name'],
            'academic_year' => $validated['academic_year'],
            'description' => $validated['description'] ?? null,
        ]);

        AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'update_election_period',
            'entity_type' => ElectionPeriod::class,
            'entity_id' => $period->id,
            'old_values' => $oldValues,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Informasi periode '{$period->name}' berhasil diperbarui.");
    }

    /**
     * Tetapkan sebagai Periode Aktif Sistem (Hanya 1 periode aktif di dalam sistem).
     */
    public function setActive(Request $request, ElectionPeriod $period): RedirectResponse
    {
        if ($period->isSystemActive()) {
            return back()->with('info', "Periode '{$period->name}' sudah merupakan periode aktif saat ini.");
        }

        DB::transaction(function () use ($period, $request) {
            // Lock semua baris periode untuk mencegah race condition
            ElectionPeriod::lockForUpdate()->get();

            // Arsipkan dan kunci bilik suara seluruh periode lain
            ElectionPeriod::where('id', '!=', $period->id)->update([
                'is_active' => false,
                'status' => 'archived',
                'voting_status' => 'closed',
            ]);

            // Tetapkan periode target menjadi aktif
            $period->update([
                'is_active' => true,
                'status' => 'active',
                'voting_status' => 'closed', // Tetap ditutup sampai admin membukanya secara sadar
            ]);

            // Alihkan konteks tampilan admin ke periode ini
            session(['admin_selected_period_id' => $period->id]);

            AuditLog::create([
                'admin_id' => Auth::guard('admin')->id(),
                'action' => 'set_active_election_period',
                'entity_type' => ElectionPeriod::class,
                'entity_id' => $period->id,
                'new_values' => ['name' => $period->name, 'status' => 'active', 'is_active' => true],
                'ip_address' => $request->ip(),
            ]);
        });

        return back()->with('success', "Periode '{$period->name}' berhasil ditetapkan sebagai Periode Aktif Sistem!");
    }

    /**
     * Buka atau Tutup Bilik Suara (Voting Mahasiswa).
     */
    public function toggleVoting(Request $request, ElectionPeriod $period): RedirectResponse
    {
        // Guard ketat: Hanya periode aktif sistem yang bilik suaranya dapat dibuka
        if (! $period->isSystemActive()) {
            return back()->with('error', 'Hanya periode yang berstatus AKTIF yang dapat membuka bilik suara! Periode arsip tidak diizinkan membuka voting.');
        }

        $newVotingStatus = ($period->voting_status === 'opened') ? 'closed' : 'opened';
        $period->update(['voting_status' => $newVotingStatus]);

        AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => ($newVotingStatus === 'opened') ? 'open_voting_booth' : 'close_voting_booth',
            'entity_type' => ElectionPeriod::class,
            'entity_id' => $period->id,
            'new_values' => ['voting_status' => $newVotingStatus],
            'ip_address' => $request->ip(),
        ]);

        $statusText = ($newVotingStatus === 'opened')
            ? 'Bilik suara berhasil DIBUKA! Mahasiswa DPT sekarang dapat melakukan pemungutan suara.'
            : 'Bilik suara berhasil DITUTUP! Akses pemungutan suara mahasiswa telah dikunci.';

        return back()->with('success', $statusText);
    }

    /**
     * Berpindah Konteks Periode yang Dilihat Admin (Period Selector Switch).
     */
    public function switchPeriod(Request $request): RedirectResponse
    {
        $request->validate([
            'period_id' => ['required', 'exists:election_periods,id'],
        ]);

        $period = ElectionPeriod::findOrFail($request->period_id);
        session(['admin_selected_period_id' => $period->id]);

        return back()->with('success', "Konteks tampilan beralih ke: {$period->name}");
    }

    /**
     * Hapus Periode (Dilindungi Validasi Keamanan Ketat).
     */
    public function destroy(Request $request, ElectionPeriod $period): RedirectResponse
    {
        // 1. Larangan hapus periode yang sedang aktif
        if ($period->isSystemActive()) {
            return back()->with('error', 'Periode yang sedang AKTIF tidak dapat dihapus! Silakan tetapkan periode lain sebagai aktif terlebih dahulu.');
        }

        // 2. Larangan hapus jika sudah memiliki suara masuk (Ballots)
        $ballotCount = $period->ballots()->count();
        if ($ballotCount > 0) {
            return back()->with('error', "Periode ini dilindungi demi integritas pemilu karena sudah memiliki {$ballotCount} suara masuk. Data pemilihan historis tidak boleh dihapus!");
        }

        // 3. Larangan hapus jika memiliki partisipasi mahasiswa yang sudah memilih
        $votedCount = $period->voterParticipations()->where('has_voted', true)->count();
        if ($votedCount > 0) {
            return back()->with('error', 'Periode ini memiliki riwayat partisipasi pemilih dan tidak dapat dihapus.');
        }

        // 4. Hapus secara aman jika masih berupa draft bersih tanpa suara
        $periodName = $period->name;
        DB::transaction(function () use ($period, $periodName, $request) {
            $period->candidatePairs()->delete();
            $period->voterParticipations()->delete();
            $period->announcements()->delete();

            AuditLog::create([
                'admin_id' => Auth::guard('admin')->id(),
                'action' => 'delete_election_period',
                'entity_type' => ElectionPeriod::class,
                'entity_id' => $period->id,
                'old_values' => ['name' => $periodName],
                'ip_address' => $request->ip(),
            ]);

            $period->delete();
        });

        // Reset session jika yang dihapus adalah periode yang sedang dipilih
        if (session('admin_selected_period_id') == $period->id) {
            session()->forget('admin_selected_period_id');
        }

        return back()->with('success', "Periode '{$periodName}' berhasil dihapus.");
    }
}
