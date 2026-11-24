@extends('layouts.admin', ['title' => 'Kelola Paslon'])

@section('content')
<div class="space-y-6 sm:space-y-8" x-data="candidateManagement()">

    <!-- Top Navigation / Header -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-start sm:items-center gap-3.5 sm:gap-4">
            <!-- Icon Tanpa Border Sesuai Standar Minimalis UBSI -->
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-[#940C10] shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Manajemen Pemilihan</span>
                    @if($period)
                        <span class="text-slate-300">•</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $period->is_active ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $period->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                            Periode: {{ $period->name }}
                        </span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">
                    Kelola Pasangan Calon (Paslon)
                </h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    Daftar Paslon Ketua & Calon Wakil Ketua BEM UBSI Kab.Karawang, Profil, Visi, Misi & Program Kerja
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            @if($period)
                <a href="{{ route('admin.candidates.create', ['period_id' => $period->id]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#940C10] hover:bg-[#7A0A0D] text-white text-xs font-bold shadow-xs hover:shadow-md transition-all">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Daftarkan Paslon Baru</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Candidate Pairs Grid (Card Foto Sesuai Halaman Voting Mahasiswa) -->
    <div id="visi-misi" class="grid grid-cols-1 md:grid-cols-2 {{ $candidates->count() > 2 ? 'xl:grid-cols-3' : '' }} gap-6 sm:gap-7 lg:gap-8">
        @forelse($candidates as $candidate)
            <div class="group relative block aspect-[3/4] sm:aspect-[4/5] rounded-3xl overflow-hidden shadow-xl shadow-black/40 transition-all duration-300 border border-slate-200/90 hover:border-[#CE9501]/80 bg-gradient-to-b from-[#52000D] via-[#7A0A0D] to-[#940C10] transform hover:-translate-y-1.5">
                
                <!-- Badge Nomor Paslon Ramping Horizontal di Pojok Kiri Atas -->
                <div class="absolute top-3.5 left-3.5 z-20">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-3.5 sm:py-1 rounded-xl bg-[#940C10]/95 border border-[#CE9501] shadow-lg shadow-black/50 backdrop-blur-xs">
                        <span class="text-[11px] sm:text-xs font-bold text-white tracking-wide">Paslon No.</span>
                        <span class="text-xs sm:text-sm font-black text-[#CE9501]">0{{ $candidate->candidate_number }}</span>
                    </div>
                </div>

                <!-- Action Edit & Delete Buttons di Pojok Kanan Atas -->
                <div class="absolute top-3.5 right-3.5 z-20 flex items-center gap-2">
                    <a href="{{ route('admin.candidates.edit', $candidate) }}"
                        class="p-2 sm:p-2.5 rounded-xl bg-black/75 hover:bg-[#940C10] border border-white/30 hover:border-[#CE9501] text-white shadow-xl shadow-black/60 backdrop-blur-md transition-all duration-200 group/btn inline-flex items-center justify-center cursor-pointer"
                        title="Edit Paslon, Visi & Misi">
                        <svg class="w-4 h-4 text-white group-hover/btn:text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                    </a>

                    <button type="button"
                        @click="handleDelete('{{ route('admin.candidates.destroy', $candidate) }}', '{{ $candidate->candidate_number }}', '{{ addslashes($candidate->chairman_name) }}')"
                        class="p-2 sm:p-2.5 rounded-xl bg-black/75 hover:bg-red-600 border border-white/30 hover:border-red-400 text-white shadow-xl shadow-black/60 backdrop-blur-md transition-all duration-200 cursor-pointer inline-flex items-center justify-center"
                        title="Hapus Paslon">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </div>

                <!-- Foto Paslon Full Card (Storage & Fallback) -->
                <img src="{{ $candidate->photo }}" 
                     onerror="this.onerror=null;this.src='{{ asset('images/paslon-0' . $candidate->candidate_number . '.png') }}';"
                     alt="Pasangan Calon No. 0{{ $candidate->candidate_number }}" 
                     class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105">

                <!-- Gradient Overlay & Info Paslon di Bagian Bawah -->
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/95 via-black/80 to-transparent p-4 sm:p-5 text-white pt-28 z-10 flex flex-col justify-end">
                    <!-- Nama Calon Ketua & Wakil Ketua Berdampingan dengan Garis Pemisah Vertikal Sesuai Halaman Voting -->
                    <div class="border-t border-white/20 pt-2.5 mb-2.5">
                        <div class="flex items-stretch justify-between gap-2 sm:gap-3">
                            <!-- Calon Ketua -->
                            <div class="flex-1 flex flex-col justify-end text-center pr-1 sm:pr-2 min-w-0">
                                <h3 class="text-xs sm:text-sm font-bold text-white group-hover:text-[#CE9501] transition-colors leading-snug break-words">
                                    {{ $candidate->chairman_name }}
                                </h3>
                                <p class="text-[10px] sm:text-[11px] font-medium text-[#CE9501] mt-0.5 whitespace-nowrap">
                                    Calon Ketua BEM
                                </p>
                            </div>

                            <!-- Garis Pemisah Vertikal Halus (Tinggi Menyesuaikan Teks Otomatis) -->
                            <div class="self-stretch w-px bg-white/20 shrink-0 my-0.5"></div>

                            <!-- Calon Wakil Ketua -->
                            <div class="flex-1 flex flex-col justify-end text-center pl-1 sm:pl-2 min-w-0">
                                <h3 class="text-xs sm:text-sm font-bold text-white group-hover:text-[#CE9501] transition-colors leading-snug break-words">
                                    {{ $candidate->vice_chairman_name }}
                                </h3>
                                <p class="text-[10px] sm:text-[11px] font-medium text-[#CE9501] mt-0.5 whitespace-nowrap">
                                    Calon Wakil Ketua BEM
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Judul Aksi / Modal Trigger & Edit Link -->
                    <div class="pt-2.5 border-t border-white/20 flex items-center justify-between gap-2">
                        <button type="button"
                            @click="openDetailModal({{ json_encode([
                                'id' => $candidate->id,
                                'number' => $candidate->candidate_number,
                                'chairman_name' => $candidate->chairman_name,
                                'chairman_nim' => $candidate->chairman_nim,
                                'chairman_prodi' => $candidate->chairman_study_program,
                                'vice_name' => $candidate->vice_chairman_name,
                                'vice_nim' => $candidate->vice_chairman_nim,
                                'vice_prodi' => $candidate->vice_chairman_study_program,
                                'photo' => $candidate->photo,
                                'fallback_photo' => asset('images/paslon-0' . $candidate->candidate_number . '.png'),
                                'vision' => $candidate->vision,
                                'mission' => $candidate->mission ?? [],
                                'work_programs' => $candidate->work_programs ?? [],
                                'edit_url' => route('admin.candidates.edit', $candidate)
                            ]) }})"
                            class="text-xs sm:text-sm text-amber-300 hover:text-white font-semibold flex items-center gap-1.5 transition-colors cursor-pointer">
                            <svg class="w-4 h-4 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <span>Visi, Misi & Proker</span>
                        </button>

                        <a href="{{ route('admin.candidates.edit', $candidate) }}"
                            class="text-xs text-slate-300 hover:text-[#CE9501] font-medium flex items-center gap-1 transition-colors">
                            <span>Sunting</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-gray-200 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-red-50 text-[#940C10] flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <line x1="17" y1="8" x2="23" y2="14"></line>
                        <line x1="23" y1="8" x2="17" y2="14"></line>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Belum Ada Pasangan Calon</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">Silakan daftarkan pasangan calon Ketua & Calon Wakil Ketua BEM untuk periode pemilihan ini.</p>
                @if($period)
                    <div class="pt-2">
                        <a href="{{ route('admin.candidates.create', ['period_id' => $period->id]) }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#940C10] text-white text-xs font-bold hover:bg-[#7A0A0D] shadow-xs">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Daftarkan Paslon Pertama</span>
                        </a>
                    </div>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Hidden Form untuk Eksekusi Hapus Paslon -->
    <form id="delete-candidate-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Detail Paslon: Visi, Misi & Program Kerja -->
    <template x-teleport="body">
        <div x-show="detailModalOpen" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-xs overflow-y-auto"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div @click.away="detailModalOpen = false"
                class="bg-white rounded-3xl border border-slate-200 max-w-2xl w-full p-6 sm:p-8 shadow-2xl space-y-6 my-8 max-h-[90vh] overflow-y-auto admin-scrollbar"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Modal Header -->
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-5">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-[#940C10] text-[#CE9501] flex flex-col items-center justify-center font-bold shadow-xs shrink-0">
                            <span class="text-[9px] uppercase font-bold text-white">No</span>
                            <span class="text-xl leading-none" x-text="'0' + (activeCandidate?.number || '')"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#940C10]">Detail Kontestan Pemilu</span>
                            <h3 class="text-base sm:text-lg font-black text-slate-900 leading-tight">
                                <span x-text="activeCandidate?.chairman_name"></span> & <span x-text="activeCandidate?.vice_name"></span>
                            </h3>
                        </div>
                    </div>

                    <button type="button" @click="detailModalOpen = false"
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="space-y-6">
                    <!-- Info Paslon Ketua & Wakil (Bersih Tanpa Box Bertingkat) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 py-4 border-y border-slate-100">
                        <div class="space-y-1">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Calon Ketua BEM</span>
                            <p class="text-sm font-bold text-slate-900" x-text="activeCandidate?.chairman_name"></p>
                            <p class="text-xs text-slate-500 font-medium" x-text="'NIM: ' + (activeCandidate?.chairman_nim || '-') + ' • ' + (activeCandidate?.chairman_prodi || '-')"></p>
                        </div>
                        <div class="space-y-1 sm:border-l sm:border-slate-100 sm:pl-5">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Calon Wakil Ketua BEM</span>
                            <p class="text-sm font-bold text-slate-900" x-text="activeCandidate?.vice_name"></p>
                            <p class="text-xs text-slate-500 font-medium" x-text="'NIM: ' + (activeCandidate?.vice_nim || '-') + ' • ' + (activeCandidate?.vice_prodi || '-')"></p>
                        </div>
                    </div>

                    <!-- Visi -->
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Visi</h4>
                        <blockquote class="text-xs sm:text-sm text-slate-800 bg-slate-50 p-4 rounded-xl border-l-2 border-[#940C10] leading-relaxed font-medium"
                           x-text="'“' + (activeCandidate?.vision || '') + '”'">
                        </blockquote>
                    </div>

                    <!-- Misi -->
                    <div class="space-y-2.5">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Misi</h4>
                        <ol class="space-y-2 text-xs text-slate-700">
                            <template x-for="(misi, idx) in (activeCandidate?.mission || [])" :key="idx">
                                <li class="flex items-start gap-2.5">
                                    <span class="font-mono font-bold text-xs text-[#940C10] shrink-0 pt-0.5 w-5" x-text="(idx + 1 < 10 ? '0' : '') + (idx + 1) + '.'"></span>
                                    <span class="leading-relaxed" x-text="misi"></span>
                                </li>
                            </template>
                        </ol>
                    </div>

                    <!-- Program Kerja Unggulan -->
                    <template x-if="activeCandidate?.work_programs && activeCandidate?.work_programs.length > 0">
                        <div class="space-y-2.5">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Program Kerja Unggulan</h4>
                            <ul class="space-y-2 text-xs text-slate-700">
                                <template x-for="(proker, idx) in activeCandidate.work_programs" :key="idx">
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-[#940C10] font-bold text-sm shrink-0 leading-tight">&ndash;</span>
                                        <span class="leading-relaxed" x-text="proker"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="detailModalOpen = false"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                        Tutup
                    </button>

                    <a :href="activeCandidate?.edit_url"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#940C10] hover:bg-[#7A0A0D] text-white text-xs font-bold shadow-xs transition-all">
                        <svg class="w-4 h-4 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        <span>Sunting Detail Paslon</span>
                    </a>
                </div>
            </div>
        </div>
    </template>

</div>

<script>
    window.candidateManagement = function candidateManagement() {
        return {
            detailModalOpen: false,
            activeCandidate: null,

            openDetailModal(candidate) {
                this.activeCandidate = candidate;
                this.detailModalOpen = true;
                this.$nextTick(() => {
                    if (window.lucide && window.lucide.createIcons) {
                        window.lucide.createIcons();
                    }
                });
            },

            handleDelete(deleteUrl, candidateNumber, chairmanName) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Hapus Paslon No. 0' + candidateNumber + '?',
                        text: 'Apakah Anda yakin ingin menghapus pasangan calon ' + chairmanName + '? Seluruh data dan berkas terkait paslon ini akan dihapus dari sistem.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#940C10',
                        cancelButtonColor: '#64748B',
                        confirmButtonText: 'Ya, Hapus Paslon',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-3xl',
                            confirmButton: 'rounded-xl font-bold px-4 py-2 text-xs',
                            cancelButton: 'rounded-xl font-semibold px-4 py-2 text-xs'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('delete-candidate-form');
                            form.action = deleteUrl;
                            form.submit();
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin menghapus Paslon No. 0' + candidateNumber + ' (' + chairmanName + ')?')) {
                        const form = document.getElementById('delete-candidate-form');
                        form.action = deleteUrl;
                        form.submit();
                    }
                }
            }
        };
    }
</script>
@endsection
