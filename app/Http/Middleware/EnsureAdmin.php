<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
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

        Auth::shouldUse('admin');

        return $next($request);
    }
}
