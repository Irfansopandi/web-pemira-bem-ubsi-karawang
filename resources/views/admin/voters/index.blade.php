@extends('layouts.admin', ['title' => 'Kelola Mahasiswa'])

@section('content')
<div class="space-y-6 sm:space-y-8" x-data="{ isDetailOpen: false, selectedVoter: null }">

    <!-- Header Halaman -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-start sm:items-center gap-3.5 sm:gap-4">
            <!-- Icon Tanpa Border Sesuai Standar Minimalis UBSI -->
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-[#940C10] shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Manajemen Pemilihan</span>
                    @if($period)
                        <span class="text-slate-300">•</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $period->isSystemActive() ? 'bg-emerald-50 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-700 border border-slate-300' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $period->isSystemActive() ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                            Periode: {{ $period->academic_year }} ({{ $period->isSystemActive() ? 'Aktif' : 'Arsip' }})
                        </span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">
                    Daftar Pemilih Tetap (DPT) & Kehadiran
                </h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    Data Induk Mahasiswa Hak Suara, Status Verifikasi Kehadiran TPS & Partisipasi Pemilihan
                </p>
            </div>
        </div>

        @if(isset($participations))
        <div class="flex items-center gap-3 flex-wrap">
            <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200/90 text-xs font-bold text-slate-700">
                <svg class="w-4 h-4 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
                <span>Total: {{ number_format($participations->total()) }} Mahasiswa DPT</span>
            </span>
        </div>
        @endif
    </div>

    <!-- Filter & Data Table -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden">
        
        <!-- Filter & Search & Sort Bar -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col 2xl:flex-row items-stretch 2xl:items-center justify-between gap-3.5 sm:gap-4">
            
            <!-- Quick Status Filter Pills (Selalu 1 Baris Sejajar / Never Wrap) -->
            <div class="flex items-center gap-1.5 sm:gap-2 flex-nowrap overflow-x-auto no-scrollbar shrink-0 max-w-full pb-1 2xl:pb-0">
                <!-- Tab Semua (DPT) -->
                <a href="{{ route('admin.voters.index', request()->except('status', 'page')) }}" 
                    class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl text-xs font-bold transition-all shadow-2xs whitespace-nowrap shrink-0 {{ !request('status') ? 'bg-[#940C10] text-white shadow-xs' : 'bg-slate-50 border border-slate-200/90 text-slate-700 hover:bg-slate-100' }}">
                    <span>Semua (DPT)</span>
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-black min-w-[20px] {{ !request('status') ? 'bg-white/20 text-white' : 'bg-slate-200/80 text-slate-700' }}">
                        {{ number_format($stats['total_students'] ?? 0) }}
                    </span>
                </a>

                <!-- Tab Sudah Hadir -->
                <a href="{{ route('admin.voters.index', array_merge(request()->except('page'), ['status' => 'present'])) }}" 
                    class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl text-xs font-bold transition-all shadow-2xs whitespace-nowrap shrink-0 {{ request('status') === 'present' ? 'bg-[#940C10] text-white shadow-xs' : 'bg-slate-50 border border-slate-200/90 text-slate-700 hover:bg-slate-100' }}">
                    <span>Sudah Hadir</span>
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-black min-w-[20px] {{ request('status') === 'present' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }}">
                        {{ number_format($stats['total_present'] ?? 0) }}
                    </span>
                </a>

                <!-- Tab Sudah Memilih -->
                <a href="{{ route('admin.voters.index', array_merge(request()->except('page'), ['status' => 'voted'])) }}" 
                    class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl text-xs font-bold transition-all shadow-2xs whitespace-nowrap shrink-0 {{ request('status') === 'voted' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-50 border border-slate-200/90 text-slate-700 hover:bg-slate-100' }}">
                    <span>Sudah Memilih</span>
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-black min-w-[20px] {{ request('status') === 'voted' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ number_format($stats['total_voted'] ?? 0) }}
                    </span>
                </a>

                <!-- Tab Belum Memilih -->
                <a href="{{ route('admin.voters.index', array_merge(request()->except('page'), ['status' => 'not_voted'])) }}" 
                    class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl text-xs font-bold transition-all shadow-2xs whitespace-nowrap shrink-0 {{ request('status') === 'not_voted' ? 'bg-slate-800 text-white shadow-xs' : 'bg-slate-50 border border-slate-200/90 text-slate-700 hover:bg-slate-100' }}">
                    <span>Belum Memilih</span>
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-black min-w-[20px] {{ request('status') === 'not_voted' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">
                        {{ number_format($stats['total_not_voted'] ?? 0) }}
                    </span>
                </a>
            </div>

            <!-- Sortir & Form Pencarian -->
            <form action="{{ route('admin.voters.index') }}" method="GET" class="flex items-center gap-2 flex-wrap sm:flex-nowrap w-full 2xl:w-auto justify-start 2xl:justify-end">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <!-- Pilihan Sortir Dropdown -->
                <div class="relative shrink-0">
                    <select name="sort" onchange="this.form.submit()" 
                        class="appearance-none pl-8 pr-8 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 focus:ring-2 focus:ring-[#940C10] focus:border-[#940C10] transition-all cursor-pointer shadow-2xs">
                        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Urutkan: Aktivitas Terbaru</option>
                        <option value="nim_asc" {{ request('sort') === 'nim_asc' ? 'selected' : '' }}>Urutkan: NIM (0 - 9)</option>
                        <option value="nim_desc" {{ request('sort') === 'nim_desc' ? 'selected' : '' }}>Urutkan: NIM (9 - 0)</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Urutkan: Nama (A - Z)</option>
                        <option value="voted_desc" {{ request('sort') === 'voted_desc' ? 'selected' : '' }}>Urutkan: Waktu Voting Tercepat</option>
                    </select>
                    <!-- Icon Sort -->
                    <svg class="w-3.5 h-3.5 text-slate-500 absolute left-2.5 top-3 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="m3 16 4 4 4-4"></path>
                        <path d="M7 20V4"></path>
                        <path d="m21 8-4-4-4 4"></path>
                        <path d="M17 4v16"></path>
                    </svg>
                    <!-- Chevron Down -->
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute right-2.5 top-3 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"></path></svg>
                </div>

                <!-- Input Pencarian -->
                <div class="relative flex-1 sm:w-56">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIM atau Nama..."
                        class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-300 text-xs font-medium focus:ring-2 focus:ring-[#940c10] focus:border-[#940c10] bg-white shadow-2xs">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-all cursor-pointer shadow-2xs shrink-0">
                    Cari
                </button>

                @if(request('search') || request('sort'))
                    <a href="{{ route('admin.voters.index', request()->only('status')) }}" class="p-2 text-slate-400 hover:text-slate-600 text-xs font-bold shrink-0" title="Reset Pencarian & Sortir">
                        &times;
                    </a>
                @endif
            </form>
        </div>

        <!-- Sub-bar Indikator Jumlah Mahasiswa di Halaman Ini -->
        <div class="px-5 sm:px-6 py-2.5 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
            <div class="flex items-center gap-2 flex-wrap">
                <span>Menampilkan:</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-white border border-slate-200/90 text-slate-800 font-bold font-mono text-[11px] shadow-2xs">
                    {{ $participations->count() }} Data
                </span>
                <span>(10 data per halaman)</span>
                @if($participations->total() > 0)
                    <span class="text-slate-300">•</span>
                    <span>Data ke-<strong>{{ $participations->firstItem() }}</strong> s/d <strong>{{ $participations->lastItem() }}</strong> dari total <strong class="text-slate-800">{{ number_format($participations->total()) }}</strong> mahasiswa</span>
                @endif
            </div>
            @if(request('status') || request('search') || request('sort'))
                <a href="{{ route('admin.voters.index') }}" class="text-[#940C10] hover:underline font-bold text-xs inline-flex items-center gap-1">
                    <span>Reset Semua Filter</span>
                </a>
            @endif
        </div>

        <!-- Tabel DPT -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-400 uppercase tracking-wider text-[10px] font-bold">
                        <th class="py-3.5 px-5">Mahasiswa</th>
                        <th class="py-3.5 px-5">Status Hak Suara</th>
                        <th class="py-3.5 px-5">Waktu Voting</th>
                        <th class="py-3.5 px-5">Kehadiran (Buku Tamu)</th>
                        <th class="py-3.5 px-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($participations as $part)
                        @php
                            $isPresent = (bool) ($part->verified_at || $part->has_voted);
                            $verifierText = 'Belum Verifikasi Kehadiran';
                            if ($isPresent) {
                                if ($part->verifiedBy) {
                                    $verifierText = 'Verifikasi Kehadiran (' . $part->verifiedBy->name . ')';
                                } elseif ($part->verified_at) {
                                    $verifierText = 'Verifikasi Kehadiran TPS (Terverifikasi)';
                                } elseif ($part->has_voted) {
                                    $verifierText = 'Bilik Suara (Mandiri)';
                                } else {
                                    $verifierText = 'Verifikasi Kehadiran TPS';
                                }
                            }
                            $verifiedAtFormatted = $part->verified_at 
                                ? $part->verified_at->translatedFormat('d M Y, H:i') . ' WIB' 
                                : ($part->has_voted && $part->voted_at ? $part->voted_at->translatedFormat('d M Y, H:i') . ' WIB' : '-');
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900 text-xs">{{ $part->student->name ?? 'Mahasiswa' }}</span>
                                    <span class="font-mono text-[11px] text-[#940C10] font-bold mt-0.5">{{ $part->student_nim }}</span>
                                    <span class="text-[11px] text-slate-500 font-medium">{{ $part->student->study_program ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $part->has_voted ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $part->has_voted ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    {{ $part->has_voted ? 'Sudah Memilih' : 'Belum Memilih' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-slate-500 font-mono">
                                {{ $part->voted_at ? $part->voted_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-5">
                                @if($isPresent)
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200" title="Tercatat hadir di Verifikasi Kehadiran Pemilih">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                            Hadir
                                        </span>
                                        @if($part->signature_url)
                                            <img src="{{ $part->signature_url }}" alt="TTD" class="h-5 w-10 object-contain bg-white rounded border border-slate-200 p-0.5" title="Tanda tangan terverifikasi">
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200" title="Belum verifikasi kehadiran TPS">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Belum Hadir
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <button type="button" 
                                    @click="selectedVoter = {
                                        nim: '{{ $part->student_nim }}',
                                        name: '{{ addslashes($part->student->name ?? 'Mahasiswa') }}',
                                        study_program: '{{ addslashes($part->student->study_program ?? '-') }}',
                                        class_name: '{{ addslashes($part->student->class_name ?? '-') }}',
                                        semester: '{{ $part->student->semester ?? '-' }}',
                                        has_voted: {{ $part->has_voted ? 'true' : 'false' }},
                                        voted_at: '{{ $part->voted_at ? $part->voted_at->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}',
                                        is_present: {{ $isPresent ? 'true' : 'false' }},
                                        verified_at: '{{ $verifiedAtFormatted }}',
                                        verifier: '{{ addslashes($verifierText) }}',
                                        signature_url: '{{ $part->signature_url }}',
                                        tps_location: '{{ addslashes($part->tps_location ?? 'Kampus UBSI Karawang') }}'
                                    }; isDetailOpen = true;"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 hover:text-[#940C10] hover:bg-red-50 hover:border-red-200 transition-all shadow-2xs cursor-pointer"
                                    title="Lihat Detail Pemilih">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                Tidak ada data pemilih yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginasi -->
        @if($participations->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $participations->links() }}
        </div>
        @endif

    </div>

    <!-- Modal Detail Mahasiswa Pemilih -->
    <template x-teleport="body">
        <div x-show="isDetailOpen" 
            x-cloak
            class="fixed inset-0 z-[100] overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden"
                @click.away="isDetailOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Modal Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <div>
                            <h3 class="text-base font-black text-slate-900 tracking-tight">Detail Pemilih Mahasiswa</h3>
                            <p class="text-xs text-slate-500 font-medium">Informasi data DPT, presensi buku tamu TPS & status voting</p>
                        </div>
                    </div>
                    <button type="button" @click="isDetailOpen = false" 
                        class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Content Body -->
                <div class="p-6 space-y-5" x-show="selectedVoter">
                    <!-- Profil Mahasiswa Card -->
                    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Data Induk Mahasiswa</span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold font-mono text-[11px] shadow-2xs">
                                DPT UBSI
                            </span>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="text-base font-black text-slate-900" x-text="selectedVoter?.name"></h4>
                            <span class="font-mono text-xs font-black text-[#940C10] mt-0.5" x-text="'NIM: ' + selectedVoter?.nim"></span>
                            <span class="text-xs text-slate-600 font-medium mt-0.5" x-text="selectedVoter?.study_program"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200/60 text-xs">
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block">Kelas:</span>
                                <span class="font-medium text-slate-700" x-text="selectedVoter?.class_name || '-'"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block">Semester:</span>
                                <span class="font-medium text-slate-700" x-text="selectedVoter?.semester || '-'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Grid: Partisipasi & Kehadiran -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Status Hak Suara -->
                        <div class="p-3.5 rounded-2xl border border-slate-200/80 bg-white shadow-2xs space-y-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Status Hak Suara</span>
                            <div>
                                <template x-if="selectedVoter?.has_voted">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Sudah Memilih
                                    </span>
                                </template>
                                <template x-if="!selectedVoter?.has_voted">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Belum Memilih
                                    </span>
                                </template>
                            </div>
                            <div class="text-[11px] text-slate-500 pt-1">
                                <span class="text-slate-400 block text-[10px]">Waktu Pencoblosan:</span>
                                <span class="font-medium font-mono text-slate-700" x-text="selectedVoter?.voted_at"></span>
                            </div>
                        </div>

                        <!-- Status Kehadiran (Buku Tamu) -->
                        <div class="p-3.5 rounded-2xl border border-slate-200/80 bg-white shadow-2xs space-y-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Buku Tamu / Kehadiran</span>
                            <div>
                                <template x-if="selectedVoter?.is_present">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-800 border border-blue-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                        Hadir
                                    </span>
                                </template>
                                <template x-if="!selectedVoter?.is_present">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Belum Hadir
                                    </span>
                                </template>
                            </div>
                            <div class="text-[11px] text-slate-500 pt-1 space-y-1">
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Verifikasi:</span>
                                    <span class="font-medium text-slate-700 block truncate" x-text="selectedVoter?.verifier"></span>
                                </div>
                                <template x-if="selectedVoter?.is_present && selectedVoter?.verified_at && selectedVoter?.verified_at !== '-'">
                                    <div>
                                        <span class="text-slate-400 block text-[10px]">Waktu Presensi:</span>
                                        <span class="font-medium font-mono text-slate-700" x-text="selectedVoter?.verified_at"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Pratinjau Tanda Tangan Digital Pemilih -->
                    <template x-if="selectedVoter?.signature_url">
                        <div class="p-3.5 rounded-2xl border border-slate-200/80 bg-white shadow-2xs space-y-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Bukti Tanda Tangan Digital (Meja Verifikasi TPS)</span>
                            <div class="p-3 bg-slate-50/70 rounded-xl border border-dashed border-slate-200 flex items-center justify-center">
                                <img :src="selectedVoter?.signature_url" alt="Tanda Tangan Pemilih" class="max-h-24 object-contain">
                            </div>
                        </div>
                    </template>

                    <!-- Prinsip Kerahasiaan Suara Notice -->
                    <div class="p-3.5 rounded-2xl bg-amber-50/80 border border-amber-200/80 flex items-start gap-3">
                        <svg class="w-4 h-4 text-[#CE9501] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <div class="text-[11px] text-amber-900 leading-relaxed">
                            <strong class="font-bold">Prinsip Kerahasiaan (Luber Jurdil):</strong>
                            Pilihan paslon BEM bersifat rahasia murni (Anonymous Ballot) dan tidak dapat diakses atau dihubungkan dengan identitas pemilih di database.
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                    <button type="button" @click="isDetailOpen = false"
                        class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection
