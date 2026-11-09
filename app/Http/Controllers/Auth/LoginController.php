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
        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ], [
            'identifier.required' => 'NIM atau Username/Email wajib diisi.',
            'password.required'   => 'Kata Sandi / Kredensial TPS wajib diisi.',
        ]);

        $identifier = trim($credentials['identifier']);
        $password   = $credentials['password'];

        // 1. Prioritas Autentikasi Mahasiswa DPT (Pencocokan NIM)
        $studentExists = Student::where('nim', $identifier)->exists();
        if ($studentExists) {
            if (Auth::guard('student')->attempt(['nim' => $identifier, 'password' => $password, 'is_active' => true])) {
                $request->session()->regenerate();
                return redirect()->intended(route('student.voting'));
            }
        }

        // 2. Autentikasi Petugas (Admin & Panitia TPS via Username atau Email)
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::guard('admin')->attempt([$field => $identifier, 'password' => $password, 'is_active' => true])) {
            $request->session()->regenerate();

            /** @var Admin $user */
            $user = Auth::guard('admin')->user();

            // Otorisasi & Pengalihan Berdasarkan Peran
            return match ($user->role) {
                'admin'     => redirect()->intended(route('admin.dashboard')),
                'committee' => redirect()->intended(route('committee.dashboard')),
                default     => redirect()->intended('/login'),
            };
        }

        // 3. Jika kedua percobaan gagal
        return back()
            ->withInput($request->only('identifier'))
            ->withErrors([
                'identifier' => 'Kredensial yang Anda masukkan salah atau akun belum aktif.',
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
