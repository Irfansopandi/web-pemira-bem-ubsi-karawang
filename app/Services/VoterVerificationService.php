<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\ElectionPeriod;
use App\Models\Student;
use App\Models\VoterParticipation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VoterVerificationService
{
    /**
     * Cari mahasiswa berdasarkan NIM untuk verifikasi kehadiran di TPS.
     *
     * @return array{status: string, message: string, student?: Student, participation?: VoterParticipation|null}
     */
    public function lookup(string $nim, ElectionPeriod $period): array
    {
        $nim = trim($nim);

        if ($nim === '') {
            return [
                'status' => 'empty',
                'message' => 'Silakan masukkan NIM mahasiswa.',
            ];
        }

        $student = Student::where('nim', $nim)->first();

        if (! $student) {
            return [
                'status' => 'not_found',
                'message' => "NIM '{$nim}' tidak terdaftar dalam Daftar Pemilih Tetap (DPT) Pemira UBSI.",
            ];
        }

        // Cek status partisipasi pemilih pada periode aktif
        $participation = VoterParticipation::where('election_period_id', $period->id)
            ->where('student_nim', $student->nim)
            ->first();

        // 1. Mahasiswa sudah mencoblos di bilik suara
        if ($participation && $participation->has_voted) {
            return [
                'status' => 'already_voted',
                'student' => $student,
                'participation' => $participation,
                'message' => "Mahasiswa telah menggunakan hak suaranya pada " . ($participation->voted_at ? $participation->voted_at->translatedFormat('d M Y, H:i') : 'periode ini') . " WIB.",
            ];
        }

        // 2. Mahasiswa sudah pernah terverifikasi hadir di meja registrasi
        if ($participation && $participation->verified_at) {
            return [
                'status' => 'already_verified',
                'student' => $student,
                'participation' => $participation,
                'message' => "NIM {$student->nim} sudah terverifikasi hadir pada pukul " . $participation->verified_at->format('H:i') . " WIB. Mahasiswa dapat langsung menuju bilik suara.",
            ];
        }

        // 3. Siap verifikasi (Data valid, belum verifikasi)
        return [
            'status' => 'ready',
            'student' => $student,
            'participation' => $participation,
            'message' => 'Data mahasiswa terdaftar di DPT. Silakan bubuhkan tanda tangan digital pemilih.',
        ];
    }

    /**
     * Simpan verifikasi kehadiran pemilih beserta tanda tangan digital.
     *
     * @return array{success: bool, message: string, participation?: VoterParticipation}
     */
    public function verify(
        string $nim,
        string $signatureData,
        ?int $adminId,
        ?string $tpsLocation,
        ElectionPeriod $period,
        ?string $phone = null,
        ?string $email = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): array {
        $nim = trim($nim);
        $student = Student::where('nim', $nim)->first();

        if (! $student) {
            return [
                'success' => false,
                'message' => "NIM '{$nim}' tidak terdaftar dalam DPT.",
            ];
        }

        // Cek apakah sudah memilih
        $existing = VoterParticipation::where('election_period_id', $period->id)
            ->where('student_nim', $student->nim)
            ->first();

        if ($existing && $existing->has_voted) {
            return [
                'success' => false,
                'message' => 'Mahasiswa ini telah menggunakan hak suaranya dan tidak dapat diverifikasi ulang.',
            ];
        }

        // Proses penyimpanan tanda tangan digital (base64 image PNG)
        $signaturePath = null;
        if (! empty($signatureData)) {
            $signaturePath = $this->saveSignatureImage($signatureData, $period->id, $student->nim);
        }

        if (! $signaturePath) {
            return [
                'success' => false,
                'message' => 'Tanda tangan digital wajib dibubuhkan sebelum verifikasi.',
            ];
        }

        $location = $tpsLocation ?: 'UBSI Karawang';

        return DB::transaction(function () use ($student, $period, $adminId, $signaturePath, $location, $phone, $email, $ipAddress, $userAgent) {
            $participation = VoterParticipation::updateOrCreate(
                [
                    'election_period_id' => $period->id,
                    'student_nim' => $student->nim,
                ],
                [
                    'has_voted' => false,
                    'verified_at' => now('Asia/Jakarta'),
                    'verified_by_admin_id' => $adminId,
                    'signature_path' => $signaturePath,
                    'phone' => $phone,
                    'tps_location' => $location,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]
            );

            // Perbarui identitas mahasiswa (Asal Kampus, Email Kampus, & Status Aktif)
            $studentUpdates = ['is_active' => true];
            if ($location) {
                $studentUpdates['asal_kampus'] = $location;
            }
            if (! empty($email)) {
                $studentUpdates['email_kampus'] = $email;
            }
            $student->update($studentUpdates);

            // Audit Trail
            if ($adminId) {
                AuditLog::create([
                    'admin_id' => $adminId,
                    'action' => 'VERIFY_VOTER_PRESENCE',
                    'entity_type' => 'VoterParticipation',
                    'entity_id' => $participation->id,
                    'old_values' => null,
                    'new_values' => [
                        'student_nim' => $student->nim,
                        'student_name' => $student->name,
                        'tps_location' => $location,
                        'verified_at' => now()->toIso8601String(),
                    ],
                    'ip_address' => $ipAddress,
                    'created_at' => now(),
                ]);
            }

            return [
                'success' => true,
                'message' => "Kehadiran NIM {$student->nim} ({$student->name}) berhasil diverifikasi! Hak suara telah aktif di bilik suara.",
                'participation' => $participation->fresh(['student', 'verifiedBy']),
            ];
        });
    }

    /**
     * Batalkan status verifikasi kehadiran pemilih (hanya jika belum mencoblos).
     *
     * @return array{success: bool, message: string}
     */
    public function cancel(VoterParticipation $participation, int $adminId, ?string $ipAddress = null): array
    {
        if ($participation->has_voted) {
            return [
                'success' => false,
                'message' => 'Tidak dapat membatalkan verifikasi pemilih yang sudah menggunakan hak suara di bilik suara.',
            ];
        }

        // Hapus file tanda tangan jika ada
        if ($participation->signature_path && Storage::disk('public')->exists($participation->signature_path)) {
            Storage::disk('public')->delete($participation->signature_path);
        }

        $studentNim = $participation->student_nim;

        $participation->update([
            'verified_at' => null,
            'verified_by_admin_id' => null,
            'signature_path' => null,
        ]);

        Student::where('nim', $studentNim)->update(['is_active' => false]);

        AuditLog::create([
            'admin_id' => $adminId,
            'action' => 'CANCEL_VOTER_PRESENCE',
            'entity_type' => 'VoterParticipation',
            'entity_id' => $participation->id,
            'old_values' => ['student_nim' => $studentNim],
            'new_values' => ['verified_at' => null],
            'ip_address' => $ipAddress,
            'created_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => "Verifikasi kehadiran untuk NIM {$studentNim} berhasil dibatalkan.",
        ];
    }

    /**
     * Dapatkan ringkasan statistik kehadiran dan riwayat terbaru.
     */
    public function getSummary(ElectionPeriod $period): array
    {
        $totalDpt = VoterParticipation::where('election_period_id', $period->id)->count();

        $totalVerified = VoterParticipation::where('election_period_id', $period->id)
            ->whereNotNull('verified_at')
            ->count();

        $totalVoted = VoterParticipation::where('election_period_id', $period->id)
            ->where('has_voted', true)
            ->count();

        $totalWaitingBooth = max(0, $totalVerified - $totalVoted);
        $totalAbsent = max(0, $totalDpt - $totalVerified);

        $recentVerifications = VoterParticipation::with('student', 'verifiedBy')
            ->where('election_period_id', $period->id)
            ->whereNotNull('verified_at')
            ->latest('verified_at')
            ->take(8)
            ->get();

        return [
            'total_dpt' => $totalDpt,
            'total_verified' => $totalVerified,
            'total_voted' => $totalVoted,
            'total_waiting_booth' => $totalWaitingBooth,
            'total_absent' => $totalAbsent,
            'attendance_rate' => $totalDpt > 0 ? round(($totalVerified / $totalDpt) * 100, 1) : 0,
            'recent_verifications' => $recentVerifications,
        ];
    }

    /**
     * Decode base64 image dan simpan ke file PNG.
     */
    protected function saveSignatureImage(string $base64Data, int $periodId, string $nim): ?string
    {
        try {
            // Format: data:image/png;base64,iVBORw0KGgo...
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]); // png

                $imageData = base64_decode($base64Data);

                if ($imageData === false) {
                    return null;
                }

                $filename = "signatures/{$periodId}/" . $nim . '_' . time() . '.' . $type;
                Storage::disk('public')->put($filename, $imageData);

                return $filename;
            }

            return null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
