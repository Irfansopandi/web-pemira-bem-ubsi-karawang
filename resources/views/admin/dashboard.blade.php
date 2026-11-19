@extends('layouts.admin', ['title' => 'Dashboard Kilasan'])

@section('content')
<div id="dashboard-live-container" x-data="liveDashboard()" class="space-y-6 sm:space-y-8">

    @php
        $hour = now()->timezone('Asia/Jakarta')->hour;
        $greeting = match(true) {
            $hour >= 4 && $hour < 11 => 'Selamat Pagi',
            $hour >= 11 && $hour < 15 => 'Selamat Siang',
            $hour >= 15 && $hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };
        $admin = Auth::guard('admin')->user();
        $adminName = $admin->name ?? 'Administrator';
    @endphp

    <!-- ======================================================== -->
    <!-- 1. GREETING & SAPAAN ADMINISTRATOR (CLEAN & BERSIH)      -->
    <!-- ======================================================== -->
    <div class="space-y-1.5 sm:space-y-2 pb-1">
        <!-- Nama Periode Pemilihan PEMIRA -->
        @if($period)
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">
                    {{ $period->name }}
                </span>
            </div>
        @endif

        <!-- Teks Sapaan Utama dengan Icon Waktu (Hitam Tanpa Border Sesuai Tema) -->
        <h2 class="text-xl sm:text-2xl xl:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug flex items-center gap-2.5 sm:gap-3 flex-wrap">
            @if($hour >= 4 && $hour < 15)
                <!-- Icon Matahari (Pagi & Siang) - Hitam Tanpa Border -->
                <svg class="w-6 h-6 sm:w-7 sm:h-7 xl:w-8 xl:h-8 text-slate-900 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                </svg>
            @elseif($hour >= 15 && $hour < 18)
                <!-- Icon Sunset / Senja (Sore) - Hitam Tanpa Border -->
                <svg class="w-6 h-6 sm:w-7 sm:h-7 xl:w-8 xl:h-8 text-slate-900 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M17 18a5 5 0 0 0-10 0"></path>
                    <line x1="12" y1="9" x2="12" y2="2"></line>
                    <line x1="4.22" y1="10.22" x2="5.64" y2="11.64"></line>
                    <line x1="1" y1="18" x2="3" y2="18"></line>
                    <line x1="21" y1="18" x2="23" y2="18"></line>
                    <line x1="18.36" y1="11.64" x2="19.78" y2="10.22"></line>
                    <line x1="23" y1="22" x2="1" y2="22"></line>
                </svg>
            @else
                <!-- Icon Bulan (Malam) - Hitam Tanpa Border -->
                <svg class="w-6 h-6 sm:w-7 sm:h-7 xl:w-8 xl:h-8 text-slate-900 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            @endif
            <span>{{ $greeting }}, <span class="text-[#940C10]">{{ $adminName }}</span>!</span>
        </h2>

        <!-- Keterangan Pengantar yang Lebih Ringkas & Bersih -->
        <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed max-w-2xl">
            Selamat datang di Dashboard Utama PEMIRA BEM UBSI Karawang. Berikut ringkasan kilasan pesta demokrasi kampus hari ini.
        </p>
    </div>

    @if($period)
        <!-- ======================================================== -->
        <!-- 2. KARTU STATISTIK UTAMA (4 KPI METRICS - HORIZONTAL CARD) -->
        <!-- ======================================================== -->
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

            <!-- 3. Belum Memilih (MERAH) -->
            <a href="{{ route('admin.voters.index', ['status' => 'not_voted']) }}"
                class="bg-[#fff8f8] p-4 sm:p-4.5 rounded-2xl border-2 border-[#940C10]/30 hover:border-[#940C10]/75 active:border-[#940C10] active:ring-2 active:ring-[#940C10]/30 transition-all duration-150 shadow-2xs group flex items-center justify-between gap-3 select-none">
                <div class="flex items-center gap-3.5 min-w-0">
                    <!-- Icon Box -->
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-[#fcedee] text-[#940C10] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <!-- Info Stack -->
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-slate-500 truncate">Belum Memilih</p>
                        <p class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight tracking-tight mt-0.5" x-text="formatNumber(stats.total_not_voted)">
                            {{ number_format($stats['total_not_voted']) }}
                        </p>
                        <p class="text-[11px] text-slate-400 font-normal truncate mt-0.5">Sisa Hak Suara</p>
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
            <a href="{{ route('admin.elections.monitoring') }}"
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
                        <p class="text-[11px] text-slate-400 font-normal truncate mt-0.5">Persentase Masuk</p>
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
        @endif

        <!-- ======================================================== -->
        <!-- 3. GRID 2 KOLOM: KILASAN PEROLEHAN SUARA & PINTASAN CEPAT -->
        <!-- ======================================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Kolom Kiri (2 Kolom): Kilasan Suara Paslon Sementara -->
            <div class="lg:col-span-2 bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs flex flex-col justify-between space-y-6">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 flex-wrap gap-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Kilasan Rekapitulasi Suara Paslon</h3>
                            <p class="text-xs text-slate-500">Perhitungan suara langsung dari kotak suara anonim</p>
                        </div>
                        <a href="{{ route('admin.elections.results') }}" 
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-[#940c10] hover:text-[#7A0A0D] hover:underline">
                            <span>Buka Hasil Lengkap</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </div>

                    <div class="space-y-4 mt-5">
                        <template x-for="item in tally" :key="item.id">
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-2.5 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <!-- Foto Paslon Unggahan (dengan fallback nomor urut) -->
                                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl overflow-hidden border border-slate-200/90 shadow-2xs bg-slate-100 shrink-0 relative flex items-center justify-center">
                                            <img x-show="item.photo_url"
                                                 :src="item.photo_url" 
                                                 :alt="item.chairman_name" 
                                                 class="w-full h-full object-cover object-top"
                                                 x-on:error="$el.style.display='none'">
                                            <div x-show="!item.photo_url"
                                                 class="w-full h-full bg-[#940c10] text-[#CE9501] font-bold text-xs flex items-center justify-center"
                                                 x-text="'0' + item.candidate_number">
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900" x-text="item.chairman_name + ' & ' + item.vice_chairman_name"></p>
                                            <span class="text-[10px] text-slate-500" x-text="'Kandidat Paslon No. 0' + item.candidate_number"></span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-extrabold text-[#940c10]" x-text="item.percentage + '%'"></span>
                                        <p class="text-[10px] text-slate-500 font-semibold" x-text="formatNumber(item.total_votes) + ' Suara'"></p>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-[#940c10] to-[#CE9501] h-2.5 rounded-full transition-all duration-700 ease-out"
                                        :style="'width: ' + item.percentage + '%'"></div>
                                </div>
                            </div>
                        </template>
                        <template x-if="tally.length === 0">
                            <div class="text-center py-8 text-slate-400 text-xs">
                                Belum ada kandidat paslon atau suara masuk pada periode ini.
                            </div>
                        </template>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="font-medium text-slate-600">
                            Live Sync: <span class="text-emerald-700 font-semibold" x-text="isSyncing ? 'Sinkronisasi...' : 'Aktif (' + lastSync + ')'">Aktif</span>
                        </span>
                    </span>
                    <a href="{{ route('admin.candidates.index') }}" class="font-semibold text-slate-700 hover:text-[#940c10]">
                        Kelola Profil & Visi Misi Paslon &rarr;
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan (1 Kolom): Pintasan Cepat Menu Pengelolaan -->
            <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Pintasan Cepat</h3>
                    <p class="text-xs text-slate-500">Akses langsung ke menu pengelolaan utama</p>
                </div>

                <div class="space-y-2.5">
                    <!-- 1. Kelola Paslon -->
                    <a href="{{ route('admin.candidates.index') }}"
                        class="p-2.5 sm:p-3 rounded-xl border-2 border-[#940C10]/25 hover:border-[#940C10]/70 active:border-[#940C10] active:ring-2 active:ring-[#940C10]/20 bg-[#fff8f8]/60 hover:bg-[#fff8f8] flex items-center justify-between group transition-all select-none">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#fcedee] text-[#940C10] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 group-hover:text-[#940C10] transition-colors">Kelola Paslon</p>
                                <span class="text-[10px] text-slate-400">Visi, misi, dan nomor urut</span>
                            </div>
                        </div>
                        <svg class="w-3.5 h-3.5 text-[#940C10] group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>

                    <!-- 2. Kelola DPT & Kehadiran -->
                    <a href="{{ route('admin.voters.index') }}"
                        class="p-2.5 sm:p-3 rounded-xl border-2 border-[#940C10]/25 hover:border-[#940C10]/70 active:border-[#940C10] active:ring-2 active:ring-[#940C10]/20 bg-[#fff8f8]/60 hover:bg-[#fff8f8] flex items-center justify-between group transition-all select-none">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#fcedee] text-[#940C10] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                    <path d="M9 14l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 group-hover:text-[#940C10] transition-colors">Kelola DPT & Kehadiran</p>
                                <span class="text-[10px] text-slate-400">Filter pemilih & status suara</span>
                            </div>
                        </div>
                        <svg class="w-3.5 h-3.5 text-[#940C10] group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>

                    <!-- 3. Monitoring TPS -->
                    <a href="{{ route('admin.elections.monitoring') }}"
                        class="p-2.5 sm:p-3 rounded-xl border-2 border-[#940C10]/25 hover:border-[#940C10]/70 active:border-[#940C10] active:ring-2 active:ring-[#940C10]/20 bg-[#fff8f8]/60 hover:bg-[#fff8f8] flex items-center justify-between group transition-all select-none">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#fcedee] text-[#940C10] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 group-hover:text-[#940C10] transition-colors">Monitoring TPS</p>
                                <span class="text-[10px] text-slate-400">Arus kehadiran pemilih live</span>
                            </div>
                        </div>
                        <svg class="w-3.5 h-3.5 text-[#940C10] group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>

                    <!-- 4. Audit Trail & Log -->
                    <a href="{{ route('admin.audit.index') }}"
                        class="p-2.5 sm:p-3 rounded-xl border-2 border-[#940C10]/25 hover:border-[#940C10]/70 active:border-[#940C10] active:ring-2 active:ring-[#940C10]/20 bg-[#fff8f8]/60 hover:bg-[#fff8f8] flex items-center justify-between group transition-all select-none">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#fcedee] text-[#940C10] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 group-hover:text-[#940C10] transition-colors">Audit Trail & Log</p>
                                <span class="text-[10px] text-slate-400">Jejak aktivitas keamanan</span>
                            </div>
                        </div>
                        <svg class="w-3.5 h-3.5 text-[#940C10] group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- 4. GRID 2 KOLOM BAWAH: KILASAN PARTISIPASI & AUDIT TERKINI -->
        <!-- ======================================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Kilasan 5 Partisipasi Terkini -->
            <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-wrap gap-2">
                    <div class="flex items-center gap-2.5">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Partisipasi Pemilih Terkini</h3>
                            <p class="text-[11px] text-slate-500">5 suara terakhir yang masuk ke sistem</p>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            LIVE
                        </span>
                    </div>
                    <a href="{{ route('admin.voters.index') }}" class="text-xs font-bold text-[#940c10] hover:underline">
                        Lihat Seluruh DPT &rarr;
                    </a>
                </div>

                <div class="space-y-2.5">
                    <template x-for="(part, idx) in recent.slice(0, 5)" :key="part.nim + '-' + idx">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs transition-all">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate" x-text="part.name"></p>
                                    <span class="text-[10px] text-slate-400 font-mono" x-text="part.nim + ' • ' + part.study_program"></span>
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400 font-mono shrink-0 pl-2" x-text="part.voted_at_diff"></span>
                        </div>
                    </template>
                    <template x-if="recent.length === 0">
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada suara masuk pada periode ini.</p>
                    </template>
                </div>
            </div>

            <!-- Kilasan 5 Log Aktivitas Sistem Terkini -->
            <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Aktivitas Sistem Terkini</h3>
                        <p class="text-[11px] text-slate-500">5 aksi log audit keamanan terakhir</p>
                    </div>
                    <a href="{{ route('admin.audit.index') }}" class="text-xs font-bold text-[#940c10] hover:underline">
                        Buka Audit Trail &rarr;
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($recentLogs as $log)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-red-100 text-[#940c10] flex items-center justify-center font-bold shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate">{{ $log->action }}</p>
                                    <span class="text-[10px] text-slate-400">oleh {{ $log->admin->name ?? 'System' }} &bull; IP: {{ $log->ip_address ?? '127.0.0.1' }}</span>
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400 font-mono shrink-0 pl-2">
                                {{ $log->created_at ? $log->created_at->diffForHumans() : '-' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada aktivitas audit tercatat.</p>
                    @endforelse
                </div>
            </div>

        </div>
    @endif

</div>

@php
    $recentFormatted = $recentParticipations ? $recentParticipations->map(function($p) {
        return [
            'name' => $p->student->name ?? 'Mahasiswa',
            'nim' => $p->student_nim,
            'study_program' => $p->student->study_program ?? '-',
            'voted_at_diff' => $p->voted_at ? $p->voted_at->diffForHumans() : '-',
        ];
    }) : [];
@endphp

<script>
function liveDashboard() {
    return {
        stats: {
            total_students: {{ $stats['total_students'] ?? 0 }},
            total_voted: {{ $stats['total_voted'] ?? 0 }},
            total_not_voted: {{ $stats['total_not_voted'] ?? 0 }},
            turnout_percentage: {{ $stats['turnout_percentage'] ?? 0 }}
        },
        tally: @json($tally ?? []),
        recent: @json($recentFormatted),
        lastSync: '{{ now()->timezone("Asia/Jakarta")->format("H:i:s") }} WIB',
        isSyncing: false,
        timer: null,
        periodId: '{{ $period->id ?? "" }}',
        liveUrl: '{{ route("admin.elections.live-data") }}',

        init() {
            if (!this.periodId) return;
            if (this.timer) clearInterval(this.timer);
            // Polling background setiap 3.5 detik tanpa reload halaman
            this.timer = setInterval(() => {
                if (!document.getElementById('dashboard-live-container')) {
                    clearInterval(this.timer);
                    return;
                }
                this.syncNow();
            }, 3500);
        },

        async syncNow() {
            if (!this.periodId) return;
            try {
                this.isSyncing = true;
                const response = await fetch(`${this.liveUrl}?period_id=${this.periodId}&limit=5`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) return;
                const data = await response.json();
                if (data.stats) {
                    this.stats = data.stats;
                    this.tally = data.tally;
                    this.recent = data.recent_participations || [];
                    this.lastSync = data.timestamp;
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
