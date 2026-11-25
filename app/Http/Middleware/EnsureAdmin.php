<?php

namespace App\Http\Middleware;

use App\Services\AdminSessionLimiterService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function __construct(
        protected AdminSessionLimiterService $sessionLimiter
    ) {}
    /**
     * Handle an incoming request.
     * Memastikan akses panel administrator hanya dapat diakses oleh akun admin terotentikasi.
     * Mahasiswa yang terotentikasi ditolak keras dan diarahkan kembali ke login mahasiswa.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Prioritas Keamanan: Jika pengguna terotentikasi sebagai Mahasiswa, tolak akses admin secara mutlak
        if (Auth::guard('student')->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Akses ditolak. Mahasiswa dilarang mengakses area administrator.',
                ], 403);
            }

            return redirect()->route('login')->withErrors([
                'identifier' => 'Akses ditolak. Mahasiswa dilarang mengakses panel administrator.',
            ]);
        }

        // 2. Jika pengunjung belum login sebagai Admin
        if (! Auth::guard('admin')->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return redirect()->guest(route('admin.login'))->withErrors([
                'identifier' => 'Silakan masuk terlebih dahulu untuk mengakses panel administrator.',
            ]);
        }

        // 3. Verifikasi integritas akun Admin (aktif & memiliki hak akses admin)
        $admin = Auth::guard('admin')->user();
        if (! $admin || ! $admin->is_active || ! $admin->isAdmin()) {
            Auth::guard('admin')->logout();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Akses ditolak. Akun administrator dinonaktifkan atau tidak memiliki izin.',
                ], 403);
            }

            return redirect()->route('admin.login')->withErrors([
                'identifier' => 'Akun administrator Anda sedang dinonaktifkan atau tidak memiliki izin akses.',
            ]);
        }

        // 4. Periksa apakah sesi login telah idle melewati batas waktu (> 5 menit)
        $lastActive = $request->session()->get('admin_last_active_at');
        if ($lastActive !== null && (now()->timestamp - (int) $lastActive) > AdminSessionLimiterService::IDLE_TIMEOUT_SECONDS) {
            $this->sessionLimiter->forget($admin->id, $request->session()->getId());
            Auth::guard('admin')->logout();
            $request->session()->forget('admin_last_active_at');

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Sesi berakhir otomatis karena tidak ada aktivitas selama 5 menit.',
                ], 401);
            }

            return redirect()->route('admin.login')->withErrors([
                'general' => 'Sesi berakhir otomatis karena tidak ada aktivitas selama 5 menit.',
            ]);
        }

        // 5. Perbarui last_activity dan pastikan kuota 5 sesi aktif tidak terlampaui
        if (! $this->sessionLimiter->touch($admin->id, $request->session()->getId())) {
            Auth::guard('admin')->logout();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Sesi administrator berakhir karena kuota perangkat aktif sudah penuh.',
                ], 401);
            }

            return redirect()->route('admin.login')->withErrors([
                'general' => 'Sesi berakhir karena batas maksimal ' . AdminSessionLimiterService::MAX_CONCURRENT_SESSIONS . ' pengguna aktif penuh.',
            ]);
        }

        $request->session()->put('admin_last_active_at', now()->timestamp);

        Auth::shouldUse('admin');

        return $next($request);
    }
}
