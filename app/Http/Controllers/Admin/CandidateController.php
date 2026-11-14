<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Ballot;
use App\Models\CandidatePair;
use App\Models\ElectionPeriod;
use App\Services\ElectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function __construct(
        protected ElectionService $electionService
    ) {}

    public function index(Request $request): View
    {
        $period = null;
        if ($request->has('period_id')) {
            $period = ElectionPeriod::find($request->period_id);
        }
        $period = $period ?? $this->electionService->getActivePeriod() ?? $this->electionService->getLatestPeriod();

        $allPeriods = ElectionPeriod::orderByDesc('id')->get();
        $candidates = $period ? $period->candidatePairs : collect();

        return view('admin.candidates.index', compact('period', 'allPeriods', 'candidates'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $period = null;
        if ($request->has('period_id')) {
            $period = ElectionPeriod::find($request->period_id);
        }
        $period = $period ?? $this->electionService->getActivePeriod() ?? $this->electionService->getLatestPeriod();

        if (! $period) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Silakan buat atau aktifkan periode pemilihan terlebih dahulu.');
        }

        return view('admin.candidates.create', compact('period'));
    }

    public function store(Request $request): RedirectResponse
    {
        $periodId = $request->input('election_period_id');

        $validated = $request->validate([
            'election_period_id' => ['required', 'exists:election_periods,id'],
            'candidate_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('candidate_pairs', 'candidate_number')->where('election_period_id', $periodId),
            ],
            'chairman_name' => ['required', 'string', 'max:255'],
            'chairman_nim' => ['required', 'string', 'max:20'],
            'chairman_study_program' => ['required', 'string', 'max:100'],
            'vice_chairman_name' => ['required', 'string', 'max:255'],
            'vice_chairman_nim' => ['required', 'string', 'max:20'],
            'vice_chairman_study_program' => ['required', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'vision' => ['required', 'string'],
            'mission_raw' => ['required', 'string'],
            'work_programs_raw' => ['nullable', 'string'],
        ], [
            'candidate_number.unique' => 'Nomor urut paslon ini sudah digunakan pada periode pemilihan terkait.',
            'mission_raw.required' => 'Misi paslon wajib diisi (minimal satu butir misi).',
        ]);

        // Process Mission lines
        $mission = array_values(array_filter(array_map('trim', explode("\n", $validated['mission_raw']))));

        // Process Work Programs lines
        $workPrograms = ! empty($validated['work_programs_raw'])
            ? array_values(array_filter(array_map('trim', explode("\n", $validated['work_programs_raw']))))
            : [];

        // Handle photo upload
        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('candidates', 'public');
            $photoUrl = '/storage/' . $path;
        }

        $candidate = CandidatePair::create([
            'election_period_id' => $validated['election_period_id'],
            'candidate_number' => $validated['candidate_number'],
            'chairman_name' => $validated['chairman_name'],
            'chairman_nim' => $validated['chairman_nim'],
            'chairman_study_program' => $validated['chairman_study_program'],
            'vice_chairman_name' => $validated['vice_chairman_name'],
            'vice_chairman_nim' => $validated['vice_chairman_nim'],
            'vice_chairman_study_program' => $validated['vice_chairman_study_program'],
            'photo_url' => $photoUrl,
            'vision' => $validated['vision'],
            'mission' => $mission,
            'work_programs' => $workPrograms,
        ]);

        AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'create_candidate_pair',
            'entity_type' => CandidatePair::class,
            'entity_id' => $candidate->id,
            'new_values' => ['candidate_number' => $candidate->candidate_number, 'chairman' => $candidate->chairman_name],
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('admin.candidates.index', ['period_id' => $periodId])
            ->with('success', "Pasangan Calon No. 0{$candidate->candidate_number} ({$candidate->chairman_name} & {$candidate->vice_chairman_name}) berhasil ditambahkan!");
    }

    public function edit(CandidatePair $candidate): View
    {
        $candidate->load('electionPeriod');

        $missionRaw = is_array($candidate->mission) ? implode("\n", $candidate->mission) : '';
        $workProgramsRaw = is_array($candidate->work_programs) ? implode("\n", $candidate->work_programs) : '';

        return view('admin.candidates.edit', compact('candidate', 'missionRaw', 'workProgramsRaw'));
    }

    public function update(Request $request, CandidatePair $candidate): RedirectResponse
    {
        $validated = $request->validate([
            'candidate_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('candidate_pairs', 'candidate_number')
                    ->where('election_period_id', $candidate->election_period_id)
                    ->ignore($candidate->id),
            ],
            'chairman_name' => ['required', 'string', 'max:255'],
            'chairman_nim' => ['required', 'string', 'max:20'],
            'chairman_study_program' => ['required', 'string', 'max:100'],
            'vice_chairman_name' => ['required', 'string', 'max:255'],
            'vice_chairman_nim' => ['required', 'string', 'max:20'],
            'vice_chairman_study_program' => ['required', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'vision' => ['required', 'string'],
            'mission_raw' => ['required', 'string'],
            'work_programs_raw' => ['nullable', 'string'],
        ], [
            'candidate_number.unique' => 'Nomor urut paslon ini sudah digunakan pada periode pemilihan terkait.',
        ]);

        $mission = array_values(array_filter(array_map('trim', explode("\n", $validated['mission_raw']))));
        $workPrograms = ! empty($validated['work_programs_raw'])
            ? array_values(array_filter(array_map('trim', explode("\n", $validated['work_programs_raw']))))
            : [];

        $data = [
            'candidate_number' => $validated['candidate_number'],
            'chairman_name' => $validated['chairman_name'],
            'chairman_nim' => $validated['chairman_nim'],
            'chairman_study_program' => $validated['chairman_study_program'],
            'vice_chairman_name' => $validated['vice_chairman_name'],
            'vice_chairman_nim' => $validated['vice_chairman_nim'],
            'vice_chairman_study_program' => $validated['vice_chairman_study_program'],
            'vision' => $validated['vision'],
            'mission' => $mission,
            'work_programs' => $workPrograms,
        ];

        if ($request->hasFile('photo')) {
            if ($candidate->photo_url && str_starts_with($candidate->photo_url, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $candidate->photo_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('photo')->store('candidates', 'public');
            $data['photo_url'] = '/storage/' . $path;
        }

        $oldValues = $candidate->only(['candidate_number', 'chairman_name', 'vision']);
        $candidate->update($data);

        AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'update_candidate_pair',
            'entity_type' => CandidatePair::class,
            'entity_id' => $candidate->id,
            'old_values' => $oldValues,
            'new_values' => ['candidate_number' => $candidate->candidate_number, 'chairman' => $candidate->chairman_name],
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('admin.candidates.index', ['period_id' => $candidate->election_period_id])
            ->with('success', "Data, visi, dan misi Paslon No. 0{$candidate->candidate_number} berhasil diperbarui!");
    }

    public function destroy(CandidatePair $candidate): RedirectResponse
    {
        $hasVotes = Ballot::where('candidate_pair_id', $candidate->id)->exists();
        if ($hasVotes) {
            return back()->with('error', 'Paslon ini tidak dapat dihapus karena sudah memiliki suara tercatat di bilik suara.');
        }

        $periodId = $candidate->election_period_id;
        $num = $candidate->candidate_number;
        $name = $candidate->chairman_name;

        if ($candidate->photo_url && str_starts_with($candidate->photo_url, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $candidate->photo_url);
            Storage::disk('public')->delete($oldPath);
        }

        $candidate->delete();

        AuditLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'delete_candidate_pair',
            'entity_type' => CandidatePair::class,
            'entity_id' => $candidate->id,
            'old_values' => ['candidate_number' => $num, 'chairman_name' => $name],
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('admin.candidates.index', ['period_id' => $periodId])
            ->with('success', "Pasangan Calon No. 0{$num} berhasil dihapus.");
    }
}

