<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixTimezoneData extends Command
{
    protected $signature = 'pemira:fix-timezone';
    protected $description = 'Fix timezone for voter participations created during UTC configuration';

    public function handle(): int
    {
        // Sesuaikan catatan yang terekam pada malam pengujian (UTC 18:00 - 23:59 tanggal 21 Nov 2026, yang sebenarnya WIB 01:00 - 06:59 22 Nov 2026)
        $affected = DB::table('voter_participations')
            ->where(function ($q) {
                $q->whereBetween('voted_at', ['2026-11-21 17:00:00', '2026-11-21 23:59:59'])
                  ->orWhereBetween('verified_at', ['2026-11-21 17:00:00', '2026-11-21 23:59:59']);
            })
            ->update([
                'voted_at' => DB::raw("IF(voted_at BETWEEN '2026-11-21 17:00:00' AND '2026-11-21 23:59:59', DATE_ADD(voted_at, INTERVAL 7 HOUR), voted_at)"),
                'verified_at' => DB::raw("IF(verified_at BETWEEN '2026-11-21 17:00:00' AND '2026-11-21 23:59:59', DATE_ADD(verified_at, INTERVAL 7 HOUR), verified_at)"),
            ]);

        $this->info("Berhasil mengonversi {$affected} data waktu ke Waktu Indonesia Barat (WIB / Asia/Jakarta).");

        return self::SUCCESS;
    }
}
