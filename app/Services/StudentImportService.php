<?php

namespace App\Services;

use App\Models\Student;
use App\Models\VoterParticipation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentImportService
{
    /**
     * Download template CSV standar yang kompatibel dengan Microsoft Excel dan Google Sheets.
     */
    public function downloadTemplateCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_dpt_pemira.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM agar Microsoft Excel langsung mengenali encoding UTF-8
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Baris Header
            fputcsv($handle, [
                'nim',
                'nama',
                'program_studi',
                'kelas',
                'semester',
                'asal_kampus',
                'email_kampus',
            ]);

            // Baris Contoh Data
            fputcsv($handle, [
                '12220001',
                'Ahmad Fauzi',
                'Sistem Informasi',
                '12.4A.01',
                '4',
                'UBSI Karawang',
                '12220001@bsi.ac.id',
            ]);
            fputcsv($handle, [
                '12220002',
                'Siti Nurhaliza',
                'Sistem Informasi',
                '12.4A.01',
                '4',
                'UBSI Cikampek',
                '12220002@bsi.ac.id',
            ]);
            fputcsv($handle, [
                '12220003',
                'Budi Santoso',
                'Teknologi Informasi',
                '12.4B.01',
                '4',
                'UBSI Karawang',
                '12220003@bsi.ac.id',
            ]);

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Memproses import file (CSV, SQL, JSON) dan mendaftarkannya langsung ke DPT Periode Pemilihan.
     */
    public function importFile(UploadedFile $file, int $periodId, bool $updateExisting = true): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        return match ($extension) {
            'csv', 'tsv', 'txt' => $this->importCsv($file->getRealPath(), $periodId, $updateExisting),
            'sql' => $this->importSql($file->getRealPath(), $periodId, $updateExisting),
            'json' => $this->importJson($file->getRealPath(), $periodId, $updateExisting),
            default => throw new \InvalidArgumentException("Format file .{$extension} tidak didukung. Harap gunakan file CSV, SQL, atau JSON."),
        };
    }

    /**
     * Import dari file CSV / Text Delimited.
     */
    protected function importCsv(string $path, int $periodId, bool $updateExisting): array
    {
        $content = file_get_contents($path);
        if ($content === false || trim($content) === '') {
            throw new \RuntimeException('File CSV kosong atau tidak dapat dibaca.');
        }

        // Hapus UTF-8 BOM jika ada
        $bom = pack('H*', 'EFBBBF');
        $content = preg_replace("/^{$bom}/", '', $content);

        // Deteksi delimiter (koma, titik koma, atau tab)
        $firstLine = strtok($content, "\r\n");
        $delimiter = $this->detectDelimiter($firstLine);

        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $content));
        if (empty($lines)) {
            throw new \RuntimeException('Tidak ada baris data yang ditemukan pada file.');
        }

        // Parse header
        $headerRaw = str_getcsv(array_shift($lines), $delimiter);
        $headerMap = $this->mapHeaderIndices($headerRaw);

        if (!isset($headerMap['nim'])) {
            throw new \RuntimeException('Kolom "nim" tidak ditemukan pada header CSV. Pastikan kolom pertama atau header bernama "nim".');
        }

        $totalRows = 0;
        $importedCount = 0;
        $updatedCount = 0;
        $participationsCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($lines as $lineIndex => $line) {
                if (trim($line) === '') {
                    continue;
                }

                $row = str_getcsv($line, $delimiter);
                $totalRows++;

                $nim = isset($headerMap['nim']) && isset($row[$headerMap['nim']]) 
                    ? trim((string) $row[$headerMap['nim']]) 
                    : '';

                if (empty($nim)) {
                    $errors[] = "Baris " . ($lineIndex + 2) . ": NIM kosong, dilewati.";
                    continue;
                }

                $name = isset($headerMap['nama']) && isset($row[$headerMap['nama']]) 
                    ? trim((string) $row[$headerMap['nama']]) 
                    : 'Mahasiswa';

                if (empty($name)) {
                    $name = 'Mahasiswa';
                }

                $prodi = isset($headerMap['prodi']) && isset($row[$headerMap['prodi']]) 
                    ? trim((string) $row[$headerMap['prodi']]) 
                    : null;

                $kelas = isset($headerMap['kelas']) && isset($row[$headerMap['kelas']]) 
                    ? trim((string) $row[$headerMap['kelas']]) 
                    : null;

                $semester = isset($headerMap['semester']) && isset($row[$headerMap['semester']]) 
                    ? trim((string) $row[$headerMap['semester']]) 
                    : null;

                $kampusRaw = isset($headerMap['asal_kampus']) && isset($row[$headerMap['asal_kampus']]) 
                    ? trim((string) $row[$headerMap['asal_kampus']]) 
                    : '';
                $asalKampus = $this->normalizeCampus($kampusRaw);

                $emailRaw = isset($headerMap['email_kampus']) && isset($row[$headerMap['email_kampus']]) 
                    ? trim((string) $row[$headerMap['email_kampus']]) 
                    : '';
                $emailKampus = !empty($emailRaw) ? $emailRaw : "{$nim}@bsi.ac.id";

                // Simpan atau Perbarui Data Mahasiswa
                $student = Student::where('nim', $nim)->first();

                if ($student) {
                    if ($updateExisting) {
                        $student->update([
                            'name' => $name,
                            'study_program' => $prodi ?: $student->study_program,
                            'class_name' => $kelas ?: $student->class_name,
                            'semester' => $semester ?: $student->semester,
                            'asal_kampus' => $asalKampus ?: $student->asal_kampus,
                            'email_kampus' => $emailKampus ?: $student->email_kampus,
                        ]);
                        $updatedCount++;
                    }
                } else {
                    $student = Student::create([
                        'nim' => $nim,
                        'name' => $name,
                        'study_program' => $prodi,
                        'class_name' => $kelas,
                        'semester' => $semester,
                        'is_active' => true,
                        'asal_kampus' => $asalKampus,
                        'email_kampus' => $emailKampus,
                    ]);
                    $importedCount++;
                }

                // Daftarkan ke Voter Participation Periode Pemilihan Ini
                $participation = VoterParticipation::firstOrCreate(
                    [
                        'election_period_id' => $periodId,
                        'student_nim' => $nim,
                    ],
                    [
                        'has_voted' => false,
                        'voted_at' => null,
                        'encrypted_vote_receipt' => null,
                        'verified_at' => null,
                        'tps_location' => null,
                    ]
                );

                if ($participation->wasRecentlyCreated) {
                    $participationsCount++;
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'success' => true,
            'total_rows' => $totalRows,
            'imported' => $importedCount,
            'updated' => $updatedCount,
            'participations' => $participationsCount,
            'errors' => $errors,
        ];
    }

    /**
     * Import dari file SQL Dump (INSERT INTO students ...).
     */
    protected function importSql(string $path, int $periodId, bool $updateExisting): array
    {
        $sql = file_get_contents($path);
        if ($sql === false || trim($sql) === '') {
            throw new \RuntimeException('File SQL kosong atau tidak dapat dibaca.');
        }

        $initialStudentCount = Student::count();
        $initialParticipationCount = VoterParticipation::where('election_period_id', $periodId)->count();

        DB::beginTransaction();
        try {
            // Eksekusi statement SQL
            DB::unprepared($sql);

            // Standarisasi asal_kampus dan email_kampus jika belum terisi pada data yang baru di-insert
            Student::whereNull('asal_kampus')->orWhere('asal_kampus', '')->update(['asal_kampus' => 'UBSI Karawang']);
            DB::statement("UPDATE students SET email_kampus = CONCAT(nim, '@bsi.ac.id') WHERE email_kampus IS NULL OR email_kampus = ''");

            // Sinkronisasi seluruh mahasiswa ke Voter Participations untuk periode ini
            $existingNims = VoterParticipation::where('election_period_id', $periodId)->pluck('student_nim')->toArray();
            $allStudents = Student::pluck('nim')->toArray();
            $missingNims = array_diff($allStudents, $existingNims);

            $newParticipations = [];
            $now = now('Asia/Jakarta');
            foreach ($missingNims as $nim) {
                $newParticipations[] = [
                    'election_period_id' => $periodId,
                    'student_nim' => $nim,
                    'has_voted' => false,
                    'voted_at' => null,
                    'encrypted_vote_receipt' => null,
                    'verified_at' => null,
                    'tps_location' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            foreach (array_chunk($newParticipations, 500) as $chunk) {
                VoterParticipation::insert($chunk);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new \RuntimeException('Gagal mengeksekusi SQL: ' . $e->getMessage());
        }

        $finalStudentCount = Student::count();
        $finalParticipationCount = VoterParticipation::where('election_period_id', $periodId)->count();

        return [
            'success' => true,
            'total_rows' => count($missingNims),
            'imported' => max(0, $finalStudentCount - $initialStudentCount),
            'updated' => 0,
            'participations' => max(0, $finalParticipationCount - $initialParticipationCount),
            'errors' => [],
        ];
    }

    /**
     * Import dari file JSON.
     */
    protected function importJson(string $path, int $periodId, bool $updateExisting): array
    {
        $raw = file_get_contents($path);
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            throw new \RuntimeException('Struktur file JSON tidak valid. Harus berupa array data mahasiswa.');
        }

        // Jika dibungkus key "students" atau "data"
        if (isset($data['students']) && is_array($data['students'])) {
            $data = $data['students'];
        } elseif (isset($data['data']) && is_array($data['data'])) {
            $data = $data['data'];
        }

        $totalRows = 0;
        $importedCount = 0;
        $updatedCount = 0;
        $participationsCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($data as $index => $item) {
                $totalRows++;
                $nim = trim((string) ($item['nim'] ?? $item['no_induk'] ?? ''));

                if (empty($nim)) {
                    $errors[] = "Item ke-{$index}: NIM kosong, dilewati.";
                    continue;
                }

                $name = trim((string) ($item['nama'] ?? $item['name'] ?? 'Mahasiswa'));
                $prodi = $item['program_studi'] ?? $item['study_program'] ?? $item['prodi'] ?? null;
                $kelas = $item['kelas'] ?? $item['class_name'] ?? null;
                $semester = $item['semester'] ?? null;
                $asalKampus = $this->normalizeCampus($item['asal_kampus'] ?? $item['kampus'] ?? '');
                $emailKampus = $item['email_kampus'] ?? $item['email'] ?? "{$nim}@bsi.ac.id";

                $student = Student::where('nim', $nim)->first();

                if ($student) {
                    if ($updateExisting) {
                        $student->update([
                            'name' => $name,
                            'study_program' => $prodi ?: $student->study_program,
                            'class_name' => $kelas ?: $student->class_name,
                            'semester' => $semester ?: $student->semester,
                            'asal_kampus' => $asalKampus ?: $student->asal_kampus,
                            'email_kampus' => $emailKampus ?: $student->email_kampus,
                        ]);
                        $updatedCount++;
                    }
                } else {
                    Student::create([
                        'nim' => $nim,
                        'name' => $name,
                        'study_program' => $prodi,
                        'class_name' => $kelas,
                        'semester' => $semester,
                        'is_active' => true,
                        'asal_kampus' => $asalKampus,
                        'email_kampus' => $emailKampus,
                    ]);
                    $importedCount++;
                }

                $participation = VoterParticipation::firstOrCreate(
                    [
                        'election_period_id' => $periodId,
                        'student_nim' => $nim,
                    ],
                    [
                        'has_voted' => false,
                        'voted_at' => null,
                        'encrypted_vote_receipt' => null,
                        'verified_at' => null,
                        'tps_location' => null,
                    ]
                );

                if ($participation->wasRecentlyCreated) {
                    $participationsCount++;
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'success' => true,
            'total_rows' => $totalRows,
            'imported' => $importedCount,
            'updated' => $updatedCount,
            'participations' => $participationsCount,
            'errors' => $errors,
        ];
    }

    /**
     * Deteksi delimiter pada baris pertama file CSV.
     */
    protected function detectDelimiter(string $line): string
    {
        $delimiters = [',', ';', "\t"];
        $counts = [];

        foreach ($delimiters as $delim) {
            $counts[$delim] = substr_count($line, $delim);
        }

        arsort($counts);
        $bestDelimiter = key($counts);

        return $counts[$bestDelimiter] > 0 ? $bestDelimiter : ',';
    }

    /**
     * Pemetaan indeks kolom header secara fleksibel dan case-insensitive.
     */
    protected function mapHeaderIndices(array $headers): array
    {
        $map = [];

        foreach ($headers as $index => $headerName) {
            $h = strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', $headerName)));

            if (in_array($h, ['nim', 'no_induk', 'nomor_induk', 'nrp'], true)) {
                $map['nim'] = $index;
            } elseif (in_array($h, ['nama', 'name', 'nama_lengkap', 'nama_mahasiswa'], true)) {
                $map['nama'] = $index;
            } elseif (in_array($h, ['prodi', 'program_studi', 'study_program', 'jurusan'], true)) {
                $map['prodi'] = $index;
            } elseif (in_array($h, ['kelas', 'class', 'class_name'], true)) {
                $map['kelas'] = $index;
            } elseif (in_array($h, ['semester', 'sem'], true)) {
                $map['semester'] = $index;
            } elseif (in_array($h, ['asal_kampus', 'kampus', 'lokasi', 'lokasi_kampus'], true)) {
                $map['asal_kampus'] = $index;
            } elseif (in_array($h, ['email_kampus', 'email', 'email_mhs'], true)) {
                $map['email_kampus'] = $index;
            }
        }

        // Fallback jika header tidak bernama NIM, asumsikan kolom index 0 adalah NIM dan index 1 adalah Nama
        if (!isset($map['nim']) && count($headers) >= 1) {
            $map['nim'] = 0;
        }
        if (!isset($map['nama']) && count($headers) >= 2) {
            $map['nama'] = 1;
        }

        return $map;
    }

    /**
     * Standarisasi nama kampus: UBSI Karawang atau UBSI Cikampek.
     */
    protected function normalizeCampus(?string $campus): string
    {
        if (empty($campus)) {
            return 'UBSI Karawang';
        }

        if (stripos($campus, 'cikampek') !== false) {
            return 'UBSI Cikampek';
        }

        return 'UBSI Karawang';
    }
}
