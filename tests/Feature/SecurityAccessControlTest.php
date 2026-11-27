<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CandidatePair;
use App\Models\ElectionPeriod;
use App\Models\Student;
use App\Models\VoterParticipation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected ElectionPeriod $period;
    protected CandidatePair $candidate;
    protected Student $student;
    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Periode Pemilihan Aktif
        $this->period = ElectionPeriod::create([
            'academic_year' => '2026/2027',
            'name' => 'PEMIRA BEM UBSI Karawang 2026/2027',
            'start_time' => now()->subDay(),
            'end_time' => now()->addDays(7),
            'status' => 'active',
            'is_active' => true,
            'voting_status' => 'opened',
        ]);

        // 2. Setup Kandidat Paslon
        $this->candidate = CandidatePair::create([
            'election_period_id' => $this->period->id,
            'candidate_number' => 1,
            'chairman_name' => 'Fajar Nugraha',
            'chairman_nim' => '12220101',
            'chairman_study_program' => 'Sistem Informasi',
            'vice_chairman_name' => 'Siti Aisyah Rahmawati',
            'vice_chairman_nim' => '12220145',
            'vice_chairman_study_program' => 'Teknologi Informasi',
            'vision' => 'Visi Paslon 01',
            'mission' => ['Misi 1', 'Misi 2'],
            'work_programs' => ['Program 1'],
        ]);

        // 3. Setup Mahasiswa DPT
        $this->student = Student::create([
            'nim' => '12229999',
            'name' => 'Muhammad Arya',
            'study_program' => 'Sistem Informasi',
            'class_name' => '12.4A.01',
            'semester' => 4,
            'is_active' => true,
        ]);

        // 4. Setup Akun Administrator Resmi KPUM
        $this->admin = Admin::create([
            'name' => 'Admin KPUM UBSI',
            'username' => 'admin_kpum',
            'email' => 'kpum@bsi.ac.id',
            'password' => bcrypt('AdminPassword123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    /**
     * Skenario 1: Mahasiswa login menggunakan NIM saja (tanpa password) setelah hadir di buku tamu.
     */
    public function test_01_student_can_login_using_nim_only_after_filling_guestbook(): void
    {
        // Catat kehadiran di buku tamu
        VoterParticipation::create([
            'election_period_id' => $this->period->id,
            'student_nim' => $this->student->nim,
            'verified_at' => now(),
            'tps_location' => 'Kampus Karawang',
            'has_voted' => false,
        ]);

        $response = $this->withSession(['login_captcha_verified' => true])
            ->post(route('login.submit'), [
                'identifier' => '12229999',
                // Tanpa password sama sekali
            ]);

        $response->assertRedirect(route('student.voting'));
        $this->assertAuthenticatedAs($this->student, 'student');
        $this->assertGuest('admin');
    }

    /**
     * Skenario 2: Mahasiswa belum mengisi buku tamu ditolak masuk voting.
     */
    public function test_02_student_who_has_not_filled_guestbook_cannot_enter_booth(): void
    {
        $unregisteredStudent = Student::create([
            'nim' => '12228888',
            'name' => 'Mahasiswa Belum Hadir',
            'study_program' => 'Sistem Informasi',
            'class_name' => '12.4A.01',
            'semester' => 4,
            'is_active' => true,
        ]);

        $response = $this->withSession(['login_captcha_verified' => true])
            ->post(route('login.submit'), [
                'identifier' => '12228888',
            ]);

        $response->assertSessionHasErrors('identifier');
        $this->assertGuest('student');
        $this->assertGuest('admin');
    }

    /**
     * Skenario 3: Mahasiswa yang sudah mengisi buku tamu dapat mengakses halaman bilik suara voting.
     */
    public function test_03_student_who_has_filled_guestbook_can_access_voting_booth(): void
    {
        VoterParticipation::create([
            'election_period_id' => $this->period->id,
            'student_nim' => $this->student->nim,
            'verified_at' => now(),
            'tps_location' => 'Kampus Karawang',
            'has_voted' => false,
        ]);

        $response = $this->actingAs($this->student, 'student')
            ->get(route('student.voting'));

        $response->assertStatus(200);
        $response->assertSee('PEMIRA BEM UBSI');
    }

    /**
     * Skenario 4: Mahasiswa membuka /admin ditolak dan diarahkan ke login mahasiswa.
     */
    public function test_04_student_accessing_admin_root_is_rejected_and_redirected_to_student_login(): void
    {
        $response = $this->actingAs($this->student, 'student')
            ->get('/admin');

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('identifier');
    }

    /**
     * Skenario 5: Mahasiswa membuka /admin/dashboard ditolak dan diarahkan ke login mahasiswa.
     */
    public function test_05_student_accessing_admin_dashboard_is_rejected_and_redirected_to_student_login(): void
    {
        $response = $this->actingAs($this->student, 'student')
            ->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('identifier');
    }

    /**
     * Skenario 6: Mahasiswa membuka halaman kelola mahasiswa (/admin/voters) ditolak.
     */
    public function test_06_student_accessing_admin_voters_management_is_rejected(): void
    {
        // Akses via route /admin/voters
        $response = $this->actingAs($this->student, 'student')
            ->get(route('admin.voters.index'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('identifier');

        // Akses via alias /admin/mahasiswa
        $responseAlias = $this->actingAs($this->student, 'student')
            ->get('/admin/mahasiswa');

        $responseAlias->assertRedirect(route('login'));
        $responseAlias->assertSessionHasErrors('identifier');
    }

    /**
     * Skenario 7: Mahasiswa membuka halaman kelola paslon (/admin/candidates & /admin/paslon) ditolak.
     */
    public function test_07_student_accessing_admin_candidates_management_is_rejected(): void
    {
        // Akses via route /admin/candidates
        $response = $this->actingAs($this->student, 'student')
            ->get(route('admin.candidates.index'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('identifier');

        // Akses via alias /admin/paslon
        $responseAlias = $this->actingAs($this->student, 'student')
            ->get('/admin/paslon');

        $responseAlias->assertRedirect(route('login'));
        $responseAlias->assertSessionHasErrors('identifier');
    }

    /**
     * Skenario 8: Mahasiswa membuka halaman kelola periode (/admin/periods & /admin/periode) ditolak.
     */
    public function test_08_student_accessing_admin_periods_management_is_rejected(): void
    {
        // Akses via route /admin/periods
        $response = $this->actingAs($this->student, 'student')
            ->get(route('admin.periods.index'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('identifier');

        // Akses via alias /admin/periode
        $responseAlias = $this->actingAs($this->student, 'student')
            ->get('/admin/periode');

        $responseAlias->assertRedirect(route('login'));
        $responseAlias->assertSessionHasErrors('identifier');

        // Akses via URL admin sembarang / tidak ada (misal /admin/halaman-rahasia) tidak menghasilkan 404, melainkan redirect ke login
        $responseNonExistent = $this->actingAs($this->student, 'student')
            ->get('/admin/halaman-rahasia');

        $responseNonExistent->assertRedirect(route('login'));
        $responseNonExistent->assertSessionHasErrors('identifier');
    }

    /**
     * Skenario 9: Mahasiswa mengakses endpoint admin secara langsung (POST/PUT/DELETE/AJAX) ditolak tanpa kebocoran data.
     */
    public function test_09_student_sending_direct_request_to_admin_endpoints_is_rejected_without_data_leak(): void
    {
        // 1. AJAX/JSON GET Data Voters
        $responseGet = $this->actingAs($this->student, 'student')
            ->getJson(route('admin.voters.index'));

        $responseGet->assertStatus(403);
        $responseGet->assertJson([
            'message' => 'Akses ditolak. Mahasiswa dilarang mengakses area administrator.',
        ]);

        // 2. POST Buat Periode Baru
        $responsePost = $this->actingAs($this->student, 'student')
            ->postJson(route('admin.periods.store'), [
                'name' => 'Ilegal Period',
                'academic_year' => '2099/2100',
            ]);

        $responsePost->assertStatus(403);
        $responsePost->assertJson([
            'message' => 'Akses ditolak. Mahasiswa dilarang mengakses area administrator.',
        ]);

        // 3. DELETE Hapus Paslon
        $responseDelete = $this->actingAs($this->student, 'student')
            ->deleteJson(route('admin.candidates.destroy', $this->candidate));

        $responseDelete->assertStatus(403);
        $responseDelete->assertJson([
            'message' => 'Akses ditolak. Mahasiswa dilarang mengakses area administrator.',
        ]);

        // Pastikan tidak ada data periode baru atau kandidat yang terhapus
        $this->assertDatabaseMissing('election_periods', ['name' => 'Ilegal Period']);
        $this->assertDatabaseHas('candidate_pairs', ['id' => $this->candidate->id]);
    }

    /**
     * Skenario 10: Admin belum login membuka dashboard admin diarahkan ke login admin.
     */
    public function test_10_unauthenticated_user_accessing_admin_dashboard_is_redirected_to_admin_login(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Skenario 11: Mahasiswa logout kemudian menekan tombol Back tidak bisa mengakses bilik suara (Anti-BFCache).
     */
    public function test_11_logged_out_student_cannot_access_protected_booth_and_receives_anti_bfcache_headers(): void
    {
        // Catat kehadiran di buku tamu agar mahasiswa diizinkan masuk bilik suara
        VoterParticipation::create([
            'election_period_id' => $this->period->id,
            'student_nim' => $this->student->nim,
            'verified_at' => now(),
            'tps_location' => 'Kampus Karawang',
            'has_voted' => false,
        ]);

        // 1. Buka voting sebagai mahasiswa terotentikasi dan verifikasi header anti-cache
        $responseAuth = $this->actingAs($this->student, 'student')
            ->get(route('student.voting'));

        $responseAuth->assertStatus(200);
        $this->assertStringContainsString('no-store', (string) $responseAuth->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-cache', (string) $responseAuth->headers->get('Cache-Control'));
        $this->assertStringContainsString('must-revalidate', (string) $responseAuth->headers->get('Cache-Control'));
        $this->assertEquals('no-cache', $responseAuth->headers->get('Pragma'));

        // 2. Mahasiswa menekan logout
        $responseLogout = $this->actingAs($this->student, 'student')
            ->post(route('logout'));

        $responseLogout->assertRedirect('/login');
        $this->assertGuest('student');

        // 3. Simulasi penekanan tombol Back (request baru ke /voting setelah sesi diakhiri)
        $responseBack = $this->get(route('student.voting'));
        $responseBack->assertRedirect(route('login'));
    }

    /**
     * Skenario 12: Session mahasiswa kedaluwarsa diarahkan kembali ke login mahasiswa.
     */
    public function test_12_expired_student_session_returns_to_student_login(): void
    {
        // Session kosong / kedaluwarsa mengakses route bilik suara
        $response = $this->get(route('student.voting'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Skenario 13: Mahasiswa mengakses file internal admin (/admin/voters/template) ditolak.
     */
    public function test_13_student_accessing_admin_internal_files_is_rejected(): void
    {
        $response = $this->actingAs($this->student, 'student')
            ->get(route('admin.voters.template'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('identifier');
    }

    /**
     * Skenario 14: Admin login dan membuka dashboard admin berhasil (HTTP 200).
     */
    public function test_14_authenticated_admin_can_access_admin_dashboard_successfully(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-cache', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('must-revalidate', (string) $response->headers->get('Cache-Control'));
    }
}
