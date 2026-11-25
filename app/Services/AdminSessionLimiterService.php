<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminSessionLimiterService
{
    public const int MAX_CONCURRENT_SESSIONS = 5;

    /**
     * Batas waktu idle (detik) berdasarkan last_activity sebelum slot sesi dibebaskan otomatis.
     * Panel admin melakukan keep-alive ping setiap 60 detik selama tab terbuka.
     */
    public const int IDLE_TIMEOUT_SECONDS = 300;

    /**
     * Periksa apakah akun admin masih memiliki kuota sesi aktif (< 5).
     */
    public function canLogin(int $adminId, ?string $currentSessionId = null): bool
    {
        return $this->withLock($adminId, function () use ($adminId, $currentSessionId): bool {
            $sessions = $this->prune($this->getSessions($adminId));
            $this->saveSessions($adminId, $sessions);

            if ($currentSessionId !== null && isset($sessions[$currentSessionId])) {
                return true;
            }

            return count($sessions) < self::MAX_CONCURRENT_SESSIONS;
        });
    }

    /**
     * Daftarkan sesi baru setelah login berhasil.
     */
    public function register(int $adminId, string $sessionId): void
    {
        $this->withLock($adminId, function () use ($adminId, $sessionId): void {
            $sessions = $this->prune($this->getSessions($adminId));
            $sessions[$sessionId] = now()->timestamp;
            $this->saveSessions($adminId, $sessions);
        });
    }

    /**
     * Perbarui last_activity untuk sesi yang sedang aktif.
     * Mengembalikan false jika sesi belum terdaftar dan seluruh kuota (5) telah terisi sesi lain.
     */
    public function touch(int $adminId, string $sessionId): bool
    {
        return $this->withLock($adminId, function () use ($adminId, $sessionId): bool {
            $sessions = $this->prune($this->getSessions($adminId));

            if (! isset($sessions[$sessionId]) && count($sessions) >= self::MAX_CONCURRENT_SESSIONS) {
                $this->saveSessions($adminId, $sessions);
                return false;
            }

            $sessions[$sessionId] = now()->timestamp;
            $this->saveSessions($adminId, $sessions);

            return true;
        });
    }

    /**
     * Hapus sesi dari daftar aktif saat admin melakukan logout.
     */
    public function forget(int $adminId, string $sessionId): void
    {
        $this->withLock($adminId, function () use ($adminId, $sessionId): void {
            $sessions = $this->prune($this->getSessions($adminId));
            unset($sessions[$sessionId]);
            $this->saveSessions($adminId, $sessions);
        });
    }

    /**
     * Hitung jumlah sesi aktif saat ini untuk akun admin.
     */
    public function activeCount(int $adminId): int
    {
        return $this->withLock($adminId, function () use ($adminId): int {
            $sessions = $this->prune($this->getSessions($adminId));
            $this->saveSessions($adminId, $sessions);

            return count($sessions);
        });
    }

    /**
     * Bersihkan sesi yang sudah melewati batas idle last_activity
     * (serta yang sudah terhapus di tabel sessions jika memakai driver database).
     *
     * @param  array<string, int>  $sessions
     * @return array<string, int>
     */
    private function prune(array $sessions): array
    {
        $cutoff = now()->timestamp - self::IDLE_TIMEOUT_SECONDS;

        $active = array_filter(
            $sessions,
            fn (int $lastActivity): bool => $lastActivity >= $cutoff
        );

        if (config('session.driver') === 'database' && ! empty($active)) {
            try {
                $existingIds = DB::table(config('session.table', 'sessions'))
                    ->whereIn('id', array_keys($active))
                    ->where('last_activity', '>=', $cutoff)
                    ->pluck('id')
                    ->all();

                $existingLookup = array_flip($existingIds);
                $active = array_intersect_key($active, $existingLookup);
            } catch (\Throwable) {
                // Abaikan jika tabel sessions belum tersedia pada konteks tertentu
            }
        }

        return $active;
    }

    /**
     * @return array<string, int>
     */
    private function getSessions(int $adminId): array
    {
        $data = Cache::get($this->cacheKey($adminId), []);

        return is_array($data) ? $data : [];
    }

    /**
     * @param  array<string, int>  $sessions
     */
    private function saveSessions(int $adminId, array $sessions): void
    {
        if (empty($sessions)) {
            Cache::forget($this->cacheKey($adminId));
            return;
        }

        Cache::put($this->cacheKey($adminId), $sessions, now()->addMinutes((int) config('session.lifetime', 480)));
    }

    private function cacheKey(int $adminId): string
    {
        return 'admin_active_sessions_' . $adminId;
    }

    /**
     * @template T
     * @param  callable(): T  $callback
     * @return T
     */
    private function withLock(int $adminId, callable $callback): mixed
    {
        return Cache::lock('admin_active_sessions_lock_' . $adminId, 10)->block(3, $callback);
    }
}
