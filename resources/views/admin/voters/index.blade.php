@extends('layouts.admin', ['title' => 'Kelola Mahasiswa'])

@section('content')
<div class="space-y-6 sm:space-y-8" x-data="votersLiveManager()">

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
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $period->isSystemActive() ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
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

        <!-- Badges Kanan: Baris Tombol Cetak & Import Sejajar + Badge Total DPT Sejajar Penuh di Bawahnya -->
        @if(isset($participations))
        <div class="w-full md:w-auto md:ml-auto shrink-0 flex flex-col items-stretch gap-2.5">
            <div class="flex items-center justify-between md:justify-end gap-2.5">
                <!-- Dropdown Cetak Data Berdasarkan Status -->
                <div class="relative flex-1 md:flex-initial" x-data="{ openPrintMenu: false }" @click.outside="openPrintMenu = false">
                    <button type="button" @click="openPrintMenu = !openPrintMenu"
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 active:scale-95 text-slate-800 border border-slate-300 text-xs font-extrabold shadow-2xs hover:shadow-sm transition-all cursor-pointer whitespace-nowrap">
                        <svg class="w-4 h-4 text-[#940C10] shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        <span class="tracking-wide">Cetak Data</span>
                        <svg class="w-3.5 h-3.5 text-slate-500 transition-transform" :class="openPrintMenu ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div x-show="openPrintMenu"
                         x-cloak
                         x-transition.opacity.duration.150ms
                         class="absolute right-0 mt-1.5 w-52 bg-white rounded-xl border border-slate-200 shadow-xl py-1.5 z-40 text-left">
                        <a href="{{ route('admin.voters.print', request()->except('status', 'page')) }}" target="_blank" @click="openPrintMenu = false"
                           class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#940C10]">
                            <span>Semua Mahasiswa (DPT)</span>
                        </a>
                        <a href="{{ route('admin.voters.print', array_merge(request()->except('page'), ['status' => 'present'])) }}" target="_blank" @click="openPrintMenu = false"
                           class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#940C10]">
                            <span>Sudah Hadir (TPS)</span>
                        </a>
                        <a href="{{ route('admin.voters.print', array_merge(request()->except('page'), ['status' => 'not_present'])) }}" target="_blank" @click="openPrintMenu = false"
                           class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#940C10]">
                            <span>Belum Hadir</span>
                        </a>
                        <a href="{{ route('admin.voters.print', array_merge(request()->except('page'), ['status' => 'voted'])) }}" target="_blank" @click="openPrintMenu = false"
                           class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#940C10]">
                            <span>Sudah Memilih</span>
                        </a>
                        <a href="{{ route('admin.voters.print', array_merge(request()->except('page'), ['status' => 'not_voted'])) }}" target="_blank" @click="openPrintMenu = false"
                           class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#940C10]">
                            <span>Belum Memilih</span>
                        </a>
                    </div>
                </div>

                <!-- Tombol Import Data (Sejajar di samping tombol Cetak) -->
                <button type="button" @click="isImportOpen = true"
                    class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#940C10] hover:bg-[#7A0A0D] active:scale-95 text-white text-xs font-extrabold shadow-sm hover:shadow-md transition-all cursor-pointer whitespace-nowrap">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="12" x2="12" y2="15"></line>
                    </svg>
                    <span class="tracking-wide">Import Data</span>
                </button>
            </div>

            <!-- Total Counter Badge (Lebar Sejajar Penuh dengan Kedua Tombol di Atasnya) -->
            <span class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-slate-50 border border-slate-200/90 text-xs font-bold text-slate-700 whitespace-nowrap">
                <svg class="w-4 h-4 text-[#940C10] shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
                <span>Total: <strong class="text-slate-900 font-black" x-text="formatNumber(stats.total_students)">{{ number_format($stats['total_students'] ?? 0) }}</strong> Mahasiswa DPT</span>
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
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-black min-w-[20px] {{ !request('status') ? 'bg-white/20 text-white' : 'bg-slate-200/80 text-slate-700' }}"
                        x-text="formatNumber(stats.total_students)">
                        {{ number_format($stats['total_students'] ?? 0) }}
                    </span>
                </a>

                <!-- Tab Sudah Hadir -->
                <a href="{{ route('admin.voters.index', array_merge(request()->except('page'), ['status' => 'present'])) }}" 
                    class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl text-xs font-bold transition-all shadow-2xs whitespace-nowrap shrink-0 {{ request('status') === 'present' ? 'bg-[#940C10] text-white shadow-xs' : 'bg-slate-50 border border-slate-200/90 text-slate-700 hover:bg-slate-100' }}">
                    <span>Sudah Hadir</span>
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-black min-w-[20px] {{ request('status') === 'present' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }}"
                        x-text="formatNumber(stats.total_present)">
                        {{ number_format($stats['total_present'] ?? 0) }}
                    </span>
                </a>

                <!-- Tab Sudah Memilih -->
                <a href="{{ route('admin.voters.index', array_merge(request()->except('page'), ['status' => 'voted'])) }}" 
                    class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl text-xs font-bold transition-all shadow-2xs whitespace-nowrap shrink-0 {{ request('status') === 'voted' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-50 border border-slate-200/90 text-slate-700 hover:bg-slate-100' }}">
                    <span>Sudah Memilih</span>
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-black min-w-[20px] {{ request('status') === 'voted' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}"
                        x-text="formatNumber(stats.total_voted)">
                        {{ number_format($stats['total_voted'] ?? 0) }}
                    </span>
                </a>

                <!-- Tab Belum Memilih -->
                <a href="{{ route('admin.voters.index', array_merge(request()->except('page'), ['status' => 'not_voted'])) }}" 
                    class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl text-xs font-bold transition-all shadow-2xs whitespace-nowrap shrink-0 {{ request('status') === 'not_voted' ? 'bg-slate-800 text-white shadow-xs' : 'bg-slate-50 border border-slate-200/90 text-slate-700 hover:bg-slate-100' }}">
                    <span>Belum Memilih</span>
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-black min-w-[20px] {{ request('status') === 'not_voted' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}"
                        x-text="formatNumber(stats.total_not_voted)">
                        {{ number_format($stats['total_not_voted'] ?? 0) }}
                    </span>
                </a>
            </div>

            <!-- Filter Kelas & Form Pencarian -->
            <form action="{{ route('admin.voters.index') }}" method="GET" class="flex items-center gap-2 flex-wrap sm:flex-nowrap w-full 2xl:w-auto justify-start 2xl:justify-end">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <!-- Filter Kelas Dropdown -->
                <div class="relative shrink-0">
                    <select name="class_name" onchange="this.form.submit()" 
                        class="appearance-none pl-8 pr-8 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 focus:ring-2 focus:ring-[#940C10] focus:border-[#940C10] transition-all cursor-pointer shadow-2xs">
                        <option value="">Kelas: Semua Kelas</option>
                        @foreach($classes ?? [] as $cls)
                            <option value="{{ $cls }}" {{ request('class_name') === $cls ? 'selected' : '' }}>Kelas: {{ $cls }}</option>
                        @endforeach
                    </select>
                    <!-- Icon Filter Kelas -->
                    <svg class="w-3.5 h-3.5 text-slate-500 absolute left-2.5 top-3 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
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

        <!-- Sub-bar Indikator Jumlah Mahasiswa di Halaman Ini & Tombol Live Sync -->
        <div class="px-5 sm:px-6 py-2 bg-slate-50/70 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5 text-xs text-slate-500 font-medium">
            <div class="flex items-center gap-2 flex-wrap">
                <span>Menampilkan:</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-white border border-slate-200/90 text-slate-800 font-bold font-mono text-[11px] shadow-2xs">
                    <span x-text="currentPageCount">{{ $participations->count() }}</span> Data
                </span>
                <span>(10 data per halaman)</span>
                <template x-if="totalCount > 0">
                    <div class="inline-flex items-center gap-2">
                        <span class="text-slate-300">•</span>
                        <span>Data ke-<strong x-text="firstItem">{{ $participations->firstItem() }}</strong> s/d <strong x-text="lastItem">{{ $participations->lastItem() }}</strong> dari total <strong class="text-slate-800" x-text="formatNumber(totalCount)">{{ number_format($participations->total()) }}</strong> mahasiswa</span>
                    </div>
                </template>
            </div>

            <!-- Bagian Kanan: Sejajar Teks Menampilkan & di Bawah Kolom Pencarian -->
            <div class="flex items-center gap-2.5 self-end sm:self-auto shrink-0">
                @if(request('status') || request('search') || request('sort'))
                    <a href="{{ route('admin.voters.index') }}" class="text-[#940C10] hover:underline font-bold text-xs inline-flex items-center gap-1">
                        <span>Reset Semua Filter</span>
                    </a>
                    <span class="text-slate-300">•</span>
                @endif

                <!-- Button Live Auto-Sync Kompak & Pas -->
                <button type="button" @click="toggleLive()"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-[11px] font-bold transition-all shadow-2xs select-none cursor-pointer"
                    :class="isLive ? 'bg-emerald-50 text-emerald-800 border-emerald-300 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-300 hover:bg-slate-200'"
                    :title="isLive ? 'Klik untuk jeda auto-sync' : 'Klik untuk aktifkan kembali auto-sync'">
                    <span class="relative flex h-1.5 w-1.5 shrink-0">
                        <span x-show="isLive" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5" :class="isLive ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                    </span>
                    <span x-text="isLive ? 'Live' : 'Jeda'"></span>
                    <span class="text-slate-300">•</span>
                    <span class="font-mono text-[10px] text-slate-500 font-normal" x-text="lastSync"></span>
                    <span class="ml-0.5 shrink-0">
                        <template x-if="isLive">
                            <svg class="w-3 h-3" :class="isSyncing ? 'animate-spin text-emerald-700' : 'text-slate-400'" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                            </svg>
                        </template>
                        <template x-if="!isLive">
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <polygon points="5 3 19 12 5 21 5 3"/>
                            </svg>
                        </template>
                    </span>
                </button>
            </div>
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
                <tbody id="voters-table-body" class="divide-y divide-slate-100 text-slate-700">
                    @include('admin.voters._table_rows', ['participations' => $participations])
                </tbody>
            </table>
        </div>

        <!-- Paginasi -->
        <div id="voters-pagination">
            @if($participations->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $participations->links() }}
            </div>
            @endif
        </div>

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
            
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden"
                @click.away="closeDetail()"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Modal Header (Compact) -->
                <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-[#940C10] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight leading-tight">Detail Pemilih Mahasiswa</h3>
                            <p class="text-[11px] text-slate-500 font-medium">Informasi data DPT, presensi buku tamu TPS & status voting</p>
                        </div>
                    </div>
                    <button type="button" @click="closeDetail()" 
                        class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center transition-all cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Content Body (Scrollable if needed, compact spacing) -->
                <div class="p-4 sm:p-5 space-y-3 overflow-y-auto" x-show="selectedVoter">
                    
                    <!-- Card 1: Data Induk Mahasiswa (Grid Horizontal Kompak) -->
                    <div class="bg-slate-50/90 rounded-xl p-3 sm:p-3.5 border border-slate-200/80">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Data Induk Mahasiswa</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-white border border-slate-200 text-slate-700 font-mono shadow-2xs">
                                DPT UBSI
                            </span>
                        </div>

                        <!-- Top: Nama, NIM, Prodi -->
                        <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-1 pb-2 border-b border-slate-200/70">
                            <div>
                                <h4 class="text-sm sm:text-base font-black text-slate-900 leading-tight" x-text="selectedVoter?.name"></h4>
                                <span class="text-xs text-slate-600 font-medium" x-text="selectedVoter?.study_program"></span>
                            </div>
                            <div class="font-mono text-xs font-black text-[#940C10] bg-white sm:bg-transparent px-2 sm:px-0 py-0.5 rounded border sm:border-0 border-slate-200 self-start sm:self-auto" x-text="'NIM: ' + selectedVoter?.nim"></div>
                        </div>

                        <!-- Bottom: Grid Atribut Lengkap -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-2 text-xs">
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Kelas</span>
                                <span class="font-semibold text-slate-800" x-text="selectedVoter?.class_name || '-'"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Semester</span>
                                <span class="font-semibold text-slate-800" x-text="selectedVoter?.semester || '-'"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">No. Telp / WA</span>
                                <span class="font-semibold text-slate-800 truncate block" x-text="selectedVoter?.phone || '-'"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Email Kampus</span>
                                <span class="font-semibold text-slate-800 truncate block" x-text="selectedVoter?.email_kampus || '-'"></span>
                            </div>
                            <div class="col-span-2 sm:col-span-4 pt-0.5">
                                <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Asal Kampus / Lokasi Presensi</span>
                                <span class="font-semibold text-slate-800" x-text="selectedVoter?.asal_kampus || selectedVoter?.tps_location || '-'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Status Grid (Hak Suara & Kehadiran Berdampingan) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        
                        <!-- Status Hak Suara -->
                        <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs space-y-1.5 flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Hak Suara</span>
                                <template x-if="selectedVoter?.has_voted">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 transition-colors">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Sudah Memilih
                                    </span>
                                </template>
                                <template x-if="!selectedVoter?.has_voted">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 transition-colors">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Belum Memilih
                                    </span>
                                </template>
                            </div>
                            <div class="text-[11px] text-slate-500 pt-0.5">
                                <span class="text-slate-400 text-[10px] block">Waktu Pencoblosan:</span>
                                <span class="font-medium font-mono text-slate-700" x-text="selectedVoter?.voted_at || '-'"></span>
                            </div>
                        </div>

                        <!-- Status Kehadiran (Buku Tamu) -->
                        <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs space-y-1.5 flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Buku Tamu</span>
                                <template x-if="selectedVoter?.is_present">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 transition-colors">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                        Hadir
                                    </span>
                                </template>
                                <template x-if="!selectedVoter?.is_present">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 transition-colors">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Belum Hadir
                                    </span>
                                </template>
                            </div>
                            <div class="text-[11px] text-slate-500 pt-0.5">
                                <span class="text-slate-400 text-[10px] block">Waktu Presensi:</span>
                                <span class="font-medium font-mono text-slate-700" x-text="selectedVoter?.verified_at || '-'"></span>
                            </div>
                        </div>

                    </div>

                    <!-- Card 3: Pratinjau Tanda Tangan (Jika Ada) -->
                    <template x-if="selectedVoter?.signature_url">
                        <div class="p-2.5 rounded-xl border border-slate-200/80 bg-white shadow-2xs flex items-center justify-between gap-3">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Tanda Tangan Digital</span>
                                <span class="text-[11px] text-slate-500">Tercatat saat presensi di Buku Tamu TPS</span>
                            </div>
                            <div class="p-1 bg-slate-50 rounded-lg border border-slate-200 shrink-0">
                                <img :src="selectedVoter?.signature_url" alt="Tanda Tangan" class="h-9 max-w-[120px] object-contain">
                            </div>
                        </div>
                    </template>

                    <!-- Card 4: Prinsip Kerahasiaan Suara (Compact Notice) -->
                    <div class="p-2.5 rounded-xl bg-amber-50/70 border border-amber-200/70 flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#CE9501] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <div class="text-[11px] text-amber-900 leading-tight">
                            <strong class="font-bold">Prinsip Kerahasiaan (Luber Jurdil):</strong>
                            Pilihan paslon BEM bersifat rahasia murni (Anonymous Ballot) dan tidak dapat diakses atau dihubungkan ke identitas mahasiswa.
                        </div>
                    </div>

                </div>

                <!-- Modal Footer (Compact) -->
                <div class="px-5 py-2.5 border-t border-slate-100 bg-slate-50/70 flex items-center justify-end shrink-0">
                    <button type="button" @click="closeDetail()"
                        class="px-4 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Modal Import Data Mahasiswa (DPT) -->
    <template x-teleport="body">
        <div x-show="isImportOpen" 
            x-cloak
            class="fixed inset-0 z-[100] overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full flex flex-col overflow-hidden"
                @click.away="if (!isUploading) isImportOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Modal Header: Bersih & Tenang -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight">Import Data Mahasiswa</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Unggah daftar pemilih DPT secara massal</p>
                    </div>
                    <button type="button" @click="isImportOpen = false" :disabled="isUploading"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer disabled:opacity-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Form Upload -->
                <form action="{{ route('admin.voters.import') }}" method="POST" enctype="multipart/form-data" 
                    @submit="isUploading = true"
                    class="flex flex-col">
                    @csrf
                    @if($period)
                        <input type="hidden" name="period_id" value="{{ $period->id }}">
                    @endif

                    <div class="p-6 space-y-4">
                        
                        <!-- Area Berkas & Link Download Template Bersih -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold text-slate-700">Berkas Data</label>
                                <a href="{{ route('admin.voters.template') }}" 
                                    class="text-xs font-semibold text-[#940C10] hover:text-[#7A0A0D] hover:underline inline-flex items-center gap-1.5 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    <span>Unduh Template CSV</span>
                                </a>
                            </div>

                            <!-- Dropzone File Upload Ramping & Minimalis -->
                            <div class="relative border-2 border-dashed border-slate-300 hover:border-[#940C10] rounded-xl py-7 px-4 text-center transition-all bg-slate-50/60 hover:bg-red-50/15 cursor-pointer group"
                                @dragover.prevent=""
                                @drop.prevent="
                                    $refs.fileInput.files = $event.dataTransfer.files;
                                    handleFileSelect({ target: $refs.fileInput });
                                ">
                                <input type="file" name="file" x-ref="fileInput" 
                                    @change="handleFileSelect($event)"
                                    accept=".csv,.sql,.json,.txt,.tsv"
                                    required
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                <template x-if="!selectedFileName">
                                    <div class="space-y-1.5 pointer-events-none">
                                        <svg class="w-8 h-8 mx-auto text-slate-400 group-hover:text-[#940C10] transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="12" x2="12" y2="15"></line>
                                        </svg>
                                        <p class="text-xs text-slate-600">
                                            <span class="font-bold text-[#940C10]">Pilih file</span> atau seret file ke sini
                                        </p>
                                        <p class="text-[11px] text-slate-400">
                                            Format: CSV (Excel), SQL, atau JSON (Maks. 250MB)
                                        </p>
                                    </div>
                                </template>

                                <template x-if="selectedFileName">
                                    <div class="space-y-1 pointer-events-none py-1">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-semibold">
                                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span x-text="selectedFileName" class="max-w-[240px] truncate"></span>
                                            <span class="text-slate-400 text-[10px]" x-text="'(' + selectedFileSize + ')'"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-400 font-medium">Klik untuk mengganti berkas</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Opsi Checkbox & Petunjuk Ringkas -->
                        <div class="space-y-2 pt-1">
                            <label class="flex items-start gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="update_existing" value="1" checked
                                    class="mt-0.5 rounded border-slate-300 text-[#940C10] focus:ring-[#940C10] focus:ring-offset-0">
                                <span class="text-xs text-slate-700 leading-snug font-medium">
                                    Perbarui data profil jika NIM sudah ada di database
                                </span>
                            </label>

                            <p class="text-[11px] text-slate-400 pl-6 leading-relaxed">
                                Format kolom: <span class="font-mono text-slate-600">nim, nama, program_studi, kelas, semester, asal_kampus</span>
                            </p>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-end gap-2.5 shrink-0">
                        <button type="button" @click="isImportOpen = false" :disabled="isUploading"
                            class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all cursor-pointer disabled:opacity-50">
                            Batal
                        </button>
                        <button type="submit" :disabled="isUploading || !selectedFileName"
                            class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-[#940C10] hover:bg-[#7A0A0D] active:scale-95 text-white text-xs font-bold shadow-xs transition-all cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                            <template x-if="isUploading">
                                <svg class="w-4 h-4 animate-spin text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <span x-text="isUploading ? 'Mengimpor...' : 'Mulai Import'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

<script>
window.votersLiveManager = function votersLiveManager() {
    return {
        isDetailOpen: false,
        selectedVoter: null,
        isImportOpen: false,
        isUploading: false,
        selectedFileName: '',
        selectedFileSize: '',
        isLive: true,
        isSyncing: false,
        timer: null,
        lastSync: '{{ now()->timezone("Asia/Jakarta")->format("H:i:s") }} WIB',
        stats: {
            total_students: {{ $stats['total_students'] ?? 0 }},
            total_present: {{ $stats['total_present'] ?? 0 }},
            total_voted: {{ $stats['total_voted'] ?? 0 }},
            total_not_voted: {{ $stats['total_not_voted'] ?? 0 }}
        },
        totalCount: {{ $participations->total() }},
        firstItem: {{ $participations->firstItem() ?? 0 }},
        lastItem: {{ $participations->lastItem() ?? 0 }},
        currentPageCount: {{ $participations->count() }},

        init() {
            this.bindEventDelegation();
            this.startLiveSync();
        },

        handleFileSelect(event) {
            const file = event.target.files && event.target.files[0];
            if (file) {
                this.selectedFileName = file.name;
                const sizeKb = Math.round(file.size / 1024);
                this.selectedFileSize = sizeKb > 1024 
                    ? (sizeKb / 1024).toFixed(2) + ' MB' 
                    : sizeKb + ' KB';
            } else {
                this.selectedFileName = '';
                this.selectedFileSize = '';
            }
        },

        bindEventDelegation() {
            // Event delegation untuk tombol detail pada baris tabel (aman terhadap pembaruan HTML)
            const tbody = document.getElementById('voters-table-body');
            if (tbody && !tbody._hasDetailListener) {
                tbody._hasDetailListener = true;
                tbody.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-voter-btn]');
                    if (btn && btn.dataset.voter) {
                        try {
                            const data = JSON.parse(btn.dataset.voter);
                            this.openDetail(data);
                        } catch(err) {
                            console.error('Failed to parse voter data:', err);
                        }
                    }
                });
            }
        },

        startLiveSync() {
            if (this.timer) clearInterval(this.timer);
            // Polling background setiap 3 detik tanpa reload halaman
            this.timer = setInterval(() => {
                if (this.isLive) {
                    this.syncNow();
                }
            }, 3000);
        },

        toggleLive() {
            this.isLive = !this.isLive;
            if (this.isLive) {
                this.syncNow();
            }
        },

        openDetail(voter) {
            this.selectedVoter = voter;
            this.isDetailOpen = true;
        },

        closeDetail() {
            this.isDetailOpen = false;
        },

        async syncNow() {
            if (this.isSyncing) return;

            try {
                this.isSyncing = true;
                const searchParams = new URLSearchParams(window.location.search);
                searchParams.set('live', '1');
                searchParams.set('_t', Date.now().toString());
                const url = `${window.location.pathname}?${searchParams.toString()}`;

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    cache: 'no-store'
                });

                if (!response.ok) return;
                const data = await response.json();

                // 1. Perbarui ringkasan statistik & angka counter tab
                if (data.stats) {
                    this.stats = data.stats;
                    this.totalCount = data.total;
                    this.firstItem = data.first_item;
                    this.lastItem = data.last_item;
                    this.currentPageCount = data.count;
                    this.lastSync = data.timestamp;
                }

                // 2. Perbarui baris tabel pemilih secara real-time
                if (data.table_html) {
                    const tbody = document.getElementById('voters-table-body');
                    if (tbody) {
                        tbody.innerHTML = data.table_html;
                    }
                    if (typeof data.pagination_html !== 'undefined') {
                        const pagContainer = document.getElementById('voters-pagination');
                        if (pagContainer && data.pagination_html) {
                            pagContainer.innerHTML = data.pagination_html;
                        }
                    }
                }

                // 3. Jika modal detail sedang terbuka, perbarui data mahasiswa yang dilihat secara live
                if (this.isDetailOpen && this.selectedVoter && data.voters_data && data.voters_data[this.selectedVoter.nim]) {
                    const latestVoter = data.voters_data[this.selectedVoter.nim];
                    this.selectedVoter = Object.assign({}, this.selectedVoter, latestVoter);
                }
            } catch (err) {
                console.warn('Live auto-sync error:', err);
            } finally {
                setTimeout(() => { this.isSyncing = false; }, 400);
            }
        },

        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        }
    }
}
</script>
@endsection
