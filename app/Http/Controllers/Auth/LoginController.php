<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan formulir autentikasi terpadu (Zero-Guest).
     */
    public function showLoginForm(): View|RedirectResponse
    {
        // Jika sudah login sebagai mahasiswa
        if (Auth::guard('student')->check()) {
            return redirect()->intended(route('student.voting'));
        }

        // Jika sudah login sebagai admin / panitia
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            return $user->role === 'admin'
                ? redirect()->intended(route('admin.dashboard'))
                : redirect()->intended(route('committee.dashboard'));
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi otomatis dengan otorisasi berbasis peran.
     */
    public function login(Request $request): RedirectResponse
    {
        $identifier = trim((string) $request->input('identifier', ''));
        $password   = (string) $request->input('password', '');

        // 1. Kasus: Keduanya Kosong / Salah
        if ($identifier === '' && $password === '') {
            return back()
                ->withErrors([
                    'general' => 'Username dan Password wajib diisi.',
                ]);
        }

        // 2. Kasus: Hanya Username Kosong
        if ($identifier === '') {
            return back()
                ->withErrors([
                    'identifier' => 'Username atau NIM wajib diisi.',
                ]);
        }

        // 3. Kasus: Hanya Password Kosong
        if ($password === '') {
            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'password' => 'Kata sandi wajib diisi.',
                ]);
        }

        // 4. Cari pengguna di tabel students (NIM) & admins (username / email)
        $student = Student::where('nim', $identifier)->first();
        $admin = Admin::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        // 5. Kasus: Username / NIM Tidak Ditemukan
        if (!$student && !$admin) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'identifier' => 'Username atau NIM tidak terdaftar.',
                ]);
        }

        // 6. Autentikasi Mahasiswa (Student)
        if ($student) {
            if (!$student->is_active) {
                return back()
                    ->withInput(['identifier' => $identifier])
                    ->withErrors([
                        'identifier' => 'Akun mahasiswa Anda sedang dinonaktifkan.',
                    ]);
            }

            // Validasi kata sandi mahasiswa (mencocokkan tanggal lahir terdaftar YYYY-MM-DD per NIM)
            if (!\Illuminate\Support\Facades\Hash::check($password, $student->password)) {
                return back()
                    ->withInput(['identifier' => $identifier])
                    ->withErrors([
                        'password' => 'Kata sandi yang Anda masukkan salah. Pastikan format tanggal lahir sesuai (YYYY-MM-DD).',
                    ]);
            }

            Auth::guard('student')->login($student);
            $request->session()->regenerate();
            return redirect()->intended(route('student.voting'));
        }

        // 7. Autentikasi Pengelola (Admin & Panitia TPS)
        if ($admin) {
            if (!$admin->is_active) {
                return back()
                    ->withInput(['identifier' => $identifier])
                    ->withErrors([
                        'identifier' => 'Akun pengelola Anda sedang dinonaktifkan.',
                    ]);
            }

            // Jika Password Salah
            if (!\Illuminate\Support\Facades\Hash::check($password, $admin->password)) {
                return back()
                    ->withInput(['identifier' => $identifier])
                    ->withErrors([
                        'password' => 'Kata sandi yang Anda masukkan salah.',
                    ]);
            }

            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();

            return match ($admin->role) {
                'admin'     => redirect()->intended(route('admin.dashboard')),
                'committee' => redirect()->intended(route('committee.dashboard')),
                default     => redirect()->intended('/login'),
            };
        }

        // Fallback jika terjadi kegagalan tak terduga (keduanya salah)
        return back()
            ->withInput(['identifier' => $identifier])
            ->withErrors([
                'general' => 'Username dan kata sandi yang Anda masukkan tidak sesuai.',
            ]);
    }

    /**
     * Akhiri sesi pengguna (Logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('student')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah berhasil keluar dari sistem.');
    }
}

