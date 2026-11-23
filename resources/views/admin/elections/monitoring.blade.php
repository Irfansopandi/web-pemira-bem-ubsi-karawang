@extends('layouts.admin', ['title' => 'Monitoring Pemungutan Suara'])

@section('content')
<div id="monitoring-live-container" x-data="liveMonitoring()" class="space-y-6 sm:space-y-8">

    <!-- Header Halaman -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 sm:gap-6">
        <div class="flex items-start sm:items-center gap-3.5 sm:gap-4">
            <!-- Icon Tanpa Border Sesuai Standar Minimalis UBSI -->
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-[#940C10] shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Monitoring & Laporan</span>
                    @if($period)
                        <span class="text-slate-300">•</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ ($period->isSystemActive() ?? $period->is_active) ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ ($period->isSystemActive() ?? $period->is_active) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                            Periode: {{ $period->academic_year ?? $period->name }}
                        </span>
                    @endif
                    <span class="text-slate-300">•</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span x-text="isSyncing ? 'SINKRONISASI...' : 'LIVE AUTO-SYNC'">LIVE AUTO-SYNC</span>
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">
                    Monitoring Pemungutan Suara
                </h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    Pemantauan Partisipasi Mahasiswa, Suara Sah & Rekapitulasi Realtime
                    <span class="text-slate-300 mx-1">•</span>
                    Terakhir disinkron: <span class="font-semibold text-slate-700 font-mono" x-text="lastSync">Live</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 sm:gap-3 shrink-0 flex-wrap sm:flex-nowrap w-full sm:w-auto">
            <button @click="syncNow()" :disabled="isSyncing" type="button"
                class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200/90 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-2xs cursor-pointer disabled:opacity-50">
                <svg class="w-4 h-4 text-slate-500 transition-transform duration-300" :class="isSyncing ? 'animate-spin text-[#940C10]' : ''" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                </svg>
                <span x-text="isSyncing ? 'Sinkronisasi...' : 'Sinkronkan Sekarang'">Sinkronkan Sekarang</span>
            </button>
            <a href="#hasil-rekapitulasi"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#940C10] hover:bg-[#7A0A0D] active:scale-95 text-white text-xs font-bold shadow-xs hover:shadow-md transition-all">
                <svg class="w-4 h-4 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                <span>Lihat Rekapitulasi</span>
            </a>
        </div>
    </div>

    <!-- Stat Metrik Grid (Sesuai Desain Interaktif Dashboard) -->
    @if($stats)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4 xl:gap-5">
        <!-- 1. Total DPT (MERAH) -->
        <a href="{{ route('admin.voters.index') }}"
            class="bg-[#fff8f8] p-4 sm:p-4.5 rounded-2xl border-2 border-[#940C10]/30 hover:border-[#940C10]/75 active:border-[#940C10] active:ring-2 active:ring-[#940C10]/30 transition-all duration-150 shadow-2xs group flex items-center justify-between gap-3 select-none">
            <div class="flex items-center gap-3.5 min-w-0">
                <!-- Icon Box -->
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#fcedee] text-[#940C10] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                    </svg>
                </div>
                <!-- Info Stack -->
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 truncate">Total DPT</p>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight tracking-tight mt-0.5" x-text="formatNumber(stats.total_students)">
                        {{ number_format($stats['total_students']) }}
                    </p>
                    <p class="text-[11px] text-slate-400 font-normal truncate mt-0.5">Mahasiswa Terdaftar</p>
                </div>
            </div>
            <!-- Chevron Right -->
            <div class="text-[#940C10] group-hover:translate-x-1 transition-transform shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        <!-- 2. Suara Masuk (KUNING) -->
        <a href="{{ route('admin.voters.index', ['status' => 'voted']) }}"
            class="bg-[#fffdf5] p-4 sm:p-4.5 rounded-2xl border-2 border-[#CE9501]/35 hover:border-[#CE9501]/85 active:border-[#CE9501] active:ring-2 active:ring-[#CE9501]/30 transition-all duration-150 shadow-2xs group flex items-center justify-between gap-3 select-none">
            <div class="flex items-center gap-3.5 min-w-0">
                <!-- Icon Box -->
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#fef7e2] text-[#CE9501] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="m9 12 2 2 4-4"></path>
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                    </svg>
                </div>
                <!-- Info Stack -->
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 truncate">Suara Masuk</p>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight tracking-tight mt-0.5" x-text="formatNumber(stats.total_voted)">
                        {{ number_format($stats['total_voted']) }}
                    </p>
                    <p class="text-[11px] text-slate-400 font-normal truncate mt-0.5">Surat Suara Sah</p>
                </div>
            </div>
            <!-- Chevron Right -->
            <div class="text-[#CE9501] group-hover:translate-x-1 transition-transform shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        <!-- 3. Sudah Hadir di TPS (MERAH) -->
        <a href="{{ route('admin.voters.index', ['status' => 'present']) }}"
            class="bg-[#fff8f8] p-4 sm:p-4.5 rounded-2xl border-2 border-[#940C10]/30 hover:border-[#940C10]/75 active:border-[#940C10] active:ring-2 active:ring-[#940C10]/30 transition-all duration-150 shadow-2xs group flex items-center justify-between gap-3 select-none">
            <div class="flex items-center gap-3.5 min-w-0">
                <!-- Icon Box -->
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#fcedee] text-[#940C10] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <polyline points="16 11 18 13 22 9"></polyline>
                    </svg>
                </div>
                <!-- Info Stack -->
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 truncate">Sudah Hadir</p>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight tracking-tight mt-0.5" x-text="formatNumber(stats.total_present)">
                        {{ number_format($stats['total_present'] ?? 0) }}
                    </p>
                    <p class="text-[11px] text-slate-400 font-normal truncate mt-0.5">Terverifikasi TPS</p>
                </div>
            </div>
            <!-- Chevron Right -->
            <div class="text-[#940C10] group-hover:translate-x-1 transition-transform shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        <!-- 4. Partisipasi (KUNING) -->
        <a href="#progres-kehadiran"
            class="bg-[#fffdf5] p-4 sm:p-4.5 rounded-2xl border-2 border-[#CE9501]/35 hover:border-[#CE9501]/85 active:border-[#CE9501] active:ring-2 active:ring-[#CE9501]/30 transition-all duration-150 shadow-2xs group flex items-center justify-between gap-3 select-none">
            <div class="flex items-center gap-3.5 min-w-0">
                <!-- Icon Box -->
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#fef7e2] text-[#CE9501] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <!-- Info Stack -->
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 truncate">Tingkat Partisipasi</p>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight tracking-tight mt-0.5" x-text="stats.turnout_percentage + '%'">
                        {{ $stats['turnout_percentage'] }}%
                    </p>
                    <p class="text-[11px] text-slate-400 font-normal truncate mt-0.5">Kehadiran TPS</p>
                </div>
            </div>
            <!-- Chevron Right -->
            <div class="text-[#CE9501] group-hover:translate-x-1 transition-transform shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
    </div>

    <!-- Turnout Visual Progress Bar -->
    <div id="progres-kehadiran" class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Progres Kehadiran Pemilih</h3>
                <p class="text-xs text-slate-500">
                    <span x-text="formatNumber(stats.total_present)">{{ number_format($stats['total_present']) }}</span> dari 
                    <span x-text="formatNumber(stats.total_students)">{{ number_format($stats['total_students']) }}</span> mahasiswa hadir di TPS
                    <span class="text-slate-400">(<span x-text="formatNumber(stats.total_voted)">{{ number_format($stats['total_voted']) }}</span> suara sah masuk)</span>
                </p>
            </div>
            <span class="text-xl font-black text-[#940c10]" x-text="stats.turnout_percentage + '%'">{{ $stats['turnout_percentage'] }}%</span>
        </div>
        <div class="w-full bg-slate-100 rounded-full h-4 overflow-hidden p-0.5 border border-slate-200">
            <div class="bg-gradient-to-r from-[#940c10] to-[#CE9501] h-3 rounded-full transition-all duration-700 ease-out"
                :style="'width: ' + stats.turnout_percentage + '%'"></div>
        </div>
    </div>
    @endif

    <!-- Section: Hasil Rekapitulasi Suara Paslon (Card Foto Full Sesuai Kelola Paslon + Presentase Bawah & Badge Bersih Tanpa Slop) -->
    <div id="hasil-rekapitulasi" class="space-y-4 pt-2">
        <div class="flex items-center justify-between flex-wrap gap-3 pb-1">
            <div>
                <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">Hasil Rekapitulasi Suara Paslon</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Perolehan suara berdasarkan kehadiran pemilih di TPS (diperbarui realtime)</p>
            </div>
            
            <!-- Badge Bersih Tanpa Border Card Bulky / Tanpa AI Slop -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-100 border border-slate-200/90 text-xs font-bold text-slate-800 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-[#940C10] animate-pulse"></span>
                <span>Total Suara Masuk: <strong class="text-[#940C10] font-black" x-text="formatNumber(stats.total_voted)">{{ number_format($stats['total_voted'] ?? 0) }}</strong></span>
            </div>
        </div>

        <!-- Candidate Pairs Grid (Card Full Foto Sesuai Kelola Paslon + Presentase di Bawah) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7">
            <template x-for="item in tally" :key="item.id">
                <div class="group relative block aspect-[3/4] sm:aspect-[4/5] rounded-3xl overflow-hidden shadow-xl shadow-black/40 transition-all duration-300 border border-slate-200/90 hover:border-[#CE9501]/80 bg-gradient-to-b from-[#52000D] via-[#7A0A0D] to-[#940C10] transform hover:-translate-y-1.5">
                    
                    <!-- Badge Nomor Paslon di Pojok Kiri Atas -->
                    <div class="absolute top-3.5 left-3.5 z-20">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-3.5 sm:py-1 rounded-xl bg-[#940C10]/95 border border-[#CE9501] shadow-lg shadow-black/50 backdrop-blur-xs">
                            <span class="text-[11px] sm:text-xs font-bold text-white tracking-wide">Paslon No.</span>
                            <span class="text-xs sm:text-sm font-black text-[#CE9501]" x-text="'0' + item.candidate_number"></span>
                        </div>
                    </div>

                    <!-- Badge Persentase Live di Pojok Kanan Atas -->
                    <div class="absolute top-3.5 right-3.5 z-20">
                        <div class="inline-flex items-center gap-1 px-3 py-1 sm:px-3.5 sm:py-1 rounded-xl bg-black/75 border border-white/20 shadow-lg shadow-black/50 backdrop-blur-xs">
                            <span class="text-xs sm:text-sm font-black text-[#CE9501]" x-text="item.percentage + '%'"></span>
                        </div>
                    </div>

                    <!-- Foto Paslon Full Card (Storage & Fallback) -->
                    <img :src="item.photo_url || ('/images/paslon-0' + item.candidate_number + '.png')" 
                         x-on:error="$el.src = '/images/paslon-0' + item.candidate_number + '.png'"
                         :alt="'Paslon No. 0' + item.candidate_number" 
                         class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105">

                    <!-- Gradient Overlay & Info Paslon di Bagian Bawah -->
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/95 via-black/85 to-transparent p-3.5 sm:p-4 text-white pt-24 z-10 flex flex-col justify-end">
                        <!-- Nama Calon Ketua & Wakil Ketua Berdampingan dengan Garis Pemisah Vertikal Sesuai Halaman Voting -->
                        <div class="border-t border-white/20 pt-2.5 mb-2">
                            <div class="flex items-stretch justify-between gap-2 sm:gap-3">
                                <!-- Calon Ketua -->
                                <div class="flex-1 flex flex-col justify-end text-center pr-1 sm:pr-2 min-w-0">
                                    <h3 class="text-xs sm:text-sm font-bold text-white group-hover:text-[#CE9501] transition-colors leading-snug break-words"
                                        x-text="item.chairman_name">
                                    </h3>
                                    <p class="text-[10px] sm:text-[11px] font-medium text-[#CE9501] mt-0.5 whitespace-nowrap">
                                        Calon Ketua BEM
                                    </p>
                                </div>

                                <!-- Garis Pemisah Vertikal Halus (Tinggi Menyesuaikan Teks Otomatis) -->
                                <div class="self-stretch w-px bg-white/20 shrink-0 my-0.5"></div>

                                <!-- Calon Wakil Ketua -->
                                <div class="flex-1 flex flex-col justify-end text-center pl-1 sm:pl-2 min-w-0">
                                    <h3 class="text-xs sm:text-sm font-bold text-white group-hover:text-[#CE9501] transition-colors leading-snug break-words"
                                        x-text="item.vice_chairman_name">
                                    </h3>
                                    <p class="text-[10px] sm:text-[11px] font-medium text-[#CE9501] mt-0.5 whitespace-nowrap">
                                        Calon Wakil Ketua BEM
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Presentase, Jumlah Suara & Progress Bar di Bagian Bawah -->
                        <div class="pt-2 border-t border-white/20 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-xl sm:text-2xl font-black text-[#CE9501]" x-text="item.percentage + '%'"></span>
                                    <span class="text-xs text-slate-300 font-medium" x-text="'(' + formatNumber(item.total_votes) + ' Suara)'"></span>
                                </div>
                                <span class="text-[11px] text-slate-300 font-mono" x-show="item.total_present" x-text="'dari ' + formatNumber(item.total_present) + ' Hadir'"></span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2.5 overflow-hidden p-0.5">
                                <div class="bg-gradient-to-r from-amber-400 to-[#CE9501] h-1.5 rounded-full transition-all duration-700 ease-out"
                                     :style="'width: ' + item.percentage + '%'"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="tally.length === 0">
                <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-slate-200/90 text-slate-400 text-xs">
                    Belum ada data pasangan calon untuk periode ini.
                </div>
            </template>
        </div>
    </div>

    <!-- Section: Aktivitas Suara Sah (Stream TPS dengan Pagination 10 per halaman, terbaru di atas) -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-wrap gap-2">
            <div>
                <h3 class="text-base font-bold text-slate-900">Aktivitas Suara Sah</h3>
                <p class="text-xs text-slate-500">Pencoblosan suara sah terbaru di bilik suara TPS (urut dari yang paling baru)</p>
            </div>
            <a href="{{ route('admin.voters.index', ['status' => 'voted']) }}" 
                class="inline-flex items-center gap-1.5 text-xs font-bold text-[#940C10] hover:text-[#7A0A0D] no-underline group/link transition-colors">
                <span>Semua Suara Sah</span>
                <svg class="w-4 h-4 transform group-hover/link:translate-x-1.5 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <!-- List Suara Sah (Terbaru di atas, maks 10 per halaman) -->
        <div class="space-y-2.5 min-h-[300px]">
            <template x-for="(v, idx) in paginatedRecent" :key="v.nim + '-' + idx">
                <div class="p-3.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 border border-slate-100 flex items-center justify-between text-xs transition-all">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-slate-900 truncate" x-text="v.name"></p>
                            <span class="text-[10px] text-slate-400 font-mono" x-text="v.nim + ' • ' + v.study_program"></span>
                        </div>
                    </div>
                    <div class="text-right shrink-0 pl-3">
                        <span class="text-xs font-extrabold text-slate-800 font-mono block" x-text="v.voted_at_time"></span>
                        <span class="text-[10px] text-slate-400 block" x-text="v.voted_at_diff"></span>
                    </div>
                </div>
            </template>
            <template x-if="recent.length === 0">
                <p class="text-xs text-slate-400 text-center py-12">Belum ada suara sah tercatat di TPS.</p>
            </template>
        </div>

        <!-- Kontrol Pagination Aktivitas Suara Sah -->
        <div x-show="recent.length > 0" class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
            <div class="text-slate-500 text-[11px]">
                <span>Halaman <strong class="text-slate-800 font-bold" x-text="currentPage"></strong> dari <strong class="text-slate-800 font-bold" x-text="totalPages"></strong></span>
                <span class="text-slate-400">(Total <span x-text="recent.length"></span> pencoblos)</span>
            </div>

            <div class="flex items-center gap-1.5" x-show="totalPages > 1">
                <button type="button" 
                    @click="prevPage()" 
                    :disabled="currentPage === 1"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"></path></svg>
                    <span>Prev</span>
                </button>

                <button type="button" 
                    @click="nextPage()" 
                    :disabled="currentPage === totalPages"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-2xs">
                    <span>Next</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"></path></svg>
                </button>
            </div>
        </div>
    </div>

</div>

@php
    $recentFormatted = $recentVoters ? $recentVoters->map(function($v) {
        return [
            'name' => $v->student->name ?? 'Mahasiswa',
            'nim' => $v->student_nim,
            'study_program' => $v->student->study_program ?? '-',
            'voted_at_time' => $v->voted_at ? $v->voted_at->timezone('Asia/Jakarta')->format('H:i') . ' WIB' : '-',
            'voted_at_full' => $v->voted_at ? $v->voted_at->timezone('Asia/Jakarta')->format('H:i:s') . ' WIB' : '-',
            'voted_at_diff' => $v->voted_at ? $v->voted_at->diffForHumans() : '-',
        ];
    }) : [];
@endphp

<script>
window.liveMonitoring = function liveMonitoring() {
    return {
        stats: {
            total_students: {{ $stats['total_students'] ?? 0 }},
            total_voted: {{ $stats['total_voted'] ?? 0 }},
            total_not_voted: {{ $stats['total_not_voted'] ?? 0 }},
            total_present: {{ $stats['total_present'] ?? 0 }},
            turnout_percentage: {{ $stats['turnout_percentage'] ?? 0 }}
        },
        tally: @json($tally ?? []),
        recent: @json($recentFormatted),
        lastSync: '{{ now()->timezone("Asia/Jakarta")->format("H:i:s") }} WIB',
        isSyncing: false,
        timer: null,
        periodId: '{{ $period->id ?? "" }}',
        liveUrl: '{{ route("admin.elections.live-data") }}',

        // Pagination Aktivitas Suara Sah (10 per halaman)
        currentPage: 1,
        perPage: 10,

        get totalPages() {
            return Math.max(1, Math.ceil(this.recent.length / this.perPage));
        },

        get paginatedRecent() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.recent.slice(start, start + this.perPage);
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },

        init() {
            if (!this.periodId) return;
            if (this.timer) clearInterval(this.timer);
            // Polling background setiap 3 detik
            this.timer = setInterval(() => {
                if (!document.getElementById('monitoring-live-container')) {
                    clearInterval(this.timer);
                    return;
                }
                this.syncNow();
            }, 3000);
        },

        async syncNow() {
            if (!this.periodId) return;
            try {
                this.isSyncing = true;
                const response = await fetch(`${this.liveUrl}?period_id=${this.periodId}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) return;
                const data = await response.json();
                if (data.stats) {
                    this.stats = data.stats;
                    this.tally = data.tally;
                    this.recent = data.recent_participations || [];
                    this.lastSync = data.timestamp;
                    if (this.currentPage > this.totalPages) {
                        this.currentPage = this.totalPages;
                    }
                }
            } catch (err) {
                console.warn('Live poll error:', err);
            } finally {
                setTimeout(() => { this.isSyncing = false; }, 350);
            }
        },

        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        }
    }
}
</script>
@endsection
