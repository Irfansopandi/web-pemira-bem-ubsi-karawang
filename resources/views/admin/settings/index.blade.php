@extends('layouts.admin', ['title' => 'Pengaturan Sistem'])

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Header Halaman -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-start sm:items-center gap-3.5 sm:gap-4">
            <!-- Icon Tanpa Border Sesuai Standar Minimalis UBSI -->
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-[#940C10] shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Setting</span>
                    <span class="text-slate-300">•</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Status Sistem: Siap Beroperasi
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">Pengaturan Sistem PEMIRA</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Parameter konfigurasi platform pemilu raya, kebijakan integritas & lingkungan server UBSI Karawang</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 transition-all shadow-2xs">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"></path></svg>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Grid Pengaturan Sistem -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">

        <!-- Kolom Kiri: Konfigurasi Identitas Institusi & Parameter Pemilu (7 Cols) -->
        <div class="lg:col-span-7 space-y-6 sm:space-y-8">
            
            <!-- Identitas Kampus & Pelaksanaan -->
            <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        <h2 class="text-base font-black text-slate-900">Identitas Institusi & KPUM</h2>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-red-50 text-[#940C10] border border-red-200">
                        UBSI Karawang
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Perguruan Tinggi</span>
                        <p class="font-extrabold text-slate-900">Universitas Bina Sarana Informatika</p>
                        <p class="text-[11px] text-slate-500">Kampus Kabupaten Karawang</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Lembaga Penyelenggara</span>
                        <p class="font-extrabold text-slate-900">Panitia PEMIRA</p>
                        <p class="text-[11px] text-slate-500">BEM UBSI Karawang</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Periode Aktif Saat Ini</span>
                        <p class="font-extrabold text-[#940C10]">{{ $activePeriod ? $activePeriod->name : 'Belum Ada Periode Aktif' }}</p>
                        <p class="text-[11px] text-slate-500">Tahun Akademik: {{ $activePeriod ? $activePeriod->academic_year : '-' }}</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Riwayat Periode</span>
                        <p class="font-extrabold text-slate-900">{{ $totalPeriods }} Periode Terarsip</p>
                        <a href="{{ route('admin.periods.index') }}" class="text-[11px] font-bold text-[#940C10] hover:underline inline-block mt-0.5">
                            Kelola Periode Pemilihan &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kebijakan Keamanan & Prinsip Kerahasiaan -->
            <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <h2 class="text-base font-black text-slate-900">Protokol Keamanan & Integritas Pemilu</h2>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-800 border border-emerald-300">
                        Luber Jurdil
                    </span>
                </div>

                <div class="space-y-3.5">
                    <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50/70 border border-slate-200/80">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="text-xs">
                            <strong class="font-extrabold text-slate-900 block">Surat Suara Anonim Murni (Anonymous Ballot)</strong>
                            <p class="text-slate-600 text-[11px] mt-0.5 leading-relaxed">
                                Identitas mahasiswa (NIM) dipisahkan total dari kotak suara digital (`ballots`). Tidak ada kolom foreign key `student_nim` pada surat suara untuk menjamin kerahasiaan pilihan mutlak.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50/70 border border-slate-200/80">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="text-xs">
                            <strong class="font-extrabold text-slate-900 block">Kebijakan Zero-Guest Policy</strong>
                            <p class="text-slate-600 text-[11px] mt-0.5 leading-relaxed">
                                Seluruh akses root (`/`) otomatis dialihkan langsung ke halaman login bilik suara mahasiswa (`/login`). Seluruh informasi paslon bersifat terproteksi pasca autentikasi.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50/70 border border-slate-200/80">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="text-xs">
                            <strong class="font-extrabold text-slate-900 block">Proteksi Double Voting & Race Condition</strong>
                            <p class="text-slate-600 text-[11px] mt-0.5 leading-relaxed">
                                Transaksi pencoblosan diamankan dengan `DB::transaction` dan `lockForUpdate` pada record partisipasi pemilih. Suara hanya dapat dimasukkan satu kali per periode pemilihan.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50/70 border border-slate-200/80">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="text-xs">
                            <strong class="font-extrabold text-slate-900 block">Hitung Mundur Otomatis Pasca Voting (30 Detik)</strong>
                            <p class="text-slate-600 text-[11px] mt-0.5 leading-relaxed">
                                Halaman bukti tanda terima dilengkapi timer hitung mundur 30 detik menuju logout otomatis guna mempercepat antrean bilik suara TPS.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Detail Lingkungan & Server (5 Cols) -->
        <div class="lg:col-span-5 space-y-6 sm:space-y-8">
            
            <!-- Lingkungan Server -->
            <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect width="20" height="8" x="2" y="2" rx="2" ry="2"/>
                            <rect width="20" height="8" x="2" y="14" rx="2" ry="2"/>
                            <line x1="6" x2="6.01" y1="6" y2="6"/>
                            <line x1="6" x2="6.01" y1="18" y2="18"/>
                        </svg>
                        <h2 class="text-base font-black text-slate-900">Spesifikasi Lingkungan Server</h2>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">PHP Engine</span>
                        <span class="font-mono font-bold text-slate-900">PHP {{ $serverInfo['php_version'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Laravel Framework</span>
                        <span class="font-mono font-bold text-slate-900">Laravel {{ $serverInfo['laravel_version'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Database Engine</span>
                        <span class="font-mono font-bold text-slate-900 uppercase">{{ $serverInfo['db_connection'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Environment</span>
                        <span class="font-mono font-bold text-slate-900 uppercase">{{ $serverInfo['app_env'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Debug Mode</span>
                        <span class="font-mono font-bold {{ config('app.debug') ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ $serverInfo['app_debug'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Zona Waktu (Timezone)</span>
                        <span class="font-mono font-bold text-slate-900">{{ $serverInfo['timezone'] }} (WIB)</span>
                    </div>

                    <div class="flex items-center justify-between py-2">
                        <span class="text-slate-500 font-medium">Driver Sesi</span>
                        <span class="font-mono font-bold text-slate-900 uppercase">{{ $serverInfo['session_driver'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Tautan Cepat Audit Trail -->
            <div class="bg-gradient-to-br from-[#1a0305] to-[#2c050a] rounded-2xl xl:rounded-3xl p-6 sm:p-7 text-white shadow-xl space-y-4 border border-red-950/40">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 text-[#CE9501] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <path d="M12 8v4"></path>
                            <path d="M12 16h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black">Audit Trail & Log Transparansi</h3>
                        <p class="text-[11px] text-slate-300">Pantau seluruh mutasi data secara terperinci</p>
                    </div>
                </div>
                <p class="text-[11px] text-slate-300/90 leading-relaxed">
                    Setiap aksi perubahan status periode, paslon, dan pengaturan terekam secara otomatis ke dalam tabel audit log sistem untuk keperluan pelaporan KPUM.
                </p>
                <div class="pt-2">
                    <a href="{{ route('admin.audit.index') }}"
                        class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-[#CE9501] text-xs font-bold transition-all border border-white/10">
                        <span>Buka Halaman Audit Trail</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
