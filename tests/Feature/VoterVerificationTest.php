<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CandidatePair;
use App\Models\ElectionPeriod;
use App\Models\Student;
use App\Models\VoterParticipation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VoterVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected ElectionPeriod $period;
    protected Student $student;
    protected CandidatePair $candidate;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = Admin::create([
            'username' => 'panitia_tps',
            'name' => 'Panitia TPS Cikampek',
            'email' => 'panitia@bsi.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'tps_location' => 'TPS Kampus UBSI Cikampek',
            'is_active' => true,
        ]);

        $this->period = ElectionPeriod::create([
            'academic_year' => '2026/2027',
            'name' => 'PEMIRA BEM UBSI Karawang 2026/2027',
            'start_time' => now()->subDay(),
            'end_time' => now()->addDays(2),
            'status' => 'active',
            'is_active' => true,
            'voting_status' => 'opened',
        ]);

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
            'mission' => ['Misi 1'],
            'work_programs' => ['Proker 1'],
        ]);

        $this->student = Student::create([
            'nim' => '12220001',
            'name' => 'Budi Santoso',
            'study_program' => 'Sistem Informasi',
            'class_name' => '12.4A.01',
            'semester' => 4,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_access_voter_verification_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.verification.index'));

        $response->assertStatus(200);
        $response->assertSee('Buku Tamu Digital');
        $response->assertSee('UNIVERSITAS BINA SARANA INFORMATIKA');
    }

    public function test_guest_cannot_access_voter_verification_page(): void
    {
        $response = $this->get(route('admin.verification.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_lookup_student_with_unregistered_nim_returns_404(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.verification.lookup'), [
                'nim' => '99999999',
            ]);

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 'not_found',
        ]);
    }

    public function test_lookup_valid_student_returns_ready_status(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.verification.lookup'), [
                'nim' => '12220001',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'ready',
            'student' => [
                'nim' => '12220001',
                'name' => 'Budi Santoso',
                'study_program' => 'Sistem Informasi',
            ],
        ]);
    }

    public function test_admin_can_verify_student_presence_with_digital_signature(): void
    {
        // 1x1 transparent png base64
        $fakeSignature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.verification.store'), [
                'nim' => '12220001',
                'signature' => $fakeSignature,
                'tps_location' => 'TPS Kampus UBSI Cikampek',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('voter_participations', [
            'election_period_id' => $this->period->id,
            'student_nim' => '12220001',
            'has_voted' => false,
            'tps_location' => 'TPS Kampus UBSI Cikampek',
            'verified_by_admin_id' => $this->admin->id,
        ]);

        $participation = VoterParticipation::where('student_nim', '12220001')->first();
        $this->assertNotNull($participation->verified_at);
        $this->assertNotNull($participation->signature_path);
        Storage::disk('public')->assertExists($participation->signature_path);

        // Mahasiswa otomatis diaktifkan
        $this->student->refresh();
        $this->assertTrue($this->student->is_active);
    }

    public function test_student_cannot_login_to_booth_before_verification(): void
    {
        $response = $this->post(route('login.submit'), [
            'identifier' => '12220001',
        ]);

        $response->assertSessionHasErrors('identifier');
        $this->assertGuest('student');
    }

    public function test_student_can_login_to_booth_after_verification(): void
    {
        // Login ke bilik suara
        $fakeSignature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
        $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.verification.store'), [
                'nim' => '12220001',
                'signature' => $fakeSignature,
                'tps_location' => 'Kampus Karawang',
            ]);

        // Login ke bilik suara
        $response = $this->post(route('login.submit'), [
            'identifier' => '12220001',
        ]);

        $response->assertRedirect(route('student.voting'));
        $this->assertAuthenticatedAs($this->student, 'student');
    }

    public function test_lookup_student_who_already_verified_returns_already_verified(): void
    {
        $fakeSignature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
        $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.verification.store'), [
                'nim' => '12220001',
                'signature' => $fakeSignature,
                'tps_location' => 'Kampus Karawang',
            ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.verification.lookup'), [
                'nim' => '12220001',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'already_verified',
        ]);
    }

    public function test_admin_can_cancel_verification_if_not_voted_yet(): void
    {
        $fakeSignature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
        $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.verification.store'), [
                'nim' => '12220001',
                'signature' => $fakeSignature,
                'tps_location' => 'Kampus Karawang',
            ]);

        $participation = VoterParticipation::where('student_nim', '12220001')->first();

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.verification.cancel', $participation));

        $response->assertSessionHas('success');
        $participation->refresh();
        $this->assertNull($participation->verified_at);
        $this->assertNull($participation->signature_path);
    }
}
