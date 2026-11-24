<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ElectionPeriod;
use App\Services\ElectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected ElectionService $electionService
    ) {}

    /**
     * Display system settings and configuration overview.
     */
    public function index(): View
    {
        $activePeriod = $this->electionService->getActivePeriod();
        $totalPeriods = ElectionPeriod::count();

        // System Environment Details
        $serverInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'Aktif (Development)' : 'Nonaktif (Production)',
            'db_connection' => config('database.default'),
            'timezone' => config('app.timezone', 'Asia/Jakarta'),
            'session_driver' => config('session.driver'),
        ];

        // Pastikan file default logo kabinet selalu tersedia sebagai backup
        $defaultLogoPath = public_path('images/default-logo-kabinet.png');
        $currentLogoPath = public_path('images/logo-kabinet.png');
        if (! File::exists($defaultLogoPath) && File::exists($currentLogoPath)) {
            File::copy($currentLogoPath, $defaultLogoPath);
        }

        $isCustomLogo = File::exists(public_path('images/.is_custom_logo')) || File::exists(public_path('images/custom-logo-kabinet.png'));
        $currentLogoUrl = File::exists($currentLogoPath)
            ? asset('images/logo-kabinet.png') . '?v=' . File::lastModified($currentLogoPath)
            : asset('images/default-logo-kabinet.png');

        return view('admin.settings.index', [
            'activePeriod' => $activePeriod,
            'totalPeriods' => $totalPeriods,
            'serverInfo' => $serverInfo,
            'isCustomLogo' => $isCustomLogo,
            'currentLogoUrl' => $currentLogoUrl,
        ]);
    }

    /**
     * Upload dan simpan kustomisasi logo kabinet PEMIRA ke Laravel Storage.
     */
    public function updateLogo(Request $request): RedirectResponse
    {
        $request->validate([
            'logo' => ['required', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:5120'], // Max 5MB
        ], [
            'logo.required' => 'Silakan pilih file gambar logo kabinet yang akan di-upload.',
            'logo.mimes' => 'Format file logo harus berupa PNG, JPG, JPEG, WEBP, atau SVG.',
            'logo.max' => 'Ukuran file logo maksimal adalah 5 MB.',
        ]);

        $file = $request->file('logo');
        $imagesDir = public_path('images');

        if (! File::isDirectory($imagesDir)) {
            File::makeDirectory($imagesDir, 0755, true);
        }

        // 1. Simpan file backup default jika belum ada
        if (! File::exists($imagesDir . '/default-logo-kabinet.png') && File::exists($imagesDir . '/logo-kabinet.png')) {
            File::copy($imagesDir . '/logo-kabinet.png', $imagesDir . '/default-logo-kabinet.png');
        }

        // 2. Simpan secara permanen ke direktori Laravel Storage (storage/app/public/branding/)
        $storedPath = $file->storeAs('branding', 'logo-kabinet.png', 'public');

        // 3. Sinkronkan file baru langsung ke public/images/ agar seluruh view ter-update seketika
        File::copy(storage_path('app/public/branding/logo-kabinet.png'), $imagesDir . '/logo-kabinet.png');
        File::copy(storage_path('app/public/branding/logo-kabinet.png'), $imagesDir . '/custom-logo-kabinet.png');
        File::put($imagesDir . '/.is_custom_logo', (string) now());

        // 4. Catat riwayat perubahan ke Audit Trail
        AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'update_cabinet_logo',
            'entity_type' => 'Setting',
            'entity_id' => null,
            'new_values' => [
                'description' => 'Memperbarui logo kabinet PEMIRA BEM UBSI',
                'storage_path' => $storedPath,
                'file_name' => $file->getClientOriginalName(),
                'updated_at' => now()->toDateTimeString(),
            ],
        ]);

        return back()->with('success', 'Logo kabinet PEMIRA BEM UBSI berhasil disimpan ke storage dan disinkronkan ke seluruh sistem.');
    }

    /**
     * Hapus logo kustom dan kembalikan ke logo bawaan sistem.
     */
    public function deleteLogo(): RedirectResponse
    {
        $imagesDir = public_path('images');

        // 1. Hapus dari direktori Laravel Storage
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists('branding/logo-kabinet.png')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete('branding/logo-kabinet.png');
        }

        if (File::exists($imagesDir . '/custom-logo-kabinet.png')) {
            File::delete($imagesDir . '/custom-logo-kabinet.png');
        }

        if (File::exists($imagesDir . '/.is_custom_logo')) {
            File::delete($imagesDir . '/.is_custom_logo');
        }

        // 2. Kembalikan logo bawaan dari default-logo-kabinet.png
        if (File::exists($imagesDir . '/default-logo-kabinet.png')) {
            File::copy($imagesDir . '/default-logo-kabinet.png', $imagesDir . '/logo-kabinet.png');
        }

        // 3. Catat Audit Trail
        AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'reset_cabinet_logo',
            'entity_type' => 'Setting',
            'entity_id' => null,
            'new_values' => [
                'description' => 'Mereset logo kabinet ke default bawaan sistem',
                'reset_at' => now()->toDateTimeString(),
            ],
        ]);

        return back()->with('success', 'Logo kabinet kustom berhasil dihapus dari storage dan seluruh sistem kembali menggunakan logo bawaan.');
    }
}
