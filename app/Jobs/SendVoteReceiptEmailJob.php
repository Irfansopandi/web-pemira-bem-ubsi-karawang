<?php

namespace App\Jobs;

use App\Mail\VoteReceiptMail;
use App\Models\AuditLog;
use App\Models\VoterParticipation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendVoteReceiptEmailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct(
        public readonly int $participationId,
        public readonly ?string $ipAddress = null,
    ) {}

    /**
     * Kirim email tanda terima pemungutan suara secara idempoten dan aman.
     */
    public function handle(): void
    {
        $lock = Cache::lock('vote_receipt_email_' . $this->participationId, 30);

        if (! $lock->get()) {
            return;
        }

        try {
            $participation = VoterParticipation::with(['student', 'electionPeriod'])
                ->find($this->participationId);

            if (! $participation || ! $participation->has_voted || ! $participation->student || ! $participation->electionPeriod) {
                return;
            }

            // Idempotency: jangan kirim ulang jika sudah pernah berhasil dikirim untuk partisipasi ini
            $alreadySent = AuditLog::where('action', 'vote_receipt_email_sent')
                ->where('entity_type', 'VoterParticipation')
                ->where('entity_id', $participation->id)
                ->exists();

            if ($alreadySent) {
                return;
            }

            $email = trim((string) ($participation->student->email_kampus ?? ''));

            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Log::warning('Pengiriman email tanda terima PEMIRA dilewati: alamat email kampus tidak valid atau kosong.', [
                    'participation_id' => $participation->id,
                    'student_nim' => $participation->student_nim,
                    'election_period_id' => $participation->election_period_id,
                ]);

                AuditLog::create([
                    'admin_id' => null,
                    'action' => 'vote_receipt_email_skipped',
                    'entity_type' => 'VoterParticipation',
                    'entity_id' => $participation->id,
                    'new_values' => [
                        'student_nim' => $participation->student_nim,
                        'election_period_id' => $participation->election_period_id,
                        'status' => 'skipped',
                        'reason' => 'Alamat email kampus tidak tersedia atau tidak valid.',
                    ],
                    'ip_address' => $this->ipAddress,
                    'created_at' => now(),
                ]);

                return;
            }

            $votedAtFormatted = $participation->voted_at
                ? $participation->voted_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB'
                : now('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB';

            try {
                Mail::to($email)->send(new VoteReceiptMail(
                    studentName: $participation->student->name,
                    studentNim: $participation->student->nim,
                    periodName: $participation->electionPeriod->name,
                    votedAtFormatted: $votedAtFormatted,
                ));

                AuditLog::create([
                    'admin_id' => null,
                    'action' => 'vote_receipt_email_sent',
                    'entity_type' => 'VoterParticipation',
                    'entity_id' => $participation->id,
                    'new_values' => [
                        'student_nim' => $participation->student_nim,
                        'election_period_id' => $participation->election_period_id,
                        'recipient_email' => $email,
                        'status' => 'sent',
                    ],
                    'ip_address' => $this->ipAddress,
                    'created_at' => now(),
                ]);
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim email tanda terima PEMIRA.', [
                    'participation_id' => $participation->id,
                    'student_nim' => $participation->student_nim,
                    'election_period_id' => $participation->election_period_id,
                    'error' => $e->getMessage(),
                ]);

                AuditLog::create([
                    'admin_id' => null,
                    'action' => 'vote_receipt_email_failed',
                    'entity_type' => 'VoterParticipation',
                    'entity_id' => $participation->id,
                    'new_values' => [
                        'student_nim' => $participation->student_nim,
                        'election_period_id' => $participation->election_period_id,
                        'recipient_email' => $email,
                        'status' => 'failed',
                        'error' => Str::limit($e->getMessage(), 250),
                    ],
                    'ip_address' => $this->ipAddress,
                    'created_at' => now(),
                ]);
            }
        } finally {
            $lock->release();
        }
    }
}
