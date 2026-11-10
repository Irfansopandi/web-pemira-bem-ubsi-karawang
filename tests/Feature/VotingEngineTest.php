<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Ballot;
use App\Models\CandidatePair;
use App\Models\ElectionPeriod;
use App\Models\Student;
use App\Models\VoterParticipation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class VotingEngineTest extends TestCase
{
    use RefreshDatabase;

    protected ElectionPeriod $period;
    protected CandidatePair $candidate1;
    protected CandidatePair $candidate2;
    protected Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->period = ElectionPeriod::create([
            'academic_year' => '2026/2027',
            'name' => 'PEMIRA BEM UBSI Karawang 2026/2027',
            'start_time' => now()->subDay(),
            'end_time' => now()->addDays(7),
            'status' => 'active',
            'is_result_published' => false,
        ]);

        $this->candidate1 = CandidatePair::create([
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
            'work_programs' => ['Proker 1'],
        ]);

        $this->candidate2 = CandidatePair::create([
            'election_period_id' => $this->period->id,
            'candidate_number' => 2,
            'chairman_name' => 'Muhammad Rizky Pratama',
            'chairman_nim' => '12220202',
            'chairman_study_program' => 'Rekayasa Perangkat Lunak',
            'vice_chairman_name' => 'Nabila Putri Maharani',
            'vice_chairman_nim' => '12220288',
            'vice_chairman_study_program' => 'Manajemen Informatika',
            'vision' => 'Visi Paslon 02',
            'mission' => ['Misi A', 'Misi B'],
            'work_programs' => ['Proker A'],
        ]);

        $this->student = Student::create([
            'nim' => '12220001',
            'name' => 'Ahmad Fauzi',
            'study_program' => 'Sistem Informasi',
            'class_group' => '12.4A.01',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        VoterParticipation::create([
            'election_period_id' => $this->period->id,
            'student_nim' => $this->student->nim,
            'has_voted' => false,
        ]);
    }

    public function test_root_redirects_to_login_zero_guest_policy(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('PEMILIHAN UMUM RAYA');
    }

    public function test_student_can_login_with_valid_nim(): void
    {
        $response = $this->post(route('login.submit'), [
            'identifier' => '12220001',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('student.voting'));
        $this->assertAuthenticatedAs($this->student, 'student');
    }

    public function test_student_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post(route('login.submit'), [
            'identifier' => '12220001',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('identifier');
        $this->assertGuest('student');
    }

    public function test_student_can_cast_anonymous_vote_successfully(): void
    {
        $this->actingAs($this->student, 'student');

        $response = $this->post(route('student.voting.store'), [
            'candidate_pair_id' => $this->candidate1->id,
        ]);

        $response->assertRedirect(route('student.completed'));

        // Check participation is marked has_voted = true
        $participation = VoterParticipation::where('student_nim', $this->student->nim)->first();
        $this->assertTrue($participation->has_voted);
        $this->assertNotNull($participation->voted_at);
        $this->assertNotNull($participation->encrypted_vote_receipt);

        // Check ballot is recorded in anonymous ballots table
        $this->assertDatabaseHas('ballots', [
            'election_period_id' => $this->period->id,
            'candidate_pair_id' => $this->candidate1->id,
        ]);
    }

    public function test_ballots_table_does_not_contain_student_nim_or_user_id(): void
    {
        // STRICT PRIVACY RULE: NO student relation column in ballots
        $this->assertFalse(
            Schema::hasColumn('ballots', 'student_nim'),
            'SECURITY VIOLATION: ballots table must never have student_nim column!'
        );

        $this->assertFalse(
            Schema::hasColumn('ballots', 'student_id'),
            'SECURITY VIOLATION: ballots table must never have student_id column!'
        );

        $this->assertFalse(
            Schema::hasColumn('ballots', 'user_id'),
            'SECURITY VIOLATION: ballots table must never have user_id column!'
        );
    }

    public function test_student_cannot_vote_twice_double_voting_prevention(): void
    {
        $this->actingAs($this->student, 'student');

        // First vote succeeds
        $this->post(route('student.voting.store'), [
            'candidate_pair_id' => $this->candidate1->id,
        ]);

        $this->assertEquals(1, Ballot::where('election_period_id', $this->period->id)->count());

        // Second vote attempt is rejected
        $response = $this->post(route('student.voting.store'), [
            'candidate_pair_id' => $this->candidate2->id,
        ]);

        $response->assertRedirect(route('student.voting'));
        $response->assertSessionHas('error');

        // Total ballots remain 1
        $this->assertEquals(1, Ballot::where('election_period_id', $this->period->id)->count());
    }

    public function test_post_voting_displays_receipt(): void
    {
        $this->actingAs($this->student, 'student');

        // Cast vote
        $this->post(route('student.voting.store'), [
            'candidate_pair_id' => $this->candidate1->id,
        ]);

        // Access receipt page
        $response = $this->get(route('student.receipt'));

        $response->assertStatus(200);
        $response->assertSee('Tanda Terima Pemilih');
        $response->assertSee('Fajar Nugraha');
    }

    public function test_admin_can_access_dashboard_and_toggle_results_publication(): void
    {
        $admin = Admin::create([
            'name' => 'Admin Test',
            'username' => 'admin_test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Panel Administrator');

        // Toggle publish
        $this->post(route('admin.periods.toggle-publish', $this->period));

        $this->period->refresh();
        $this->assertTrue($this->period->is_result_published);
    }
}
