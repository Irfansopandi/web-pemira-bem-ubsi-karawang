@extends('layouts.app', ['title' => 'Kelola Paslon & Visi Misi — Admin PEMIRA UBSI'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Top Navigation / Header -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#940c10] text-[#CE9501] flex items-center justify-center font-bold shadow-xs">
                <i data-lucide="users" class="w-7 h-7"></i>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-[#940c10]">Manajemen Kontestan Pemilu</span>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Paslon, Visi & Misi</h1>
                <p class="text-xs text-gray-500 font-normal">Periode: {{ $period->name ?? 'Belum ada periode aktif' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Dashboard</span>
            </a>

            @if($period)
                <a href="{{ route('admin.candidates.create', ['period_id' => $period->id]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#940c10] text-white text-xs font-semibold hover:bg-[#7A0A0D] shadow-xs transition-all">
                    <i data-lucide="plus" class="w-4 h-4 text-[#CE9501]"></i>
                    <span>+ Daftarkan Paslon Baru</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Candidate Pairs Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @forelse($candidates as $candidate)
            <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between">
                <!-- Header -->
                <div class="p-6 sm:p-8 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <img src="{{ $candidate->photo }}" alt="Foto Paslon" class="w-14 h-18 object-cover rounded-xl border border-gray-200 shadow-xs bg-white shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-[#940c10] text-[#CE9501] flex flex-col items-center justify-center font-bold shadow-xs shrink-0">
                            <span class="text-[9px] uppercase font-bold text-white">No</span>
                            <span class="text-xl leading-none">0{{ $candidate->candidate_number }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#940c10]">Pasangan Calon No. 0{{ $candidate->candidate_number }}</span>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900 mt-0.5">{{ $candidate->chairman_name }} & {{ $candidate->vice_chairman_name }}</h2>
                        </div>
                    </div>

                    <!-- Action Edit & Delete Buttons -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.candidates.edit', $candidate) }}"
                            class="p-2.5 rounded-xl bg-gray-100 text-gray-700 hover:bg-[#940c10] hover:text-white transition-colors" title="Edit Paslon & Visi Misi">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </a>

                        <form action="{{ route('admin.candidates.destroy', $candidate) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus Paslon No. 0{{ $candidate->candidate_number }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 rounded-xl bg-red-50 text-[#940c10] hover:bg-red-600 hover:text-white transition-colors" title="Hapus Paslon">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Pair Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#940c10]">Calon Ketua</span>
                            <p class="text-sm font-bold text-gray-900">{{ $candidate->chairman_name }}</p>
                            <p class="text-xs text-gray-600 font-mono">NIM: {{ $candidate->chairman_nim }}</p>
                            <p class="text-[11px] text-gray-500">{{ $candidate->chairman_study_program }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#CE9501]">Calon Wakil Ketua</span>
                            <p class="text-sm font-bold text-gray-900">{{ $candidate->vice_chairman_name }}</p>
                            <p class="text-xs text-gray-600 font-mono">NIM: {{ $candidate->vice_chairman_nim }}</p>
                            <p class="text-[11px] text-gray-500">{{ $candidate->vice_chairman_study_program }}</p>
                        </div>
                    </div>

                    <!-- Visi -->
                    <div class="space-y-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Visi:</span>
                        <p class="text-xs italic text-gray-800 bg-red-50/40 p-4 rounded-xl border border-red-100 leading-relaxed">
                            "{{ $candidate->vision }}"
                        </p>
                    </div>

                    <!-- Misi -->
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Misi ({{ count($candidate->mission ?? []) }} Butir):</span>
                        <ul class="space-y-1.5 text-xs text-gray-700">
                            @foreach($candidate->mission ?? [] as $misi)
                                <li class="flex items-start gap-2">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                                    <span>{{ $misi }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Proker -->
                    @if(!empty($candidate->work_programs))
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Program Kerja Unggulan:</span>
                        <ul class="space-y-1.5 text-xs text-gray-700">
                            @foreach($candidate->work_programs as $proker)
                                <li class="flex items-start gap-2">
                                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-[#CE9501] shrink-0 mt-0.5"></i>
                                    <span class="font-medium">{{ $proker }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span>Terdaftar di Kotak Suara Sah</span>
                    <a href="{{ route('admin.candidates.edit', $candidate) }}" class="font-bold text-[#940c10] hover:underline inline-flex items-center gap-1">
                        <span>Sunting Detail</span>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="lg:col-span-2 text-center py-16 bg-white rounded-3xl border border-gray-200 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
                    <i data-lucide="user-x" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Belum Ada Pasangan Calon</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">Silakan daftarkan pasangan calon Ketua & Wakil Ketua BEM untuk periode ini.</p>
                <div class="pt-2">
                    <a href="{{ route('admin.candidates.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#940c10] text-white text-xs font-bold hover:bg-[#7A0A0D]">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Daftarkan Paslon Pertama</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection

