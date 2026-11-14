<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\CandidatePair;
use App\Models\ElectionPeriod;
use App\Models\Student;
use App\Models\VoterParticipation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Otoritas (Admins & Committee)
        $admin = Admin::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator PEMIRA UBSI',
                'email' => 'admin.pemira@bsi.ac.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'tps_location' => 'Pusat Kampus UBSI Karawang',
                'is_active' => true,
            ]
        );

        $committee = Admin::firstOrCreate(
            ['username' => 'panitia_tps1'],
            [
                'name' => 'Petugas TPS 01 (Aula Utama)',
                'email' => 'tps01@bsi.ac.id',
                'password' => Hash::make('panitia123'),
                'role' => 'committee',
                'tps_location' => 'Aula Utama Gedung BSI Karawang',
                'is_active' => true,
            ]
        );

        // 2. Periode Pemilihan Aktif (Multi-Period Archiving)
        $period = ElectionPeriod::firstOrCreate(
            ['academic_year' => '2026/2027'],
            [
                'name' => 'PEMIRA BEM Universitas BSI Karawang 2026/2027',
                'start_time' => now()->subDay(),
                'end_time' => now()->addDays(7),
                'status' => 'active',
                'is_result_published' => false,
            ]
        );

        // 3. Pasangan Calon Ketua & Wakil Ketua BEM (3 Paslon)
        $paslon1 = CandidatePair::updateOrCreate(
            [
                'election_period_id' => $period->id,
                'candidate_number' => 1,
            ],
            [
                'chairman_name' => 'Fajar Nugraha',
                'chairman_nim' => '12220101',
                'chairman_study_program' => 'Sistem Informasi',
                'vice_chairman_name' => 'Siti Aisyah Rahmawati',
                'vice_chairman_nim' => '12220145',
                'vice_chairman_study_program' => 'Teknologi Informasi',
                'photo_url' => 'storage/candidates/paslon-01.png',
                'vision' => 'Mewujudkan BEM UBSI Karawang yang Progresif, Kolaboratif, Transparan, dan Berorientasi pada Pengembangan Potensi Mahasiswa di Era Digital.',
                'mission' => [
                    'Membangun ruang aspirasi mahasiswa yang terbuka, inklusif, dan responsif terhadap isu kampus.',
                    'Mengoptimalkan sinergi dan kolaborasi antar organisasi mahasiswa dan himpunan program studi.',
                    'Mendorong inovasi teknologi dan literasi digital dalam kegiatan kepemudaan kampus.',
                    'Menjunjung tinggi transparansi administrasi serta akuntabilitas keuangan organisasi.',
                ],
                'work_programs' => [
                    'UBSI Tech & Youth Festival (Pameran Karya & Karir Mahasiswa)',
                    'Portal Terpadu Aspirasi Digital Mahasiswa',
                    'BEM Menyapa: Forum Dialog Terbuka & Solutif',
                ],
            ]
        );

        $paslon2 = CandidatePair::updateOrCreate(
            [
                'election_period_id' => $period->id,
                'candidate_number' => 2,
            ],
            [
                'chairman_name' => 'Muhammad Rizky Pratama',
                'chairman_nim' => '12220202',
                'chairman_study_program' => 'Rekayasa Perangkat Lunak',
                'vice_chairman_name' => 'Nabila Putri Maharani',
                'vice_chairman_nim' => '12220288',
                'vice_chairman_study_program' => 'Manajemen Informatika',
                'photo_url' => 'storage/candidates/paslon-02.png',
                'vision' => 'Terwujudnya BEM UBSI Karawang yang Berintegritas, Mandiri, Berdaya Saing Global, dan Mengabdi Nyata untuk Civitas Akademika.',
                'mission' => [
                    'Menguatkan kemitraan strategis dengan industri dan komunitas teknologi nasional.',
                    'Mengembangkan program inkubasi wirausaha dan sertifikasi keahlian mahasiswa.',
                    'Mewujudkan advokasi kesejahteraan dan sarana prasarana mahasiswa yang solutif.',
                    'Menyelenggarakan pengabdian masyarakat berkelanjutan berbasis riset dan teknologi.',
                ],
                'work_programs' => [
                    'Career & Industry Mentorship Program',
                    'Rumah Advokasi Sahabat Mahasiswa',
                    'Desa Binaan Berdaya UBSI Karawang',
                ],
            ]
        );

        $paslon3 = CandidatePair::updateOrCreate(
            [
                'election_period_id' => $period->id,
                'candidate_number' => 3,
            ],
            [
                'chairman_name' => 'Dimas Aditya Pratama',
                'chairman_nim' => '12220303',
                'chairman_study_program' => 'Sistem Informasi',
                'vice_chairman_name' => 'Zahra Amanda Putri',
                'vice_chairman_nim' => '12220355',
                'vice_chairman_study_program' => 'Teknologi Informasi',
                'photo_url' => 'storage/candidates/paslon-03.png',
                'vision' => 'Mentransformasi BEM UBSI Karawang sebagai Katalisator Perubahan Mahasiswa yang Berintegritas, Kreatif, Adaptif, dan Peduli Sosial.',
                'mission' => [
                    'Menggalang gerakan solidaritas dan kepedulian sosial kampus berbasis pengabdian.',
                    'Membangun inkubator kreativitas dan riset terapan bagi talenta muda mahasiswa.',
                    'Mengawal advokasi hak-hak akademik dan transparansi birokrasi kampus secara konstruktif.',
                    'Memperluas jejaring BEM UBSI Karawang di kancah daerah maupun nasional.',
                ],
                'work_programs' => [
                    'UBSI Social & Green Movement',
                    'Ruang Solutif: Hotline Advokasi Mahasiswa 24 Jam',
                    'Pekan Olahraga & Seni Demokrasi Mahasiswa (PORDEM)',
                ],
            ]
        );

        // 4. Data Mahasiswa DPT (Primary Key NIM dengan Tanggal Lahir Unik per NIM)
        $studentsData = [
            ['nim' => '12220001', 'name' => 'Ahmad Fauzi', 'study_program' => 'Sistem Informasi', 'class_group' => '12.4A.01', 'birth_date' => '2004-03-15'],
            ['nim' => '12220002', 'name' => 'Dinda Permata', 'study_program' => 'Teknologi Informasi', 'class_group' => '12.4B.01', 'birth_date' => '2004-07-22'],
            ['nim' => '12220003', 'name' => 'Budi Santoso', 'study_program' => 'Rekayasa Perangkat Lunak', 'class_group' => '12.4C.01', 'birth_date' => '2003-11-09'],
            ['nim' => '12220004', 'name' => 'Siti Nurhaliza', 'study_program' => 'Sistem Informasi', 'class_group' => '12.4A.01', 'birth_date' => '2004-01-30'],
            ['nim' => '12220005', 'name' => 'Rian Hidayat', 'study_program' => 'Manajemen Informatika', 'class_group' => '12.4D.01', 'birth_date' => '2003-09-18'],
            ['nim' => '12220006', 'name' => 'Maya Anggraini', 'study_program' => 'Teknologi Informasi', 'class_group' => '12.4B.01', 'birth_date' => '2004-05-25'],
            ['nim' => '12220007', 'name' => 'Reza Pratama', 'study_program' => 'Rekayasa Perangkat Lunak', 'class_group' => '12.4C.01', 'birth_date' => '2004-12-04'],
            ['nim' => '12220008', 'name' => 'Putri Ayu', 'study_program' => 'Sistem Informasi', 'class_group' => '12.4A.01', 'birth_date' => '2004-08-14'],
            ['nim' => '12220009', 'name' => 'Ilham Maulana', 'study_program' => 'Manajemen Informatika', 'class_group' => '12.4D.01', 'birth_date' => '2003-06-11'],
            ['nim' => '12220010', 'name' => 'Dewi Lestari', 'study_program' => 'Sistem Informasi', 'class_group' => '12.4A.01', 'birth_date' => '2004-10-28'],
        ];

        foreach ($studentsData as $studentInfo) {
            $student = Student::updateOrCreate(
                ['nim' => $studentInfo['nim']],
                [
                    'name' => $studentInfo['name'],
                    'study_program' => $studentInfo['study_program'],
                    'class_group' => $studentInfo['class_group'],
                    'academic_year' => '2026/2027',
                    'password' => Hash::make($studentInfo['birth_date']), // Tanggal lahir unik per mahasiswa (format YYYY-MM-DD)
                    'is_active' => true,
                ]
            );

            // Record status partisipasi (student_nim)
            VoterParticipation::firstOrCreate(
                [
                    'election_period_id' => $period->id,
                    'student_nim' => $student->nim,
                ],
                [
                    'has_voted' => false,
                    'tps_location' => 'TPS 01 - Gedung Utama UBSI Karawang',
                ]
            );
        }

        // 5. Pengumuman Resmi KPUM
        Announcement::firstOrCreate(
            ['title' => 'Tata Tertib & Panduan Pemungutan Suara E-Voting PEMIRA BEM UBSI Karawang'],
            [
                'election_period_id' => $period->id,
                'created_by_admin_id' => $admin->id,
                'content' => "Selamat datang di pesta demokrasi PEMIRA BEM UBSI Karawang. Setiap mahasiswa aktif memiliki 1 (satu) hak suara yang dilindungi prinsip LUBER JURDIL. Pastikan memilih sesuai hati nurani. Suara yang telah dikirim bersifat final dan tidak dapat dibatalkan.",
                'published_at' => now(),
                'is_active' => true,
            ]
        );
    }
}
