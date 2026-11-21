<?php

namespace App\Services;

use App\Models\VoterParticipation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GuestbookService
{
    /**
     * Memeriksa apakah mahasiswa dengan NIM ini sudah mengisi buku tamu TPS.
     * Dapat dispesifikasikan untuk periode pemilihan tertentu.
     *
     * @return array{checked: bool, is_present: bool, message: string}
     */
    public function isStudentPresent(string $nim, ?int $periodId = null): array
    {
        $enabled = (bool) config('services.guestbook.check_enabled', true);
        $baseUrl = rtrim((string) config('services.guestbook.base_url', ''), '/');

        // 1. Cek apakah ada record verifikasi kehadiran lokal / seeder testing (terisolasi per periode jika ada periodId)
        $query = VoterParticipation::where('student_nim', $nim)->whereNotNull('verified_at');
        if ($periodId) {
            $query->where('election_period_id', $periodId);
        }
        $isSeederVerified = $query->exists();

        // 2. Jika pemeriksaan buku tamu dinonaktifkan
        if (! $enabled) {
            return [
                'checked' => false,
                'is_present' => true,
                'message' => 'Pemeriksaan buku tamu dinonaktifkan.',
            ];
        }

        // 3. Jika URL eksternal belum dihubungkan (masih placeholder) atau saat pengujian lokal:
        // Mahasiswa seeder yang telah ditandai hadir di TPS otomatis terverifikasi
        $isPlaceholderUrl = empty($baseUrl) || str_contains($baseUrl, 'nama-domain-buku-tamu.com');

        if ($isPlaceholderUrl) {
            if ($isSeederVerified) {
                return [
                    'checked' => true,
                    'is_present' => true,
                    'message' => 'Mahasiswa terverifikasi hadir di Buku Tamu TPS (Data Seeder).',
                ];
            }

            return [
                'checked' => true,
                'is_present' => false,
                'message' => 'NIM belum tercatat hadir di Buku Tamu TPS.',
            ];
        }

        // 4. Panggil API web buku tamu eksternal secara realtime
        try {
            $endpoint = "{$baseUrl}/api/pemira/cek-kehadiran/{$nim}";

            $response = Http::timeout(4)
                ->acceptJson()
                ->get($endpoint);

            if ($response->successful()) {
                $data = $response->json();

                $isPresent = (bool) (
                    $data['hadir'] 
                    ?? $data['sudah_hadir'] 
                    ?? $data['is_present'] 
                    ?? $data['present'] 
                    ?? ($data['status'] ?? null) === 'success'
                    ?? false
                );

                return [
                    'checked' => true,
                    'is_present' => $isPresent,
                    'message' => $isPresent 
                        ? 'Mahasiswa terverifikasi hadir di Buku Tamu TPS.' 
                        : 'NIM belum tercatat hadir di Buku Tamu TPS.',
                ];
            }

            // Fallback jika API eksternal merespon selain 200: periksa data seeder lokal
            if ($isSeederVerified) {
                return [
                    'checked' => true,
                    'is_present' => true,
                    'message' => 'Mahasiswa terverifikasi hadir di Buku Tamu TPS (Data Seeder).',
                ];
            }

            Log::warning("Web buku tamu merespon HTTP {$response->status()} untuk NIM {$nim}");

            return [
                'checked' => true,
                'is_present' => false,
                'message' => 'Gagal memverifikasi status kehadiran ke server buku tamu.',
            ];
        } catch (\Throwable $e) {
            Log::warning("Koneksi ke web buku tamu gagal ({$baseUrl}): " . $e->getMessage());

            // Jika offline/gagal koneksi tetapi merupakan data dummy seeder yang sudah hadir:
            if ($isSeederVerified) {
                return [
                    'checked' => true,
                    'is_present' => true,
                    'message' => 'Mahasiswa terverifikasi hadir di Buku Tamu TPS (Data Seeder).',
                ];
            }

            return [
                'checked' => false,
                'is_present' => false,
                'message' => 'Tidak dapat terhubung ke sistem buku tamu. Pastikan URL domain benar dan server aktif.',
            ];
        }
    }
}
