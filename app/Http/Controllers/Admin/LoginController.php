<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\AdminSessionLimiterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        protected AdminSessionLimiterService $sessionLimiter
    ) {}
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

        // Batasi maksimal 5 perangkat/sesi aktif per akun administrator
        if (! $this->sessionLimiter->canLogin($admin->id, $request->session()->getId())) {
            return back()
                ->withInput(['identifier' => $identifier])
                ->withErrors([
                    'general' => 'Batas maksimal ' . AdminSessionLimiterService::MAX_CONCURRENT_SESSIONS . ' pengguna aktif tercapai. Keluar dari perangkat lain untuk masuk.',
                ]);
        }

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();
        $request->session()->put('admin_last_active_at', now()->timestamp);
        $this->sessionLimiter->register($admin->id, $request->session()->getId());

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Akhiri sesi administrator (Logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        $adminId = Auth::guard('admin')->id();
        $sessionId = $request->session()->getId();

        if ($adminId && $sessionId) {
            $this->sessionLimiter->forget((int) $adminId, $sessionId);
        }

        Auth::guard('admin')->logout();

        if (! Auth::guard('student')->check()) {
            $request->session()->invalidate();
        }
        $request->session()->regenerateToken();

        if ($request->boolean('idle_expired')) {
            return redirect()->route('admin.login')->withErrors([
                'general' => 'Sesi berakhir otomatis karena tidak ada aktivitas selama 5 menit.',
            ]);
        }

        return redirect()->route('admin.login')->with('status', 'Anda telah berhasil keluar dari sistem administrator.');
    }
}

