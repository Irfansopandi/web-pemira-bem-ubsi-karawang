<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan formulir autentikasi khusus Administrator KPUM.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('login')->withErrors([
                'identifier' => 'Akses ditolak. Anda sedang login sebagai mahasiswa.',
            ]);
        }

        if (Auth::guard('admin')->check()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return view('admin.login');
    }

    /**
     * Proses autentikasi Administrator KPUM.
     */
    public function login(Request $request): RedirectResponse
    {
        $identifier = trim((string) $request->input('identifier', ''));
        $password   = (string) $request->input('password', '');

        if ($identifier === '' && $password === '') {
            return back()
                ->withErrors([
                    'general' => 'Username/Email dan Kata Sandi wajib diisi.',
                ]);
        }

        if ($identifier === '') {
            return back()
                ->withErrors([
                    'identifier' => 'Username atau email administrator wajib diisi.',
                ]);
        }

        if ($password === '') {
            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'password' => 'Kata sandi wajib diisi.',
                ]);
        }

        $admin = Admin::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (! $admin) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'identifier' => 'Akun administrator tidak ditemukan dalam sistem.',
                ]);
        }

        if (! $admin->is_active) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'identifier' => 'Akun administrator Anda sedang dinonaktifkan.',
                ]);
        }

        if (! Hash::check($password, $admin->password)) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'password' => 'Kata sandi yang Anda masukkan salah.',
                ]);
        }

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Akhiri sesi administrator (Logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        if (! Auth::guard('student')->check()) {
            $request->session()->invalidate();
        }
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'Anda telah berhasil keluar dari sistem administrator.');
    }
}

