@extends('layouts.app', ['title' => 'Bilik Suara Elektronik — PEMIRA BEM UBSI Karawang'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{
    modalOpen: false,
    selectedCandidateId: null,
    selectedCandidateNumber: null,
    selectedCandidateNames: '',
    confirmVote(id, number, names) {
        this.selectedCandidateId = id;
        this.selectedCandidateNumber = number;
        this.selectedCandidateNames = names;
        this.modalOpen = true;
    }
}">

    <!-- Top Bilik Suara Header -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-xs mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#940c10] text-[#CE9501] flex items-center justify-center font-bold shadow-xs">
                <i data-lucide="vote" class="w-7 h-7"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#CE9501]">Surat Suara Elektronik Sah</span>
                <h1 class="text-2xl font-black text-gray-900">Bilik Suara PEMIRA BEM UBSI</h1>
                <p class="text-xs text-gray-500">Tahun Akademik {{ $activePeriod->academic_year }} &bull; Kampus UBSI Karawang</p>
            </div>
        </div>

        <!-- Student Identity Badge -->
        <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-200/80 w-full md:w-auto">
            <div class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-700 font-bold text-sm">
                {{ substr($student->name, 0, 1) }}
            </div>
            <div class="space-y-0.5">
                <p class="text-xs font-bold text-gray-900">{{ $student->name }}</p>
                <p class="text-[11px] font-mono text-gray-600">NIM: {{ $student->nim }} &bull; {{ $student->study_program }}</p>
                <div class="flex items-center gap-1.5 text-[10px] text-emerald-700 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Hak Suara Terverifikasi Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Banner -->
    <div class="mb-8 p-4 rounded-2xl bg-red-50/60 border border-red-200/70 flex items-center gap-3 text-xs text-[#940c10]">
        <i data-lucide="shield-check" class="w-5 h-5 shrink-0 text-[#940c10]"></i>
        <p>
            <strong>Jaminan Kerahasiaan Suara:</strong> Surat suara Anda diproses menggunakan enkripsi anonim (Anonymous Ballot). Pilihan Anda tidak dapat dilacak kembali oleh siapa pun, termasuk panitia dan administrator.
        </p>
    </div>

    <!-- Candidate Ballot Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @foreach($candidates as $candidate)
            <div class="bg-white rounded-3xl border-2 border-gray-200 hover:border-[#940c10] overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                <!-- Card Header -->
                <div class="p-6 sm:p-8 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-[#940c10] text-[#CE9501] flex flex-col items-center justify-center font-black shadow-xs ring-4 ring-[#CE9501]/30">
                            <span class="text-[10px] uppercase font-bold text-white">Nomor</span>
                            <span class="text-3xl leading-none">0{{ $candidate->candidate_number }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-[#940c10]">Surat Suara Paslon</span>
                            <h2 class="text-xl font-extrabold text-gray-900">{{ $candidate->chairman_name }} & {{ $candidate->vice_chairman_name }}</h2>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Pair Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#940c10]">Calon Ketua</span>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $candidate->chairman_name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $candidate->chairman_nim }}</p>
                            <p class="text-xs text-gray-600">{{ $candidate->chairman_study_program }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#CE9501]">Calon Wakil Ketua</span>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $candidate->vice_chairman_name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $candidate->vice_chairman_nim }}</p>
                            <p class="text-xs text-gray-600">{{ $candidate->vice_chairman_study_program }}</p>
                        </div>
                    </div>

                    <!-- Visi -->
                    <div class="space-y-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Visi Utama:</span>
                        <p class="text-sm italic text-gray-700 bg-gray-50 p-4 rounded-2xl border border-gray-100 leading-relaxed">
                            "{{ $candidate->vision }}"
                        </p>
                    </div>

                    <!-- Misi Highlight -->
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Misi Pokok:</span>
                        <ul class="space-y-2 text-xs text-gray-600">
                            @foreach(array_slice($candidate->mission ?? [], 0, 3) as $m)
                                <li class="flex items-start gap-2">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
                                    <span>{{ $m }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Card Bottom Coblos Button -->
                <div class="p-6 bg-gradient-to-t from-gray-50 to-white border-t border-gray-100">
                    <button type="button"
                        @click="confirmVote({{ $candidate->id }}, '0{{ $candidate->candidate_number }}', '{{ addslashes($candidate->chairman_name . ' & ' . $candidate->vice_chairman_name) }}')"
                        class="w-full flex items-center justify-center gap-3 py-4 px-6 rounded-2xl text-white bg-[#940c10] hover:bg-[#7A0A0D] text-base font-extrabold shadow-md hover:shadow-lg transition-all transform group-hover:scale-[1.01] ring-2 ring-[#CE9501]/40">
                        <i data-lucide="check-circle" class="w-6 h-6 text-[#CE9501]"></i>
                        <span>COBLOS PASLON 0{{ $candidate->candidate_number }}</span>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- MODAL KONFIRMASI COBLOS (POPUP) -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="modalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-6 sm:p-8 space-y-6 border border-gray-200">
                
                <!-- Icon Header -->
                <div class="text-center space-y-3">
                    <div class="w-16 h-16 rounded-2xl bg-red-100 text-[#940c10] flex items-center justify-center mx-auto ring-4 ring-red-50">
                        <i data-lucide="help-circle" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-[#CE9501]">Konfirmasi Hak Suara</span>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">Yakin dengan Pilihan Anda?</h3>
                    </div>
                </div>

                <!-- Choice Summary -->
                <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 text-center space-y-2">
                    <p class="text-xs text-gray-500 font-medium">Anda akan memberikan suara untuk:</p>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#940c10] text-[#CE9501] text-xs font-bold">
                        <span>Pasangan Calon No. <span x-text="selectedCandidateNumber"></span></span>
                    </div>
                    <p class="text-lg font-black text-gray-900" x-text="selectedCandidateNames"></p>
                </div>

                <div class="text-xs text-gray-500 text-center leading-relaxed">
                    Pilihan yang telah dikirimkan bersifat <strong>final, permanen, dan tidak dapat dibatalkan</strong>. Suara akan segera dimasukkan ke kotak suara digital tanpa mencatat identitas Anda.
                </div>

                <!-- Form Action Buttons -->
                <form action="{{ route('student.voting.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="candidate_pair_id" :value="selectedCandidateId">

                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-4 px-4 rounded-xl text-white bg-[#059669] hover:bg-emerald-700 text-sm font-extrabold shadow-sm transition-all">
                        <i data-lucide="check" class="w-5 h-5"></i>
                        <span>Ya, Kirim Suara Saya Sekarang</span>
                    </button>

                    <button type="button" @click="modalOpen = false"
                        class="w-full py-3 px-4 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200 text-sm font-bold transition-colors">
                        Batal / Periksa Kembali
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

