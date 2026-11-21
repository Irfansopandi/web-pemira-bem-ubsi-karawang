<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\VoterParticipation;
use App\Services\ElectionService;
use App\Services\GuestbookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        protected GuestbookService $guestbookService,
        protected ElectionService $electionService,
    ) {}

    /**
     * Tampilkan formulir bilik suara digital khusus mahasiswa (Zero-Guest).
     */
    public function showLoginForm(): View|RedirectResponse
    {
        // Jika sudah login sebagai mahasiswa, periksa validitas status pemilihan
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $activePeriod = $this->electionService->getActivePeriod();

            // Cegah redirect loop jika periode pemilihan tidak ada/tidak aktif
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

                // Cek apakah mahasiswa pernah voting di periode manapun (arsip)
                $anyVoted = VoterParticipation::where('student_nim', $student->nim)
                    ->where('has_voted', true)
                    ->exists();

                if ($anyVoted) {
                    return redirect()->route('student.receipt');
                }

                // Bersihkan sesi mahasiswa agar halaman login tampil normal tanpa loop
                Auth::guard('student')->logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                return view('auth.login')->with('info', 'Saat ini belum ada periode pemilihan PEMIRA yang sedang dibuka.');
            }

            // Jika ada periode aktif, cek apakah mahasiswa sudah pernah memilih pada periode ini
            $participation = VoterParticipation::where('election_period_id', $activePeriod->id)
                ->where('student_nim', $student->nim)
                ->first();

            if ($participation && $participation->has_voted) {
                return redirect()->route('student.receipt');
            }

            // Jika bilik suara periode aktif ditutup tetapi mahasiswa pernah voting di periode lain
            if ($activePeriod->isVotingClosed()) {
                $pastVoted = VoterParticipation::where('student_nim', $student->nim)
                    ->where('has_voted', true)
                    ->exists();

                if ($pastVoted) {
                    return redirect()->route('student.receipt');
                }

                // Bersihkan sesi dan arahkan ke login dengan pemberitahuan jelas
                Auth::guard('student')->logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                return view('auth.login')->withErrors([
                    'identifier' => "Bilik suara periode {$activePeriod->academic_year} saat ini sedang ditutup oleh Panitia KPUM.",
                ]);
            }

            return redirect()->route('student.voting');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi mahasiswa pemilih (cukup input NIM).
     * Terintegrasi dengan buku tamu TPS eksternal & terisolasi per periode.
     */
    public function login(Request $request): RedirectResponse
    {
        $identifier = trim((string) $request->input('identifier', ''));

        // 1. Validasi NIM tidak boleh kosong
        if ($identifier === '') {
            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'identifier' => 'Silakan masukkan NIM Anda.',
                ]);
        }

        // 2. Cari mahasiswa di DPT berdasarkan NIM
        $student = Student::where('nim', $identifier)->first();

        if (! $student) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'identifier' => "NIM '{$identifier}' tidak terdaftar di DPT.",
                ]);
        }

        // 3. Cek status periode aktif
        $activePeriod = $this->electionService->getActivePeriod();

        if (! $activePeriod) {
            $latestPeriod = $this->electionService->getLatestPeriod();
            if ($latestPeriod) {
                $hasVoted = VoterParticipation::where('election_period_id', $latestPeriod->id)
                    ->where('student_nim', $student->nim)
                    ->where('has_voted', true)
                    ->exists();

                if ($hasVoted) {
                    Auth::guard('student')->login($student);
                    $request->session()->regenerate();

                    return redirect()->route('student.receipt')->with('info', 'Periode telah berakhir. Berikut bukti suara Anda.');
                }
            }

            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'identifier' => 'Periode pemilihan belum dibuka oleh KPUM.',
                ]);
        }

        // 4. Cek apakah mahasiswa sudah pernah menggunakan hak suara pada periode ini
        $participation = VoterParticipation::where('election_period_id', $activePeriod->id)
            ->where('student_nim', $student->nim)
            ->first();

        if ($participation && $participation->has_voted) {
            Auth::guard('student')->login($student);
            $request->session()->regenerate();

            return redirect()->route('student.receipt')->with('info', 'Anda sudah memilih. Berikut tanda terima resmi Anda.');
        }

        // 4b. Cek status bilik suara (Jika belum memilih dan bilik suara ditutup, tolak login)
        if ($activePeriod->isVotingClosed()) {
            // Periksa jika mahasiswa pernah memilih di periode sebelumnya (misal arsip)
            $pastVotedParticipation = VoterParticipation::where('student_nim', $student->nim)
                ->where('has_voted', true)
                ->latest('voted_at')
                ->first();

            if ($pastVotedParticipation) {
                Auth::guard('student')->login($student);
                $request->session()->regenerate();

                return redirect()->route('student.receipt')->with('info', 'Bilik suara saat ini sedang ditutup. Menampilkan tanda terima suara Anda pada periode sebelumnya.');
            }

            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'identifier' => 'Bilik suara saat ini sedang ditutup oleh Panitia KPUM.',
                ]);
        }

        // 5. Verifikasi kehadiran fisik mahasiswa di Meja Registrasi TPS (Verifikasi Kehadiran Pemilih)
        $presence = $this->guestbookService->isStudentPresent($student->nim, $activePeriod->id);

        if (! $presence['is_present']) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'identifier' => "NIM {$student->nim} belum melakukan Verifikasi Kehadiran di Meja Registrasi TPS. Silakan hubungi Panitia KPUM terlebih dahulu.",
                ]);
        }

        // 6. Jika lolos seluruh verifikasi, pastikan status mahasiswa aktif
        $student->update(['is_active' => true]);

        // Pertahankan data verifikasi kehadiran (tanda tangan digital & panitia) jika sudah ada
        $existingParticipation = VoterParticipation::where('election_period_id', $activePeriod->id)
            ->where('student_nim', $student->nim)
            ->first();

        if (! $existingParticipation) {
            VoterParticipation::create([
                'election_period_id' => $activePeriod->id,
                'student_nim' => $student->nim,
                'has_voted' => false,
                'verified_at' => now(),
                'tps_location' => 'TPS Kampus UBSI Cikampek',
            ]);
        }

        // Login dan langsung masuk ke bilik suara
        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        return redirect()->route('student.voting');
    }

    /**
     * Akhiri sesi mahasiswa pemilih (Logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah keluar dari bilik suara.');
    }
}
