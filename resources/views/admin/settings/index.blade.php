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

        <div>
            <!-- Button Kembali ke Dashboard dihapus sesuai permintaan -->
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
                        <p class="text-[11px] text-slate-500">BEM UBSI Kab.Karawang</p>
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

            <!-- Kustomisasi Logo Kabinet PEMIRA -->
            <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-5"
                x-data="{
                    previewUrl: null,
                    hasSelectedFile: false,
                    isUploading: false,
                    handleFileChange(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.hasSelectedFile = true;
                            this.previewUrl = URL.createObjectURL(file);
                        } else {
                            this.hasSelectedFile = false;
                            this.previewUrl = null;
                        }
                    }
                }">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <circle cx="9" cy="9" r="2"/>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                        </svg>
                        <div>
                            <h2 class="text-base font-black text-slate-900 leading-tight">Logo Kabinet PEMIRA</h2>
                            <p class="text-[11px] text-slate-500 font-medium">Kustomisasi identitas logo kabinet kepengurusan</p>
                        </div>
                    </div>
                    @if($isCustomLogo)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-800 border border-emerald-300">
                            Logo Kustom Aktif
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-slate-100 text-slate-700 border border-slate-300">
                            Logo Bawaan
                        </span>
                    @endif
                </div>

                <!-- Preview Box Logo -->
                <div class="flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-50 border border-slate-200/80 relative overflow-hidden group">
                    <div class="relative w-24 h-24 sm:w-28 sm:h-28 flex items-center justify-center">
                        <img :src="previewUrl || '{{ $currentLogoUrl }}'" 
                            alt="Logo Kabinet" 
                            class="max-w-full max-h-full object-contain drop-shadow-md transition-transform duration-200 group-hover:scale-105">
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 mt-2 block" x-text="previewUrl ? 'Pratinjau File yang Dipilih' : 'Logo Kabinet yang Sedang Aktif'"></span>
                </div>

                <!-- Petunjuk Format -->
                <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-200 text-[11px] text-slate-600 space-y-1">
                    <p class="font-bold text-slate-800">Panduan Format Gambar:</p>
                    <p>Format yang didukung: <span class="font-bold text-slate-700">PNG (disarankan transparan), JPG, WEBP, atau SVG</span>. Rasio 1:1, ukuran file maksimal 5 MB.</p>
                </div>

                <!-- Form Upload / Ubah Logo -->
                <form action="{{ route('admin.settings.logo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-3" @submit="isUploading = true">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih File Logo Baru</label>
                        <input type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml" required @change="handleFileChange($event)"
                            class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-[#940C10] hover:file:bg-red-100 cursor-pointer border border-slate-300 rounded-xl bg-slate-50/50 p-1.5 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20">
                    </div>

                    <button type="submit" :disabled="!hasSelectedFile || isUploading"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#940C10] hover:bg-[#7A0A0D] text-white text-xs font-bold shadow-md shadow-red-950/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                        <template x-if="isUploading">
                            <svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <template x-if="!isUploading">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </template>
                        <span x-text="isUploading ? 'Menyimpan Logo...' : 'Upload & Simpan Logo Baru'">Upload & Simpan Logo Baru</span>
                    </button>
                </form>

                <!-- Tombol Hapus Logo Kustom (Jika aktif) -->
                @if($isCustomLogo)
                <div class="pt-3 border-t border-slate-100">
                    <form id="delete-logo-form" action="{{ route('admin.settings.logo.delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDeleteLogo()"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 transition-all cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            <span>Hapus Logo Kustom (Kembali ke Bawaan)</span>
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <script>
                function confirmDeleteLogo() {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Hapus Logo Kustom?',
                            text: 'Logo kabinet kustom akan dihapus dan tampilan seluruh sistem akan kembali menggunakan logo bawaan resmi.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Iya, Hapus Logo',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            buttonsStyling: false,
                            customClass: {
                                popup: 'rounded-3xl border border-slate-100 bg-white shadow-2xl p-6 text-slate-900',
                                confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-[#940C10] hover:bg-[#7A0A0D] transition-colors cursor-pointer mr-3',
                                cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-xs text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('delete-logo-form').submit();
                            }
                        });
                    } else {
                        if (confirm('Hapus logo kustom dan kembalikan ke logo bawaan resmi?')) {
                            document.getElementById('delete-logo-form').submit();
                        }
                    }
                }
            </script>

        </div>

    </div>

</div>
@endsection
