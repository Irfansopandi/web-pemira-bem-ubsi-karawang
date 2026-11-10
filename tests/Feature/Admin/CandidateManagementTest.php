<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\CandidatePair;
use App\Models\ElectionPeriod;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected ElectionPeriod $period;
    protected CandidatePair $candidate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Administrator KPUM',
            'username' => 'admin_test',
            'email' => 'admin_test@ubsi.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->period = ElectionPeriod::create([
            'academic_year' => '2026/2027',
            'name' => 'PEMIRA BEM UBSI 2026/2027',
            'start_time' => now()->subDay(),
            'end_time' => now()->addDays(7),
            'status' => 'active',
            'is_result_published' => false,
        ]);

        $this->candidate = CandidatePair::create([
            'election_period_id' => $this->period->id,
            'candidate_number' => 1,
            'chairman_name' => 'Fajar Pratama',
            'chairman_nim' => '12220101',
            'chairman_study_program' => 'Sistem Informasi',
            'vice_chairman_name' => 'Dewi Lestari',
            'vice_chairman_nim' => '12220102',
            'vice_chairman_study_program' => 'Teknologi Informasi',
            'vision' => 'Visi awal calon nomor satu',
            'mission' => ['Misi satu', 'Misi dua'],
            'work_programs' => ['Proker unggulan satu'],
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.candidates.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_student_cannot_access_candidate_management(): void
    {
        $student = Student::create([
            'nim' => '12229999',
            'name' => 'Budi Santoso',
            'email' => '12229999@bsi.ac.id',
            'study_program' => 'Sistem Informasi',
            'class_group' => '12.4A.01',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $response = $this->actingAs($student, 'student')
            ->get(route('admin.candidates.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_candidate_list(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.candidates.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Paslon');
        $response->assertSee('Visi awal calon nomor satu');
    }

    public function test_admin_can_view_edit_candidate_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.candidates.edit', $this->candidate));

        $response->assertStatus(200);
        $response->assertSee('Edit Pasangan Calon No. 01');
        $response->assertSee('Visi awal calon nomor satu');
    }

    public function test_admin_can_update_candidate_vision_and_mission(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.candidates.update', $this->candidate), [
                'candidate_number' => 1,
                'chairman_name' => $this->candidate->chairman_name,
                'chairman_nim' => $this->candidate->chairman_nim,
                'chairman_study_program' => $this->candidate->chairman_study_program,
                'vice_chairman_name' => $this->candidate->vice_chairman_name,
                'vice_chairman_nim' => $this->candidate->vice_chairman_nim,
                'vice_chairman_study_program' => $this->candidate->vice_chairman_study_program,
                'vision' => 'Visi baru yang lebih progresif dan berdaya saing',
                'mission_raw' => "Mewujudkan transparansi organisasi\nMendorong digitalisasi kampus UBSI\nMeningkatkan prestasi mahasiswa",
                'work_programs_raw' => "UBSI Hackathon\nPojok Aspirasi Mahasiswa",
            ]);

        $response->assertRedirect(route('admin.candidates.index', ['period_id' => $this->period->id]));

        $this->candidate->refresh();
        $this->assertEquals('Visi baru yang lebih progresif dan berdaya saing', $this->candidate->vision);
        $this->assertCount(3, $this->candidate->mission);
        $this->assertEquals('Mewujudkan transparansi organisasi', $this->candidate->mission[0]);
    }
}
