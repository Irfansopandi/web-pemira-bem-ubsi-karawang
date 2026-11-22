<?php

namespace App\Services;

use App\Models\VoterParticipation;

class GuestbookService
{
    /**
     * Memeriksa apakah mahasiswa dengan NIM ini sudah mengisi Buku Tamu internal.
     * Terintegrasi murni dengan database internal PEMIRA dan terisolasi per periode pemilihan.
     *
     * @return array{checked: bool, is_present: bool, message: string}
     */
    public function isStudentPresent(string $nim, ?int $periodId = null): array
    {
        $enabled = (bool) config('services.guestbook.check_enabled', true);

        // Jika pemeriksaan buku tamu dinonaktifkan via konfigurasi
        if (! $enabled) {
            return [
                'checked' => false,
                'is_present' => true,
                'message' => 'Pemeriksaan buku tamu dinonaktifkan.',
            ];
        }

        // Cek data kehadiran aktual di database internal PEMIRA (tabel voter_participations)
        $query = VoterParticipation::where('student_nim', trim($nim))
            ->whereNotNull('verified_at');

        if ($periodId) {
            $query->where('election_period_id', $periodId);
        }

        $isPresent = $query->exists();

        return [
            'checked' => true,
            'is_present' => $isPresent,
            'message' => $isPresent
                ? 'Mahasiswa terverifikasi hadir di Buku Tamu internal.'
                : 'NIM belum mengisi atau tercatat hadir di Buku Tamu internal.',
        ];
    }
}
