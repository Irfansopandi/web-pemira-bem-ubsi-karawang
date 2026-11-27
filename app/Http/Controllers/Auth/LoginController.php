<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\VoterParticipation;
use App\Services\ElectionService;
use App\Services\GuestbookService;
use Illuminate\Http\JsonResponse;
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
     * Buat soal captcha matematika acak dengan hasil maksimal 10 (1..10).
     */
    private function generateMathCaptcha(): string
    {
        $useAddition = (bool) random_int(0, 1);

        if ($useAddition) {
            $a = random_int(1, 9);
            $b = random_int(1, 10 - $a);
            $answer = $a + $b;
            $question = "{$a} + {$b} = ?";
        } else {
            $a = random_int(2, 10);
            $b = random_int(1, $a - 1);
            $answer = $a - $b;
            $question = "{$a} - {$b} = ?";
        }

        session([
            'login_captcha_answer' => $answer,
            'login_captcha_question' => $question,
            'login_captcha_verified' => false,
        ]);

        return $question;
    }

    /**
     * Verifikasi jawaban captcha matematika sebelum tombol masuk aktif.
     */
    public function verifyCaptcha(Request $request): JsonResponse
    {
        $answer = $request->input('answer');
        $expected = session('login_captcha_answer');

        if ($expected === null) {
            return response()->json([
                'verified' => false,
                'message' => 'Sesi verifikasi diperbarui. Silakan jawab soal baru.',
                'question' => $this->generateMathCaptcha(),
            ], 422);
        }

        if ($answer !== null && $answer !== '' && is_numeric($answer) && (int) $answer === (int) $expected) {
            session(['login_captcha_verified' => true]);

            return response()->json([
                'verified' => true,
                'message' => 'Verifikasi berhasil! Silakan tekan Buka Bilik Suara.',
            ]);
        }

        return response()->json([
            'verified' => false,
            'message' => 'Jawaban kurang tepat. Silakan coba soal baru.',
            'question' => $this->generateMathCaptcha(),
        ], 422);
    }

    /**
     * Muat ulang soal captcha matematika baru (hasil maksimal 10).
     */
    public function refreshCaptcha(): JsonResponse
    {
        return response()->json([
            'question' => $this->generateMathCaptcha(),
        ]);
    }

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
                if (! Auth::guard('admin')->check()) {
                    request()->session()->invalidate();
                }
                request()->session()->regenerateToken();

                return view('auth.login', [
                    'captchaQuestion' => $this->generateMathCaptcha(),
                ])->with('info', 'Saat ini belum ada periode pemilihan PEMIRA yang sedang dibuka.');
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
                if (! Auth::guard('admin')->check()) {
                    request()->session()->invalidate();
                }
                request()->session()->regenerateToken();

                return view('auth.login', [
                    'captchaQuestion' => $this->generateMathCaptcha(),
                ])->withErrors([
                    'identifier' => "Bilik suara periode {$activePeriod->academic_year} saat ini sedang ditutup oleh Panitia KPUM.",
                ]);
            }

            return redirect()->route('student.voting');
        }

        return view('auth.login', [
            'captchaQuestion' => $this->generateMathCaptcha(),
        ]);
    }

    /**
     * Proses autentikasi mahasiswa pemilih (cukup input NIM).
     * Terintegrasi dengan buku tamu TPS eksternal & terisolasi per periode.
     */
    public function login(Request $request): RedirectResponse
    {
        $identifier = trim((string) $request->input('identifier', ''));

        // 0. Validasi Captcha Matematika (maksimal hasil 10) sebelum memproses login
        $captchaVerified = session('login_captcha_verified') === true;
        $captchaExpected = session('login_captcha_answer');
        $captchaInput = $request->input('captcha_answer');

        $isInlineMatch = $captchaExpected !== null
            && $captchaInput !== null
            && $captchaInput !== ''
            && is_numeric($captchaInput)
            && (int) $captchaInput === (int) $captchaExpected;

        if (! $captchaVerified && ! $isInlineMatch) {
            $this->generateMathCaptcha();

            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'captcha' => 'Silakan selesaikan verifikasi matematika terlebih dahulu.',
                ]);
        }

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

        // 5. Verifikasi kehadiran mahasiswa berdasarkan data Buku Tamu internal
        $presence = $this->guestbookService->isStudentPresent($student->nim, $activePeriod->id);

        if (! $presence['is_present']) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'identifier' => "NIM {$student->nim} ({$student->name}) belum mengisi Buku Tamu.",
                ]);
        }

        // 6. Jika lolos seluruh verifikasi, pastikan status mahasiswa aktif
        $student->update(['is_active' => true]);

        // Bersihkan sesi captcha setelah berhasil melewati gerbang login
        session()->forget(['login_captcha_verified', 'login_captcha_answer', 'login_captcha_question']);

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

        // Jika admin juga sedang login di browser yang sama, jangan hapus sesi admin
        if (! Auth::guard('admin')->check()) {
            $request->session()->invalidate();
        }
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah keluar dari bilik suara.');
    }
}
