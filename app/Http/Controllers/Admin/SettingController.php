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

        // 2. Normalisasi gambar (auto-trim margin transparan & resize proporsional ke 512x512)
        //    agar ukuran visualnya selalu seragam dengan logo UBSI & BEM UBSI di semua halaman
        $storageDir = storage_path('app/public/branding');
        if (! File::isDirectory($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        $storageLogoPath = $storageDir . '/logo-kabinet.png';
        $normalized = $this->normalizeLogoImage($file->getRealPath(), strtolower($file->getClientOriginalExtension()), $storageLogoPath);

        if ($normalized) {
            $storedPath = 'branding/logo-kabinet.png';
        } else {
            // Fallback untuk SVG atau jika GD tidak mendukung format sumber
            $storedPath = $file->storeAs('branding', 'logo-kabinet.png', 'public');
        }

        // 3. Sinkronkan file baru langsung ke public/images/ agar seluruh view ter-update seketika
        File::copy(storage_path('app/public/' . $storedPath), $imagesDir . '/logo-kabinet.png');
        File::copy(storage_path('app/public/' . $storedPath), $imagesDir . '/custom-logo-kabinet.png');
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

    /**
     * Pangkas margin transparan berlebih dan sesuaikan logo ke kanvas standar 512x512
     * agar tampil proporsional dan seragam dengan logo resmi lainnya di semua halaman.
     */
    private function normalizeLogoImage(string $sourcePath, string $extension, string $destinationPath): bool
    {
        if (! function_exists('imagecreatetruecolor') || $extension === 'svg') {
            return false;
        }

        $src = match ($extension) {
            'png' => @imagecreatefrompng($sourcePath),
            'jpg', 'jpeg' => @imagecreatefromjpeg($sourcePath),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            default => false,
        };

        if (! $src) {
            return false;
        }

        $w = imagesx($src);
        $h = imagesy($src);

        if ($w <= 0 || $h <= 0) {
            imagedestroy($src);
            return false;
        }

        // Cari bounding box piksel tidak transparan (auto-trim margin kosong)
        $minX = $w;
        $minY = $h;
        $maxX = -1;
        $maxY = -1;

        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $rgba = imagecolorat($src, $x, $y);
                $alpha = ($rgba >> 24) & 0x7F;
                if ($alpha < 122) {
                    if ($x < $minX) $minX = $x;
                    if ($y < $minY) $minY = $y;
                    if ($x > $maxX) $maxX = $x;
                    if ($y > $maxY) $maxY = $y;
                }
            }
        }

        if ($maxX < $minX || $maxY < $minY) {
            $minX = 0;
            $minY = 0;
            $maxX = $w - 1;
            $maxY = $h - 1;
        }

        $cropW = $maxX - $minX + 1;
        $cropH = $maxY - $minY + 1;

        // Kanvas standar 512x512 dengan ~95% fill (486px area isi, sama seperti logo-ubsi & logo-bem-ubsi)
        $canvasSize = 512;
        $innerMax = 486;
        $scale = min($innerMax / $cropW, $innerMax / $cropH);
        $targetW = max(1, (int) round($cropW * $scale));
        $targetH = max(1, (int) round($cropH * $scale));
        $offsetX = (int) floor(($canvasSize - $targetW) / 2);
        $offsetY = (int) floor(($canvasSize - $targetH) / 2);

        $dst = imagecreatetruecolor($canvasSize, $canvasSize);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $canvasSize, $canvasSize, $transparent);

        imagealphablending($dst, true);
        imagecopyresampled($dst, $src, $offsetX, $offsetY, $minX, $minY, $targetW, $targetH, $cropW, $cropH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);

        $saved = imagepng($dst, $destinationPath, 6);

        imagedestroy($src);
        imagedestroy($dst);

        return (bool) $saved;
    }
}
